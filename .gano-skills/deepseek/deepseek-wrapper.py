#!/usr/bin/env python3
"""
Deepseek API Wrapper — Official API client

Supports:
- deepseek-chat (general chat, coding)
- deepseek-reasoner (R1, chain-of-thought reasoning)

Usage:
  python deepseek-wrapper.py chat "Your prompt" [--model MODEL]
  python deepseek-wrapper.py chat "Your prompt" --model reasoner
  python deepseek-wrapper.py models
  python deepseek-wrapper.py status
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
CREDENTIALS_FILE = Path.home() / ".deepseek" / "credentials" / "deepseek-api.json"

DEFAULT_MODELS = {
    "chat": "deepseek-chat",
    "reasoner": "deepseek-reasoner",
}

class DeepseekAuth:
    """Handle Deepseek API authentication"""

    def __init__(self):
        self.credentials = self._load_credentials()
        self.api_key = self.credentials.get('api_key')
        self.endpoint = self.credentials.get('endpoint', 'https://api.deepseek.com/v1')
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
            "User-Agent": "GanoDeepseek/1.0.0"
        }

class DeepseekClient:
    """Deepseek API client — official API for chat and reasoning"""

    def __init__(self, auth: DeepseekAuth):
        self.auth = auth
        self.session = requests.Session()
        self.session.headers.update(auth.get_headers())

    def chat(self, prompt: str, model: Optional[str] = None, temperature: float = 0.7, max_tokens: int = 1024) -> str:
        """Send chat request to Deepseek"""
        if not model:
            model = DEFAULT_MODELS["chat"]

        # Resolve aliases
        if model in DEFAULT_MODELS:
            model = DEFAULT_MODELS[model]

        print(f"[INFO] Sending prompt to Deepseek ({model})...")

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
                    choice = result['choices'][0]
                    message = choice.get('message', {})
                    content = message.get('content', '')
                    reasoning = message.get('reasoning_content', '')
                    
                    # Print usage info
                    usage = result.get('usage', {})
                    if usage:
                        print(f"[INFO] Tokens: {usage.get('prompt_tokens', 0)} in / {usage.get('completion_tokens', 0)} out")
                    
                    # Return reasoning if present (for reasoner model)
                    if reasoning:
                        return f"[REASONING]\n{reasoning}\n\n[ANSWER]\n{content}"
                    return content
                else:
                    return "[ERROR] No response from Deepseek"
            elif response.status_code == 402:
                return "[ERROR] Insufficient balance. Add credits at https://platform.deepseek.com/"
            elif response.status_code == 429:
                return "[ERROR] Rate limited. Please retry shortly."
            else:
                return f"[ERROR] API returned {response.status_code}: {response.text[:200]}"

        except requests.exceptions.Timeout:
            return "[ERROR] Request timed out."
        except Exception as e:
            return f"[ERROR] {type(e).__name__}: {e}"

    def list_models(self) -> List[str]:
        """List available models"""
        return self.auth.models

    def get_status(self) -> Dict:
        """Get API status"""
        return {
            "service": "Deepseek",
            "endpoint": self.auth.endpoint,
            "models_cached": len(self.auth.models),
            "key_status": "active" if self.auth.api_key else "missing",
        }

def main():
    if len(sys.argv) < 2:
        print("Usage: deepseek-wrapper.py <command> [args...]")
        print("Commands:")
        print("  chat <prompt> [--model MODEL]  - Send chat request")
        print("  models                         - List available models")
        print("  status                         - Show API status")
        print()
        print("Model aliases:")
        for alias, model in DEFAULT_MODELS.items():
            print(f"  {alias:10} -> {model}")
        print()
        print("Note: deepseek-reasoner exposes chain-of-thought reasoning.")
        sys.exit(1)

    command = sys.argv[1]
    args = sys.argv[2:] if len(sys.argv) > 2 else []

    try:
        auth = DeepseekAuth()
        client = DeepseekClient(auth)

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
            print(f"\n[DEEPSEEK]\n{result}\n")

        elif command == "models":
            print()
            models = client.list_models()
            print("[DEEPSEEK] Available Models:")
            for i, m in enumerate(models, 1):
                print(f"  {i}. {m}")
            print()

        elif command == "status":
            print()
            status = client.get_status()
            print("[DEEPSEEK] API Status:")
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
