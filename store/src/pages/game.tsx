import { useEffect, useRef, useState, useCallback } from 'react';
import { Helmet } from '@dr.pogodin/react-helmet';
import { GameEngine, type GameState, type GameStats } from '../game/GameEngine';

const DEFAULT_STATS: GameStats = {
  health: 100, maxHealth: 100, armor: 0, ammo: 50, maxAmmo: 200,
  weaponName: 'PISTOL', reloading: false, score: 0, kills: 0,
  totalEnemies: 0, level: 1, levelName: 'SECTOR ZERO',
  damageFlash: 0, state: 'menu', fps: 60,
};

export default function GamePage() {
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const overlayRef = useRef<HTMLCanvasElement>(null);
  const engineRef = useRef<GameEngine | null>(null);
  const [stats, setStats] = useState<GameStats>(DEFAULT_STATS);
  const [gameState, setGameState] = useState<GameState>('menu');
  const [muted, setMuted] = useState(false);

  useEffect(() => {
    return () => {
      engineRef.current?.destroy();
      engineRef.current = null;
    };
  }, []);

  const startGame = useCallback(() => {
    if (!canvasRef.current || !overlayRef.current) return;
    if (!engineRef.current) {
      engineRef.current = new GameEngine(
        canvasRef.current,
        overlayRef.current,
        setStats,
        setGameState,
      );
    }
    engineRef.current.startGame();
  }, []);

  const restartGame = useCallback(() => {
    engineRef.current?.restart();
  }, []);

  const nextLevel = useCallback(() => {
    engineRef.current?.nextLevel();
  }, []);

  const toggleMute = useCallback(() => {
    const m = engineRef.current?.toggleMute() ?? false;
    setMuted(m);
  }, []);

  const barColor = (val: number, max: number, type: 'health' | 'armor' | 'ammo') => {
    const pct = val / max;
    if (type === 'health') return pct > 0.5 ? '#4ade80' : pct > 0.25 ? '#facc15' : '#ef4444';
    if (type === 'armor') return '#60a5fa';
    return '#C8F04D';
  };

  return (
    <>
      <Helmet>
        <title>Demo FPS — Gano Digital</title>
        <meta name="description" content="Demo técnica: un shooter en primera persona construido en el navegador con Three.js. Una muestra de nuestra capacidad de ingeniería front-end en Gano Digital." />
        <link rel="canonical" href="https://gano.digital/game" />
        <meta property="og:title" content="Demo FPS — Gano Digital" />
        <meta property="og:description" content="Demo técnica: un FPS jugable construido en el navegador con Three.js y Web Audio. Capacidad de ingeniería de Gano Digital." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://gano.digital/game" />
        <meta property="og:image" content="https://gano.digital/api/og?title=Demo+FPS&description=Shooter+en+primera+persona+construido+en+el+navegador+con+Three.js.&tag=Demo" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Demo FPS — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Demo FPS — Gano Digital" />
        <meta name="twitter:description" content="Demo técnica: un FPS jugable construido en el navegador con Three.js." />
        <meta name="twitter:image" content="https://gano.digital/api/og?title=Demo+FPS&description=Shooter+en+primera+persona+construido+en+el+navegador+con+Three.js.&tag=Demo" />
        <script type="application/ld+json">
          {JSON.stringify({
            '@context': 'https://schema.org',
            '@graph': [
              {
                '@type': 'VideoGame',
                name: 'Demo FPS Gano Digital',
                description:
                  'Demo técnica: shooter en primera persona construido en el navegador con Three.js y Web Audio.',
                genre: 'FPS',
                applicationCategory: 'Game',
                operatingSystem: 'Web Browser',
                url: 'https://gano.digital/game',
                author: { '@id': 'https://gano.digital/#organization' },
                publisher: { '@id': 'https://gano.digital/#organization' },
              },
              {
                '@type': 'WebPage',
                '@id': 'https://gano.digital/game#webpage',
                url: 'https://gano.digital/game',
                name: 'Demo FPS — Gano Digital',
                isPartOf: { '@id': 'https://gano.digital/#website' },
              },
            ],
          })}
        </script>
      </Helmet>

      {/* Full-viewport game container — no header/footer chrome */}
      <div className="fixed inset-0 bg-black overflow-hidden" style={{ zIndex: 50 }}>

        {/* Three.js canvas */}
        <canvas
          ref={canvasRef}
          className="absolute inset-0 w-full h-full"
          style={{ display: 'block' }}
        />

        {/* 2D overlay (weapon, crosshair, minimap) */}
        <canvas
          ref={overlayRef}
          className="absolute inset-0 w-full h-full pointer-events-none"
          style={{ imageRendering: 'pixelated' }}
        />

        {/* ── HUD (visible while playing) ── */}
        {gameState === 'playing' && (
          <div className="absolute inset-0 pointer-events-none select-none">

            {/* Top bar — level info + fps */}
            <div className="absolute top-0 left-0 right-0 flex justify-between items-start px-4 pt-3">
              <div style={{ fontFamily: 'monospace', color: '#C8F04D', fontSize: 11, letterSpacing: '0.15em', textShadow: '0 0 8px #C8F04D' }}>
                LEVEL {stats.level} — {stats.levelName}
              </div>
              <div style={{ fontFamily: 'monospace', color: '#666', fontSize: 10 }}>
                {stats.fps} FPS
              </div>
              <div style={{ fontFamily: 'monospace', color: '#C8F04D', fontSize: 11, letterSpacing: '0.1em' }}>
                SCORE: {stats.score.toString().padStart(6, '0')}
              </div>
            </div>

            {/* Bottom HUD */}
            <div className="absolute bottom-0 left-0 right-0 px-4 pb-4">
              <div className="flex items-end justify-between">

                {/* Left — health + armor */}
                <div style={{ minWidth: 180 }}>
                  {/* Health */}
                  <div className="flex items-center gap-2 mb-1">
                    <span style={{ fontFamily: 'monospace', color: '#aaa', fontSize: 10, letterSpacing: '0.1em', width: 28 }}>HP</span>
                    <div style={{ flex: 1, height: 8, background: '#111', border: '1px solid #333', borderRadius: 2, overflow: 'hidden' }}>
                      <div style={{
                        height: '100%',
                        width: `${(stats.health / stats.maxHealth) * 100}%`,
                        background: barColor(stats.health, stats.maxHealth, 'health'),
                        transition: 'width 0.15s, background 0.3s',
                        boxShadow: `0 0 6px ${barColor(stats.health, stats.maxHealth, 'health')}`,
                      }} />
                    </div>
                    <span style={{ fontFamily: 'monospace', color: barColor(stats.health, stats.maxHealth, 'health'), fontSize: 12, width: 28, textAlign: 'right' }}>
                      {stats.health}
                    </span>
                  </div>
                  {/* Armor */}
                  <div className="flex items-center gap-2">
                    <span style={{ fontFamily: 'monospace', color: '#aaa', fontSize: 10, letterSpacing: '0.1em', width: 28 }}>ARM</span>
                    <div style={{ flex: 1, height: 6, background: '#111', border: '1px solid #333', borderRadius: 2, overflow: 'hidden' }}>
                      <div style={{
                        height: '100%',
                        width: `${(stats.armor / 100) * 100}%`,
                        background: '#60a5fa',
                        transition: 'width 0.15s',
                        boxShadow: '0 0 4px #60a5fa',
                      }} />
                    </div>
                    <span style={{ fontFamily: 'monospace', color: '#60a5fa', fontSize: 12, width: 28, textAlign: 'right' }}>
                      {stats.armor}
                    </span>
                  </div>
                </div>

                {/* Center — kills */}
                <div className="text-center">
                  <div style={{ fontFamily: 'monospace', color: '#666', fontSize: 9, letterSpacing: '0.15em', marginBottom: 2 }}>KILLS</div>
                  <div style={{ fontFamily: 'monospace', color: '#ff6644', fontSize: 18, letterSpacing: '0.05em' }}>
                    {stats.kills}<span style={{ color: '#444', fontSize: 12 }}>/{stats.totalEnemies}</span>
                  </div>
                </div>

                {/* Right — weapon + ammo */}
                <div style={{ minWidth: 180, textAlign: 'right' }}>
                  <div style={{ fontFamily: 'monospace', color: '#C8F04D', fontSize: 13, letterSpacing: '0.15em', marginBottom: 4, textShadow: '0 0 8px #C8F04D' }}>
                    {stats.weaponName}
                    {stats.reloading && <span style={{ color: '#ff8844', fontSize: 10, marginLeft: 8 }}>RELOADING...</span>}
                  </div>
                  <div className="flex items-center justify-end gap-2">
                    <div style={{ width: 80, height: 6, background: '#111', border: '1px solid #333', borderRadius: 2, overflow: 'hidden' }}>
                      <div style={{
                        height: '100%',
                        width: `${(stats.ammo / stats.maxAmmo) * 100}%`,
                        background: barColor(stats.ammo, stats.maxAmmo, 'ammo'),
                        transition: 'width 0.1s',
                        boxShadow: `0 0 4px ${barColor(stats.ammo, stats.maxAmmo, 'ammo')}`,
                      }} />
                    </div>
                    <span style={{ fontFamily: 'monospace', color: '#C8F04D', fontSize: 14 }}>
                      {stats.ammo}<span style={{ color: '#555', fontSize: 10 }}>/{stats.maxAmmo}</span>
                    </span>
                  </div>
                  <div style={{ fontFamily: 'monospace', color: '#444', fontSize: 9, marginTop: 3 }}>
                    [1] PISTOL  [2] SHOTGUN  [3] ROCKET
                  </div>
                </div>
              </div>
            </div>

            {/* Mute button */}
            <button
              className="absolute top-3 right-16 pointer-events-auto"
              onClick={toggleMute}
              style={{ fontFamily: 'monospace', color: muted ? '#666' : '#C8F04D', fontSize: 10, background: 'none', border: 'none', cursor: 'pointer', letterSpacing: '0.1em' }}
            >
              {muted ? '🔇 MUTED' : '🔊 SOUND'}
            </button>
          </div>
        )}

        {/* ── MENU SCREEN ── */}
        {gameState === 'menu' && (
          <div className="absolute inset-0 flex flex-col items-center justify-center" style={{ background: 'rgba(0,0,0,0.92)' }}>
            <div style={{ textAlign: 'center', maxWidth: 520, padding: '0 24px' }}>
              {/* Title */}
              <div style={{ fontFamily: 'monospace', color: '#D97E3A', fontSize: 11, letterSpacing: '0.3em', marginBottom: 16, textShadow: '0 0 12px #D97E3A' }}>
                GANO DIGITAL PRESENTA
              </div>
              <h1 style={{ fontFamily: 'monospace', color: '#F5F5F5', fontSize: 'clamp(40px,8vw,72px)', fontWeight: 900, letterSpacing: '-0.02em', lineHeight: 1, marginBottom: 8 }}>
                GANO LABS
              </h1>
              <div style={{ fontFamily: 'monospace', color: '#D97E3A', fontSize: 14, letterSpacing: '0.4em', marginBottom: 40 }}>
                F P S
              </div>

              {/* Controls */}
              <div style={{ background: '#111', border: '1px solid #222', borderRadius: 4, padding: '16px 24px', marginBottom: 32, textAlign: 'left' }}>
                <div style={{ fontFamily: 'monospace', color: '#666', fontSize: 10, letterSpacing: '0.2em', marginBottom: 12 }}>CONTROLS</div>
                {[
                  ['WASD / ARROWS', 'Move'],
                  ['MOUSE', 'Aim'],
                  ['LEFT CLICK', 'Shoot'],
                  ['1 / 2 / 3', 'Switch weapon'],
                  ['SCROLL', 'Cycle weapon'],
                  ['REACH EXIT', 'Next level'],
                ].map(([key, action]) => (
                  <div key={key} className="flex justify-between" style={{ fontFamily: 'monospace', fontSize: 11, marginBottom: 6 }}>
                    <span style={{ color: '#C8F04D' }}>{key}</span>
                    <span style={{ color: '#888' }}>{action}</span>
                  </div>
                ))}
              </div>

              {/* Difficulty note */}
              <div style={{ fontFamily: 'monospace', color: '#ff4444', fontSize: 10, letterSpacing: '0.15em', marginBottom: 24 }}>
                ⚠ DIFFICULTY: BRUTAL — 3 LEVELS — 20+ MIN GAMEPLAY
              </div>

              <button
                onClick={startGame}
                style={{
                  fontFamily: 'monospace', fontWeight: 900, fontSize: 16,
                  letterSpacing: '0.3em', color: '#0A0A0A',
                  background: '#C8F04D', border: 'none', borderRadius: 2,
                  padding: '14px 48px', cursor: 'pointer',
                  boxShadow: '0 0 24px #C8F04D88',
                  transition: 'transform 0.1s, box-shadow 0.1s',
                }}
                onMouseEnter={e => { (e.target as HTMLElement).style.transform = 'scale(1.04)'; }}
                onMouseLeave={e => { (e.target as HTMLElement).style.transform = 'scale(1)'; }}
              >
                START GAME
              </button>

              <div style={{ fontFamily: 'monospace', color: '#333', fontSize: 9, marginTop: 20, letterSpacing: '0.1em' }}>
                CLICK THE GAME AREA TO CAPTURE MOUSE — MOVE MOUSE TO AIM
              </div>
            </div>
          </div>
        )}

        {/* ── DEAD SCREEN ── */}
        {gameState === 'dead' && (
          <div className="absolute inset-0 flex flex-col items-center justify-center" style={{ background: 'rgba(80,0,0,0.88)' }}>
            <div style={{ textAlign: 'center' }}>
              <div style={{ fontFamily: 'monospace', color: '#ff2222', fontSize: 'clamp(36px,7vw,64px)', fontWeight: 900, letterSpacing: '0.05em', marginBottom: 8, textShadow: '0 0 30px #ff0000' }}>
                YOU DIED
              </div>
              <div style={{ fontFamily: 'monospace', color: '#888', fontSize: 12, letterSpacing: '0.2em', marginBottom: 32 }}>
                SCORE: {stats.score.toString().padStart(6, '0')} — KILLS: {stats.kills}
              </div>
              <button
                onClick={restartGame}
                style={{
                  fontFamily: 'monospace', fontWeight: 900, fontSize: 14,
                  letterSpacing: '0.3em', color: '#0A0A0A',
                  background: '#ff4444', border: 'none', borderRadius: 2,
                  padding: '12px 40px', cursor: 'pointer',
                  boxShadow: '0 0 20px #ff444488',
                }}
              >
                TRY AGAIN
              </button>
            </div>
          </div>
        )}

        {/* ── LEVEL COMPLETE ── */}
        {gameState === 'levelcomplete' && (
          <div className="absolute inset-0 flex flex-col items-center justify-center" style={{ background: 'rgba(0,0,0,0.88)' }}>
            <div style={{ textAlign: 'center' }}>
              <div style={{ fontFamily: 'monospace', color: '#C8F04D', fontSize: 'clamp(28px,5vw,48px)', fontWeight: 900, letterSpacing: '0.1em', marginBottom: 8, textShadow: '0 0 20px #C8F04D' }}>
                LEVEL CLEAR
              </div>
              <div style={{ fontFamily: 'monospace', color: '#888', fontSize: 12, letterSpacing: '0.2em', marginBottom: 8 }}>
                SCORE: {stats.score.toString().padStart(6, '0')}
              </div>
              <div style={{ fontFamily: 'monospace', color: '#666', fontSize: 11, letterSpacing: '0.15em', marginBottom: 32 }}>
                KILLS: {stats.kills} — PROCEEDING TO LEVEL {stats.level + 1}
              </div>
              <button
                onClick={nextLevel}
                style={{
                  fontFamily: 'monospace', fontWeight: 900, fontSize: 14,
                  letterSpacing: '0.3em', color: '#0A0A0A',
                  background: '#C8F04D', border: 'none', borderRadius: 2,
                  padding: '12px 40px', cursor: 'pointer',
                  boxShadow: '0 0 20px #C8F04D88',
                }}
              >
                NEXT LEVEL
              </button>
            </div>
          </div>
        )}

        {/* ── VICTORY ── */}
        {gameState === 'victory' && (
          <div className="absolute inset-0 flex flex-col items-center justify-center" style={{ background: 'rgba(0,0,0,0.92)' }}>
            <div style={{ textAlign: 'center' }}>
              <div style={{ fontFamily: 'monospace', color: '#C8F04D', fontSize: 11, letterSpacing: '0.3em', marginBottom: 12, textShadow: '0 0 12px #C8F04D' }}>
                MISSION ACCOMPLISHED
              </div>
              <div style={{ fontFamily: 'monospace', color: '#F5F5F5', fontSize: 'clamp(32px,6vw,56px)', fontWeight: 900, letterSpacing: '-0.02em', marginBottom: 8 }}>
                YOU WIN
              </div>
              <div style={{ fontFamily: 'monospace', color: '#C8F04D', fontSize: 20, letterSpacing: '0.1em', marginBottom: 8, textShadow: '0 0 16px #C8F04D' }}>
                FINAL SCORE: {stats.score.toString().padStart(6, '0')}
              </div>
              <div style={{ fontFamily: 'monospace', color: '#888', fontSize: 12, letterSpacing: '0.15em', marginBottom: 32 }}>
                TOTAL KILLS: {stats.kills}
              </div>
              <button
                onClick={restartGame}
                style={{
                  fontFamily: 'monospace', fontWeight: 900, fontSize: 14,
                  letterSpacing: '0.3em', color: '#0A0A0A',
                  background: '#C8F04D', border: 'none', borderRadius: 2,
                  padding: '12px 40px', cursor: 'pointer',
                  boxShadow: '0 0 20px #C8F04D88',
                }}
              >
                PLAY AGAIN
              </button>
            </div>
          </div>
        )}

        {/* Back to site link */}
        <a
          href="/"
          style={{
            position: 'absolute', top: 12, left: 12,
            fontFamily: 'monospace', color: '#444', fontSize: 10,
            letterSpacing: '0.1em', textDecoration: 'none',
            transition: 'color 0.2s',
          }}
          onMouseEnter={e => { (e.target as HTMLElement).style.color = '#C8F04D'; }}
          onMouseLeave={e => { (e.target as HTMLElement).style.color = '#444'; }}
        >
          ← BACK TO SITE
        </a>
      </div>
    </>
  );
}
