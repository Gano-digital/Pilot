# Gano Digital SFTP Manager — API Reference

This document describes how to integrate Gano Digital SFTP Manager into Claude automation workflows.

---

## Quick Start

### Import the Library

```python
from gano_sftp_manager import (
    GanoSFTPClient,
    CredentialManager,
    SessionReport,
    FileChange
)
```

### Basic Connection

```python
# Step 1: Create credential manager
cred_mgr = CredentialManager()

# Step 2: Load encrypted credentials
credentials = cred_mgr.load_credentials("your_master_password")

# Step 3: Create SFTP client
client = GanoSFTPClient(
    host=credentials['host'],
    port=credentials['port'],
    username=credentials['username'],
    password=credentials['password']
)

# Step 4: Connect
if client.connect():
    print("✅ Connected to server")
else:
    print("❌ Connection failed")
```

---

## CredentialManager Class

Handles encrypted storage and retrieval of SFTP credentials.

### Methods

#### `save_credentials(master_password: str, credentials: Dict) -> bool`

Save SFTP credentials encrypted with master password.

**Parameters**:
- `master_password` (str): Master password for encryption
- `credentials` (Dict): Dictionary with keys:
  - `host` (str): SFTP server address
  - `port` (int): SFTP port (usually 22)
  - `username` (str): FTP username
  - `password` (str): FTP password

**Returns**: `True` if saved successfully, `False` on error

**Example**:
```python
cred_mgr = CredentialManager()
creds = {
    'host': 'ftp.hosting.com',
    'port': 22,
    'username': 'diego',
    'password': 'secure_password'
}
if cred_mgr.save_credentials("my_master_pass", creds):
    print("✅ Credentials saved")
```

#### `load_credentials(master_password: str) -> Optional[Dict]`

Load and decrypt SFTP credentials.

**Parameters**:
- `master_password` (str): Master password for decryption

**Returns**: Dictionary with decrypted credentials, or `None` if wrong password/no credentials

**Example**:
```python
cred_mgr = CredentialManager()
credentials = cred_mgr.load_credentials("my_master_pass")
if credentials:
    print(f"✅ Loaded credentials for {credentials['username']}")
else:
    print("❌ Failed to load credentials (wrong password?)")
```

---

## GanoSFTPClient Class

Main client for SFTP operations with session tracking.

### Initialization

```python
client = GanoSFTPClient(
    host="ftp.example.com",
    port=22,
    username="user",
    password="pass"
)
```

### Methods

#### `connect() -> bool`

Establish SFTP connection to server.

**Returns**: `True` if connected, `False` if connection failed

**Example**:
```python
if client.connect():
    print("✅ Connected")
else:
    print("❌ Connection failed")
```

#### `disconnect() -> None`

Close SFTP connection.

**Example**:
```python
client.disconnect()
print("✅ Disconnected")
```

#### `list_files(remote_path: str) -> List[str]`

List files in remote directory.

**Parameters**:
- `remote_path` (str): Remote path (e.g., `/wp-content/themes/`)

**Returns**: List of filenames in directory

**Example**:
```python
files = client.list_files("/wp-content/themes/gano-child/")
for file in files:
    print(f"  - {file}")
```

#### `download_file(remote_path: str, local_path: str) -> bool`

Download file from server.

**Parameters**:
- `remote_path` (str): Full remote file path
- `local_path` (str): Local file path to save to

**Returns**: `True` if successful, `False` if failed

**Example**:
```python
if client.download_file("/wp-content/themes/gano-child/style.css", "local_style.css"):
    print("✅ Downloaded")
else:
    print("❌ Download failed")
```

#### `upload_file(local_path: str, remote_path: str) -> bool`

Upload file to server. Automatically tracks in current session.

**Parameters**:
- `local_path` (str): Local file path
- `remote_path` (str): Full remote destination path

**Returns**: `True` if successful, `False` if failed

**Example**:
```python
if client.upload_file("gano-sota-animations.css", "/wp-content/themes/gano-child/gano-sota-animations.css"):
    print("✅ Uploaded")
else:
    print("❌ Upload failed")
```

#### `start_session(objective: str, scope: str = "standard") -> None`

Begin session tracking for subsequent operations.

**Parameters**:
- `objective` (str): Goal of the session (e.g., "Update CSS animations")
- `scope` (str): Impact level - `"standard"`, `"minor"`, `"major"`, or `"critical"`

**Example**:
```python
client.start_session(
    objective="Update SOTA page animations",
    scope="major"
)
print("✅ Session started")
```

#### `end_session() -> SessionReport`

End session and return comprehensive report.

**Returns**: `SessionReport` object with session details

**Example**:
```python
report = client.end_session()
print(f"✅ Session completed in {report.total_duration_seconds:.0f} seconds")
print(f"   Files modified: {len(report.files_modified)}")
print(report.to_markdown())  # Markdown-formatted report
```

