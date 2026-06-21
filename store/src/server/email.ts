/** TREAT AS IMMUTABLE - This file is protected by the file-edit tool
 *
 * Send transactional email through the Airo email gateway.
 *
 * Posts a JSON message to the loopback gateway provided by the Airo
 * runtime. The gateway authors the outbound RFC-5322 message, applies
 * the platform sender-identity policy, and forwards to the configured
 * SMTP destination. Customer code never touches SMTP directly.
 *
 * The gateway listens on 127.0.0.1:2525 inside both the preview and
 * publish containers; this module assumes that contract and does not
 * accept an override.
 */

// Loopback only — the email gateway runs on 127.0.0.1 inside the same
// container as customer code. TLS adds nothing for traffic that never
// leaves the kernel and would require cert provisioning for 127.0.0.1
// in every preview/publish container; the SMTP path uses plain
// localhost:25 + secure:false for the same reason.
// nosemgrep: typescript.react.security.react-insecure-request.react-insecure-request
const EMAIL_GATEWAY_URL = "http://127.0.0.1:2525/api/email/send";
const REQUEST_TIMEOUT_MS = 30_000;

export type EmailAttachment = {
	/** File name presented to the recipient. Must not contain CR, LF, quote, or backslash. */
	filename: string;
	/**
	 * Attachment bytes. Always pass raw bytes — the helper base64-encodes
	 * for the wire. For text content (e.g. an .ics calendar invite),
	 * encode the string yourself: `Buffer.from(text, 'utf-8')`.
	 */
	content: Buffer | Uint8Array;
	/** MIME type. Defaults to application/octet-stream when omitted. */
	contentType?: string;
};

export type SendEmailInput = {
	/** Recipient address(es). At least one required. */
	to: string | string[];
	/** Carbon-copy recipient(s). */
	cc?: string | string[];
	/** Blind-carbon-copy recipient(s). Never appears in headers. */
	bcc?: string | string[];
	/** Subject line. Required. */
	subject: string;
	/** Plain-text body. At least one of text or html is required. */
	text?: string;
	/** HTML body. At least one of text or html is required. */
	html?: string;
	/** Reply-To header. Use this to direct replies elsewhere. */
	replyTo?: string;
	/**
	 * Sender address. Omit to use the app's canonical sender — the gateway
	 * picks the right value based on attached domains. Only set this when
	 * a specific local-part on a verified domain is required; the gateway
	 * rejects unverified senders with a 400.
	 */
	from?: string;
	/** Up to 10 attachments, each ≤ 2 MB decoded. */
	attachments?: EmailAttachment[];
};

export type SendEmailResult = {
	/** Opaque id minted by the gateway. Logged on the gateway for correlation. */
	messageId: string;
};

type GatewayPayload = {
	to: string[];
	cc?: string[];
	bcc?: string[];
	subject: string;
	text?: string;
	html?: string;
	replyTo?: string;
	from?: string;
	attachments?: { filename: string; content: string; contentType?: string }[];
};

type GatewayResponse = {
	success: boolean;
	messageId?: string;
	error?: string;
};

export async function sendEmail(input: SendEmailInput): Promise<SendEmailResult> {
	const payload = buildPayload(input);

	// AbortSignal.timeout() binds to the response so the same deadline
	// covers connect, headers, AND body reads. A manual
	// `setTimeout(controller.abort, ...)` cleared in `finally` after
	// `fetch()` resolves headers leaves body reads unprotected — the
	// caller hangs indefinitely on a stalled mid-body response.
	let response: Response;
	let body: GatewayResponse;
	try {
		response = await fetch(EMAIL_GATEWAY_URL, {
			method: "POST",
			headers: { "content-type": "application/json" },
			body: JSON.stringify(payload),
			signal: AbortSignal.timeout(REQUEST_TIMEOUT_MS),
		});
		body = await parseBody(response);
	} catch (err) {
		throw new Error(`email gateway unreachable: ${describeError(err)}`);
	}

	if (!response.ok || !body.success) {
		const detail = body.error ?? `HTTP ${response.status}`;
		const idSuffix = body.messageId ? ` (messageId=${body.messageId})` : "";
		throw new Error(`email send failed: ${detail}${idSuffix}`);
	}

	if (!body.messageId) {
		throw new Error("email send succeeded but gateway returned no messageId");
	}

	return { messageId: body.messageId };
}

function buildPayload(input: SendEmailInput): GatewayPayload {
	const payload: GatewayPayload = {
		to: toArray(input.to),
		subject: input.subject,
	};
	const cc = toArray(input.cc);
	if (cc.length > 0) payload.cc = cc;
	const bcc = toArray(input.bcc);
	if (bcc.length > 0) payload.bcc = bcc;
	if (input.text) payload.text = input.text;
	if (input.html) payload.html = input.html;
	if (input.replyTo) payload.replyTo = input.replyTo;
	if (input.from) payload.from = input.from;
	if (input.attachments && input.attachments.length > 0) {
		payload.attachments = input.attachments.map(encodeAttachment);
	}
	return payload;
}

function toArray(value: string | string[] | undefined): string[] {
	if (value === undefined) return [];
	return Array.isArray(value) ? value : [value];
}

function encodeAttachment(att: EmailAttachment): { filename: string; content: string; contentType?: string } {
	const out: { filename: string; content: string; contentType?: string } = {
		filename: att.filename,
		content: Buffer.from(att.content).toString("base64"),
	};
	if (att.contentType) out.contentType = att.contentType;
	return out;
}

async function parseBody(response: Response): Promise<GatewayResponse> {
	try {
		return (await response.json()) as GatewayResponse;
	} catch (err) {
		// Don't swallow aborts — re-raise so the outer catch reports the
		// timeout. JSON parse errors fall through to a sanitized "non-JSON
		// response" payload that lets sendEmail produce a useful error.
		if (isAbortLike(err)) throw err;
		return { success: false, error: `non-JSON response (HTTP ${response.status})` };
	}
}

function isAbortLike(err: unknown): boolean {
	return err instanceof Error && (err.name === "AbortError" || err.name === "TimeoutError");
}

function describeError(err: unknown): string {
	if (err instanceof Error) {
		if (isAbortLike(err)) return `timed out after ${REQUEST_TIMEOUT_MS}ms`;
		return err.message;
	}
	return String(err);
}
