#!/usr/bin/env python3
"""
Kimi CLI Wrapper — Local authentication proxy using stored OAuth tokens

Usage:
  python kimi-wrapper.py chat "Your prompt"
  python kimi-wrapper.py code "Analyze this code"
  python kimi-wrapper.py task "Do something"
"""

import json
import sys
import os
import requests
from pathlib import Path

# Force UTF-8 encoding for output
if sys.stdout.encoding.lower() != 'utf-8':
    import io
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')
    sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8', errors='replace')

# Configuration
CREDENTIALS_FILE = Path.home() / ".kimi" / "credentials" / "kimi-code.json"
API_BASE_URL = "https://api.kimi.com/coding/v1"

class KimiAuth:
    """Handle Kimi authentication using stored OAuth tokens"""

    def __init__(self):
        self.credentials = self._load_credentials()
        self.access_token = self.credentials.get('access_token')
        self.api_key = self.access_token

    def _load_credentials(self):
        """Load OAuth tokens from local storage"""
        if not CREDENTIALS_FILE.exists():
            raise FileNotFoundError(f"Credentials file not found: {CREDENTIALS_FILE}")

        with open(CREDENTIALS_FILE, 'r') as f:
            creds = json.load(f)

        if not creds.get('access_token'):
            raise ValueError("access_token not found in credentials")

        return creds

    def get_headers(self):
        """Return API headers with authentication"""
        return {
            "Authorization": f"Bearer {self.access_token}",
            "Content-Type": "application/json",
            "User-Agent": "KimiCLI/1.0.0-local"
        }

class KimiClient:
    """Kimi API client"""

    def __init__(self, auth):
        self.auth = auth
        self.session = requests.Session()
        self.session.headers.update(auth.get_headers())

    def chat(self, prompt, model="kimi-code/kimi-for-coding"):
        """Send a chat request to Kimi"""
        print(f"[INFO] Sending prompt to Kimi ({model})...")

        payload = {
            "messages": [
                {"role": "user", "content": prompt}
            ],
            "model": model,
            "stream": False
        }

        try:
            response = self.session.post(
                f"{API_BASE_URL}/chat/completions",
                json=payload,
                timeout=30
            )

            if response.status_code == 200:
                result = response.json()
                if 'choices' in result and len(result['choices']) > 0:
                    message = result['choices'][0].get('message', {}).get('content', '')
                    return message
                else:
                    return "[ERROR] No response from Kimi"
            else:
                return f"[ERROR] API returned {response.status_code}: {response.text}"

        except requests.exceptions.ConnectionError:
            return "[ERROR] Connection failed. Check your internet connection."
        except requests.exceptions.Timeout:
            return "[ERROR] Request timed out."
        except Exception as e:
            return f"[ERROR] {type(e).__name__}: {e}"

    def analyze_code(self, code_text):
        """Analyze code with Kimi"""
        prompt = f"Analyze this code and provide feedback:\n\n```\n{code_text}\n```"
        return self.chat(prompt)

def main():
    """CLI entry point"""

    # Parse arguments
    if len(sys.argv) < 2:
        print("Usage: kimi-wrapper.py <command> <args...>")
        print("  chat <prompt>     - Send a chat request")
        print("  code <code>       - Analyze code")
        print("  task <task>       - Request a task")
        sys.exit(1)

    command = sys.argv[1]
    args = sys.argv[2:] if len(sys.argv) > 2 else []

    try:
        # Load authentication
        print("[INFO] Loading Kimi authentication...")
        auth = KimiAuth()
        print(f"[OK] Authenticated as: {auth.credentials.get('scope')}")

        # Create client
        client = KimiClient(auth)

        # Execute command
        if command == "chat":
            prompt = " ".join(args) if args else sys.stdin.read()
            print()
            result = client.chat(prompt)
            print(f"\n[KIMI]\n{result}\n")

        elif command == "code":
            code = " ".join(args) if args else sys.stdin.read()
            print()
            result = client.analyze_code(code)
            print(f"\n[KIMI]\n{result}\n")

        elif command == "task":
            task = " ".join(args) if args else sys.stdin.read()
            prompt = f"Help me with this task: {task}"
            print()
            result = client.chat(prompt)
            print(f"\n[KIMI]\n{result}\n")

        elif command == "version":
            print(f"KimiCLI Wrapper 1.0.0 (local mode)")
            print(f"API: {API_BASE_URL}")
            print(f"Token scope: {auth.credentials.get('scope')}")

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
