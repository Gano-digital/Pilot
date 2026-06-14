# Reference — matemática, canvas y WordPress

## Julia / Mandelbrot / Tricorn

- `z' = z² + c` (Julia: `c` fijo; Mandelbrot: `c = z₀`).
- Tricorn: usar `(sx, sy) = (zx, -zy)` y `(sx+isy)² + c`.
- Escape: `|z|² > 4`.

## Newton z³ − 1 en ℂ

Iterar `zr, zi` con `f = z³ - 1`, `f' = 3z²`; división compleja estándar. Ver skill principal (catálogo de fallos).

## Metabola (campo tipo Blinn)

`S = Σ rᵢ² / ((fx-xᵢ)² + (fy-yᵢ)² + ε)`; umbral y suavizado opcional en el borde.

## Caleidoscopio (cuña)

`translate(cx,cy); rotate(2πk/n); beginPath; moveTo(0,0); arc(0,0,R,0,2π/n); closePath; clip();` dibujar contenido; `restore` por sector.

## Morton (Z-order)

Decodificar `t = k` bit a bit alternando a `x` e `y` (no confundir con desplazamientos incorrectos de `k` en el bucle).

## ImageData

Alpha opaco: **`data[i+3]`**. Evitar índices ambiguos en bucles 2×2.

## mirrorInk (simetría vertical)

Callback **`(d, hw, h, t)`** — siempre aceptar `t` aunque no se use.

## Canvas después de transform

`stroke()` agota el trazo; para espejo: **`beginPath` + geometría de nuevo**.

## SVG

`<use href="#idReal">`; IDs con sufijo único por instancia (`dataset.demoId`).

## Encolado mínimo (patrón PHP)

```php
/**
 * Registra asset visual de demostración (front).
 */
function gano_enqueue_visual_fractal_demo(): void {
	if ( is_admin() ) {
		return;
	}
	$ver = (string) filemtime( get_stylesheet_directory() . '/assets/js/visual/fractal-demo.js' );
	wp_enqueue_script(
		'gano-visual-fractal-demo',
		get_stylesheet_directory_uri() . '/assets/js/visual/fractal-demo.js',
		[],
		$ver,
		true
	);
}
// add_action( 'wp_enqueue_scripts', 'gano_enqueue_visual_fractal_demo' );
```

Condicionar con `is_page()`, `is_page_template()`, etc., para no cargar en todo el sitio.

## CSP

Antes de inline grande o `style=` dinámico, leer reglas activas en `gano-security.php`; preferir **archivo .js/.css** same-origin.

## Validación sintaxis (HTML standalone)

Extraer cuerpo de la IIFE del `<script>` y ejecutar `new Function(cuerpo)` con Node (no evalúa DOM, solo parse).

## Catálogo extendido de fallos (sesiones previas)

- Newton mal derivado; multibrot en polares inconsistente → Tricorn o fórmula cerrada correcta.
- Reacción–difusión: remuestrear rejilla `res` al canvas `w×h`, no mezclar índices.
- Simetría diagonal: `fillRect(u,v)` + `fillRect(v,u)` es claro y estable.
- Polígono en cuña: `moveTo` inicial explícito.
- `@keyframes` globales: nombres únicos por demo o SMIL por elemento.
- Variable `c` reutilizada (canvas vs constante matemática): renombrar.
