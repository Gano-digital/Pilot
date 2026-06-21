import * as THREE from 'three';
import type { LevelData } from './LevelGenerator';
import { isWalkable } from './LevelGenerator';

export type EnemyType = 'grunt' | 'soldier' | 'boss';

type AIState = 'idle' | 'patrol' | 'chase' | 'attack' | 'dead';

export interface EnemyConfig {
  health: number;
  speed: number;
  damage: number;
  attackRange: number;
  sightRange: number;
  attackRate: number; // ms
  scoreValue: number;
  color: number;
  size: number;
}

const CONFIGS: Record<EnemyType, EnemyConfig> = {
  grunt:   { health: 40,  speed: 3.5, damage: 8,  attackRange: 2.5, sightRange: 20, attackRate: 1200, scoreValue: 100, color: 0x8B4513, size: 0.7 },
  soldier: { health: 80,  speed: 4.5, damage: 15, attackRange: 8,   sightRange: 28, attackRate: 1500, scoreValue: 250, color: 0x556B2F, size: 0.8 },
  boss:    { health: 250, speed: 3.0, damage: 30, attackRange: 5,   sightRange: 35, attackRate: 2000, scoreValue: 750, color: 0x8B0000, size: 1.2 },
};

export class Enemy {
  mesh: THREE.Group;
  type: EnemyType;
  config: EnemyConfig;
  health: number;
  state: AIState = 'patrol';
  position: THREE.Vector3;
  dead = false;

  private patrolTarget: THREE.Vector3;
  private lastAttack = 0;
  private stateTimer = 0;
  private floatTime = 0;

  // Cached materials for hurt flash — avoids creating new materials every frame
  private bodyMat: THREE.MeshLambertMaterial;
  private hurtMat: THREE.MeshLambertMaterial;
  private hurtTimer = 0;

  constructor(type: EnemyType, x: number, z: number, scene: THREE.Scene) {
    this.type = type;
    this.config = { ...CONFIGS[type] };
    this.health = this.config.health;
    this.position = new THREE.Vector3(x, 0, z);
    this.patrolTarget = new THREE.Vector3(x, 0, z);

    this.bodyMat = new THREE.MeshLambertMaterial({ color: this.config.color });
    this.hurtMat = new THREE.MeshLambertMaterial({ color: 0xffffff });

    this.mesh = this.buildMesh();
    this.mesh.position.set(x, this.config.size, z);
    scene.add(this.mesh);
  }

  private buildMesh(): THREE.Group {
    const g = new THREE.Group();
    const cfg = this.config;
    const darkMat = new THREE.MeshLambertMaterial({ color: new THREE.Color(cfg.color).multiplyScalar(0.5).getHex() });
    const eyeMat  = new THREE.MeshLambertMaterial({ color: 0xff2200, emissive: 0xff2200, emissiveIntensity: 0.8 });

    // Body — index 0, used for hurt flash
    const body = new THREE.Mesh(new THREE.BoxGeometry(cfg.size * 0.8, cfg.size * 1.2, cfg.size * 0.5), this.bodyMat);
    g.add(body);

    // Head
    const head = new THREE.Mesh(new THREE.BoxGeometry(cfg.size * 0.6, cfg.size * 0.6, cfg.size * 0.5), this.bodyMat);
    head.position.y = cfg.size * 0.9;
    g.add(head);

    // Eyes
    const eyeGeo = new THREE.BoxGeometry(cfg.size * 0.12, cfg.size * 0.1, 0.05);
    const eyeL = new THREE.Mesh(eyeGeo, eyeMat);
    eyeL.position.set(-cfg.size * 0.15, cfg.size * 0.95, cfg.size * 0.26);
    const eyeR = new THREE.Mesh(eyeGeo, eyeMat);
    eyeR.position.set( cfg.size * 0.15, cfg.size * 0.95, cfg.size * 0.26);
    g.add(eyeL, eyeR);

    // Arms
    const armGeo = new THREE.BoxGeometry(cfg.size * 0.2, cfg.size * 0.8, cfg.size * 0.2);
    const armL = new THREE.Mesh(armGeo, darkMat);
    armL.position.set(-cfg.size * 0.55, 0, 0);
    const armR = new THREE.Mesh(armGeo, darkMat);
    armR.position.set( cfg.size * 0.55, 0, 0);
    g.add(armL, armR);

    // Legs
    const legGeo = new THREE.BoxGeometry(cfg.size * 0.25, cfg.size * 0.7, cfg.size * 0.25);
    const legL = new THREE.Mesh(legGeo, darkMat);
    legL.position.set(-cfg.size * 0.22, -cfg.size * 0.95, 0);
    const legR = new THREE.Mesh(legGeo, darkMat);
    legR.position.set( cfg.size * 0.22, -cfg.size * 0.95, 0);
    g.add(legL, legR);

    // Boss horns
    if (this.type === 'boss') {
      const hornMat = new THREE.MeshLambertMaterial({ color: 0x333333 });
      const hornGeo = new THREE.ConeGeometry(cfg.size * 0.1, cfg.size * 0.4, 4);
      const hornL = new THREE.Mesh(hornGeo, hornMat);
      hornL.position.set(-cfg.size * 0.2, cfg.size * 1.35, 0);
      const hornR = new THREE.Mesh(hornGeo, hornMat);
      hornR.position.set( cfg.size * 0.2, cfg.size * 1.35, 0);
      g.add(hornL, hornR);
    }

    return g;
  }

