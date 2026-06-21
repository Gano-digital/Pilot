import { afterEach, describe, expect, it, vi } from 'vitest'

import {
  EMPTY_FORMAT_OVERRIDE_BUNDLE,
  buildFormatOverrideStyle,
  deriveFormatOverrideScope,
  findApplicableFormatOverride,
  targetsMatch,
  type FormatOverrideBundle,
  type FormatOverrideSidecar,
  type FormatOverrideTarget,
} from '../format-overrides'

const expressionHash = `sha256:${'a'.repeat(64)}`
const differentExpressionHash = `sha256:${'b'.repeat(64)}`

const target: FormatOverrideTarget = {
  file: 'src/pages/index.tsx',
  tagName: 'h1',
  sourceKind: 'bound-expression',
  contentKey: null,
  contentKeyTemplate: null,
  expressionHash,
}

describe('format override runtime helpers', () => {
  afterEach(() => {
    vi.restoreAllMocks()
  })

  it('returns missing for absent sidecar entries', () => {
    expect(findApplicableFormatOverride(EMPTY_FORMAT_OVERRIDE_BUNDLE, 'abc123', target)).toEqual({
      status: 'missing',
    })
  })

  it('returns applicable marks when every guard field matches', () => {
    const sidecar: FormatOverrideSidecar = {
      version: 1,
      overrides: {
        abc123: {
          target,
          marks: { bold: true, italic: true, color: '#123abc' },
          updatedAt: '2026-05-28T12:00:00.000Z',
        },
      },
    }
    const bundle: FormatOverrideBundle = { version: 1, scopes: { 'pages/index': sidecar } }

    expect(findApplicableFormatOverride(bundle, 'abc123', target)).toEqual({
      status: 'applicable',
      marks: { bold: true, italic: true, color: '#123abc' },
    })
  })

  it('returns guard-mismatch when a stale structural id points at a different expression', () => {
    const sidecar: FormatOverrideSidecar = {
      version: 1,
      overrides: {
        abc123: {
          target,
          marks: { bold: true, italic: false, color: null },
          updatedAt: '2026-05-28T12:00:00.000Z',
        },
      },
    }
    const bundle: FormatOverrideBundle = { version: 1, scopes: { 'pages/index': sidecar } }
    const actual = { ...target, expressionHash: differentExpressionHash }

    expect(findApplicableFormatOverride(bundle, 'abc123', actual)).toEqual({
      status: 'guard-mismatch',
      expected: target,
      actual,
    })
  })

  it('fails open when a top-level valid sidecar contains a malformed entry', () => {
    const warn = vi.spyOn(console, 'warn').mockImplementation(() => {})
    const bundle = {
      version: 1,
      scopes: {
        'pages/index': {
          version: 1,
          overrides: {
            abc123: {},
          },
        },
      },
    } as unknown as FormatOverrideBundle

    expect(findApplicableFormatOverride(bundle, 'abc123', target)).toEqual({
      status: 'missing',
    })
    expect(warn).toHaveBeenCalledWith(
      '[format-overrides] Ignoring malformed override entry.',
      { scope: 'pages/index', devId: 'abc123' },
    )
  })

  it.each([
    ['file', { file: 'src/pages/about.tsx' }],
    ['tagName', { tagName: 'p' }],
    ['sourceKind', { sourceKind: 'content-key' as const }],
    ['contentKey', { contentKey: 'home.title' }],
    ['contentKeyTemplate', { contentKeyTemplate: 'products[].name' }],
    ['expressionHash', { expressionHash: differentExpressionHash }],
  ])('returns false when target %s differs', (_field, patch) => {
    expect(targetsMatch(target, { ...target, ...patch })).toBe(false)
  })

  it('treats null and undefined optional target fields as equivalent', () => {
    const expected = {
      ...target,
      contentKey: undefined,
      contentKeyTemplate: undefined,
      expressionHash: undefined,
    }
    const actual = {
      ...target,
      contentKey: null,
      contentKeyTemplate: null,
      expressionHash: null,
    }

    expect(targetsMatch(expected, actual)).toBe(true)
  })

  it('builds inline styles for the v1 mark set only', () => {
    expect(buildFormatOverrideStyle({ bold: true, italic: true, color: '#123abc' })).toEqual({
      fontWeight: 700,
      fontStyle: 'italic',
      color: '#123abc',
    })
  })

  it('derives scoped sidecar locations from source files', () => {
    expect(deriveFormatOverrideScope('src/pages/index.tsx')).toEqual({
      key: 'pages/index',
      filePath: 'format-overrides/pages/index.json',
    })
    expect(deriveFormatOverrideScope('src/pages/about.tsx')).toEqual({
      key: 'pages/about',
      filePath: 'format-overrides/pages/about.json',
    })
    expect(deriveFormatOverrideScope('src/pages/home.tsx')).toEqual({
      key: 'pages/home',
      filePath: 'format-overrides/pages/home.json',
    })
    expect(deriveFormatOverrideScope('src/pages/blog/index.tsx')).toEqual({
      key: 'pages/blog/index',
      filePath: 'format-overrides/pages/blog/index.json',
    })
    expect(deriveFormatOverrideScope('src/layouts/RootLayout.tsx')).toEqual({
      key: 'shared',
      filePath: 'format-overrides/shared.json',
    })
    expect(deriveFormatOverrideScope('src/pages/../secret.tsx')).toEqual({
      key: 'shared',
      filePath: 'format-overrides/shared.json',
    })
    expect(deriveFormatOverrideScope('src/pages/blog/[slug].tsx')).toEqual({
      key: 'shared',
      filePath: 'format-overrides/shared.json',
    })
    expect(deriveFormatOverrideScope('src/pages/blog/post.draft.tsx')).toEqual({
      key: 'shared',
      filePath: 'format-overrides/shared.json',
    })
  })
})
