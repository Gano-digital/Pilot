# Gano Digital SFTP Manager — Troubleshooting Guide

## Common Issues & Solutions

### Connection Issues

#### ❌ "Connection refused" or "No route to host"

**Symptoms**:
```
ConnectionRefusedError: [Errno 111] Connection refused
```

**Causes**:
1. Wrong host address
2. Wrong port number
3. Server is down
4. Firewall blocking connection

**Solutions**:
```bash
# Test connection manually
ping ftp.hosting.com

# Test SSH connectivity
ssh -p 22 username@ftp.hosting.com

# Check host and port are correct in your config
cat ~/.gano-sftp/config.env
```

---

#### ❌ "Connection timed out"

**Symptoms**:
```
TimeoutError: [Errno 110] Connection timed out
```

**Causes**:
1. Server not responding
2. Network connectivity issue
3. Firewall blocking outgoing connections
4. ISP blocking ports

**Solutions**:
```bash
# Test internet connection
ping 8.8.8.8

# Increase timeout (edit application)
# client.timeout = 30  # seconds

# Try different port
# Standard SFTP: 22
# Some hosts use: 2222, 30000, etc.
# Check with hosting provider
```

---

### Authentication Issues

#### ❌ "Authentication failed" or "Permission denied"

**Symptoms**:
```
paramiko.ssh_exception.AuthenticationException: Authentication failed
```

**Causes**:
1. Wrong username/password
2. Account locked (too many failed attempts)
3. Wrong authentication method
4. Account expired

**Solutions**:

Step 1: Verify credentials in hosting control panel
```bash
# Check your hosting provider's control panel
# For cPanel: cPanel > FTP Accounts
# For Plesk: Plesk > Files > FTP Access > FTP Accounts
```

Step 2: Reset password if needed
```bash
# Most hosting providers allow password reset in control panel
# 1. Go to FTP/SFTP accounts
# 2. Click "Change Password" for your account
# 3. Set new password
# 4. Wait 5 minutes for changes to propagate
```

Step 3: Delete old credentials and reconnect
```bash
# Remove encrypted credentials
rm ~/.gano-sftp/credentials.enc

# Delete master password hash
rm ~/.gano-sftp/.master

# Restart application and enter new credentials
python3 gano_sftp_manager.py
```

Step 4: Check account status
```bash
# Some hosts limit concurrent connections
# Log out of FTP elsewhere (FileZilla, etc.)
# Try connecting again
```

---

#### ❌ "Wrong password" when loading credentials

**Symptoms**:
```
❌ Error loading credentials: (error from cryptography library)
```

**Causes**:
1. Entered wrong master password
2. Credentials file corrupted
3. Master password was changed

**Solutions**:
```bash
# Reset credentials (you'll need to re-enter FTP details)
rm ~/.gano-sftp/credentials.enc ~/.gano-sftp/.master

# Then restart the application
python3 gano_sftp_manager.py
# You'll be prompted to enter credentials again
```

---

### File Operation Issues

#### ❌ "Permission denied" when uploading

**Symptoms**:
```
IOError: [Errno 13] Permission denied
```

**Causes**:
1. Directory doesn't exist
2. Incorrect directory permissions
3. Uploading to wrong location
4. Account doesn't have write permissions

**Solutions**:

Step 1: Verify target directory exists
```bash
# List directories via File Manager tab
# Or use SSH
ssh user@host
ls -la /wp-content/themes/

# If directory doesn't exist, create it:
mkdir -p /wp-content/themes/gano-child
```

Step 2: Check directory permissions
```bash
# Via SSH
chmod 755 /wp-content/themes/gano-child
chmod 755 /wp-content/plugins/

# Files should be 644
chmod 644 /wp-content/themes/gano-child/style.css
```

Step 3: Verify account has write access
```bash
# Contact hosting provider if you lack write permissions
# Usually cPanel accounts have write access by default
```

---

#### ❌ "File not found" when downloading

**Symptoms**:
```
FileNotFoundError: /path/to/file.css
IOError: [Errno 2] No such file or directory
```

**Causes**:
1. Wrong file path
2. File doesn't exist on server
3. Typo in filename

**Solutions**:

Step 1: List files to verify path
```python
# In application: File Manager tab
# Refresh files in target directory
# Click desired file to see full path
```

Step 2: Check file path manually
```bash
ssh user@host
ls -la /wp-content/themes/gano-child/
# Verify file.css exists and show exact path
```

