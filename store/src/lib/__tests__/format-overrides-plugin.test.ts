import { mkdir, mkdtemp, rm, writeFile } from 'node:fs/promises'
import { EventEmitter } from 'node:events'
import { tmpdir } from 'node:os'
import { join } from 'node:path'

import { afterEach, describe, expect, it, vi } from 'vitest'
import type { Plugin } from 'vite'

import { FORMAT_OVERRIDES_MODULE_ID, formatOverridesPlugin } from '../../../format-overrides-plugin'

interface DirectPluginHooks {
  resolveId: (id: string) => unknown
  load: (id: string) => unknown | Promise<unknown>
  configureServer?: (server: unknown) => void
}

function directHooks(plugin: Plugin): DirectPluginHooks {
  return plugin as Plugin & DirectPluginHooks
}

function fakeViteServer() {
  const watcher = new EventEmitter() as EventEmitter & { add: ReturnType<typeof vi.fn> }
  watcher.add = vi.fn()
  const moduleNode = { id: '\0virtual:format-overrides' }
  return {
    watcher,
    moduleGraph: {
      getModuleById: vi.fn(() => moduleNode),
      invalidateModule: vi.fn(),
    },
    ws: {
      send: vi.fn(),
    },
  }
}

async function withTempRoot<T>(fn: (root: string) => Promise<T>): Promise<T> {
  const root = await mkdtemp(join(tmpdir(), 'format-overrides-'))
  try {
    return await fn(root)
  } finally {
    await rm(root, { recursive: true, force: true })
  }
}

describe('formatOverridesPlugin', () => {
  afterEach(() => {
    vi.useRealTimers()
    vi.restoreAllMocks()
  })

  it('returns an empty sidecar bundle when format-overrides/ is missing', async () => {
    await withTempRoot(async (root) => {
      const plugin = directHooks(formatOverridesPlugin(root))
      const resolved = plugin.resolveId(FORMAT_OVERRIDES_MODULE_ID)
      expect(resolved).toBe('\0virtual:format-overrides')

      const loaded = await plugin.load('\0virtual:format-overrides')
      expect(String(loaded)).toContain('"version":1')
      expect(String(loaded)).toContain('"scopes":{}')
    })
  })

  it('embeds scoped sidecar JSON when a page sidecar exists', async () => {
    await withTempRoot(async (root) => {
      const expressionHash = `sha256:${'a'.repeat(64)}`

      await mkdir(join(root, 'format-overrides/pages'), { recursive: true })
      await writeFile(
        join(root, 'format-overrides/pages/index.json'),
        JSON.stringify({
          version: 1,
          overrides: {
            abc123: {
              target: {
                file: 'src/pages/index.tsx',
                tagName: 'h1',
                sourceKind: 'bound-expression',
                contentKey: null,
                contentKeyTemplate: null,
                expressionHash,
              },
              marks: { bold: true, italic: false, color: '#123abc' },
              updatedAt: '2026-05-28T12:00:00.000Z',
            },
          },
        }),
      )

      const plugin = directHooks(formatOverridesPlugin(root))
      const loaded = await plugin.load('\0virtual:format-overrides')

      expect(String(loaded)).toContain('"pages/index"')
      expect(String(loaded)).toContain('abc123')
      expect(String(loaded)).toContain('#123abc')
      expect(String(loaded)).toContain('export default')
    })
  })

  it('falls back to an empty scoped sidecar when JSON is invalid', async () => {
    await withTempRoot(async (root) => {
      const warn = vi.spyOn(console, 'warn').mockImplementation(() => undefined)
      await mkdir(join(root, 'format-overrides/pages'), { recursive: true })
      await writeFile(join(root, 'format-overrides/pages/index.json'), '{not json')

      const plugin = directHooks(formatOverridesPlugin(root))
      const loaded = await plugin.load('\0virtual:format-overrides')

      expect(String(loaded)).toContain('"version":1')
      expect(String(loaded)).toContain('"pages/index"')
      expect(String(loaded)).toContain('"overrides":{}')
      expect(JSON.parse(String(warn.mock.calls[0][0]))).toMatchObject({
        event: 'format-overrides.sidecar.invalid',
        scope: 'pages/index',
      })
    })
  })

  it('falls back to an empty scoped sidecar when the sidecar version is unsupported', async () => {
    await withTempRoot(async (root) => {
      const warn = vi.spyOn(console, 'warn').mockImplementation(() => undefined)
      await mkdir(join(root, 'format-overrides'), { recursive: true })
      await writeFile(
        join(root, 'format-overrides/shared.json'),
        JSON.stringify({ version: 2, overrides: { abc123: {} } }),
      )

      const plugin = directHooks(formatOverridesPlugin(root))
      const loaded = await plugin.load('\0virtual:format-overrides')

      expect(String(loaded)).toContain('"version":1')
      expect(String(loaded)).toContain('"shared"')
      expect(String(loaded)).toContain('"overrides":{}')
      expect(String(loaded)).not.toContain('abc123')
      expect(JSON.parse(String(warn.mock.calls[0][0]))).toMatchObject({
        event: 'format-overrides.sidecar.invalid',
        scope: 'shared',
      })
    })
  })

  it('warns about invalid sidecars during production builds', async () => {
    const previousNodeEnv = process.env.NODE_ENV
    process.env.NODE_ENV = 'production'

    try {
      await withTempRoot(async (root) => {
        const warn = vi.spyOn(console, 'warn').mockImplementation(() => undefined)
        await mkdir(join(root, 'format-overrides/pages'), { recursive: true })
        await writeFile(join(root, 'format-overrides/pages/index.json'), '{not json')

        const plugin = directHooks(formatOverridesPlugin(root))
        await plugin.load('\0virtual:format-overrides')

        expect(JSON.parse(String(warn.mock.calls[0][0]))).toMatchObject({
          event: 'format-overrides.sidecar.invalid',
          scope: 'pages/index',
        })
      })
    } finally {
      if (previousNodeEnv === undefined) {
        delete process.env.NODE_ENV
      } else {
        process.env.NODE_ENV = previousNodeEnv
      }
    }
  })

  it('pushes sidecar updates through custom HMR without full page reload', async () => {
    await withTempRoot(async (root) => {
      const expressionHash = `sha256:${'a'.repeat(64)}`
      const sidecarPath = join(root, 'format-overrides/pages/index.json')
      await mkdir(join(root, 'format-overrides/pages'), { recursive: true })
      await writeFile(
        sidecarPath,
        JSON.stringify({
          version: 1,
          overrides: {
            abc123: {
              target: {
                file: 'src/pages/index.tsx',
                tagName: 'h1',
                sourceKind: 'bound-expression',
                contentKey: null,
                contentKeyTemplate: null,
                expressionHash,
              },
              marks: { bold: true },
              updatedAt: '2026-05-28T12:00:00.000Z',
            },
          },
        }),
      )

      const plugin = directHooks(formatOverridesPlugin(root))
      const server = fakeViteServer()
      plugin.configureServer?.(server)

      server.watcher.emit('change', sidecarPath)

      expect(server.moduleGraph.invalidateModule).toHaveBeenCalled()
      expect(server.ws.send).toHaveBeenCalledWith('format-overrides:update', expect.objectContaining({
        version: 1,
        scopes: expect.objectContaining({
          'pages/index': expect.objectContaining({
            overrides: expect.objectContaining({
              abc123: expect.objectContaining({
                marks: { bold: true },
              }),
            }),
          }),
        }),
      }))
      expect(server.ws.send).not.toHaveBeenCalledWith({ type: 'full-reload' })
    })
  })
})
