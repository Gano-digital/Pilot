import * as THREE from 'three';
import type { LevelData } from './LevelGenerator';
import { isWalkable } from './LevelGenerator';

export interface WeaponState {
  name: string;
  ammo: number;
  maxAmmo: number;
  damage: number;
  fireRate: number; // ms between shots
  spread: number;   // pellets for shotgun
  isProjectile: boolean;
  lastFired: number;
  reloading: boolean;
  reloadTime: number;
}

const WEAPON_DEFAULTS = (): WeaponState[] => [
  { name: 'PISTOL',  ammo: 50,  maxAmmo: 200, damage: 15, fireRate: 400,  spread: 1, isProjectile: false, lastFired: 0, reloading: false, reloadTime: 800 },
  { name: 'SHOTGUN', ammo: 20,  maxAmmo: 60,  damage: 12, fireRate: 900,  spread: 7, isProjectile: false, lastFired: 0, reloading: false, reloadTime: 1200 },
  { name: 'ROCKET',  ammo: 5,   maxAmmo: 20,  damage: 80, fireRate: 1200, spread: 1, isProjectile: true,  lastFired: 0, reloading: false, reloadTime: 1500 },
];

export class Player {
  camera: THREE.PerspectiveCamera;
  health = 100;
  maxHealth = 100;
  armor = 0;
  maxArmor = 100;
  dead = false;
  score = 0;
  kills = 0;

  // Movement
  private moveSpeed = 8;
  keys: Record<string, boolean> = {};
  yaw = 0;
  private pitch = 0;
  private bobTime = 0;
  private reloadTimers: ReturnType<typeof setTimeout>[] = [];

  // Weapons
  weapons: WeaponState[] = WEAPON_DEFAULTS();
  currentWeapon = 0;

  // Damage flash
  damageFlash = 0;
  private invincibleTime = 0;

  // Bound event handlers (so we can remove them on destroy)
  private _onKeyDown: (e: KeyboardEvent) => void;
  private _onKeyUp: (e: KeyboardEvent) => void;

  constructor(canvas: HTMLCanvasElement) {
    this.camera = new THREE.PerspectiveCamera(75, canvas.clientWidth / canvas.clientHeight, 0.1, 500);
    this.camera.position.set(0, 2.5, 0);

    this._onKeyDown = (e: KeyboardEvent) => {
      this.keys[e.code] = true;
      if (e.code === 'Digit1') this.currentWeapon = 0;
      if (e.code === 'Digit2') this.currentWeapon = 1;
      if (e.code === 'Digit3') this.currentWeapon = 2;
    };
    this._onKeyUp = (e: KeyboardEvent) => { this.keys[e.code] = false; };

    window.addEventListener('keydown', this._onKeyDown);
    window.addEventListener('keyup', this._onKeyUp);
  }

  onMouseMove(dx: number, dy: number) {
    const sens = 0.002;
    this.yaw -= dx * sens;
    this.pitch -= dy * sens;
    this.pitch = Math.max(-Math.PI / 3, Math.min(Math.PI / 3, this.pitch));
    this.camera.rotation.order = 'YXZ';
    this.camera.rotation.y = this.yaw;
    this.camera.rotation.x = this.pitch;
  }

  get weapon(): WeaponState { return this.weapons[this.currentWeapon]; }

  canFire(): boolean {
    const w = this.weapon;
    return !this.dead && !w.reloading && w.ammo > 0 && Date.now() - w.lastFired > w.fireRate;
  }

  fire(): boolean {
    if (!this.canFire()) return false;
    const w = this.weapon;
    w.ammo--;
    w.lastFired = Date.now();
    if (w.ammo === 0) {
      w.reloading = true;
      const t = setTimeout(() => { w.reloading = false; }, w.reloadTime);
      this.reloadTimers.push(t);
    }
    return true;
  }

  takeDamage(amount: number) {
    if (this.dead || Date.now() < this.invincibleTime) return;
    this.invincibleTime = Date.now() + 200;
    let dmg = amount;
    if (this.armor > 0) {
      const absorbed = Math.min(this.armor, dmg * 0.5);
      this.armor -= absorbed;
      dmg -= absorbed;
    }
    this.health -= dmg;
    this.damageFlash = 1.0;
    if (this.health <= 0) {
      this.health = 0;
      this.dead = true;
    }
  }

  addHealth(amount: number) { this.health = Math.min(this.maxHealth, this.health + amount); }
  addArmor(amount: number)  { this.armor  = Math.min(this.maxArmor,  this.armor  + amount); }

  addAmmo(weaponIndex: number, amount: number) {
    const w = this.weapons[weaponIndex];
    w.ammo = Math.min(w.maxAmmo, w.ammo + amount);
  }

  update(dt: number, level: LevelData) {
    if (this.dead) return;

    this.damageFlash = Math.max(0, this.damageFlash - dt * 4);

    const forward = new THREE.Vector3(-Math.sin(this.yaw), 0, -Math.cos(this.yaw));
    const right   = new THREE.Vector3( Math.cos(this.yaw), 0, -Math.sin(this.yaw));

    const move = new THREE.Vector3();
    if (this.keys['KeyW'] || this.keys['ArrowUp'])    move.add(forward);
    if (this.keys['KeyS'] || this.keys['ArrowDown'])  move.sub(forward);
    if (this.keys['KeyA'] || this.keys['ArrowLeft'])  move.sub(right);
    if (this.keys['KeyD'] || this.keys['ArrowRight']) move.add(right);

    if (move.lengthSq() > 0) {
      move.normalize().multiplyScalar(this.moveSpeed * dt);
      this.bobTime += dt * 8;

      const nx = this.camera.position.x + move.x;
      if (isWalkable(level, nx, this.camera.position.z)) this.camera.position.x = nx;

      const nz = this.camera.position.z + move.z;
      if (isWalkable(level, this.camera.position.x, nz)) this.camera.position.z = nz;
    }

    const bobAmt = move.lengthSq() > 0 ? 0.06 : 0;
    this.camera.position.y = 2.5 + Math.sin(this.bobTime) * bobAmt;
  }

  getRayDirection(): THREE.Vector3 {
    const dir = new THREE.Vector3(0, 0, -1);
    dir.applyEuler(this.camera.rotation);
    return dir.normalize();
  }

  spawnAt(x: number, z: number) {
    this.camera.position.set(x, 2.5, z);
    this.yaw = 0;
    this.pitch = 0;
    this.camera.rotation.set(0, 0, 0);
  }

  reset() {
    // Clear pending reload timers
    this.reloadTimers.forEach(t => clearTimeout(t));
    this.reloadTimers = [];

    this.health = 100;
    this.armor = 0;
    this.dead = false;
    this.score = 0;
    this.kills = 0;
    this.damageFlash = 0;
    this.invincibleTime = 0;
    this.weapons = WEAPON_DEFAULTS();
    this.currentWeapon = 0;
    this.keys = {};
  }

  destroy() {
    this.reloadTimers.forEach(t => clearTimeout(t));
    this.reloadTimers = [];
    window.removeEventListener('keydown', this._onKeyDown);
    window.removeEventListener('keyup', this._onKeyUp);
  }
}