  takeDamage(amount: number): boolean {
    if (this.dead) return false;
    this.health -= amount;
    this.hurtTimer = 0.15;
    // Swap to pre-cached hurt material — no allocation
    (this.mesh.children[0] as THREE.Mesh).material = this.hurtMat;
    (this.mesh.children[1] as THREE.Mesh).material = this.hurtMat;
    if (this.health <= 0) {
      this.health = 0;
      this.die();
      return true;
    }
    this.state = 'chase';
    return false;
  }

  private die() {
    this.dead = true;
    this.state = 'dead';
    this.mesh.rotation.z = Math.PI / 2;
    this.mesh.position.y = this.config.size * 0.3;
    setTimeout(() => { this.mesh.visible = false; }, 3000);
  }

  update(dt: number, playerPos: THREE.Vector3, level: LevelData, onAttackPlayer: (dmg: number) => void) {
    if (this.dead) return;

    this.floatTime += dt;
    this.stateTimer += dt;

    // Hurt flash — restore body material after timer
    if (this.hurtTimer > 0) {
      this.hurtTimer -= dt;
      if (this.hurtTimer <= 0) {
        (this.mesh.children[0] as THREE.Mesh).material = this.bodyMat;
        (this.mesh.children[1] as THREE.Mesh).material = this.bodyMat;
      }
    }

    const distToPlayer = this.position.distanceTo(playerPos);
    const cfg = this.config;

    switch (this.state) {
      case 'idle':
        if (distToPlayer < cfg.sightRange) this.state = 'chase';
        if (this.stateTimer > 2) { this.state = 'patrol'; this.stateTimer = 0; }
        break;

      case 'patrol': {
        const toTarget = new THREE.Vector3().subVectors(this.patrolTarget, this.position);
        if (toTarget.length() < 0.5 || this.stateTimer > 4) {
          const angle = Math.random() * Math.PI * 2;
          const dist  = 3 + Math.random() * 5;
          const nx = this.position.x + Math.cos(angle) * dist;
          const nz = this.position.z + Math.sin(angle) * dist;
          if (isWalkable(level, nx, nz)) this.patrolTarget.set(nx, 0, nz);
          this.stateTimer = 0;
        } else {
          this.moveToward(this.patrolTarget, cfg.speed * 0.5, dt, level);
        }
        if (distToPlayer < cfg.sightRange) this.state = 'chase';
        break;
      }

      case 'chase':
        if (distToPlayer < cfg.attackRange) {
          this.state = 'attack';
          this.stateTimer = 0;
        } else if (distToPlayer > cfg.sightRange * 1.5) {
          this.state = 'patrol';
        } else {
          this.moveToward(playerPos, cfg.speed, dt, level);
        }
        break;

      case 'attack':
        this.mesh.lookAt(playerPos.x, this.mesh.position.y, playerPos.z);
        if (distToPlayer > cfg.attackRange * 1.3) {
          this.state = 'chase';
        } else if (Date.now() - this.lastAttack > cfg.attackRate) {
          this.lastAttack = Date.now();
          onAttackPlayer(cfg.damage);
        }
        break;
    }

    // Bob animation
    this.mesh.position.set(
      this.position.x,
      this.config.size + Math.sin(this.floatTime * 2) * 0.05,
      this.position.z,
    );
  }

  private moveToward(target: THREE.Vector3, speed: number, dt: number, level: LevelData) {
    const dir = new THREE.Vector3().subVectors(target, this.position);
    dir.y = 0;
    if (dir.lengthSq() < 0.01) return;
    dir.normalize().multiplyScalar(speed * dt);

    const nx = this.position.x + dir.x;
    const nz = this.position.z + dir.z;

    if (isWalkable(level, nx, this.position.z)) this.position.x = nx;
    if (isWalkable(level, this.position.x, nz)) this.position.z = nz;

    this.mesh.lookAt(target.x, this.mesh.position.y, target.z);
  }

  dispose(scene: THREE.Scene) {
    scene.remove(this.mesh);
    this.bodyMat.dispose();
    this.hurtMat.dispose();
  }
}
