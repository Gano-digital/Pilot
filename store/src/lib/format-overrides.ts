import type { CSSProperties } from 'react'

export type FormatOverrideSourceKind = 'bound-expression' | 'content-key' | 'content-key-template'

export interface FormatOverrideTarget {
  file: string
  tagName: string
  sourceKind: FormatOverrideSourceKind
  contentKey?: string | null
  contentKeyTemplate?: string | null
  expressionHash?: string | null
}

export interface FormatOverrideMarks {
  bold?: boolean
  italic?: boolean
  color?: string | null
}

export interface FormatOverrideEntry {
  target: FormatOverrideTarget
  marks: FormatOverrideMarks
  updatedAt: string
}

export interface FormatOverrideSidecar {
  version: 1
  overrides: Record<string, FormatOverrideEntry>
}

export interface FormatOverrideBundle {
  version: 1
  scopes: Record<string, FormatOverrideSidecar>
}

export interface FormatOverrideScope {
  key: string
  filePath: string
}

export type FormatOverrideLookupResult =
  | { status: 'missing' }
  | { status: 'applicable'; marks: FormatOverrideMarks }
  | { status: 'guard-mismatch'; expected: FormatOverrideTarget; actual: FormatOverrideTarget }

export const EMPTY_FORMAT_OVERRIDE_SIDECAR: FormatOverrideSidecar = {
  version: 1,
  overrides: {},
}

export const EMPTY_FORMAT_OVERRIDE_BUNDLE: FormatOverrideBundle = {
  version: 1,
  scopes: {},
}

function isRecord(value: unknown): value is Record<string, unknown> {
  return !!value && typeof value === 'object' && !Array.isArray(value)
}

function isFormatOverrideSourceKind(value: unknown): value is FormatOverrideSourceKind {
  return value === 'bound-expression' || value === 'content-key' || value === 'content-key-template'
}

function isFormatOverrideTarget(value: unknown): value is FormatOverrideTarget {
  if (!isRecord(value)) return false
  return (
    typeof value.file === 'string' &&
    typeof value.tagName === 'string' &&
    isFormatOverrideSourceKind(value.sourceKind) &&
    (value.contentKey === undefined || value.contentKey === null || typeof value.contentKey === 'string') &&
    (
      value.contentKeyTemplate === undefined ||
      value.contentKeyTemplate === null ||
      typeof value.contentKeyTemplate === 'string'
    ) &&
    (value.expressionHash === undefined || value.expressionHash === null || typeof value.expressionHash === 'string')
  )
}

function isFormatOverrideMarks(value: unknown): value is FormatOverrideMarks {
  if (!isRecord(value)) return false
  return (
    (value.bold === undefined || typeof value.bold === 'boolean') &&
    (value.italic === undefined || typeof value.italic === 'boolean') &&
    (value.color === undefined || value.color === null || typeof value.color === 'string')
  )
}

function isFormatOverrideEntry(value: unknown): value is FormatOverrideEntry {
  if (!isRecord(value)) return false
  return (
    isFormatOverrideTarget(value.target) &&
    isFormatOverrideMarks(value.marks) &&
    typeof value.updatedAt === 'string'
  )
}

function warnMalformedFormatOverrideEntry(scope: string, devId: string): void {
  if (import.meta.env.DEV) {
    console.warn('[format-overrides] Ignoring malformed override entry.', { scope, devId })
  }
}

function normalizeNullable(value: string | null | undefined): string | null {
  return value ?? null
}

function normalizeSourceFile(file: string): string {
  const normalized = file.replace(/\\/g, '/')
  const srcIndex = normalized.indexOf('/src/')
  if (srcIndex !== -1) return normalized.slice(srcIndex + 1)
  return normalized.replace(/^\/+/, '')
}

function toSafePagePath(rawPagePath: string): string | null {
  const segments = rawPagePath.split('/')
  if (segments.some((segment) => !/^[a-zA-Z0-9_-]+$/.test(segment))) return null

  return segments.map((segment) => segment.toLowerCase()).join('/')
}

export function deriveFormatOverrideScope(file: string): FormatOverrideScope {
  // Keep this scope derivation in sync with agents/src/services/FormatOverrideService.ts.
  // The server writes by target.file and the runtime reads by the same derived scope key.
  const normalized = normalizeSourceFile(file)
  const pageMatch = /^src\/pages\/(.+)\.[jt]sx?$/.exec(normalized)
  if (!pageMatch?.[1]) {
    return { key: 'shared', filePath: 'format-overrides/shared.json' }
  }

  const pagePath = toSafePagePath(pageMatch[1])
  if (!pagePath) {
    return { key: 'shared', filePath: 'format-overrides/shared.json' }
  }

  return {
    key: `pages/${pagePath}`,
    filePath: `format-overrides/pages/${pagePath}.json`,
  }
}

export function targetsMatch(expected: FormatOverrideTarget, actual: FormatOverrideTarget): boolean {
  return expected.file === actual.file &&
    expected.tagName === actual.tagName &&
    expected.sourceKind === actual.sourceKind &&
    normalizeNullable(expected.contentKey) === normalizeNullable(actual.contentKey) &&
    normalizeNullable(expected.contentKeyTemplate) === normalizeNullable(actual.contentKeyTemplate) &&
    normalizeNullable(expected.expressionHash) === normalizeNullable(actual.expressionHash)
}

export function findApplicableFormatOverride(
  bundle: FormatOverrideBundle,
  devId: string,
  actual: FormatOverrideTarget,
): FormatOverrideLookupResult {
  const scope = deriveFormatOverrideScope(actual.file)
  const sidecar = bundle.scopes[scope.key] ?? EMPTY_FORMAT_OVERRIDE_SIDECAR
  const entry = sidecar.overrides[devId]
  if (!entry) return { status: 'missing' }
  if (!isFormatOverrideEntry(entry)) {
    warnMalformedFormatOverrideEntry(scope.key, devId)
    return { status: 'missing' }
  }
  if (!targetsMatch(entry.target, actual)) {
    return { status: 'guard-mismatch', expected: entry.target, actual }
  }
  return { status: 'applicable', marks: entry.marks }
}

export function buildFormatOverrideStyle(marks: FormatOverrideMarks): CSSProperties {
  return {
    ...(marks.bold ? { fontWeight: 700 } : {}),
    ...(marks.italic ? { fontStyle: 'italic' } : {}),
    ...(marks.color ? { color: marks.color } : {}),
  }
}