Step 3: Use correct path
```python
# If file is in /wp-content/themes/gano-child/
client.download_file(
    "/wp-content/themes/gano-child/style.css",
    "./local_style.css"
)
```

---

#### ❌ "Upload failed" with no error message

**Symptoms**:
```
❌ Upload failed
```

**Causes**:
1. Disk full on server
2. Connection dropped mid-upload
3. File too large
4. Network timeout

**Solutions**:

Step 1: Check server disk space
```bash
ssh user@host
df -h /home/
# Check if disk is full
```

Step 2: Try uploading smaller file
```python
# If file is large (>100MB), split it
# Upload one part at a time
```

Step 3: Check connection
```bash
# Ensure connection is active
# Use "Refresh" in File Manager tab
```

Step 4: Try again with longer timeout
```python
# Edit gano_sftp_manager.py
# Increase timeout: client.timeout = 30
```

---

### Session & Reporting Issues

#### ❌ Session reports not saving

**Symptoms**:
```
No reports appear in "Reports & History" tab
```

**Causes**:
1. Reports directory doesn't exist
2. Permission denied on reports directory
3. Session was never ended

**Solutions**:

Step 1: Create reports directory
```bash
mkdir -p ~/.gano-sftp/reports
chmod 700 ~/.gano-sftp/reports
```

Step 2: Check permissions
```bash
ls -la ~/.gano-sftp/
# Should show rwx for reports directory
```

Step 3: End session properly
```python
# Make sure you click "⏹️  End Session & Report"
# Don't just close the application
```

---

#### ❌ "Permission denied" accessing reports directory

**Symptoms**:
```
PermissionError: [Errno 13] Permission denied: '~/.gano-sftp/reports'
```

**Causes**:
1. Directory has wrong permissions
2. User doesn't own directory

**Solutions**:
```bash
# Fix permissions
chmod 700 ~/.gano-sftp/reports

# Check ownership
ls -la ~/.gano-sftp/
# Should show your username as owner

# If wrong owner, fix it
chown -R $USER:$USER ~/.gano-sftp/
```

---

### GUI & Application Issues

#### ❌ "PyQt5 not found"

**Symptoms**:
```
ModuleNotFoundError: No module named 'PyQt5'
```

**Causes**:
1. PyQt5 not installed
2. Wrong Python environment

**Solutions**:
```bash
# Install PyQt5
pip install PyQt5 --break-system-packages

# Or with conda
conda install pyqt5

# Verify installation
python3 -c "import PyQt5; print('✅ PyQt5 installed')"
```

---

#### ❌ Application crashes on startup

**Symptoms**:
```
Segmentation fault
or
(Segmentation fault (core dumped))
```

**Causes**:
1. Corrupted Qt libraries
2. Display server not available
3. Conflicting PyQt5 versions

**Solutions**:

Step 1: Reinstall PyQt5
```bash
pip uninstall PyQt5
pip install PyQt5 --break-system-packages
```

Step 2: Run in headless mode (if on remote server)
```bash
# Set display
export DISPLAY=:0

# Or use SSH X forwarding
ssh -X user@host
```

Step 3: Use environment variable to debug
```bash
export QT_DEBUG_PLUGINS=1
python3 gano_sftp_manager.py 2>&1 | head -50
```

---

#### ❌ UI doesn't render correctly (buttons/text cut off)

**Symptoms**:
- Text is too small/large
- Buttons are cut off
- Layout is broken

**Causes**:
1. High DPI display (4K, Retina)
2. Window too small
3. Font size issue

**Solutions**:

Step 1: Resize window
```python
# Edit gano_sftp_manager.py
# Change window size:
self.setGeometry(100, 100, 1600, 1000)  # Larger size
```

Step 2: Increase font sizes
```python
# In apply_dark_theme():
# Increase font sizes for displays
```

Step 3: Enable high DPI scaling
```python
# Add to beginning of main():
os.environ['QT_SCALE_BY_DPI_TYPE'] = 'logical'
os.environ['QT_AUTO_SCREEN_SCALE_FACTOR'] = '1'
```

---

### Performance Issues

#### ❌ Application is slow/freezes when listing large directories

**Symptoms**:
- Clicking "Refresh" makes app freeze
- Takes 10+ seconds to list files

**Causes**:
1. Large number of files (1000+)
2. Slow network connection
3. Server response slow

**Solutions**:

Step 1: Use specific directory
```python
# Instead of listing /
# List specific directory:
# Remote Path: /wp-content/themes/gano-child/
```

Step 2: Increase timeout
```python
# Edit GanoSFTPClient.list_files()
# Add timeout parameter
```

