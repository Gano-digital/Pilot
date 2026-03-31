<?php
/**
 * Template Name: Gano Premium Shop
 */

get_header(); ?>

<div class="gano-shop-header" style="text-align:center; padding: 100px 20px; background: #0f1115;">
    <h1 style="font-size: 4rem; font-weight: 900; color: #D4AF37; margin-bottom: 10px;">Ecosistemas Digitales</h1>
    <p style="color: #888; font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Infraestructura blindada de alto rendimiento para soberanía digital absoluta.</p>
    
    <div class="gano-price-toggle" style="margin-top: 50px;">
        <span>Mensual</span>
        <label class="toggle-switch">
            <input type="checkbox" id="billing-toggle">
            <span class="slider"></span>
        </label>
        <span>Anual (Ahorro 20%)</span>
    </div>
</div>

<div class="gano-shop-container">
    <!-- Ecosystem Discovery Tool (Quiz) -->
    <div class="gano-quiz-container">
        <div id="gano-quiz-ui">
            <h2 class="gano-quiz-title">Configurador de Ecosistema Gano</h2>
            <p style="color: #888; margin-bottom: 40px;">Diseñemos la infraestructura ideal para tu visión digital.</p>

            <!-- Step 1: Expertise -->
            <div class="gano-quiz-step active" data-name="expertise">
                <p class="gano-quiz-question">¿Cuál es tu nivel de gestión técnica deseado?</p>
                <div class="gano-quiz-options">
                    <div class="gano-quiz-option" data-value="managed">
                        <i class="fas fa-magic"></i>
                        <span>Experiencia Gestionada</span>
                        <p>Nosotros nos encargamos del setup y tú solo de crecer.</p>
                    </div>
                    <div class="gano-quiz-option" data-value="expert">
                        <i class="fas fa-terminal"></i>
                        <span>Modo Experto</span>
                        <p>Tú controlas cada aspecto; acceso root y configuración manual.</p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Scale -->
            <div class="gano-quiz-step" data-name="scale">
                <p class="gano-quiz-question">¿Cuál es la escala actual de tu proyecto?</p>
                <div class="gano-quiz-options">
                    <div class="gano-quiz-option" data-value="starter">
                        <i class="fas fa-rocket"></i>
                        <span>Lanzamiento</span>
                        <p>Nueva marca o landing page de alto impacto.</p>
                    </div>
                    <div class="gano-quiz-option" data-value="growing">
                        <i class="fas fa-chart-line"></i>
                        <span>En Crecimiento</span>
                        <p>PyME con tráfico constante y necesidades de seguridad.</p>
                    </div>
                    <div class="gano-quiz-option" data-value="enterprise">
                        <i class="fas fa-building"></i>
                        <span>Enterprise</span>
                        <p>E-commerce masivo o infraestructura crítica corporativa.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Result Area -->
        <div id="gano-quiz-result" style="display: none;">
            <div class="gano-result-card">
                <div class="result-info">
                    <h3 id="result-title" style="color: var(--gano-gold); margin-bottom: 10px;">Recomendación</h3>
                    <h4 id="result-bundle" style="color: #fff; font-size: 1.8rem; margin-bottom: 20px;">Bundle Name</h4>
                    <p id="result-desc" style="color: #ccc; line-height: 1.6;">Descripción personalizada basada en tus necesidades.</p>
                    <a href="#" id="result-buy-link" class="gano-buy-button" style="display: inline-block; margin-top: 30px;">Activar Ecosistema</a>
                </div>
                <div class="result-price">
                    <span id="result-price-val">$ 0</span>
                </div>
            </div>
            <button onclick="location.reload()" style="background:none; border:none; color:#555; cursor:pointer; margin-top:30px; text-decoration:underline;">Reiniciar Configurador</button>
        </div>
    </div>

    <!-- 3-Year Business Bundles Section -->
    <!-- Standard Technical Catalog -->
    <h2 id="standard-catalog" style="grid-column: 1 / -1; text-align: center; color: #555; font-size: 2rem; margin: 80px 0 40px; text-transform: uppercase; letter-spacing: 2px;">Catálogo Técnico SOTA</h2>
    
    <?php
    $products = array(
        (object)[
            'sku'   => 'GD-STARTUP-01',
            'name'  => 'Startup Blueprint',
            'price' => 1910000, // 3 Years (Discounted)
            'setup' => 796000,
            'badge' => '3 Años Incluidos',
            'specs' => ['Storage' => '10GB NVMe', 'Security' => 'Blindaje Base', 'Cert' => 'SSL Gano'],
            'url'   => '?gano_add_bundle=GD-STARTUP-01'
        ],
        (object)[
            'sku'   => 'GD-BASIC-01',
            'name'  => 'Ecosistema Básico',
            'price' => 9600000, // 3 Years
            'setup' => 4000000,
            'badge' => 'Most Popular (3yr)',
            'specs' => ['Storage' => '30GB NVMe', 'Optimization' => 'Premium', 'Security' => 'Zero-Trust'],
            'url'   => '?gano_add_bundle=GD-BASIC-01'
        ],
        (object)[
            'sku'   => 'GD-ADVANCED-01',
            'name'  => 'Ecosistema Avanzado',
            'price' => 24000000, // 3 Years
            'setup' => 10000000,
            'badge' => 'High Performance (3yr)',
            'specs' => ['Storage' => '100GB NVMe', 'Elasticity' => 'High', 'Security' => 'Anti-DDoS'],
            'url'   => '?gano_add_bundle=GD-ADVANCED-01'
        ],
        (object)[
            'sku'   => 'GD-SOBERANIA-01',
            'name'  => 'Soberanía Digital',
            'price' => 48000000, // 3 Years
            'setup' => 20000000,
            'badge' => 'Maximum Security (3yr)',
            'specs' => ['Storage' => '500GB NVMe', 'Sovereignty' => 'Absolute', 'Encryption' => 'Quantum Safe'],
            'url'   => '?gano_add_bundle=GD-SOBERANIA-01'
        ]
    );

    foreach ($products as $p): ?>
        <div class="gano-product-card">
            <?php if ($p->badge): ?>
                <div class="gano-product-badge"><?php echo $p->badge; ?></div>
            <?php endif; ?>
            
            <h2 class="gano-product-title"><?php echo $p->name; ?></h2>
            
            <div class="gano-product-price">
                $ <span class="gano-price-value" data-setup="<?php echo $p->setup; ?>"><?php echo number_format($p->price, 0, ',', '.'); ?></span>
                <span>COP / SETUP</span>
            </div>

            <ul class="gano-product-specs">
                <?php foreach ($p->specs as $label => $val): ?>
                    <li><?php echo $label; ?> <span><?php echo $val; ?></span></li>
                <?php endforeach; ?>
            </ul>

            <a href="<?php echo $p->url; ?>" class="gano-buy-button">Adquirir Ecosistema</a>
        </div>
    <?php endforeach; ?>
