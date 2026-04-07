# Agent SDK Upgrade Strategy — v0.2.84 → v0.2.92

**Current Status:** .gsd/sdk/package.json shows v0.2.84  
**Target:** v0.2.92 (available per dependabot)  
**Timeline:** Week 2 (1-2 hours estimated)  
**Risk Level:** LOW (patch release, backward compatible expected)

---

## Why Upgrade?

1. **Security patches** — v0.2.85+ may include fixes
2. **New features** — Tool use improvements, better error handling
3. **Dependency health** — Stay current with Anthropic releases
4. **CI/CD alignment** — Dependabot suggests update regularly

---

## Pre-Upgrade Checklist

- [ ] Verify current tests pass: `cd .gsd/sdk && npm run test`
- [ ] Verify current integration tests pass: `npm run test:integration`
- [ ] Check breaking changes: Search CHANGELOG in @anthropic-ai/claude-agent-sdk docs
- [ ] Create git branch: `git checkout -b feat/sdk-upgrade-0292`

---

## Upgrade Steps

### 1. Update package.json

```bash
cd .gsd/sdk
npm install @anthropic-ai/claude-agent-sdk@^0.2.92
```

Verify change in package.json:
```json
"@anthropic-ai/claude-agent-sdk": "^0.2.92"
```

### 2. Run npm ci (clean install)

```bash
npm ci
```

### 3. Rebuild TypeScript

```bash
npm run build
```

Expected: `dist/` folder updated with new types

### 4. Run Full Test Suite

```bash
npm run test
npm run test:unit
npm run test:integration
```

Expected: All tests pass (if not, document failures with SDK version)

### 5. Test locally

```bash
npm run build
cd ..
node index.js  # Quick smoke test
```

### 6. Commit and PR

```bash
git add .gsd/sdk/package.json .gsd/sdk/package-lock.json
git commit -m "chore: upgrade @anthropic-ai/claude-agent-sdk to v0.2.92"
git push -u origin feat/sdk-upgrade-0292
```

Open PR for Diego review.

---

## Rollback Plan (if needed)

If tests fail:

```bash
git reset --hard HEAD~1  # Undo commit
npm ci  # Restore v0.2.84
npm run test  # Verify revert works
```

---

## Future Maintenance

- Check dependabot monthly for new releases
- Add this to quarterly SOTA maintenance cycle
- Keep @types/node synced (currently ^22.0.0)

---

*Task ID: task-006-sdk-upgrade (dispatch-unified.json)*  
*Assigned to: VS Code (automation via npm)*  
*Owner: Diego (final approval)*
