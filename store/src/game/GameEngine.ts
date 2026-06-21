import * as THREE from 'three';
import { Player } from './Player';
import { Enemy, type EnemyType } from './Enemy';
import { Pickup, type PickupType } from './Pickup';
import { Projectile } from './Projectile';
import { AudioManager } from './AudioManager';
import { WeaponRenderer } from './WeaponRenderer';
import { generateLevel, buildLevelMesh, type LevelData } from './LevelGenerator';

export type GameState = 'menu' | 'playing' | 'dead' | 'levelcomplete' | 'victory';

export interface GameStats {
  health: number;
  maxHealth: number;
  armor: number;
  ammo: number;
  maxAmmo: number;
  weaponName: string;
  reloading: boolean;
  score: number;
  kills: number;
  totalEnemies: number;
  level: number;
  levelName: string;
  damageFlash: number;
  state: GameState;
  fps: number;
}

const MAX_LEVELS = 3;

export class GameEngine {
  private renderer!: THREE.WebGLRenderer;
  private scene!: THREE.Scene;
  private player!: Player;
  private enemies: Enemy[] = [];
  private pickups: Pickup[] = [];
  private projectiles: Projectile[] = [];
  private audio: AudioManager;
  private weaponRenderer!: WeaponRenderer;
  private level!: LevelData;
  private levelMeshes: THREE.Mesh[] = [];
  private exitMesh!: THREE.Mesh;

  private canvas: HTMLCanvasElement;
  private overlayCanvas: HTMLCanvasElement;
  private animFrame = 0;
  private lastTime = -1; // -1 = not yet started; avoids dt=0 on first frame
  private loopRunning = false;
  private state: GameState = 'menu';
  private currentLevel = 0;
  private totalEnemies = 0;
  private fpsCounter = 0;
  private fpsTime = 0;
  private fps = 60;

  private onStatsUpdate: (stats: GameStats) => void;
  private onStateChange: (state: GameState) => void;

  // Pointer lock
  private shooting = false;

  constructor(
    canvas: HTMLCanvasElement,
    overlayCanvas: HTMLCanvasElement,
    onStatsUpdate: (s: GameStats) => void,
    onStateChange: (s: GameState) => void,
  ) {
    this.canvas = canvas;
    this.overlayCanvas = overlayCanvas;
    this.onStatsUpdate = onStatsUpdate;
    this.onStateChange = onStateChange;
    this.audio = new AudioManager();
    this.audio.init();
    this.initRenderer();
    this.initPointerLock();
  }

  private initRenderer() {
    this.renderer = new THREE.WebGLRenderer({ canvas: this.canvas, antialias: false });
    this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
    this.renderer.shadowMap.enabled = true;
    this.renderer.shadowMap.type = THREE.BasicShadowMap;
    this.resize();
    window.addEventListener('resize', () => this.resize());
  }

  private resize() {
    const w = this.canvas.clientWidth;
    const h = this.canvas.clientHeight;
    this.renderer.setSize(w, h, false);
    this.overlayCanvas.width = w;
    this.overlayCanvas.height = h;
    if (this.player) {
      this.player.camera.aspect = w / h;
      this.player.camera.updateProjectionMatrix();
    }
  }

  // Whether the user has clicked the canvas and mouse is "captured" (even without pointer lock)
  private mouseCaptured = false;

