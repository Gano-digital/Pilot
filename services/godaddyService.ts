// ── GoDaddy Reseller API service ─────────────────────────────────────────────
//
// Server-side / CI-CD only. Do NOT import this module into browser-bundled
// code — the API credentials would be exposed in the public bundle.
//
// Required environment variables:
//   GODADDY_API_KEY    – API key from the GoDaddy Developer Portal
//                        (the segment before the underscore in the portal key,
//                         e.g. for "3mM44YwfLcmKrY_Kpamb4Uk1KT1RgFSaMHgPA"
//                         the key is "3mM44YwfLcmKrY")
//   GODADDY_API_SECRET – API secret from the GoDaddy Developer Portal
//                        (the segment after the underscore, e.g. "Kpamb4Uk1KT1RgFSaMHgPA")
//   GODADDY_API_ENV    – 'OTE' for the test/sandbox environment (default)
//                         or 'PRODUCTION' for live credentials.
//
// GoDaddy Developer Portal: https://developer.godaddy.com
// OTE base URL  : https://api.ote-godaddy.com
// PROD base URL : https://api.godaddy.com
// ─────────────────────────────────────────────────────────────────────────────

export type GodaddyEnv = 'OTE' | 'PRODUCTION';

const BASE_URLS: Record<GodaddyEnv, string> = {
  OTE: 'https://api.ote-godaddy.com',
  PRODUCTION: 'https://api.godaddy.com',
};

// ── Public types ─────────────────────────────────────────────────────────────

export interface DomainAvailability {
  available: boolean;
  currency: string;
  definitive: boolean;
  domain: string;
  period: number;
  price: number;
}

export interface DomainSummary {
  createdAt: string;
  domain: string;
  domainId: number;
  expires: string;
  locked: boolean;
  nameServers: string[] | null;
  privacy: boolean;
  renewAuto: boolean;
  status: string;
}

export type DnsRecordType =
  | 'A'
  | 'AAAA'
  | 'CNAME'
  | 'MX'
  | 'NS'
  | 'SOA'
  | 'SRV'
  | 'TXT';

export interface DnsRecord {
  data: string;
  name: string;
  port?: number;
  priority?: number;
  protocol?: string;
  service?: string;
  ttl: number;
  type: DnsRecordType;
  weight?: number;
}

export interface GodaddyApiError {
  code: string;
  message: string;
  fields?: Array<{ code: string; message: string; path: string }>;
}

// ── Internal helpers ─────────────────────────────────────────────────────────

const getConfig = (): { apiKey: string; apiSecret: string; baseUrl: string } => {
  const apiKey = process.env.GODADDY_API_KEY;
  const apiSecret = process.env.GODADDY_API_SECRET;
  const env = (process.env.GODADDY_API_ENV as GodaddyEnv) ?? 'OTE';

  if (!apiKey) {
    throw new Error('GODADDY_API_KEY environment variable is not set.');
  }
  if (!apiSecret) {
    throw new Error('GODADDY_API_SECRET environment variable is not set.');
  }
  if (env !== 'OTE' && env !== 'PRODUCTION') {
    throw new Error(`GODADDY_API_ENV must be 'OTE' or 'PRODUCTION', got: '${env}'`);
  }

  return { apiKey, apiSecret, baseUrl: BASE_URLS[env] };
};

const apiRequest = async <T>(
  path: string,
  options: RequestInit = {}
): Promise<T> => {
  const { apiKey, apiSecret, baseUrl } = getConfig();
  const url = `${baseUrl}${path}`;

  const response = await fetch(url, {
    ...options,
    headers: {
      Authorization: `sso-key ${apiKey}:${apiSecret}`,
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
  });

  if (!response.ok) {
    let errBody: GodaddyApiError = { code: 'HTTP_ERROR', message: response.statusText };
    try {
      errBody = (await response.json()) as GodaddyApiError;
    } catch {
      // ignore JSON parse failure – use the default
    }
    throw new Error(
      `GoDaddy API Error [${response.status}]: ${errBody.message} (${errBody.code})`
    );
  }

  if (response.status === 204) {
    return undefined as unknown as T;
  }

  return response.json() as Promise<T>;
};

// ── Domain operations ────────────────────────────────────────────────────────

/** List all domains in the account, optionally filtered by status. */
export const listDomains = async (statuses?: string[]): Promise<DomainSummary[]> => {
  const query = statuses?.length ? `?statuses=${statuses.join(',')}` : '';
  return apiRequest<DomainSummary[]>(`/v1/domains${query}`);
};

/** Check whether a domain name is available for registration. */
export const checkDomainAvailability = async (
  domain: string
): Promise<DomainAvailability> => {
  return apiRequest<DomainAvailability>(
    `/v1/domains/available?domain=${encodeURIComponent(domain)}`
  );
};

/** Retrieve details for a single domain. */
export const getDomain = async (domain: string): Promise<DomainSummary> => {
  return apiRequest<DomainSummary>(`/v1/domains/${encodeURIComponent(domain)}`);
};

// ── DNS record operations ────────────────────────────────────────────────────

/**
 * Retrieve DNS records for a domain.
 * Optionally filter by record type and/or name (use '@' for the zone apex).
 */
export const getDnsRecords = async (
  domain: string,
  type?: DnsRecordType,
  name?: string
): Promise<DnsRecord[]> => {
  let path = `/v1/domains/${encodeURIComponent(domain)}/records`;
  if (type) path += `/${type}`;
  if (type && name) path += `/${name}`; // '@' must not be percent-encoded here
  return apiRequest<DnsRecord[]>(path);
};

/**
 * Add or update DNS records (PATCH – upserts by type+name combination).
 * Existing records that share the same type+name are replaced; others survive.
 */
export const upsertDnsRecords = async (
  domain: string,
  records: DnsRecord[]
): Promise<void> => {
  await apiRequest<void>(`/v1/domains/${encodeURIComponent(domain)}/records`, {
    method: 'PATCH',
    body: JSON.stringify(records),
  });
};

/**
 * Replace ALL records of a given type+name with the supplied list (PUT).
 * Use '@' as `name` for the zone apex (e.g. the root A record).
 */
export const replaceDnsRecords = async (
  domain: string,
  type: DnsRecordType,
  name: string,
  records: DnsRecord[]
): Promise<void> => {
  await apiRequest<void>(
    `/v1/domains/${encodeURIComponent(domain)}/records/${type}/${name}`,
    { method: 'PUT', body: JSON.stringify(records) }
  );
};
