import * as THREE from 'three';

export interface Room {
  x: number; z: number;
  w: number; d: number;
  id: number;
}

export interface SpawnPoint {
  x: number; z: number; type: 'player' | 'enemy_grunt' | 'enemy_soldier' | 'enemy_boss' | 'health' | 'ammo' | 'armor';
}

export interface LevelData {
  rooms: Room[];
  corridors: Room[];
  spawns: SpawnPoint[];
  exitPos: THREE.Vector3;
  skyColor: number;
  floorColor: number;
  wallColor: number;
  accentColor: number;
  name: string;
}

// Deterministic seeded RNG
function seededRng(seed: number) {
  let s = seed;
  return () => {
    s = (s * 1664525 + 1013904223) & 0xffffffff;
    return (s >>> 0) / 0xffffffff;
  };
}

const CELL = 8; // world units per grid cell

export function generateLevel(levelIndex: number): LevelData {
  const rng = seededRng(levelIndex * 7919 + 1234);

  const themes = [
    { sky: 0x0a0a0a, floor: 0x1a1a1a, wall: 0x2a2a2a, accent: 0xc8f04d, name: 'SECTOR ZERO' },
    { sky: 0x050510, floor: 0x0d0d1a, wall: 0x1a1a2e, accent: 0x754df0, name: 'VOID STATION' },
    { sky: 0x100505, floor: 0x1a0d0d, wall: 0x2e1a1a, accent: 0xff4444, name: 'INFERNO CORE' },
  ];
  const theme = themes[levelIndex % themes.length];

  const rooms: Room[] = [];
  const corridors: Room[] = [];
  const spawns: SpawnPoint[] = [];

  // Generate rooms on a grid
  const gridW = 10 + levelIndex * 2;
  const gridH = 10 + levelIndex * 2;
  const placed: boolean[][] = Array.from({ length: gridW }, () => Array(gridH).fill(false));

  const numRooms = 8 + levelIndex * 3;
  let attempts = 0;

  while (rooms.length < numRooms && attempts < 500) {
    attempts++;
    const rw = 2 + Math.floor(rng() * 4);
    const rh = 2 + Math.floor(rng() * 4);
    const rx = Math.floor(rng() * (gridW - rw - 1)) + 1;
    const rz = Math.floor(rng() * (gridH - rh - 1)) + 1;

    // Check overlap
    let overlap = false;
    for (let x = rx - 1; x <= rx + rw; x++) {
      for (let z = rz - 1; z <= rz + rh; z++) {
        if (x >= 0 && x < gridW && z >= 0 && z < gridH && placed[x][z]) {
          overlap = true; break;
        }
      }
      if (overlap) break;
    }
    if (overlap) continue;

    for (let x = rx; x < rx + rw; x++)
      for (let z = rz; z < rz + rh; z++)
        placed[x][z] = true;

    rooms.push({ x: rx * CELL, z: rz * CELL, w: rw * CELL, d: rh * CELL, id: rooms.length });
  }

  // Connect rooms with corridors
  for (let i = 1; i < rooms.length; i++) {
    const a = rooms[i - 1];
    const b = rooms[i];
    const ax = a.x + a.w / 2;
    const az = a.z + a.d / 2;
    const bx = b.x + b.w / 2;
    const bz = b.z + b.d / 2;

    // L-shaped corridor
    const cw = CELL;
    if (Math.abs(bx - ax) > 1) {
      corridors.push({
        x: Math.min(ax, bx),
        z: az - cw / 2,
        w: Math.abs(bx - ax) + cw,
        d: cw,
        id: -1,
      });
    }
    if (Math.abs(bz - az) > 1) {
      corridors.push({
        x: bx - cw / 2,
        z: Math.min(az, bz),
        w: cw,
        d: Math.abs(bz - az) + cw,
        id: -1,
      });
    }
  }

  // Player spawn in first room center
  const firstRoom = rooms[0];
  spawns.push({
    x: firstRoom.x + firstRoom.w / 2,
    z: firstRoom.z + firstRoom.d / 2,
    type: 'player',
  });

  // Enemy spawns — scale with level
  const enemyDensity = 0.4 + levelIndex * 0.15;
  for (let i = 1; i < rooms.length; i++) {
    const room = rooms[i];
    const numEnemies = Math.floor(rng() * (2 + levelIndex) * enemyDensity) + 1;
    for (let e = 0; e < numEnemies; e++) {
      const ex = room.x + 1 + rng() * (room.w - 2);
      const ez = room.z + 1 + rng() * (room.d - 2);
      const roll = rng();
      let type: SpawnPoint['type'];
      if (roll < 0.5) type = 'enemy_grunt';
      else if (roll < 0.85) type = 'enemy_soldier';
      else type = 'enemy_boss';
      spawns.push({ x: ex, z: ez, type });
    }

    // Pickups
    if (rng() < 0.5) spawns.push({ x: room.x + room.w / 2, z: room.z + room.d / 2, type: 'health' });
    if (rng() < 0.4) spawns.push({ x: room.x + 1, z: room.z + 1, type: 'ammo' });
    if (rng() < 0.25) spawns.push({ x: room.x + room.w - 1, z: room.z + 1, type: 'armor' });
  }

  // Exit in last room
  const lastRoom = rooms[rooms.length - 1];
  const exitPos = new THREE.Vector3(lastRoom.x + lastRoom.w / 2, 0, lastRoom.z + lastRoom.d / 2);

  return {
    rooms,
    corridors,
    spawns,
    exitPos,
    skyColor: theme.sky,
    floorColor: theme.floor,
    wallColor: theme.wall,
    accentColor: theme.accent,
    name: theme.name,
  };
}