---

## SessionReport Class

Dataclass representing a completed session.

### Attributes

```python
@dataclass
class SessionReport:
    session_id: str                          # Unique session ID (UUID)
    objective: str                           # Session objective
    scope: str                              # Impact level
    start_time: datetime.datetime           # When session started
    end_time: Optional[datetime.datetime]   # When session ended
    files_modified: List[FileChange]        # All file modifications
    total_duration_seconds: float           # Total duration
    status: str                             # 'in_progress', 'completed', 'error'
```

### Methods

#### `to_markdown() -> str`

Generate markdown-formatted session report.

**Returns**: Formatted markdown string

**Example**:
```python
report = client.end_session()
markdown = report.to_markdown()

# Save to file
with open("session_report.md", "w") as f:
    f.write(markdown)

# Or print
print(markdown)
```

**Output Example**:
```markdown
# Session Report

**Objective:** Update SOTA page animations
**Scope:** major
**Session ID:** a1b2c3d4

## Timeline
- **Started:** 2026-03-19 14:30:45
- **Ended:** 2026-03-19 14:45:22
- **Duration:** 14.6 minutes

## Files Modified (3)

### UPLOAD: `/wp-content/themes/gano-child/gano-sota-animations.css`
- Size: 0 → 14240 bytes
- Backup: ✅ `~/.gano-sftp/backups/gano-sota-animations.css`

### UPLOAD: `/wp-content/plugins/gano-content-importer/js/scroll-reveal.js`
- Size: 0 → 3892 bytes
- Backup: ✅ `~/.gano-sftp/backups/scroll-reveal.js`

...

## Status
✅ **COMPLETED**
All changes verified with SHA256 hashes and backed up.
```

---

## FileChange Class

Dataclass tracking individual file modifications.

### Attributes

```python
@dataclass
class FileChange:
    path: str                # Remote file path
    action: str             # 'edit', 'upload', 'download', 'delete'
    timestamp: datetime     # When changed
    lines_added: int        # Lines added (code files)
    lines_removed: int      # Lines removed (code files)
    size_before: int        # Size before (bytes)
    size_after: int         # Size after (bytes)
    hash_before: str        # SHA256 before
    hash_after: str         # SHA256 after
    backup_path: str        # Backup file location
```

---

## Practical Examples

### Example 1: Update WordPress Theme

```python
from gano_sftp_manager import GanoSFTPClient, CredentialManager

# Load credentials
cred_mgr = CredentialManager()
creds = cred_mgr.load_credentials("master_password")

# Connect
client = GanoSFTPClient(**creds)
client.connect()

# Start session
client.start_session(
    objective="Update gano-child theme animations",
    scope="major"
)

# Upload CSS
client.upload_file(
    "gano-sota-animations.css",
    "/wp-content/themes/gano-child/gano-sota-animations.css"
)

# Upload JavaScript
client.upload_file(
    "scroll-reveal.js",
    "/wp-content/plugins/gano-content-importer/js/scroll-reveal.js"
)

# End session and get report
report = client.end_session()
print(report.to_markdown())

# Cleanup
client.disconnect()
```

### Example 2: Batch File Upload

```python
from pathlib import Path

# Upload all CSS files to theme
css_dir = Path("./css")
for css_file in css_dir.glob("*.css"):
    remote_path = f"/wp-content/themes/gano-child/{css_file.name}"
    if client.upload_file(str(css_file), remote_path):
        print(f"✅ Uploaded {css_file.name}")
    else:
        print(f"❌ Failed {css_file.name}")
```

### Example 3: Backup Before Modification

```python
# Download file for backup
client.download_file(
    "/wp-content/themes/gano-child/style.css",
    "./backup_style.css"
)

# Modify local copy
with open("backup_style.css", "r") as f:
    css = f.read()

# Add new styles
css += """
/* New styles */
.gano-sota { animation: fadeInUp 0.6s ease-out; }
"""

# Save modified
with open("style.css", "w") as f:
    f.write(css)

# Upload modified
client.upload_file("style.css", "/wp-content/themes/gano-child/style.css")
```

### Example 4: Automated Deployment

```python
#!/usr/bin/env python3

import json
from pathlib import Path
from gano_sftp_manager import GanoSFTPClient, CredentialManager

# Load config
with open("deploy_config.json") as f:
    config = json.load(f)

# Load credentials
cred_mgr = CredentialManager()
creds = cred_mgr.load_credentials(config['master_password'])

# Connect
client = GanoSFTPClient(**creds)
if not client.connect():
    print("❌ Connection failed")
    exit(1)

# Start deployment session
client.start_session(
    objective=f"Deploy {config['version']}",
    scope="critical"
)

# Upload all files in deploy list
for file_config in config['files']:
    local = file_config['local']
    remote = file_config['remote']

    print(f"📤 Uploading {Path(local).name}...")
    client.upload_file(local, remote)

# Get report
report = client.end_session()

# Save report
report_file = f"deploy_{report.session_id}.md"
with open(report_file, "w") as f:
    f.write(report.to_markdown())

print(f"✅ Deployment complete: {report_file}")
client.disconnect()
```

