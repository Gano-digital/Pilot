import * as THREE from 'three';

export type PickupType = 'health' | 'ammo' | 'armor';

export class Pickup {
  mesh: THREE.Mesh;
  type: PickupType;
  position: THREE.Vector3;
  collected = false;
  private floatTime = 0;
  private baseY: number;

  constructor(type: PickupType, x: number, z: number, scene: THREE.Scene) {
    this.type = type;
    this.position = new THREE.Vector3(x, 0.5, z);
    this.baseY = 0.5;

    const colors: Record<PickupType, number> = { health: 0xff2244, ammo: 0xffcc00, armor: 0x4488ff };
    const emissive: Record<PickupType, number> = { health: 0xff0022, ammo: 0xffaa00, armor: 0x2266ff };

    const mat = new THREE.MeshLambertMaterial({
      color: colors[type],
      emissive: emissive[type],
      emissiveIntensity: 0.5,
    });

    let geo: THREE.BufferGeometry;
    if (type === 'health') {
      geo = new THREE.BoxGeometry(0.5, 0.5, 0.5);
    } else if (type === 'ammo') {
      geo = new THREE.BoxGeometry(0.6, 0.35, 0.35);
    } else {
      geo = new THREE.OctahedronGeometry(0.35, 0);
    }

    this.mesh = new THREE.Mesh(geo, mat);
    this.mesh.position.copy(this.position);
    this.mesh.castShadow = true;
    scene.add(this.mesh);
  }

  update(dt: number) {
    if (this.collected) return;
    this.floatTime += dt;
    this.mesh.position.y = this.baseY + Math.sin(this.floatTime * 2.5) * 0.15;
    this.mesh.rotation.y += dt * 1.5;
  }

  checkCollect(playerPos: THREE.Vector3): boolean {
    if (this.collected) return false;
    if (playerPos.distanceTo(this.position) < 1.2) {
      this.collected = true;
      this.mesh.visible = false;
      return true;
    }
    return false;
  }

  dispose(scene: THREE.Scene) {
    scene.remove(this.mesh);
  }
}
