<?php
/**
 * Template Name: Showcase Futurista
 * Página de showcase de capacidades procedurales.
 */
get_header();
?>

<main class="gano-showcase">

  <!-- ═══════════════════════════════════════════════════════════════ -->
  <!-- HERO: Plasma WebGL2 + Glitch                                  -->
  <!-- ═══════════════════════════════════════════════════════════════ -->
  <section class="gano-showcase__hero">
    <canvas id="gs-hero-plasma" class="gano-showcase__hero-canvas" data-gs-effect="plasma"></canvas>
    <div class="gano-showcase__hero-content">
      <div class="gano-showcase__badge">Showcase en vivo — 100% código nativo</div>
      <h1>
        <span class="glitch">Soberanía Digital</span><br>
        en cada píxel
      </h1>
      <p>Estos efectos corren puros en el navegador: sin videos, sin imágenes, sin frameworks pesados. Solo matemáticas, Canvas 2D, WebGL y CSS 3D. Así construimos Gano.</p>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════════════════════════ -->
  <!-- GRID DE TARJETAS                                              -->
  <!-- ═══════════════════════════════════════════════════════════════ -->
  <div class="gano-showcase__grid">

    <!-- 2. Partículas conectadas -->
    <article class="gano-showcase__card">
      <div class="gano-showcase__stage">
        <canvas data-gs-effect="particles"></canvas>
      </div>
      <div class="gano-showcase__info">
        <span class="gano-showcase__tag">Infraestructura</span>
        <h3>Red neuronal de nodos</h3>
        <p>Cada partícula es un servidor. Las conexiones representan el tráfico entre datacenters. Todo en tiempo real, sin bibliotecas externas.</p>
      </div>
    </article>

    <!-- 3. Espectro radial -->
    <article class="gano-showcase__card">
      <div class="gano-showcase__stage">
        <canvas data-gs-effect="radial"></canvas>
      </div>
      <div class="gano-showcase__info">
        <span class="gano-showcase__tag">Rendimiento</span>
        <h3>Análisis espectral de carga</h3>
        <p>Visualización radial del uso de recursos. Barras polares que pulsan con la "frecuencia" de tu operación digital.</p>
      </div>
    </article>

    <!-- 4. Osciloscopio bloom -->
    <article class="gano-showcase__card">
      <div class="gano-showcase__stage">
        <canvas data-gs-effect="scope"></canvas>
      </div>
      <div class="gano-showcase__info">
        <span class="gano-showcase__tag">Monitoreo</span>
        <h3>Latencia en tiempo real</h3>
        <p>Osciloscopio procedural con bloom post-procesado. Representa la latencia de red medida en milisegundos.</p>
      </div>
    </article>

    <!-- 5. Icosaedro wireframe -->
    <article class="gano-showcase__card">
      <div class="gano-showcase__stage">
        <canvas data-gs-effect="ico"></canvas>
      </div>
      <div class="gano-showcase__info">
        <span class="gano-showcase__tag">Seguridad</span>
        <h3>Geometría de Zero-Trust</h3>
        <p>Icosaedro proyectado en 2D con 12 vértices y 30 aristas. Simboliza la redundancia y la resiliencia de nuestra arquitectura.</p>
      </div>
    </article>

    <!-- 6. Losa isométrica CSS 3D -->
    <article class="gano-showcase__card">
      <div class="gano-showcase__stage">
        <div class="gano-showcase__iso-wrap">
          <div class="gano-showcase__iso-slab">
            <div class="gano-showcase__iso-face"></div>
            <div class="gano-showcase__iso-top"></div>
            <div class="gano-showcase__iso-side"></div>
          </div>
        </div>
      </div>
      <div class="gano-showcase__info">
        <span class="gano-showcase__tag">Hosting NVMe</span>
        <h3>Bloque de infraestructura</h3>
        <p>Extrusión isométrica pura en CSS 3D. Sin modelos 3D, sin cargas de red. Solo transformaciones y gradientes.</p>
      </div>
    </article>

    <!-- 7. Bloques "GANO" isométricos -->
    <article class="gano-showcase__card">
      <div class="gano-showcase__stage">
        <div class="gano-showcase__gano-blocks">
          <div class="gano-showcase__gano-block">G</div>
          <div class="gano-showcase__gano-block">A</div>
          <div class="gano-showcase__gano-block">N</div>
          <div class="gano-showcase__gano-block">O</div>
        </div>
      </div>
      <div class="gano-showcase__info">
        <span class="gano-showcase__tag">Brand</span>
        <h3>Tipografía constructiva</h3>
        <p>Letras como prismas en perspectiva. Cada carácter flota con su propio delay de animación, creando una ola mecánica.</p>
      </div>
    </article>

    <!-- 9. Metabolas triples -->
    <article class="gano-showcase__card">
      <div class="gano-showcase__stage tall">
        <canvas data-gs-effect="meta"></canvas>
      </div>
      <div class="gano-showcase__info">
        <span class="gano-showcase__tag">Fusión</span>
        <h3>Campos metabólicos</h3>
        <p>Simulación de potencial inverso 1/r². Tres centros de energía que se funden y separan como servicios escalando en la nube.</p>
      </div>
    </article>

    <!-- 12. Vúmetros SVG -->
    <article class="gano-showcase__card">
      <div class="gano-showcase__stage" data-gs-effect="vu">
        <!-- SVG inyectado por JS -->
      </div>
      <div class="gano-showcase__info">
        <span class="gano-showcase__tag">Estado del sistema</span>
        <h3>Métricas operativas</h3>
        <p>Vúmetros analógicos simulados en SVG. Cada aguja representa un indicador clave: uptime, throughput, IOPS, temperatura.</p>
      </div>
    </article>

  </div>

  <!-- ═══════════════════════════════════════════════════════════════ -->
  <!-- SEPARADOR SVG ANIMADO                                         -->
  <!-- ═══════════════════════════════════════════════════════════════ -->
  <div class="gano-showcase__separator">
    <svg viewBox="0 0 800 120" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <defs>
        <linearGradient id="gs-sep-g" x1="0" x2="1" y1="0" y2="0">
          <stop offset="0" stop-color="#4cd7f6"/>
          <stop offset="1" stop-color="#c0c1ff"/>
        </linearGradient>
      </defs>
      <g>
        <rect x="40" y="44" width="720" height="32" fill="#111a2e" stroke="#1e2d4d" stroke-width="2" rx="4"/>
        <rect x="60" y="50" width="120" height="20" fill="url(#gs-sep-g)">
          <animate attributeName="x" values="60;600;60" dur="6s" repeatCount="indefinite"/>
        </rect>
      </g>
    </svg>
  </div>

  <!-- ═══════════════════════════════════════════════════════════════ -->
  <!-- CTA: Julia + anillo caleidoscópico                            -->
  <!-- ═══════════════════════════════════════════════════════════════ -->
  <section class="gano-showcase__cta">
    <canvas id="gs-cta-julia" class="gano-showcase__cta-canvas" data-gs-effect="julia"></canvas>
    <div class="gano-showcase__cta-content">
      <h2>¿Listo para despegar?</h2>
      <p>Todo lo que ves aquí corre en nuestros servidores. Sin plantillas genéricas. Sin límites de diseño impuestos por plataformas ajenas. Tu marca, tu código, tu soberanía.</p>
      <a href="/contacto/" class="gano-showcase__btn">
        <span>Inicia tu proyecto</span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </div>
  </section>

</main>

<?php
get_footer();