</div>

<div class="gano-bundle-section" style="padding: 100px 20px; background: #0a0c10; text-align: center;">
    <h2 style="color: var(--gano-gold); font-size: 2.5rem; margin-bottom: 50px;">Configurador de Ecosistema</h2>
    
    <div class="gano-bundle-builder" style="display: flex; gap: 40px; justify-content: center; flex-wrap: wrap; max-width: 1200px; margin: 0 auto; text-align: left;">
        <div class="builder-options" style="flex: 1; min-width: 300px; background: rgba(255,255,255,0.02); padding: 40px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05);">
            <h3 style="color: #fff; margin-bottom: 30px;">Selecciona Aditamentos</h3>
            
            <label style="display: block; margin-bottom: 20px; cursor: pointer;">
                <input type="checkbox" class="bundle-addon" data-price="450000" checked> 
                <span style="color: #fff; margin-left: 10px;">Cifrado Post-Cuántico (+ $450k)</span>
            </label>
            
            <label style="display: block; margin-bottom: 20px; cursor: pointer;">
                <input type="checkbox" class="bundle-addon" data-price="890000"> 
                <span style="color: #fff; margin-left: 10px;">Agente IA Nivel 3 (Acciones Autónomas) (+ $890k)</span>
            </label>
            
            <label style="display: block; margin-bottom: 20px; cursor: pointer;">
                <input type="checkbox" class="bundle-addon" data-price="1200000"> 
                <span style="color: #fff; margin-left: 10px;">Soporte White-Glove 24/7 (+ $1.2M)</span>
            </label>
        </div>
        
        <div class="builder-result" style="flex: 1; min-width: 300px; background: var(--gano-gold-soft); padding: 40px; border-radius: 20px; border: 1px solid var(--gano-gold); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h3 style="color: var(--gano-gold);">Ecosistema Personalizado</h3>
                <p style="color: #888;">Precio Final (Setup + Bundles)</p>
                <div style="font-size: 3rem; font-weight: 900; color: #fff; margin: 20px 0;">
                    $ <span id="bundle-total">1.246.000</span> COP
                </div>
            </div>
            <a href="#" class="gano-buy-button" style="margin-top: 0;">Configurar e Iniciar</a>
        </div>
    </div>

    <!-- Comparison Table -->
    <div class="gano-comparison" style="margin-top: 150px; text-align: left; max-width: 1000px; margin-left: auto; margin-right: auto;">
        <h2 style="color: #fff; text-align: center; margin-bottom: 50px;">Gano Ecosistemas vs Hosting Tradicional</h2>
        <table class="gano-table">
            <thead>
                <tr>
                    <th>Característica</th>
                    <th>Hosting Tradicional</th>
                    <th>Gano Digital SOTA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Infraestructura</td>
                    <td>SSD Estándar (Shared)</td>
                    <td><span class="gano-text-green">NVMe Gen4 Enterprise Dedicado</span></td>
                </tr>
                <tr>
                    <td>Seguridad</td>
                    <td>Plugins Pasivos</td>
                    <td><span class="gano-text-green">Blindaje Gano + WAF Capa 7</span></td>
                </tr>
                <tr>
                    <td>Gestión</td>
                    <td>Soporte Humano (Tickets)</td>
                    <td><span class="gano-text-green">Agente IA Autónomo 24/7</span></td>
                </tr>
                <tr>
                    <td>Soberanía</td>
                    <td>Propiedad del Proveedor</td>
                    <td><span class="gano-text-green">Digital Sovereignty Guaranteed</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addons = document.querySelectorAll('.bundle-addon');
    const totalDisplay = document.getElementById('bundle-total');
    let basePrice = 796000; // Startup Blueprint base
    
    function updateTotal() {
        let addonTotal = 0;
        addons.forEach(addon => {
            if (addon.checked) {
                addonTotal += parseInt(addon.getAttribute('data-price'));
            }
        });
        const final = basePrice + addonTotal;
        totalDisplay.innerText = new Intl.NumberFormat('es-CO').format(final);
    }
    
    addons.forEach(addon => {
        addon.addEventListener('change', updateTotal);
    });
    
    updateTotal();
});
</script>

<?php get_footer(); ?>
