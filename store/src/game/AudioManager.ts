/**
 * AudioManager — procedural Web Audio API sounds, zero external assets.
 * All sounds are synthesized from oscillators, noise buffers, and filters.
 */
export class AudioManager {
  private ctx: AudioContext | null = null;
  private masterGain: GainNode | null = null;
  private ambientOsc: OscillatorNode | null = null;
  private muted = false;

  init() {
    try {
      this.ctx = new AudioContext();
      this.masterGain = this.ctx.createGain();
      this.masterGain.gain.value = 0.4;
      this.masterGain.connect(this.ctx.destination);
    } catch {
      console.warn('Web Audio not available');
    }
  }

  resume() {
    if (this.ctx?.state === 'suspended') this.ctx.resume();
  }

  private get ac(): AudioContext | null { return this.ctx; }
  private get mg(): GainNode | null { return this.masterGain; }

  private noise(duration: number, freq: number, decay: number, vol = 0.5) {
    const ac = this.ac; const mg = this.mg;
    if (!ac || !mg || this.muted) return;
    const bufSize = ac.sampleRate * duration;
    const buf = ac.createBuffer(1, bufSize, ac.sampleRate);
    const data = buf.getChannelData(0);
    for (let i = 0; i < bufSize; i++) data[i] = (Math.random() * 2 - 1);
    const src = ac.createBufferSource();
    src.buffer = buf;
    const filter = ac.createBiquadFilter();
    filter.type = 'bandpass';
    filter.frequency.value = freq;
    filter.Q.value = 1.5;
    const gain = ac.createGain();
    gain.gain.setValueAtTime(vol, ac.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ac.currentTime + decay);
    src.connect(filter);
    filter.connect(gain);
    gain.connect(mg);
    src.start();
    src.stop(ac.currentTime + decay);
  }

  private tone(freq: number, duration: number, type: OscillatorType = 'square', vol = 0.3) {
    const ac = this.ac; const mg = this.mg;
    if (!ac || !mg || this.muted) return;
    const osc = ac.createOscillator();
    const gain = ac.createGain();
    osc.type = type;
    osc.frequency.value = freq;
    gain.gain.setValueAtTime(vol, ac.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ac.currentTime + duration);
    osc.connect(gain);
    gain.connect(mg);
    osc.start();
    osc.stop(ac.currentTime + duration);
  }

  shootPistol() {
    this.noise(0.12, 800, 0.12, 0.6);
    this.tone(120, 0.08, 'sawtooth', 0.2);
  }

  shootShotgun() {
    this.noise(0.25, 400, 0.25, 0.9);
    this.tone(80, 0.15, 'sawtooth', 0.3);
    setTimeout(() => this.noise(0.1, 600, 0.1, 0.4), 30);
  }

  shootRocket() {
    this.noise(0.08, 1200, 0.08, 0.5);
    const ac = this.ac; const mg = this.mg;
    if (!ac || !mg || this.muted) return;
    const osc = ac.createOscillator();
    const gain = ac.createGain();
    osc.type = 'sawtooth';
    osc.frequency.setValueAtTime(200, ac.currentTime);
    osc.frequency.exponentialRampToValueAtTime(60, ac.currentTime + 0.4);
    gain.gain.setValueAtTime(0.4, ac.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ac.currentTime + 0.4);
    osc.connect(gain);
    gain.connect(mg);
    osc.start();
    osc.stop(ac.currentTime + 0.4);
  }

  explosion() {
    this.noise(0.6, 200, 0.6, 1.0);
    this.noise(0.4, 100, 0.4, 0.8);
    this.tone(50, 0.3, 'sawtooth', 0.5);
  }

  enemyHit() {
    this.tone(300, 0.05, 'square', 0.2);
    this.noise(0.05, 1500, 0.05, 0.3);
  }

  enemyDeath() {
    const ac = this.ac; const mg = this.mg;
    if (!ac || !mg || this.muted) return;
    const osc = ac.createOscillator();
    const gain = ac.createGain();
    osc.type = 'square';
    osc.frequency.setValueAtTime(400, ac.currentTime);
    osc.frequency.exponentialRampToValueAtTime(40, ac.currentTime + 0.5);
    gain.gain.setValueAtTime(0.4, ac.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ac.currentTime + 0.5);
    osc.connect(gain);
    gain.connect(mg);
    osc.start();
    osc.stop(ac.currentTime + 0.5);
    this.noise(0.3, 300, 0.3, 0.5);
  }

  playerHurt() {
    this.tone(150, 0.15, 'sawtooth', 0.4);
    this.noise(0.1, 2000, 0.1, 0.3);
  }

  playerDeath() {
    const ac = this.ac; const mg = this.mg;
    if (!ac || !mg || this.muted) return;
    const osc = ac.createOscillator();
    const gain = ac.createGain();
    osc.type = 'sawtooth';
    osc.frequency.setValueAtTime(600, ac.currentTime);
    osc.frequency.exponentialRampToValueAtTime(30, ac.currentTime + 1.5);
    gain.gain.setValueAtTime(0.6, ac.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ac.currentTime + 1.5);
    osc.connect(gain);
    gain.connect(mg);
    osc.start();
    osc.stop(ac.currentTime + 1.5);
    this.noise(1.0, 150, 1.0, 0.8);
  }

  pickupHealth() {
    this.tone(440, 0.1, 'sine', 0.3);
    setTimeout(() => this.tone(660, 0.1, 'sine', 0.3), 80);
    setTimeout(() => this.tone(880, 0.15, 'sine', 0.3), 160);
  }

  pickupAmmo() {
    this.tone(330, 0.08, 'square', 0.2);
    setTimeout(() => this.tone(440, 0.08, 'square', 0.2), 60);
  }

  levelComplete() {
    const notes = [523, 659, 784, 1047];
    notes.forEach((n, i) => setTimeout(() => this.tone(n, 0.3, 'sine', 0.4), i * 120));
  }

  startAmbient() {
    const ac = this.ac; const mg = this.mg;
    if (!ac || !mg || this.muted) return;
    this.stopAmbient();
    const osc = ac.createOscillator();
    const gain = ac.createGain();
    osc.type = 'sine';
    osc.frequency.value = 55;
    gain.gain.value = 0.04;
    osc.connect(gain);
    gain.connect(mg);
    osc.start();
    this.ambientOsc = osc;
  }

  stopAmbient() {
    try { this.ambientOsc?.stop(); } catch { /* already stopped */ }
    this.ambientOsc = null;
  }

  setMuted(m: boolean) { this.muted = m; }
  toggleMute() { this.muted = !this.muted; return this.muted; }
}
