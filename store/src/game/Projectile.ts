import * as THREE from 'three';
import type { Enemy } from './Enemy';
import type { LevelData } from './LevelGenerator';
import { isWalkable } from './LevelGenerator';

export class Projectile {
  mesh: THREE.Mesh;
  velocity: THREE.Vector3;
  alive = true;
  private age = 0;
  private maxAge = 3;
  private splashRadius = 3;
  private damage = 80;

  constructor(origin: THREE.Vector3, direction: THREE.Vector3, scene: THREE.Scene) {
    const geo = new THREE.SphereGeometry(0.15, 6, 6);
    const mat = new THREE.MeshLambertMaterial({ color: 0xff6600, emissive: 0xff3300, emissiveIntensity: 1 });
    this.mesh = new THREE.Mesh(geo, mat);
    this.mesh.position.copy(origin);
    this.velocity = direction.clone().normalize().multiplyScalar(25);
    scene.add(this.mesh);
  }

  update(dt: number, level: LevelData, enemies: Enemy[], onHitEnemy: (e: Enemy, dmg: number) => void, onExplode: (pos: THREE.Vector3) => void) {
    if (!this.alive) return;
    this.age += dt;
    if (this.age > this.maxAge) { this.explode(enemies, onHitEnemy, onExplode); return; }

    const next = this.mesh.position.clone().addScaledVector(this.velocity, dt);

    // Wall collision
    if (!isWalkable(level, next.x, next.z)) {
      this.explode(enemies, onHitEnemy, onExplode);
      return;
    }

    this.mesh.position.copy(next);

    // Enemy collision
    for (const e of enemies) {
      if (e.dead) continue;
      if (this.mesh.position.distanceTo(e.position) < 1.2) {
        this.explode(enemies, onHitEnemy, onExplode);
        return;
      }
    }
  }

  private explode(enemies: Enemy[], onHitEnemy: (e: Enemy, dmg: number) => void, onExplode: (pos: THREE.Vector3) => void) {
    this.alive = false;
    this.mesh.visible = false;
    onExplode(this.mesh.position.clone());
    // Splash damage
    for (const e of enemies) {
      if (e.dead) continue;
      const dist = this.mesh.position.distanceTo(e.position);
      if (dist < this.splashRadius) {
        const falloff = 1 - dist / this.splashRadius;
        onHitEnemy(e, Math.round(this.damage * falloff));
      }
    }
  }

  dispose(scene: THREE.Scene) {
    scene.remove(this.mesh);
  }
}
