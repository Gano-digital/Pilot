<?php
/**
 * Template Name: SOTA Single Template v2
 * Template Post Type: page
 */

get_header(); ?>

<!-- Reading Progress Bar -->
<div id="gano-read-progress" style="position: fixed; top: 0; left: 0; width: 0%; height: 4px; background: var(--gano-gold, #D4AF37); z-index: 9999; transition: width 0.1s;"></div>

<div class="sota-single-container" style="background: #05070a; color: #fff; min-height: 100vh; padding-top: 120px;">
    
    <header class="sota-hero" style="position: relative; padding: 100px 20px; text-align: center; border-bottom: 2px solid rgba(212,175,55,0.2);">
        <div class="sota-hero-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; opacity: 0.3; z-index: 1;">
            <?php the_post_thumbnail('full', ['style' => 'width: 100%; height: 100%; object-fit: cover; filter: brightness(0.5) blur(10px);']); ?>
        </div>
        
        <div class="sota-hero-content" style="position: relative; z-index: 2; max-width: 900px; margin: 0 auto;">
            <div class="sota-meta" style="margin-bottom: 20px;">
                <span style="background: var(--gano-gold, #D4AF37); color: #000; padding: 4px 12px; border-radius: 4px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">SOTA EXPERT</span>
                <span style="margin-left: 15px; font-size: 0.85rem; color: #888;">⏱️ Lectura Técnica: 12 min</span>
            </div>
            <h1 style="font-size: 3.5rem; font-weight: 900; margin-bottom: 20px; line-height: 1.1;"><?php the_title(); ?></h1>
            <p style="font-size: 1.2rem; color: #888;">Ingeniería de vanguardia aplicada a la soberanía digital del siglo XXI.</p>
        </div>
    </header>

    <div class="sota-layout" style="display: flex; gap: 50px; max-width: 1200px; margin: 60px auto; padding: 0 20px;">
        <aside style="flex: 0 0 280px; position: sticky; top: 150px; height: fit-content;">
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 30px; border-radius: 12px;">
                <h4 style="color: #D4AF37; margin-bottom: 15px; font-size: 1rem;">NAVEGACIÓN SOTA</h4>
                <ul style="list-style: none; font-size: 0.9rem; line-height: 2.5; padding: 0; margin: 0;">
                    <li><a href="#intro" style="color: #fff; text-decoration: none;">1. Infraestructura Base</a></li>
                    <li><a href="#tech" style="color: #888; text-decoration: none;">2. Capas de Seguridad</a></li>
                    <li><a href="#impact" style="color: #888; text-decoration: none;">3. ROI & Eficiencia</a></li>
                </ul>
                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">
                <div id="gano-quiz-engine-sidebar">
                    <p style="font-size: 0.8rem; color: #888;">¿Qué tan soberano eres?</p>
                    <a href="#sovereignty-quiz" style="color: #D4AF37; text-decoration: none; font-weight: 700; font-size: 0.85rem;">TOMAR TEST →</a>
                </div>
            </div>
        </aside>

        <article class="sota-post-content" style="flex: 1; font-size: 1.15rem; line-height: 1.8; color: #cbd5e1;">
            <div id="intro">
                <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
            </div>

            <!-- Quiz Engagement Tool -->
            <div id="sovereignty-quiz" style="margin-top: 80px;">
                <div id="gano-quiz-container">
                    <div id="gano-quiz-header"><h2>Digital Sovereignty Quiz</h2></div>
                    <div id="gano-quiz-engine">Cargando motor de test...</div>
                </div>
            </div>

            <div class="sota-upsell-box" style="margin-top: 100px; background: #111; border: 1px solid var(--gano-gold, #D4AF37); padding: 50px; border-radius: 12px; text-align: center;">
                <h3 style="color: #fff; font-size: 2rem;">¿Listo para migrar a SOTA?</h3>
                <p style="color: #888; margin-bottom: 30px;">Activa tu infraestructura NVMe hoy mismo con nuestros ecosistemas.</p>
                <div style="display: flex; gap: 20px; justify-content: center;">
                    <a href="<?php echo home_url('/ecosistemas/'); ?>" class="gano-buy-button" style="margin: 0; border-radius: 4px;">Planes y Precios</a>
                </div>
            </div>
        </article>
    </div>
</div>

<script>
window.onscroll = function() {
    let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    let scrolled = (winScroll / height) * 100;
    document.getElementById("gano-read-progress").style.width = scrolled + "%";
};
</script>

<?php get_footer(); ?>