  private initPointerLock() {
    // Try pointer lock — silently fall back if sandboxed
    const tryLock = () => {
      if (this.state !== 'playing') return;
      try {
        const result = this.canvas.requestPointerLock();
        if (result instanceof Promise) {
          result.catch(() => { /* sandboxed — fallback mouse tracking active */ });
        }
      } catch {
        // Sandboxed iframe — fallback mouse tracking active
      }
    };

    this.canvas.addEventListener('click', () => {
      this.mouseCaptured = true;
      tryLock();
    });

    // Release capture when clicking outside canvas
    document.addEventListener('click', (e) => {
      if (e.target !== this.canvas) {
        this.mouseCaptured = false;
        this.shooting = false;
      }
    });

    document.addEventListener('pointerlockchange', () => {
      if (document.pointerLockElement !== this.canvas && this.state === 'playing') {
        this.shooting = false;
      }
    });

    document.addEventListener('mousemove', (e) => {
      if (this.state !== 'playing') return;

      // Pointer lock path (desktop, non-sandboxed)
      if (document.pointerLockElement === this.canvas) {
        this.player?.onMouseMove(e.movementX, e.movementY);
        return;
      }

      // Fallback: use movementX/Y directly (works in most browsers even without lock)
      if (this.mouseCaptured && (e.movementX !== 0 || e.movementY !== 0)) {
        this.player?.onMouseMove(e.movementX, e.movementY);
      }
    });

    document.addEventListener('mousedown', (e) => {
      if (e.button !== 0) return;
      const locked = document.pointerLockElement === this.canvas;
      if (locked || (this.mouseCaptured && e.target === this.canvas)) {
        this.shooting = true;
      }
    });

    document.addEventListener('mouseup', (e) => {
      if (e.button === 0) this.shooting = false;
    });

    // Scroll to switch weapons
    this.canvas.addEventListener('wheel', (e) => {
      if (this.state !== 'playing') return;
      const dir = e.deltaY > 0 ? 1 : -1;
      this.player.currentWeapon = (this.player.currentWeapon + dir + 3) % 3;
    });
  }

  startGame() {
    this.audio.resume();
    this.currentLevel = 0;
    this.loadLevel(0);
    this.setState('playing');
    this.audio.startAmbient();
    if (!this.loopRunning) {
      this.loopRunning = true;
      this.lastTime = -1;
      this.animFrame = requestAnimationFrame((t) => this.loop(t));
    }
  }

  private loadLevel(index: number) {
    // Clear previous level
    if (this.scene) {
      this.levelMeshes.forEach(m => this.scene.remove(m));
      this.enemies.forEach(e => this.scene.remove(e.mesh));
      this.pickups.forEach(p => p.dispose(this.scene));
      this.projectiles.forEach(p => p.dispose(this.scene));
    }
    this.enemies = [];
    this.pickups = [];
    this.projectiles = [];

    this.scene = new THREE.Scene();
    this.level = generateLevel(index);
    this.scene.background = new THREE.Color(this.level.skyColor);
    this.scene.fog = new THREE.Fog(this.level.skyColor, 20, 80);

    // Lighting
    const ambient = new THREE.AmbientLight(0xffffff, 0.4);
    this.scene.add(ambient);

    // Multiple point lights for atmosphere
    const lightPositions = this.level.rooms.slice(0, 6).map(r => ({
      x: r.x + r.w / 2, z: r.z + r.d / 2,
    }));
    lightPositions.forEach(lp => {
      const light = new THREE.PointLight(this.level.accentColor, 1.5, 25);
      light.position.set(lp.x, 4, lp.z);
      light.castShadow = false;
      this.scene.add(light);
    });

    // Build geometry
    this.levelMeshes = buildLevelMesh(this.level, this.scene);

    // Exit marker
    const exitGeo = new THREE.BoxGeometry(2, 0.1, 2);
    const exitMat = new THREE.MeshLambertMaterial({
      color: this.level.accentColor,
      emissive: this.level.accentColor,
      emissiveIntensity: 0.8,
    });
    this.exitMesh = new THREE.Mesh(exitGeo, exitMat);
    this.exitMesh.position.copy(this.level.exitPos);
    this.exitMesh.position.y = 0.05;
    this.scene.add(this.exitMesh);

    // Player
    if (!this.player) {
      this.player = new Player(this.canvas);
    } else {
      this.player.reset();
    }

    // Weapon renderer
    this.weaponRenderer = new WeaponRenderer(this.overlayCanvas);

    // Spawn entities
    this.totalEnemies = 0;
    for (const spawn of this.level.spawns) {
      if (spawn.type === 'player') {
        this.player.spawnAt(spawn.x, spawn.z);
      } else if (spawn.type.startsWith('enemy_')) {
        const type = spawn.type.replace('enemy_', '') as EnemyType;
        this.enemies.push(new Enemy(type, spawn.x, spawn.z, this.scene));
        this.totalEnemies++;
      } else {
        this.pickups.push(new Pickup(spawn.type as PickupType, spawn.x, spawn.z, this.scene));
      }
    }
  }

