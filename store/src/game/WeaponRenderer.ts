/**
 * WeaponRenderer — draws the weapon sprite in screen-space using a 2D canvas overlay.
 * Low-poly pixel-art style, no external assets.
 */
export class WeaponRenderer {
  private canvas: HTMLCanvasElement;
  private ctx: CanvasRenderingContext2D;
  private bobOffset = 0;
  private fireFlash = 0;
  private reloadAnim = 0;
  private currentWeapon = 0;

  constructor(overlayCanvas: HTMLCanvasElement) {
    this.canvas = overlayCanvas;
    this.ctx = overlayCanvas.getContext('2d')!;
  }

  triggerFire() {
    this.fireFlash = 1.0;
    this.reloadAnim = 1.0;
  }

  update(dt: number, weaponIndex: number, isMoving: boolean) {
    this.currentWeapon = weaponIndex;
    this.fireFlash = Math.max(0, this.fireFlash - dt * 12);
    this.reloadAnim = Math.max(0, this.reloadAnim - dt * 6);
    if (isMoving) {
      this.bobOffset = Math.sin(Date.now() * 0.008) * 8;
    } else {
      this.bobOffset *= 0.85;
    }
  }

  draw(reloading: boolean) {
    const ctx = this.ctx;
    const W = this.canvas.width;
    const H = this.canvas.height;

    // Clear weapon area only (bottom half)
    ctx.clearRect(0, H * 0.5, W, H * 0.5);

    const cx = W / 2;
    const cy = H;
    const bob = this.bobOffset;
    const reloadDrop = reloading ? 60 : this.reloadAnim * 40;
    const baseY = cy + bob + reloadDrop;

    ctx.save();
    ctx.imageSmoothingEnabled = false;

    if (this.currentWeapon === 0) this.drawPistol(ctx, cx, baseY);
    else if (this.currentWeapon === 1) this.drawShotgun(ctx, cx, baseY);
    else this.drawRocket(ctx, cx, baseY);

    // Muzzle flash
    if (this.fireFlash > 0.1) {
      const alpha = this.fireFlash * 0.9;
      const size = 40 + this.fireFlash * 30;
      const grd = ctx.createRadialGradient(cx, baseY - 180, 0, cx, baseY - 180, size);
      grd.addColorStop(0, `rgba(255,240,100,${alpha})`);
      grd.addColorStop(0.4, `rgba(255,140,0,${alpha * 0.7})`);
      grd.addColorStop(1, 'rgba(255,80,0,0)');
      ctx.fillStyle = grd;
      ctx.beginPath();
      ctx.arc(cx, baseY - 180, size, 0, Math.PI * 2);
      ctx.fill();
    }

    ctx.restore();
  }

  private drawPistol(ctx: CanvasRenderingContext2D, cx: number, baseY: number) {
    const s = 3; // pixel scale
    // Barrel
    ctx.fillStyle = '#555';
    ctx.fillRect(cx - 4 * s, baseY - 60 * s / 3, 8 * s, 3 * s);
    // Slide
    ctx.fillStyle = '#444';
    ctx.fillRect(cx - 6 * s, baseY - 50 * s / 3, 12 * s, 8 * s);
    // Frame
    ctx.fillStyle = '#333';
    ctx.fillRect(cx - 5 * s, baseY - 30 * s / 3, 10 * s, 12 * s);
    // Grip
    ctx.fillStyle = '#222';
    ctx.fillRect(cx - 3 * s, baseY - 10 * s / 3, 7 * s, 14 * s);
    // Highlight
    ctx.fillStyle = '#777';
    ctx.fillRect(cx - 5 * s, baseY - 50 * s / 3, 2 * s, 6 * s);
  }

  private drawShotgun(ctx: CanvasRenderingContext2D, cx: number, baseY: number) {
    const s = 3;
    // Long barrel
    ctx.fillStyle = '#555';
    ctx.fillRect(cx - 20 * s, baseY - 65 * s / 3, 40 * s, 4 * s);
    // Second barrel
    ctx.fillStyle = '#444';
    ctx.fillRect(cx - 20 * s, baseY - 58 * s / 3, 40 * s, 3 * s);
    // Stock
    ctx.fillStyle = '#6B3A2A';
    ctx.fillRect(cx + 8 * s, baseY - 45 * s / 3, 14 * s, 18 * s);
    // Receiver
    ctx.fillStyle = '#333';
    ctx.fillRect(cx - 8 * s, baseY - 45 * s / 3, 18 * s, 14 * s);
    // Guard
    ctx.fillStyle = '#222';
    ctx.fillRect(cx - 4 * s, baseY - 25 * s / 3, 10 * s, 8 * s);
    // Highlight
    ctx.fillStyle = '#777';
    ctx.fillRect(cx - 20 * s, baseY - 65 * s / 3, 3 * s, 4 * s);
  }

  private drawRocket(ctx: CanvasRenderingContext2D, cx: number, baseY: number) {
    const s = 3;
    // Tube
    ctx.fillStyle = '#444';
    ctx.fillRect(cx - 22 * s, baseY - 60 * s / 3, 44 * s, 10 * s);
    // Front ring
    ctx.fillStyle = '#666';
    ctx.fillRect(cx - 22 * s, baseY - 62 * s / 3, 4 * s, 14 * s);
    // Rear opening
    ctx.fillStyle = '#222';
    ctx.fillRect(cx + 18 * s, baseY - 58 * s / 3, 4 * s, 8 * s);
    // Handle
    ctx.fillStyle = '#333';
    ctx.fillRect(cx - 4 * s, baseY - 30 * s / 3, 10 * s, 16 * s);
    // Sight
    ctx.fillStyle = '#C8F04D';
    ctx.fillRect(cx - 1 * s, baseY - 68 * s / 3, 2 * s, 4 * s);
    // Highlight
    ctx.fillStyle = '#888';
    ctx.fillRect(cx - 22 * s, baseY - 60 * s / 3, 44 * s, 2 * s);
  }
}