Step 3: Use pagination
```python
# List files in batches instead of all at once
# (Feature for Phase 2)
```

---

#### ❌ Upload is very slow

**Symptoms**:
- File takes forever to upload
- Speed shows <100 KB/s

**Causes**:
1. Slow internet connection
2. Server throttling
3. Large file size
4. Server busy

**Solutions**:

Step 1: Check internet speed
```bash
# Test connection speed
iperf3 -c speedtest.server.com

# Or use online tool
# https://speedtest.net
```

Step 2: Split large files
```python
# Don't upload 500MB files at once
# Split into 50MB chunks and upload separately
```

Step 3: Try different time
```bash
# Upload during off-peak hours
# Server might be less busy
```

---

### Data Loss Prevention

#### ❌ Accidentally deleted important file

**Symptoms**:
- File was deleted from server
- No backup available

**Solutions**:

Step 1: Check audit log for file location
```bash
grep "upload.*filename" ~/.gano-sftp/audit.log
# This shows when file was uploaded
```

Step 2: Contact hosting provider
```bash
# Some providers have server backups
# They may be able to restore the file
```

Step 3: For future prevention
```python
# Always use sessions
# Session reports show what changed
# Backups are created before modifications
```

---

## Diagnostic Commands

### Check Application Logs

```bash
# View last 100 audit entries
tail -100 ~/.gano-sftp/audit.log

# Filter by action type
grep "ACTION=upload" ~/.gano-sftp/audit.log | tail -20

# View session reports
ls -lah ~/.gano-sftp/reports/

# View most recent report
cat ~/.gano-sftp/reports/session_*.md | head -50
```

### Check Connection

```bash
# Test SSH connectivity
ssh -v -p 22 username@ftp.hosting.com

# Test SFTP directly
sftp -v -P 22 username@ftp.hosting.com
> ls
> quit
```

### Check Encryption

```bash
# Verify credentials file is encrypted
file ~/.gano-sftp/credentials.enc
# Should show: data

# Check file size (not readable)
ls -lah ~/.gano-sftp/credentials.enc

# Don't try to read it (binary data)
# hexdump -C ~/.gano-sftp/credentials.enc | head
```

### Check Permissions

```bash
# Check permissions on config directory
ls -la ~/.gano-sftp/
# Should be: drwx------ (700)

# Check credentials file permissions
ls -la ~/.gano-sftp/credentials.enc
# Should be: -rw------- (600)

# Check reports directory
ls -la ~/.gano-sftp/reports/
# Should be: drwx------ (700)
```

---

## Getting Help

### Provide Diagnostic Information

When reporting issues, include:

```bash
# 1. Python version
python3 --version

# 2. PyQt5 version
python3 -c "import PyQt5; print(PyQt5.__version__)"

# 3. Paramiko version
python3 -c "import paramiko; print(paramiko.__version__)"

# 4. OS information
uname -a

# 5. Recent audit log (remove sensitive info)
tail -20 ~/.gano-sftp/audit.log | sed 's/password.*/[REDACTED]/g'

# 6. Error message (from console output)
# When running: python3 gano_sftp_manager.py
```

### Contact Support

```
Email: support@ganodigital.com
Subject: [GANO SFTP] [Your Issue Title]

Include:
1. Description of problem
2. Steps to reproduce
3. Error messages
4. Diagnostic output from above
5. Expected behavior
6. Actual behavior
```

---

## FAQs

**Q: Is my password stored securely?**
A: Yes. Passwords are encrypted with AES-256-CBC using PBKDF2 key derivation. They're never stored in plaintext or visible logs.

**Q: What happens if I forget my master password?**
A: Delete `~/.gano-sftp/credentials.enc` and `~/.gano-sftp/.master`, then reconnect and set a new master password.

**Q: Can I use the same credentials for multiple hosts?**
A: Currently, only one set of credentials is supported. For multiple hosts, create separate GANO_SFTP instances.

**Q: Are my files backed up automatically?**
A: Yes. Before modifications, backups are created in `~/.gano-sftp/backups/`

**Q: Can I send session reports via email?**
A: Yes, in Phase 2. Currently, reports are saved locally and can be manually shared.

**Q: How long are reports kept?**
A: Indefinitely. They're stored in `~/.gano-sftp/reports/` and never auto-deleted.

**Q: Can I use SSH keys instead of passwords?**
A: Yes, in Phase 2 (coming soon). Currently, password authentication is supported.

---

**Last Updated**: 2026-03-19
**Version**: 1.0.0
