import { afterEach, describe, expect, it, vi } from 'vitest'

import type { FormatOverrideBundle } from '../format-overrides'
import {
  FORMAT_OVERRIDES_WILL_UPDATE_EVENT,
  getFormatOverrideBundle,
  setFormatOverrideBundle,
  subscribeFormatOverrideBundle,
} from '../format-overrides-store'

const emptyBundle: FormatOverrideBundle = { version: 1, scopes: {} }

describe('format override store', () => {
  afterEach(() => {
    setFormatOverrideBundle(emptyBundle)
    vi.restoreAllMocks()
  })

  it('unsubscribes listeners with a void cleanup callback', () => {
    const listener = vi.fn()
    const unsubscribe = subscribeFormatOverrideBundle(listener)

    const result = unsubscribe()
    setFormatOverrideBundle(emptyBundle)

    expect(result).toBeUndefined()
    expect(listener).not.toHaveBeenCalled()
  })

  it('dispatches a will-update window event before notifying subscribers', () => {
    const bundle: FormatOverrideBundle = {
      version: 1,
      scopes: {
        shared: { version: 1, overrides: {} },
      },
    }
    const events: FormatOverrideBundle[] = []
    window.addEventListener(FORMAT_OVERRIDES_WILL_UPDATE_EVENT, ((event: CustomEvent<FormatOverrideBundle>) => {
      events.push(event.detail)
    }) as EventListener, { once: true })

    const listener = vi.fn(() => {
      expect(getFormatOverrideBundle()).toBe(bundle)
      expect(events).toEqual([bundle])
    })
    const unsubscribe = subscribeFormatOverrideBundle(listener)

    setFormatOverrideBundle(bundle)
    unsubscribe()

    expect(listener).toHaveBeenCalledOnce()
  })
})