  private setState(s: GameState) {
    this.state = s;
    this.onStateChange(s);
  }

  private loop(time: number) {
    this.animFrame = requestAnimationFrame((t) => this.loop(t));
    // First frame: skip update to avoid a huge dt spike
    if (this.lastTime < 0) { this.lastTime = time; return; }
    const dt = Math.min((time - this.lastTime) / 1000, 0.05);
    this.lastTime = time;

    // FPS counter
    this.fpsCounter++;
    this.fpsTime += dt;
    if (this.fpsTime >= 0.5) {
      this.fps = Math.round(this.fpsCounter / this.fpsTime);
      this.fpsCounter = 0;
      this.fpsTime = 0;
    }

    if (this.state === 'playing') {
      this.update(dt);
    }

    this.render(dt);
    this.updateStats();
  }

  private update(dt: number) {
    const player = this.player;
    if (!player) return;

    player.update(dt, this.level);

    // Shooting
    if (this.shooting && player.canFire()) {
      this.handleShoot();
    }

    // Enemies
    for (const enemy of this.enemies) {
      enemy.update(dt, player.camera.position, this.level, (dmg) => {
        player.takeDamage(dmg);
        this.audio.playerHurt();
      });
    }

    // Projectiles
    for (const proj of this.projectiles) {
      proj.update(dt, this.level, this.enemies,
        (e, dmg) => this.hitEnemy(e, dmg),
        (pos) => this.createExplosion(pos),
      );
    }
    // Cleanup dead projectiles
    this.projectiles = this.projectiles.filter(p => {
      if (!p.alive) { p.dispose(this.scene); return false; }
      return true;
    });

    // Pickups
    for (const pickup of this.pickups) {
      pickup.update(dt);
      if (pickup.checkCollect(player.camera.position)) {
        if (pickup.type === 'health') { player.addHealth(25); this.audio.pickupHealth(); }
        else if (pickup.type === 'ammo') { player.addAmmo(0, 20); player.addAmmo(1, 8); player.addAmmo(2, 3); this.audio.pickupAmmo(); }
        else if (pickup.type === 'armor') { player.addArmor(30); this.audio.pickupHealth(); }
      }
    }

    // Exit check
    if (this.exitMesh) {
      const dist = player.camera.position.distanceTo(this.level.exitPos);
      if (dist < 2.5) {
        this.onLevelComplete();
        return;
      }
      // Pulse exit
      const pulse = 0.5 + Math.sin(Date.now() * 0.004) * 0.5;
      (this.exitMesh.material as THREE.MeshLambertMaterial).emissiveIntensity = pulse;
    }

    // Player death
    if (player.dead) {
      this.audio.playerDeath();
      this.setState('dead');
    }
  }

  private handleShoot() {
    const player = this.player;
    if (!player.fire()) return;

    const w = player.weapon;
    const origin = player.camera.position.clone();

    if (w.name === 'PISTOL') {
      this.audio.shootPistol();
      this.hitscan(origin, player.getRayDirection(), w.damage);
    } else if (w.name === 'SHOTGUN') {
      this.audio.shootShotgun();
      for (let i = 0; i < w.spread; i++) {
        const spreadAmt = 0.06;
        const dir = player.getRayDirection().clone();
        dir.x += (Math.random() - 0.5) * spreadAmt;
        dir.y += (Math.random() - 0.5) * spreadAmt;
        dir.z += (Math.random() - 0.5) * spreadAmt;
        this.hitscan(origin, dir.normalize(), w.damage);
      }
    } else if (w.name === 'ROCKET') {
      this.audio.shootRocket();
      const dir = player.getRayDirection();
      // Offset origin slightly forward to avoid self-collision
      const projOrigin = origin.clone().addScaledVector(dir, 1.0);
      projOrigin.y -= 0.3;
      this.projectiles.push(new Projectile(projOrigin, dir, this.scene));
    }

    this.weaponRenderer.triggerFire();
  }

