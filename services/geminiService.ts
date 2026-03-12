
import { GoogleGenAI } from "@google/genai";

const API_KEY = process.env.API_KEY;

if (!API_KEY) {
  throw new Error("API_KEY environment variable not set.");
}

const ai = new GoogleGenAI({ apiKey: API_KEY });

// ── Rate limiting ─────────────────────────────────────────────────────────────
// Prevents accidental API flooding and limits cost exposure if the key is
// extracted from the compiled bundle.  Max 10 requests per 60-second window.
// Note: this runs in a single-threaded browser context – no race conditions.
const RATE_LIMIT_MAX = 10;
const RATE_LIMIT_WINDOW_MS = 60_000;
const requestTimestamps: number[] = [];

const checkRateLimit = (): void => {
  const now = Date.now();
  // Remove all timestamps outside the rolling window in one splice.
  const cutoff = now - RATE_LIMIT_WINDOW_MS;
  const firstValid = requestTimestamps.findIndex(ts => ts >= cutoff);
  if (firstValid > 0) {
    requestTimestamps.splice(0, firstValid);
  } else if (firstValid === -1) {
    requestTimestamps.length = 0;
  }
  if (requestTimestamps.length >= RATE_LIMIT_MAX) {
    const retryAfterSec = Math.ceil(
      (requestTimestamps[0] + RATE_LIMIT_WINDOW_MS - now) / 1000
    );
    throw new Error(
      `Rate limit reached (${RATE_LIMIT_MAX} requests / minute). Please wait ${retryAfterSec} second(s) and try again.`
    );
  }
  requestTimestamps.push(now);
};
// ─────────────────────────────────────────────────────────────────────────────

const cleanGeneratedCode = (rawText: string): string => {
  let code = rawText.trim();
  if (code.startsWith("```python")) {
    code = code.substring("```python".length).trimStart();
  }
  if (code.endsWith("```")) {
    code = code.substring(0, code.length - "```".length).trimEnd();
  }
  return code;
};

export const generatePythonScript = async (userPrompt: string): Promise<string> => {
  try {
    checkRateLimit();

    const fullPrompt = `
You are an expert Python developer specializing in web scraping and robust application development.
Your task is to generate a complete, self-contained Python script based on the following user request.
The final output must be a single, executable Python file.
Adhere strictly to all requirements in the user's request.
The output should ONLY be the Python code, enclosed in a single markdown code block (\`\`\`python ... \`\`\`).
Do not include any explanations, introductions, or concluding remarks outside of the code block.

--- USER REQUEST ---
${userPrompt}
`;

    const response = await ai.models.generateContent({
        model: 'gemini-2.5-flash',
        contents: fullPrompt,
    });

    const rawText = response.text;
    if (!rawText) {
      throw new Error("Received an empty response from the API.");
    }
    
    return cleanGeneratedCode(rawText);

  } catch (error) {
    console.error("Error generating script with Gemini API:", error);
    if (error instanceof Error) {
        throw new Error(`Gemini API Error: ${error.message}`);
    }
    throw new Error("An unknown error occurred while communicating with the Gemini API.");
  }
};