### Example 5: Claude Automation Integration

```python
# For Claude to use when automating file updates

async def claude_deploy_sota_pages():
    """Claude's automation: Deploy SOTA content"""
    from gano_sftp_manager import GanoSFTPClient, CredentialManager

    # Load Diego's encrypted credentials
    cred_mgr = CredentialManager()
    master_pwd = os.environ['GANO_MASTER_PASSWORD']  # Set by Diego
    creds = cred_mgr.load_credentials(master_pwd)

    # Connect
    client = GanoSFTPClient(**creds)
    if not client.connect():
        send_alert("SFTP connection failed")
        return

    # Start session with clear objective
    client.start_session(
        objective="Deploy 20 SOTA pages with animations",
        scope="major"
    )

    # Upload plugin
    client.upload_file(
        "gano-content-importer-v2.0.php",
        "/wp-content/plugins/gano-content-importer/gano-content-importer.php"
    )

    # Upload animations
    client.upload_file(
        "gano-sota-animations.css",
        "/wp-content/themes/gano-child/gano-sota-animations.css"
    )

    # Upload JS
    client.upload_file(
        "scroll-reveal.js",
        "/wp-content/plugins/gano-content-importer/js/scroll-reveal.js"
    )

    # End session
    report = client.end_session()

    # Send report to Diego
    send_email_report(
        to="diego@gano.digital",
        subject=f"✅ SOTA Pages Deployed ({report.session_id})",
        body=report.to_markdown()
    )

    client.disconnect()
```

---

## Error Handling

### Connection Errors

```python
if not client.connect():
    # Handle connection failure
    print(f"❌ Connection failed: {client.host}:{client.port}")
    # Possible causes:
    # - Wrong host/port
    # - Server down
    # - Authentication failed
```

### Decryption Errors

```python
creds = cred_mgr.load_credentials("wrong_password")
if creds is None:
    print("❌ Wrong master password")
    # User must enter correct password
```

### Upload/Download Errors

```python
try:
    client.upload_file(local, remote)
except Exception as e:
    print(f"❌ Upload failed: {e}")
    # Possible causes:
    # - File not found
    # - Permission denied
    # - Disk full
    # - Network error
```

---

## Best Practices

### 1. Always Use Sessions

```python
# ✅ Good: Track work with sessions
client.start_session("objective here", "scope")
# ... do work ...
report = client.end_session()

# ❌ Bad: No tracking
# ... do work without session ...
```

### 2. Handle Disconnection

```python
try:
    client.connect()
    # ... operations ...
finally:
    client.disconnect()  # Always cleanup
```

### 3. Verify Uploads

```python
# Upload and verify integrity
if client.upload_file(local, remote):
    # Download to verify
    client.download_file(remote, "/tmp/verify.txt")
    # Check hash matches (automatic in Session Report)
```

### 4. Store Master Password Securely

```python
# ❌ Bad
master_pwd = "hardcoded_password"

# ✅ Good
import os
master_pwd = os.environ['GANO_MASTER_PASSWORD']
# Or use getpass
import getpass
master_pwd = getpass.getpass("Master password: ")
```

### 5. Save Reports

```python
report = client.end_session()

# Save to file
import json
with open(f"session_{report.session_id}.md", "w") as f:
    f.write(report.to_markdown())

# Or send via email
send_report_email(report.to_markdown())
```

---

## Troubleshooting

### "Module not found: gano_sftp_manager"

```bash
# Make sure you're in the correct directory
cd /path/to/gano-sftp-manager

# Or add to Python path
export PYTHONPATH=$PYTHONPATH:/path/to/gano-sftp-manager
```

### "TypeError: expected str, got NoneType"

Usually means credentials dict is None (wrong password):

```python
creds = cred_mgr.load_credentials(wrong_pwd)  # Returns None
client = GanoSFTPClient(**creds)  # TypeError!

# Fix:
creds = cred_mgr.load_credentials(correct_pwd)
if creds:
    client = GanoSFTPClient(**creds)
else:
    print("Wrong password")
```

### "Connection timeout"

Server not responding or port is wrong:

```python
# Increase timeout
client.timeout = 30  # seconds
```

---

## Version Information

- **Version**: 1.0.0
- **Python**: 3.9+
- **Last Updated**: 2026-03-19

---

## Support

For issues, feature requests, or questions:

- 📧 Email: support@ganodigital.com
- 📋 GitHub: [issue tracking]
- 💬 Slack: [internal team]