  private hitscan(origin: THREE.Vector3, direction: THREE.Vector3, damage: number) {
    const raycaster = new THREE.Raycaster(origin, direction, 0.1, 60);
    // Check enemies
    const enemyMeshes = this.enemies.filter(e => !e.dead).map(e => e.mesh);
    const hits = raycaster.intersectObjects(enemyMeshes, true);
    if (hits.length > 0) {
      // Find which enemy was hit
      const hitObj = hits[0].object;
      for (const enemy of this.enemies) {
        if (enemy.dead) continue;
        if (enemy.mesh === hitObj || enemy.mesh.children.includes(hitObj)) {
          this.hitEnemy(enemy, damage);
          return;
        }
      }
    }
  }

  private hitEnemy(enemy: Enemy, damage: number) {
    const killed = enemy.takeDamage(damage);
    if (killed) {
      this.audio.enemyDeath();
      this.player.score += enemy.config.scoreValue;
      this.player.kills++;
    } else {
      this.audio.enemyHit();
    }
  }

  private createExplosion(pos: THREE.Vector3) {
    this.audio.explosion();
    // Visual: temporary sphere
    const geo = new THREE.SphereGeometry(2, 8, 8);
    const mat = new THREE.MeshLambertMaterial({ color: 0xff6600, emissive: 0xff3300, emissiveIntensity: 1, transparent: true, opacity: 0.8 });
    const mesh = new THREE.Mesh(geo, mat);
    mesh.position.copy(pos);
    this.scene.add(mesh);
    let age = 0;
    const fade = () => {
      age += 0.016;
      mat.opacity = Math.max(0, 0.8 - age * 3);
      mesh.scale.setScalar(1 + age * 4);
      if (age < 0.3) requestAnimationFrame(fade);
      else this.scene.remove(mesh);
    };
    requestAnimationFrame(fade);
  }

  private onLevelComplete() {
    this.audio.levelComplete();
    this.currentLevel++;
    if (this.currentLevel >= MAX_LEVELS) {
      this.setState('victory');
    } else {
      this.setState('levelcomplete');
    }
  }

  nextLevel() {
    this.audio.resume();
    this.loadLevel(this.currentLevel);
    this.setState('playing');
    this.audio.startAmbient();
  }

  restart() {
    this.audio.resume();
    this.currentLevel = 0;
    this.loadLevel(0);
    this.setState('playing');
    this.audio.startAmbient();
  }

  private render(dt: number) {
    if (!this.scene || !this.player) return;
    this.renderer.render(this.scene, this.player.camera);

    // Overlay canvas
    const ctx = this.overlayCanvas.getContext('2d')!;
    const W = this.overlayCanvas.width;
    const H = this.overlayCanvas.height;
    ctx.clearRect(0, 0, W, H);

    if (this.state === 'playing') {
      const k = this.player.keys;
      const isMoving = !!(k['KeyW'] || k['KeyS'] || k['KeyA'] || k['KeyD'] ||
                          k['ArrowUp'] || k['ArrowDown'] || k['ArrowLeft'] || k['ArrowRight']);

      this.weaponRenderer.update(dt, this.player.currentWeapon, isMoving);
      this.weaponRenderer.draw(this.player.weapon.reloading);

      // Damage flash overlay
      if (this.player.damageFlash > 0) {
        ctx.fillStyle = `rgba(220,0,0,${this.player.damageFlash * 0.35})`;
        ctx.fillRect(0, 0, W, H);
      }

      // Crosshair
      this.drawCrosshair(ctx, W, H);

      // Minimap
      this.drawMinimap(ctx, W, H);
    }
  }

  private drawCrosshair(ctx: CanvasRenderingContext2D, W: number, H: number) {
    const cx = W / 2, cy = H / 2;
    const size = 10, gap = 4, thick = 2;
    ctx.strokeStyle = 'rgba(200,240,77,0.9)';
    ctx.lineWidth = thick;
    ctx.beginPath();
    ctx.moveTo(cx - size - gap, cy); ctx.lineTo(cx - gap, cy);
    ctx.moveTo(cx + gap, cy);       ctx.lineTo(cx + size + gap, cy);
    ctx.moveTo(cx, cy - size - gap); ctx.lineTo(cx, cy - gap);
    ctx.moveTo(cx, cy + gap);        ctx.lineTo(cx, cy + size + gap);
    ctx.stroke();
    // Center dot
    ctx.fillStyle = 'rgba(200,240,77,0.9)';
    ctx.beginPath();
    ctx.arc(cx, cy, 1.5, 0, Math.PI * 2);
    ctx.fill();
  }

