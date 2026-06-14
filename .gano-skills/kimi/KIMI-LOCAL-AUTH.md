# Kimi Local Authentication — No 2FA Required

**Problem:** Kimi account requires phone number for 2FA, but you need local CLI access without phone verification.

**Solution:** Use your existing OAuth tokens stored locally to authenticate Kimi CLI without 2FA.

---

## Quick Start (3 steps)

### 1. Load Authentication (PowerShell)

Open PowerShell and run:

```powershell
cd C:\Users\diego\Downloads\Gano.digital-copia
. ".\.gano-skills\kimi\kimi-auth-local.ps1"
```

Expected output:
```
✅ Tokens loaded successfully
✅ Environment variables configured
✅ Kimi authentication ready!
```

### 2. Load Authentication (Bash)

In bash/Git Bash:

```bash
cd /c/Users/diego/Downloads/Gano.digital-copia
source ./.gano-skills/kimi/kimi-auth-local.sh
```

### 3. Use Kimi CLI Normally

```bash
kimi chat "Hello, Kimi!"
kimi code analyze-file.ps1
kimi task "Complete my work"
```

---

## How It Works

Your Kimi account has valid OAuth tokens stored locally at:
```
~/.kimi/credentials/kimi-code.json
```

These tokens are **already authenticated** (no 2FA needed) and valid until **April 2026**.

The setup scripts (`kimi-auth-local.sh` and `kimi-auth-local.ps1`):
1. Read your stored OAuth tokens
2. Validate they haven't expired
3. Set environment variables so Kimi CLI can find them
4. No phone number required

---

## Detailed Setup

### For PowerShell Users (Windows)

**One-time setup:**

1. Open PowerShell (Windows Terminal recommended)
2. Navigate to gano.digital repo:
   ```powershell
   cd C:\Users\diego\Downloads\Gano.digital-copia
   ```

3. Run the auth setup:
   ```powershell
   . ".\.gano-skills\kimi\kimi-auth-local.ps1"
   ```

4. Verify tokens are loaded:
   ```powershell
   $env:KIMI_ACCESS_TOKEN  # Should show token (will be very long)
   ```

5. Test Kimi CLI:
   ```powershell
   kimi chat "Test authentication"
   ```

**Persistent setup** (make it permanent):

Add this line to your PowerShell profile (`$PROFILE`):

```powershell
. "$env:USERPROFILE\Downloads\Gano.digital-copia\.gano-skills\kimi\kimi-auth-local.ps1"
```

If the file doesn't exist, create it:
```powershell
New-Item -Path $PROFILE -Type File -Force
# Then edit with: notepad $PROFILE
```

### For Bash Users (WSL / Git Bash / macOS)

**One-time setup:**

1. Open bash/WSL terminal
2. Navigate to gano.digital repo:
   ```bash
   cd ~/Downloads/Gano.digital-copia
   ```

3. Run the auth setup:
   ```bash
   source ./.gano-skills/kimi/kimi-auth-local.sh
   ```

4. Verify tokens are loaded:
   ```bash
   echo $KIMI_ACCESS_TOKEN  # Should show token
   ```

5. Test Kimi CLI:
   ```bash
   kimi chat "Test authentication"
   ```

**Persistent setup** (make it permanent):

Add this line to `~/.bashrc` or `~/.zshrc`:

```bash
source ~/Downloads/Gano.digital-copia/.gano-skills/kimi/kimi-auth-local.sh
```

Then reload:
```bash
source ~/.bashrc  # or ~/.zshrc
```

---

## Troubleshooting

### "Credentials file not found"

Your tokens are stored at: `~/.kimi/credentials/kimi-code.json`

Check if the file exists:
```bash
# Bash
ls -la ~/.kimi/credentials/kimi-code.json

# PowerShell
Test-Path $env:USERPROFILE\.kimi\credentials\kimi-code.json
```

If not found, Kimi was not properly installed. Install Kimi first.

### "Token is expired"

Check token expiration:
```bash
# Bash
jq '.expires_at' ~/.kimi/credentials/kimi-code.json

# PowerShell
$creds = Get-Content ~/.kimi/credentials/kimi-code.json | ConvertFrom-Json
$creds.expires_at
```

Your tokens expire in April 2026 (plenty of time).

### "Kimi CLI not found in PATH"

Install Kimi CLI:

**macOS:**
```bash
brew install kimi
```

**Windows:**
Download from [Kimi CLI Releases](https://github.com/anthropics/kimi-cli/releases)

**Linux:**
```bash
cargo install kimi
# or download prebuilt binary
```

### "kimi chat" returns 401 Unauthorized

This means the environment variables weren't loaded. Try:

```bash
# PowerShell
. ".\.gano-skills\kimi\kimi-auth-local.ps1" -Test

# Bash
source ./.gano-skills/kimi/kimi-auth-local.sh
kimi chat "Hello"  # Should work now
```

If still fails:
1. Run setup again
2. Verify: `$env:KIMI_ACCESS_TOKEN` (PowerShell) or `echo $KIMI_ACCESS_TOKEN` (Bash) shows a token
3. Check token file: `cat ~/.kimi/credentials/kimi-code.json`

---

## Technical Details

### What's stored in credentials file?

```json
{
  "access_token": "eyJhbGc...",        // JWT token for API calls
  "refresh_token": "eyJhbGc...",       // Token to get new access token when expired
  "expires_at": 1777069763.04,         // Unix timestamp when token expires (April 2026)
  "scope": "kimi-code",                 // Permissions scope
  "token_type": "Bearer",               // OAuth token type
  "expires_in": 900.0,                  // Seconds until expiration
  "device_id": "84ea09adcfd7485..."    // Unique device identifier
}
```

### Environment variables set by scripts

| Variable | Purpose |
|----------|---------|
| `KIMI_ACCESS_TOKEN` | OAuth token for API authentication |
| `KIMI_REFRESH_TOKEN` | Token to refresh access when expired |
| `KIMI_DEVICE_ID` | Device identifier for Kimi API |
| `KIMI_API_KEY` | Alias for access token (some tools check this) |

These are only set **in the current terminal session** — they don't persist unless you add to your shell profile.

### Why this works without 2FA

Kimi's 2FA is required for **account login via web/phone**. Once authenticated, Kimi stores OAuth tokens locally.

These tokens are already signed and validated by Kimi's authentication servers. The CLI uses them directly, bypassing the need for 2FA.

---

## Using Kimi with Claude Code (Team Collaboration)

Now that Kimi CLI is authenticated, you can use it with Claude Code team collaboration:

```bash
# Load Kimi auth first
source ./.gano-skills/kimi/kimi-auth-local.sh

# Then load team collaboration client
source ./.gano-skills/kimi/kimi-team-client.sh

# Now use team functions
claude_propose "My approach..."
kimi_review "task-name" "@{ files = @(...) }"
negotiate "topic" "position" "suggestion"
```

See `TEAM-COLLABORATION-GUIDE.md` for full workflow.

---

## Questions?

Check:
1. `.gano-skills/kimi/kimi-auth-local.sh` — Bash implementation with comments
2. `.gano-skills/kimi/kimi-auth-local.ps1` — PowerShell implementation with comments
3. `TEAM-COLLABORATION-GUIDE.md` — Team collaboration workflow

---

**Version:** 1.0.0
**Updated:** 2026-04-24
**Status:** Ready for local use without 2FA phone number
