import { useSyncExternalStore } from 'react'

import initialFormatOverrideBundle from 'virtual:format-overrides'

import type { FormatOverrideBundle } from './format-overrides'

export const FORMAT_OVERRIDES_UPDATE_EVENT = 'format-overrides:update'
export const FORMAT_OVERRIDES_WILL_UPDATE_EVENT = 'airo-format-overrides:will-update'

const listeners = new Set<() => void>()
let currentBundle: FormatOverrideBundle = initialFormatOverrideBundle

export function getFormatOverrideBundle(): FormatOverrideBundle {
  return currentBundle
}

export function subscribeFormatOverrideBundle(listener: () => void): () => void {
  listeners.add(listener)
  return () => {
    listeners.delete(listener)
  }
}

export function setFormatOverrideBundle(bundle: FormatOverrideBundle): void {
  currentBundle = bundle
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent(FORMAT_OVERRIDES_WILL_UPDATE_EVENT, { detail: bundle }))
  }
  listeners.forEach((listener) => listener())
}

export function useFormatOverrideBundle(): FormatOverrideBundle {
  return useSyncExternalStore(
    subscribeFormatOverrideBundle,
    getFormatOverrideBundle,
    getFormatOverrideBundle,
  )
}

if (import.meta.hot) {
  import.meta.hot.on(FORMAT_OVERRIDES_UPDATE_EVENT, (bundle: FormatOverrideBundle) => {
    setFormatOverrideBundle(bundle)
  })
}
