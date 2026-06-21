/**
 * @vitest-environment jsdom
 */
import { act, render, screen } from '@testing-library/react'
import { beforeEach, describe, expect, it, vi } from 'vitest'

import type { FormatOverrideBundle } from '../../lib/format-overrides'
import { setFormatOverrideBundle } from '../../lib/format-overrides-store'
import { FormattedBoundText } from '../FormattedBoundText'

const { initialBundle } = vi.hoisted((): { initialBundle: FormatOverrideBundle } => ({
  initialBundle: {
    version: 1,
    scopes: {
      'pages/index': {
        version: 1,
        overrides: {
          abc123: {
            target: {
              file: 'src/pages/index.tsx',
              tagName: 'h1',
              sourceKind: 'bound-expression',
              contentKey: null,
              contentKeyTemplate: null,
              expressionHash: `sha256:${'a'.repeat(64)}`,
            },
            marks: { bold: true, italic: true, color: '#123abc' },
            updatedAt: '2026-05-28T12:00:00.000Z',
          },
          stale: {
            target: {
              file: 'src/pages/index.tsx',
              tagName: 'p',
              sourceKind: 'bound-expression',
              contentKey: null,
              contentKeyTemplate: null,
              expressionHash: `sha256:${'b'.repeat(64)}`,
            },
            marks: { bold: true },
            updatedAt: '2026-05-28T12:00:00.000Z',
          },
        },
      },
    },
  },
}))

vi.mock(
  'virtual:format-overrides',
  () => ({
    default: initialBundle,
  }),
)

const guard = {
  file: 'src/pages/index.tsx',
  tagName: 'h1',
  sourceKind: 'bound-expression' as const,
  contentKey: null,
  contentKeyTemplate: null,
  expressionHash: `sha256:${'a'.repeat(64)}`,
}

describe('FormattedBoundText', () => {
  beforeEach(() => {
    vi.restoreAllMocks()
    setFormatOverrideBundle(initialBundle)
  })

  it('renders children unchanged when no override exists', () => {
    const { container } = render(
      <FormattedBoundText devId="missing" guard={guard}>
        Site title
      </FormattedBoundText>,
    )

    expect(container.querySelector('[data-airo-formatted-bound-text]')).toBeNull()
    expect(container.textContent).toBe('Site title')
  })

  it('wraps children and applies matching override marks', () => {
    render(
      <FormattedBoundText devId="abc123" guard={guard}>
        Site title
      </FormattedBoundText>,
    )

    const formatted = screen.getByText('Site title')
    expect(formatted).toHaveAttribute('data-airo-formatted-bound-text', 'true')
    expect(formatted).toHaveAttribute('data-airo-format-bold', 'true')
    expect(formatted).toHaveAttribute('data-airo-format-italic', 'true')
    expect(formatted).toHaveAttribute('data-airo-format-color', '#123abc')
    expect(formatted).toHaveStyle({
      fontWeight: '700',
      fontStyle: 'italic',
      color: '#123abc',
    })
  })

  it('renders children unchanged and warns in dev on guard mismatch', () => {
    const warn = vi.spyOn(console, 'warn').mockImplementation(() => {})

    const { container } = render(
      <FormattedBoundText devId="stale" guard={guard}>
        Site title
      </FormattedBoundText>,
    )

    expect(container.querySelector('[data-airo-formatted-bound-text]')).toBeNull()
    expect(container.textContent).toBe('Site title')
    expect(warn).toHaveBeenCalledWith(
      '[format-overrides] Ignoring stale override for bound text.',
      expect.objectContaining({ devId: 'stale' }),
    )
  })

  it('updates rendered marks when the format override bundle changes', () => {
    render(
      <FormattedBoundText devId="missing" guard={guard}>
        Site title
      </FormattedBoundText>,
    )

    expect(screen.getByText('Site title')).not.toHaveAttribute('data-airo-formatted-bound-text')

    act(() => {
      setFormatOverrideBundle({
        version: 1,
        scopes: {
          'pages/index': {
            version: 1,
            overrides: {
              missing: {
                target: guard,
                marks: { italic: true },
                updatedAt: '2026-05-28T12:00:00.000Z',
              },
            },
          },
        },
      })
    })

    const formatted = screen.getByText('Site title')
    expect(formatted).toHaveAttribute('data-airo-formatted-bound-text', 'true')
    expect(formatted).toHaveAttribute('data-airo-format-italic', 'true')
    expect(formatted).toHaveStyle({ fontStyle: 'italic' })
  })
})
