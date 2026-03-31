# Gano Digital SFTP Manager — Installation & Setup

## Prerequisites

- Python 3.9+
- PyQt5
- Paramiko (SFTP library)
- cryptography (for AES-256 encryption)
- Linux, macOS, or Windows with Python installed

## Step 1: Install Dependencies

```bash
pip install pyqt5 paramiko cryptography python-dotenv pillow --break-system-packages
```

Or with conda:

```bash
conda create -n gano-sftp python=3.9
conda activate gano-sftp
conda install pyqt5 paramiko cryptography pillow
pip install python-dotenv
```

## Step 2: Verify Installation

Test that all dependencies are properly installed:

```bash
python3 -c "import PyQt5, paramiko, cryptography; print('✅ All dependencies installed')"
```

## Step 3: Run the Application

```bash
python3 gano_sftp_manager.py
```

The application window will open. The first time you run it, it will create a configuration directory:

```
~/.gano-sftp/
├── credentials.enc    (encrypted connection credentials)
├── .master           (master password hash)
├── audit.log         (immutable action log)
└── reports/          (session reports directory)
```

## Step 4: First Time Setup

### 4.1 Enter Connection Details

In the **File Manager** tab:

1. **Host**: Your SFTP server address (e.g., `ftp.hosting.com`)
2. **Port**: SFTP port (usually 22, FTP is 21)
3. **Username**: Your FTP account username
4. **Password**: Your FTP account password
5. Click **🔗 Connect**

### 4.2 Set Master Password (Optional but Recommended)

The first time you connect, optionally set a master password via the **Security** tab:

```
🔑 Change Master Password → Enter a strong password
```

This encrypts your stored credentials with AES-256-CBC.

## Step 5: Start Managing Files

### File Manager

- Navigate to **Remote Path** (default: `/`)
- Click **🔄 Refresh** to list files
- **Upload** files to server
- **Download** files to local machine
- All operations are logged

### Session Tracking

1. Navigate to **Session Tracker** tab
2. Enter **Session Objective** (e.g., "Update CSS animations for SOTA pages")
3. Select **Scope** (standard/minor/major/critical)
4. Click **▶️ Start Session**
5. Make changes (upload files, edit files, etc.)
6. Click **⏹️ End Session & Report**

### Session Reports

- Reports are saved automatically to `~/.gano-sftp/reports/`
- View reports in the **Reports & History** tab
- Each report includes:
  - Objective and scope
  - Files modified
  - Duration
  - Status and hash verification

### Security

- All credentials are encrypted with **AES-256-CBC**
- Master password uses **PBKDF2** with 100,000 iterations
- Every action is logged in **audit.log**
- File integrity verified with **SHA256** hashes

## Troubleshooting

### "Connection refused" error

**Cause**: Wrong host, port, or server is down

**Solution**:
1. Verify host/port are correct
2. Test with external tool: `sftp -P 22 user@host`
3. Check server status

### "Authentication failed"

**Cause**: Wrong username/password

**Solution**:
1. Verify credentials in hosting control panel
2. Some hosts require specific FTP ports (21) vs SFTP (22)
3. Reset password in hosting panel and retry

### "Permission denied" on upload

**Cause**: Insufficient permissions on server

**Solution**:
1. Check file permissions in hosting control panel
2. Upload to writable directory (usually `public_html`)
3. Contact hosting support

### "PyQt5 not found"

**Cause**: Dependencies not installed

**Solution**:
```bash
pip install PyQt5 --break-system-packages
```

### "Master password incorrect"

**Cause**: Forgotten master password

**Solution**:
1. Delete `~/.gano-sftp/credentials.enc`
2. Delete `~/.gano-sftp/.master`
3. Reconnect and set new master password

## File Organization

Your configuration is stored in:

```
~/.gano-sftp/
├── credentials.enc      ← Encrypted SFTP credentials (AES-256)
├── .master             ← Master password hash (PBKDF2)
├── audit.log           ← All actions logged here
└── reports/
    ├── session_a1b2c3d4_20260319_145030.md
    ├── session_e5f6g7h8_20260319_153045.md
    └── ... (more reports)
```

## Advanced: Command Line Usage

You can also use the SFTP manager programmatically:

```python
from gano_sftp_manager import GanoSFTPClient, CredentialManager

# Initialize with encrypted credentials
cred_mgr = CredentialManager()
creds = cred_mgr.load_credentials("your_master_password")

client = GanoSFTPClient(
    host=creds['host'],
    port=creds['port'],
    username=creds['username'],
    password=creds['password']
)

# Connect and track session
client.connect()
client.start_session("Update theme CSS", "minor")

# Upload file
client.upload_file("local_file.css", "/wp-content/themes/gano-child/style.css")

# End session with report
report = client.end_session()
print(report.to_markdown())
```

## Security Best Practices

1. **Keep master password secure** — Store separately from application
2. **Enable 2FA** on your hosting account
3. **Regular backups** — Always backup important files before modifications
4. **Review audit logs** — Check `~/.gano-sftp/audit.log` regularly
5. **Don't share config** — Never share the `.gano-sftp` directory
6. **SSH key auth** — For maximum security, use SSH keys instead of passwords (Paramiko supports this)

## Updates & Maintenance

### Updating the Application

Download latest version and replace `gano_sftp_manager.py`:

```bash
# Backup current version
cp gano_sftp_manager.py gano_sftp_manager.py.bak

# Install new version
# (Replace with new file)

# Your configuration directory is preserved
# ~/.gano-sftp/ remains unchanged
```

### Rotating Master Password

1. Go to **Security** tab
2. Click **🔑 Change Master Password**
3. Enter current password
4. Enter new password (twice to confirm)
5. Old credentials are re-encrypted with new password

### Viewing Audit Log

Access all actions in:

```bash
cat ~/.gano-sftp/audit.log
```

Each entry shows:
- Timestamp (ISO format)
- Action type (upload, download, connect, etc.)
- File details
- Additional notes

---

**Created**: 2026-03-19
**Version**: 1.0.0
**Built for**: Gano Digital SFTP Management