  private drawMinimap(ctx: CanvasRenderingContext2D, W: number, H: number) {
    const mapSize = 140;
    const mx = W - mapSize - 12;
    const my = 12;
    // Background
    ctx.fillStyle = 'rgba(0,0,0,0.65)';
    ctx.fillRect(mx, my, mapSize, mapSize);
    ctx.strokeStyle = 'rgba(200,240,77,0.4)';
    ctx.lineWidth = 1;
    ctx.strokeRect(mx, my, mapSize, mapSize);

    // Find bounds
    const allRects = [...this.level.rooms, ...this.level.corridors];
    let minX = Infinity, minZ = Infinity, maxX = -Infinity, maxZ = -Infinity;
    for (const r of allRects) {
      minX = Math.min(minX, r.x); minZ = Math.min(minZ, r.z);
      maxX = Math.max(maxX, r.x + r.w); maxZ = Math.max(maxZ, r.z + r.d);
    }
    const rangeX = maxX - minX || 1;
    const rangeZ = maxZ - minZ || 1;
    const s = (mapSize - 4) / Math.max(rangeX, rangeZ);

    const toMap = (wx: number, wz: number) => ({
      x: mx + 2 + (wx - minX) * s,
      y: my + 2 + (wz - minZ) * s,
    });

    // Rooms
    ctx.fillStyle = 'rgba(60,60,60,0.8)';
    for (const r of allRects) {
      const p = toMap(r.x, r.z);
      ctx.fillRect(p.x, p.y, r.w * s, r.d * s);
    }

    // Exit
    const ep = toMap(this.level.exitPos.x, this.level.exitPos.z);
    ctx.fillStyle = `#${this.level.accentColor.toString(16).padStart(6, '0')}`;
    ctx.fillRect(ep.x - 3, ep.y - 3, 6, 6);

    // Enemies
    for (const e of this.enemies) {
      if (e.dead) continue;
      const p = toMap(e.position.x, e.position.z);
      ctx.fillStyle = e.type === 'boss' ? '#ff4444' : '#ff8844';
      ctx.beginPath();
      ctx.arc(p.x, p.y, 2.5, 0, Math.PI * 2);
      ctx.fill();
    }

    // Player
    const pp = toMap(this.player.camera.position.x, this.player.camera.position.z);
    ctx.fillStyle = '#C8F04D';
    ctx.beginPath();
    ctx.arc(pp.x, pp.y, 3.5, 0, Math.PI * 2);
    ctx.fill();
    // Direction indicator
    const yaw = this.player.yaw;
    ctx.strokeStyle = '#C8F04D';
    ctx.lineWidth = 1.5;
    ctx.beginPath();
    ctx.moveTo(pp.x, pp.y);
    ctx.lineTo(pp.x - Math.sin(yaw) * 7, pp.y - Math.cos(yaw) * 7);
    ctx.stroke();
  }

  private updateStats() {
    if (!this.player) return;
    const w = this.player.weapon;
    this.onStatsUpdate({
      health: this.player.health,
      maxHealth: this.player.maxHealth,
      armor: this.player.armor,
      ammo: w.ammo,
      maxAmmo: w.maxAmmo,
      weaponName: w.name,
      reloading: w.reloading,
      score: this.player.score,
      kills: this.player.kills,
      totalEnemies: this.totalEnemies,
      level: this.currentLevel + 1,
      levelName: this.level?.name ?? '',
      damageFlash: this.player.damageFlash,
      state: this.state,
      fps: this.fps,
    });
  }

  toggleMute() {
    return this.audio.toggleMute();
  }

  destroy() {
    cancelAnimationFrame(this.animFrame);
    this.loopRunning = false;
    this.audio.stopAmbient();
    this.player?.destroy();
    this.renderer.dispose();
    try { document.exitPointerLock(); } catch { /* sandboxed — ignore */ }
  }
}
