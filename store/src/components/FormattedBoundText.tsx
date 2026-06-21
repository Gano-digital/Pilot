import type { ReactNode } from 'react'

import {
  buildFormatOverrideStyle,
  findApplicableFormatOverride,
  type FormatOverrideTarget,
} from '@/lib/format-overrides'
import { useFormatOverrideBundle } from '@/lib/format-overrides-store'

interface FormattedBoundTextProps {
  devId: string
  guard: FormatOverrideTarget
  children: ReactNode
}

export function FormattedBoundText({ devId, guard, children }: FormattedBoundTextProps) {
  const formatOverrideBundle = useFormatOverrideBundle()
  const result = findApplicableFormatOverride(formatOverrideBundle, devId, guard)

  if (result.status === 'missing') {
    return <>{children}</>
  }

  if (result.status === 'guard-mismatch') {
    if (import.meta.env.DEV) {
      console.warn('[format-overrides] Ignoring stale override for bound text.', {
        devId,
        expected: result.expected,
        actual: result.actual,
      })
    }
    return <>{children}</>
  }

  const marks = result.marks

  return (
    <span
      data-airo-formatted-bound-text="true"
      data-airo-format-bold={marks.bold ? 'true' : undefined}
      data-airo-format-italic={marks.italic ? 'true' : undefined}
      data-airo-format-color={marks.color || undefined}
      style={buildFormatOverrideStyle(marks)}
    >
      {children}
    </span>
  )
}
