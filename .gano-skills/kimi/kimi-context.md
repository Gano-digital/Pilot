# Kimi Context for Gano Digital

## Project Overview

**Gano Digital** is a WordPress Reseller Platform with integrated Design System (SOTA). This is a complex Phase 4 project involving:
- WordPress theme customization and plugin management
- Complete design system implementation (colors, typography, motion, accessibility)
- Webhook integration with GoDaddy Reseller API
- E2E testing and deployment automation
- Multi-phase rollout strategy

Current Status: Phase 4 active, Blocks 1-5 completed in audit, webhook integration pending

## Current Goals

**Primary:** Complete Tasks 5-9 of Phase 4 (currently in execution)
**Secondary:** Maintain token efficiency, synchronize memory with Claude Code, automate repetitive tasks
**Tertiary:** Optimize CSS, ensure accessibility compliance (WCAG 2.1 AA), document all changes

## Codebase Structure

```
Gano.digital-copia/
├── .claude/                           # Claude Code settings
│   ├── settings.json                  # IDE hooks, Kimi integration config
│   ├── kimi-integration.md            # Integration guide for Kimi
│   └── projects/                      # Memory and project files
├── .gano-skills/                      # Gano Digital custom tools
│   └── kimi/                          # Kimi CLI integration
│       ├── kimi-config.yaml           # Kimi configuration
│       ├── kimi-context.md            # This file
│       ├── kimi-helper.ps1            # PowerShell helper script
│       ├── kimi-tasks.yaml            # Task templates
│       └── README.md                  # Kimi documentation
├── wp-content/
│   ├── themes/
│   │   └── gano-digital/              # Main theme
│   │       ├── style.css              # Foundation styles
│   │       ├── design-system.css      # Design system variables
│   │       ├── responsive.css         # Responsive breakpoints
│   │       └── accessibility.css      # Accessibility styles
│   └── plugins/
│       └── gano-reseller-integration/ # GoDaddy Reseller API
├── wiki/
│   └── dev-sessions/                  # Development session logs
├── .github/
│   └── workflows/                     # GitHub Actions (5 core workflows in main)
└── index.php                          # WordPress entry point
```

## Recent Commits

| Task | Commit SHA | Message |
|------|-----------|---------|
| Tasks 1-4 Setup | abc123... | Initial Kimi setup: config, context, helper, tasks templates |
| Audit Phase 4 Block 1-4 | def456... | Block 4 homepage enriquecida con SOTA copy |
| CTA Registration Audit | ghi789... | Fase 2 audit complete: 6 bloques, 127 líneas netas |
| Deployment to Server | jkl012... | SCP deployment: 3 files to server, permisos ajustados |

## Remaining Tasks (Phase 4: Tasks 5-9)

| Task | Owner | Status | Notes |
|------|-------|--------|-------|
| Task 5 | Claude Code | Pending | Webhook integration testing |
| Task 6 | Kimi | Pending | CSS optimization & accessibility audit |
| Task 7 | Claude Code | Pending | E2E test automation |
| Task 8 | Kimi | Pending | Memory sync & documentation |
| Task 9 | Both | Pending | Final verification & production deployment |

## Design System Standards

### Colors
- Primary: #2563eb (Tailwind blue-600)
- Secondary: #10b981 (Tailwind emerald-500)
- Accent: #f59e0b (Tailwind amber-500)
- Neutral: #6b7280 (Tailwind gray-500)

### Typography
- Headlines: Inter Bold (600), 28-48px
- Body: Inter Regular (400), 14-16px
- Monospace: Fira Code (400), 12-14px

### Motion
- Fade: 200ms cubic-bezier(0.4, 0, 0.2, 1)
- Slide: 300ms cubic-bezier(0.34, 1.56, 0.64, 1)
- No motion for users with prefers-reduced-motion

### Accessibility
- WCAG 2.1 AA compliance minimum
- Color contrast ratio: 4.5:1 for normal text, 3:1 for large text
- Focus indicators on all interactive elements
- Semantic HTML with ARIA labels where needed

## Key Contacts & Resources

### SSH Access
- **Host:** f1rml03th382@72.167.102.145
- **Port:** 22
- **Key:** C:\Users\diego\.ssh\id_rsa
- **Use:** `ssh -o IdentitiesOnly=yes f1rml03th382@72.167.102.145`

### GitHub & Git
- **Repository:** gano.digital (private)
- **Main branch:** protected, requires PR review
- **Workflow:** Feature branch → PR → Review → Merge

### File Paths
- **Project root:** C:\Users\diego\Downloads\Gano.digital-copia
- **Memory location:** C:\Users\diego\.claude\projects\C--Users-diego\memory
- **Session logs:** wiki/dev-sessions/

## Kimi's Role

Kimi acts as an **autonomous development assistant** in parallel to Claude Code:

1. **Monitoring:** Watches for file changes, tracks task progress
2. **Optimization:** Suggests CSS improvements, tests accessibility
3. **Documentation:** Generates logs, syncs memory state
4. **Automation:** Runs repetitive tasks (linting, testing, deployment validation)
5. **Coordination:** Communicates progress via logs and memory files

Kimi is **NOT** a replacement for Claude Code but a complementary tool for:
- Background task execution
- Real-time code monitoring
- Automated testing workflows
- Memory synchronization

## Commands Reference

### Git Operations
```bash
git status                               # Check status
git add <path>                          # Stage files
git commit -m "message"                 # Create commit
git push origin <branch>                # Push to remote
```

### SSH & Deployment
```bash
ssh -o IdentitiesOnly=yes f1rml03th382@72.167.102.145  # Connect to server
scp -o IdentitiesOnly=yes file.php f1rml03th382@72.167.102.145:/path/  # Upload
```

### PHP & Linting
```bash
php -l wp-content/plugins/*/index.php   # Lint PHP files
php -S localhost:8000                   # Local server
```

### Kimi Commands
```bash
kimi --version                          # Check Kimi version
kimi run-task <task-name>              # Execute task from kimi-tasks.yaml
. .\.gano-skills\kimi\kimi-helper.ps1  # Load helper functions
```

## Performance Targets

- **Build time:** < 30 seconds
- **Page load (mobile):** < 2.5 seconds
- **Lighthouse score:** > 85
- **Accessibility:** WCAG 2.1 AA
- **Token efficiency:** < 100k per session

## Escalation Criteria

Kimi escalates to Claude Code when:
1. Manual code review needed
2. Architecture decisions required
3. Merge conflicts encountered
4. Security implications detected
5. Performance issues exceed thresholds

## Last Updated
- Date: 2026-04-23
- By: Kimi Setup Task 4
- Next sync: After Task 5 completion
