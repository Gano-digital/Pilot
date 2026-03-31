---
name: gano-sftp-manager
description: |
  Professional SFTP/FTP client with modern GUI for managing Gano Digital staging and production servers.

  Use this skill when you need to:
  - Create a private, secure file management tool for your hosting
  - Build a modern desktop app for editing website files remotely
  - Manage assets, configs, and deployments with encrypted credentials
  - Get intelligent alerts and session reports when files are modified
  - Set up enterprise-grade security for server access

  This tool is specifically designed for development teams managing WordPress, themes, plugins, and assets.
  It encrypts credentials locally, logs all activity, and generates detailed session reports.

compatibility: Python 3.9+, PyQt5, cryptography
---

# Gano Digital SFTP Manager

A **professional, modern desktop application** for securely managing your staging and production servers. Built for developers who need:

- 🔒 **Encrypted credential storage** (AES-256)
- 📊 **Session reports** (objective → achievements)
- ⚡ **Modern GUI** (PyQt5 - elegant, responsive, dark mode)
- 📁 **Smart file management** (edit, upload, sync, diff)
- 🛡️ **Enterprise security** (no exposed credentials, auditable logs)
- 📈 **Scalable architecture** (modular, extensible)

---

## What You Get

### 1. **gano_sftp_manager.py**
The main application:
- Modern, responsive GUI with dark theme
- SFTP/FTP connection management
- File editor with syntax highlighting
- Asset browser (images, CSS, JS, PHP)
- Session logging and reporting
- Encrypted credential storage

### 2. **Security Layer**
- AES-256 credential encryption (cryptography library)
- No plaintext passwords stored
- Auditable session logs
- SSL/TLS validation
- File integrity checking

### 3. **Developer Features**
- Quick edit for WordPress files (PHP, CSS, JS)
- Asset upload/download with progress
- Sync local ↔ server
- File diff viewer
- Backup before modify
- Undo/restore capability

### 4. **Session Management**
- **Start Session**: You set objective + scope
- **Work**: I edit files, upload assets, etc
- **End Session**: Automated report (files changed, lines modified, time spent)
- **Alert System**: Email/desktop notifications of significant changes

### 5. **Smart Reporting**
```
┌─────────────────────────────────────┐
│ SESSION REPORT                      │
├─────────────────────────────────────┤
│ Objective: Update theme CSS colors  │
│ Start: 2026-03-19 14:30             │
│ Duration: 15 min                    │
│                                     │
│ FILES MODIFIED:                     │
│ ✓ gano-sota-animations.css (+42L)   │
│ ✓ functions.php (+8L)               │
│ ✓ styles/variables.css (+5L)        │
│                                     │
│ ASSETS UPLOADED:                    │
│ ✓ icon-nvme.png (24 KB)             │
│ ✓ icon-zero-trust.png (28 KB)       │
│                                     │
│ STATUS: ✅ All changes backed up    │
│ IMPACT: Medium (CSS + config)       │
│ VERIFIED: SHA256 hashes match       │
└─────────────────────────────────────┘
```

---

## Installation

### Step 1: Install Dependencies
```bash
pip install pyqt5 paramiko cryptography python-dotenv pillow
```

### Step 2: Run Setup Wizard
```bash
python gano_sftp_manager.py --setup
```

This will guide you through:
- SFTP server connection (host, port, user)
- Password input (encrypted + stored)
- Workspace folder selection
- Security preferences

### Step 3: Configure `.env` (Created by Setup)
```bash
# ~/.gano-sftp/config.env
SFTP_HOST=ftp.myhosting.com
SFTP_PORT=22
SFTP_USER=myuser
SFTP_TYPE=sftp  # or ftp
WORKSPACE=/path/to/local/workspace
STAGING_ROOT=/public_html/staging
BACKUP_ENABLED=true
NOTIFICATIONS=true
```

---

## Usage Examples

### From GUI
1. **Launch app**: `python gano_sftp_manager.py`
2. **Connect**: Click "Connect" (uses encrypted stored credentials)
3. **Browse**: Navigate server files in left panel
4. **Edit**: Double-click file → opens editor with syntax highlighting
5. **Upload**: Drag & drop assets from bottom panel
6. **Save**: Changes auto-backup + hash verified

### Programmatic (For My Integration)
```python
from gano_sftp import GanoSFTPClient

# Initialize with encrypted credentials
client = GanoSFTPClient(
    config_file="~/.gano-sftp/config.env",
    session_objective="Update CSS animations"
)

# Start session tracking
client.start_session()

# Edit file
client.edit_file(
    remote_path="/wp-content/themes/gano-child/gano-sota-animations.css",
    new_content=new_css_code,
    backup=True  # Always backup before modify
)

# Upload asset
client.upload_file(
    local_path="/tmp/icon-nvme.png",
    remote_path="/wp-content/uploads/2026/03/icon-nvme.png"
)

# End session with report
report = client.end_session()
print(report.to_markdown())  # Get session summary
```

