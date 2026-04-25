#!/usr/bin/env python3
"""
OpenRouter API Wrapper — Universal LLM gateway

Supports 300+ models via single endpoint:
- Anthropic Claude 3.5 Sonnet, Claude 3 Opus
- OpenAI GPT-4o, GPT-4o-mini
- Meta Llama 3.3, Llama 4
- Deepseek V3, R1
- Google Gemini 1.5 Pro
- Mistral, Qwen, and more

Usage:
  python openrouter-wrapper.py chat "Your prompt" [--model MODEL]
  python openrouter-wrapper.py chat "Your prompt" --model anthropic/claude-3.5-sonnet
  python openrouter-wrapper.py models
  python openrouter-wrapper.py status
"""

import json
import sys
import os
import requests
from pathlib import Path
from typing import Optional, List, Dict

# Force UTF-8 encoding for output
if sys.stdout.encoding.lower() != 'utf-8':
    import io
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')
    sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8', errors='replace')

# Configuration
CREDENTIALS_FILE = Path.home() / ".openrouter" / "credentials" / "openrouter-api.json"

# Default models with fallbacks (updated 2026-04-24)
DEFAULT_MODELS = {
    "claude": "anthropic/claude-sonnet-4.6",
    "gpt4": "openai/gpt-5.5-pro",
    "llama": "meta-llama/llama-3.3-70b-instruct",
    "deepseek": "deepseek/deepseek-v4-pro",
    "gemini": "google/gemini-2.0-pro-exp",
    "free": "google/gemma-4-26b-a4b-it:free",
}

class OpenRouterAuth:
    """Handle OpenRouter API authentication"""

    def __init__(self):
        self.credentials = self._load_credentials()
        self.api_key = self.credentials.get('api_key')
        self.endpoint = self.credentials.get('endpoint', 'https://openrouter.ai/api/v1')
        self.models = self.credentials.get('models', list(DEFAULT_MODELS.values()))

    def _load_credentials(self) -> dict:
        if not CREDENTIALS_FILE.exists():
            raise FileNotFoundError(f"Credentials file not found: {CREDENTIALS_FILE}")

        with open(CREDENTIALS_FILE, 'r', encoding='utf-8') as f:
            creds = json.load(f)

        if not creds.get('api_key'):
            raise ValueError("api_key not found in credentials")

        return creds

    def get_headers(self) -> dict:
        return {
            "Authorization": f"Bearer {self.api_key}",
            "Content-Type": "application/json",
            "HTTP-Referer": "https://gano.digital",
            "X-Title": "Gano Digital",
            "User-Agent": "GanoOpenRouter/1.0.0"
        }

class OpenRouterClient:
    """OpenRouter API client — unified gateway to 300+ LLMs"""

    def __init__(self, auth: OpenRouterAuth):
        self.auth = auth
        self.session = requests.Session()
        self.session.headers.update(auth.get_headers())

    def chat(self, prompt: str, model: Optional[str] = None, temperature: float = 0.7, max_tokens: int = 1024) -> str:
        """Send chat request via OpenRouter"""
        if not model:
            model = DEFAULT_MODELS["claude"]

        # Resolve aliases
        if model in DEFAULT_MODELS:
            model = DEFAULT_MODELS[model]

        print(f"[INFO] Sending prompt to OpenRouter ({model})...")

        payload = {
            "model": model,
            "messages": [{"role": "user", "content": prompt}],
            "temperature": temperature,
            "max_tokens": max_tokens,
            "top_p": 0.9,
        }

        try:
            response = self.session.post(
                f"{self.auth.endpoint}/chat/completions",
                json=payload,
                timeout=60
            )

            if response.status_code == 200:
                result = response.json()
                if 'choices' in result and len(result['choices']) > 0:
                    message = result['choices'][0].get('message', {}).get('content', '')
                    # Print usage info
                    usage = result.get('usage', {})
                    if usage:
                        print(f"[INFO] Tokens: {usage.get('prompt_tokens', 0)} in / {usage.get('completion_tokens', 0)} out")
                    return message
                else:
                    return "[ERROR] No response from OpenRouter"
            elif response.status_code == 429:
                return "[ERROR] Rate limited. Try a different model or wait."
            elif response.status_code == 402:
                return "[ERROR] Insufficient credits. Purchase at https://openrouter.ai/settings/credits"
            else:
                return f"[ERROR] API returned {response.status_code}: {response.text[:200]}"

        except requests.exceptions.Timeout:
            return "[ERROR] Request timed out."
        except Exception as e:
            return f"[ERROR] {type(e).__name__}: {e}"

    def list_models(self) -> List[str]:
        """List available models (from cached credentials)"""
        return self.auth.models

    def get_status(self) -> Dict:
        """Get API status"""
        return {
            "service": "OpenRouter",
            "endpoint": self.auth.endpoint,
            "models_cached": len(self.auth.models),
            "key_status": "active" if self.auth.api_key else "missing",
        }

def main():
    if len(sys.argv) < 2:
        print("Usage: openrouter-wrapper.py <command> [args...]")
        print("Commands:")
        print("  chat <prompt> [--model MODEL]  - Send chat request")
        print("  models                         - List available models")
        print("  status                         - Show API status")
        print()
        print("Model aliases:")
        for alias, model in DEFAULT_MODELS.items():
            print(f"  {alias:10} -> {model}")
        sys.exit(1)

    command = sys.argv[1]
    args = sys.argv[2:] if len(sys.argv) > 2 else []

    try:
        auth = OpenRouterAuth()
        client = OpenRouterClient(auth)

        if command == "chat":
            # Parse --model flag
            model = None
            if "--model" in args:
                idx = args.index("--model")
                model = args[idx + 1]
                args = args[:idx] + args[idx + 2:]

            prompt = " ".join(args) if args else sys.stdin.read()
            print()
            result = client.chat(prompt, model=model)
            print(f"\n[OPENROUTER]\n{result}\n")

        elif command == "models":
            print()
            models = client.list_models()
            print("[OPENROUTER] Available Models:")
            for i, m in enumerate(models, 1):
                print(f"  {i}. {m}")
            print()

        elif command == "status":
            print()
            status = client.get_status()
            print("[OPENROUTER] API Status:")
            for key, value in status.items():
                print(f"  {key}: {value}")
            print()

        else:
            print(f"[ERROR] Unknown command: {command}")
            sys.exit(1)

    except FileNotFoundError as e:
        print(f"[ERROR] {e}")
        sys.exit(1)
    except ValueError as e:
        print(f"[ERROR] Authentication: {e}")
        sys.exit(1)
    except Exception as e:
        print(f"[ERROR] {type(e).__name__}: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()