const WALL_H = 6;
const WALL_THICK = 0.5;

export function buildLevelMesh(level: LevelData, scene: THREE.Scene): THREE.Mesh[] {
  const meshes: THREE.Mesh[] = [];
  const allRects = [...level.rooms, ...level.corridors];

  // Shared materials
  const wallMat = new THREE.MeshLambertMaterial({ color: level.wallColor });
  const floorMat = new THREE.MeshLambertMaterial({ color: level.floorColor });
  const ceilMat = new THREE.MeshLambertMaterial({ color: new THREE.Color(level.wallColor).multiplyScalar(0.6).getHex() });
  const accentMat = new THREE.MeshLambertMaterial({ color: level.accentColor, emissive: level.accentColor, emissiveIntensity: 0.3 });

  for (const rect of allRects) {
    const { x, z, w, d } = rect;

    // Floor
    const floorGeo = new THREE.BoxGeometry(w, 0.2, d);
    const floor = new THREE.Mesh(floorGeo, floorMat);
    floor.position.set(x + w / 2, -0.1, z + d / 2);
    floor.receiveShadow = true;
    scene.add(floor);
    meshes.push(floor);

    // Ceiling
    const ceilGeo = new THREE.BoxGeometry(w, 0.2, d);
    const ceil = new THREE.Mesh(ceilGeo, ceilMat);
    ceil.position.set(x + w / 2, WALL_H + 0.1, z + d / 2);
    scene.add(ceil);
    meshes.push(ceil);

    // Walls — N, S, E, W
    const wallDefs = [
      { px: x + w / 2, pz: z,           ww: w + WALL_THICK, wd: WALL_THICK }, // N
      { px: x + w / 2, pz: z + d,       ww: w + WALL_THICK, wd: WALL_THICK }, // S
      { px: x,         pz: z + d / 2,   ww: WALL_THICK,     wd: d },          // W
      { px: x + w,     pz: z + d / 2,   ww: WALL_THICK,     wd: d },          // E
    ];

    for (const wd of wallDefs) {
      const geo = new THREE.BoxGeometry(wd.ww, WALL_H, wd.wd);
      const mesh = new THREE.Mesh(geo, wallMat);
      mesh.position.set(wd.px, WALL_H / 2, wd.pz);
      mesh.castShadow = true;
      mesh.receiveShadow = true;
      scene.add(mesh);
      meshes.push(mesh);
    }

    // Accent strip on floor edge
    if (rect.id >= 0) {
      const stripGeo = new THREE.BoxGeometry(w - 0.4, 0.08, 0.15);
      const strip = new THREE.Mesh(stripGeo, accentMat);
      strip.position.set(x + w / 2, 0.04, z + 0.2);
      scene.add(strip);
      meshes.push(strip);
    }
  }

  return meshes;
}

export function getCollisionBoxes(level: LevelData): THREE.Box3[] {
  const boxes: THREE.Box3[] = [];
  const allRects = [...level.rooms, ...level.corridors];

  for (const rect of allRects) {
    const { x, z, w, d } = rect;
    // N wall
    boxes.push(new THREE.Box3(new THREE.Vector3(x - WALL_THICK, 0, z - WALL_THICK), new THREE.Vector3(x + w + WALL_THICK, WALL_H, z + WALL_THICK)));
    // S wall
    boxes.push(new THREE.Box3(new THREE.Vector3(x - WALL_THICK, 0, z + d - WALL_THICK), new THREE.Vector3(x + w + WALL_THICK, WALL_H, z + d + WALL_THICK)));
    // W wall
    boxes.push(new THREE.Box3(new THREE.Vector3(x - WALL_THICK, 0, z), new THREE.Vector3(x + WALL_THICK, WALL_H, z + d)));
    // E wall
    boxes.push(new THREE.Box3(new THREE.Vector3(x + w - WALL_THICK, 0, z), new THREE.Vector3(x + w + WALL_THICK, WALL_H, z + d)));
  }

  return boxes;
}

/** Returns true if a point is inside any room/corridor (walkable area) */
export function isWalkable(level: LevelData, x: number, z: number, margin = 0.6): boolean {
  const allRects = [...level.rooms, ...level.corridors];
  for (const rect of allRects) {
    if (
      x > rect.x + margin &&
      x < rect.x + rect.w - margin &&
      z > rect.z + margin &&
      z < rect.z + rect.d - margin
    ) return true;
  }
  return false;
}