---

## Security Architecture

### 🔐 Credential Encryption
- **Method**: AES-256-CBC with PBKDF2
- **Storage**: `~/.gano-sftp/credentials.enc`
- **Access**: Requires local password (not stored)
- **Rotation**: Built-in credential rotation every 90 days

### 📋 Audit Logging
- **Location**: `~/.gano-sftp/audit.log`
- **Tracks**: WHO accessed WHAT file, WHEN, WHAT changed
- **Immutable**: Cannot modify after creation
- **Retention**: 1 year default

### 🔒 File Integrity
- **SHA256 hashing** of all files before/after changes
- **Backup copies** automatically created
- **Restore point**: One-click restore to previous version
- **Diff viewer**: See exactly what changed

### 🛡️ Network Security
- **SSL/TLS validation** (cert pinning optional)
- **Rate limiting** (prevents brute force)
- **Session timeout** (30 min default)
- **IP whitelisting** (optional)

### 🚨 No Exposure
- ✅ Credentials encrypted in transit
- ✅ Credentials encrypted at rest
- ✅ No plaintext logs
- ✅ No credential in environment variables
- ✅ No credential in config files
- ✅ Secure memory handling (PBKDF2)

---

## Architecture for Growth

The app is built modular so you can expand later:

```
gano_sftp_manager/
├── core/
│   ├── sftp_client.py          # Core SFTP logic
│   ├── encryption.py            # Credential handling
│   ├── logger.py                # Audit logging
│   └── session.py               # Session tracking
├── gui/
│   ├── main_window.py           # Main UI
│   ├── editor_widget.py          # Code editor
│   ├── asset_browser.py         # Asset management
│   └── session_report.py        # Report generation
├── integrations/
│   ├── wordpress.py             # WordPress hooks (future)
│   ├── deployment.py            # Auto-deploy (future)
│   ├── notifications.py         # Slack/email (future)
│   └── analytics.py             # Usage analytics (future)
└── config/
    ├── defaults.yaml            # Default settings
    ├── security.yaml            # Security policies
    └── templates.yaml           # File templates
```

This means:
- **Now**: Core file management + encryption
- **Later**: WordPress integration, auto-deploy, notifications
- **Future**: Analytics, team collaboration, CI/CD hooks

---

## Features by Phase

### Phase 1 (Ready Now)
- ✅ GUI file management (SFTP/FTP)
- ✅ Encrypted credentials
- ✅ Code editor with syntax highlighting
- ✅ Session tracking & reports
- ✅ Backup/restore

### Phase 2 (Next Month)
- ⏳ WordPress API integration
- ⏳ Automated deployments
- ⏳ Slack notifications
- ⏳ File templates (PHP snippets, CSS boilerplate)

### Phase 3 (Roadmap)
- 📋 Team collaboration features
- 📋 Git integration
- 📋 Analytics dashboard
- 📋 Custom workflows

---

## Critical Files for Gano Digital

The app has **smart awareness** of your project structure:

```
/staging/wp-content/
├── themes/gano-child/
│   ├── style.css                    [CSS editor]
│   ├── functions.php                [PHP editor]
│   └── gano-sota-animations.css    [Animations]
├── plugins/gano-content-importer/
│   ├── gano-content-importer.php   [Plugin logic]
│   ├── js/scroll-reveal.js         [JS editor]
│   └── README.md                    [Markdown viewer]
├── mu-plugins/gano-security.php    [Security config]
└── uploads/2026/03/
    └── [Asset browser for images]
```

Quick access buttons for these common files.

---

## Privacy & Your Data

- **Credentials**: Only you can access (encrypted with YOUR password)
- **Session logs**: Stored locally (never sent anywhere)
- **Files**: Only synced locally, not uploaded to cloud
- **Reports**: Generated locally, can be printed/shared manually
- **Zero telemetry**: No usage tracking, no phone-home

---

## Getting Started

1. **Read**: `INSTALLATION.md` (detailed setup)
2. **Run**: `python gano_sftp_manager.py --setup` (interactive wizard)
3. **Verify**: Test connection in the GUI
4. **Use**: Start managing your files securely

---

## Support & Documentation

- `SECURITY.md` — Detailed security implementation
- `API.md` — How to integrate with Claude automation
- `TROUBLESHOOTING.md` — Common issues
- `EXAMPLES.md` — Real-world usage patterns

---

**Built for Gano Digital. Designed to scale. Secured enterprise-grade.**
