<?php
$pdo = new PDO('mysql:host=localhost;dbname=gano_staging;charset=utf8mb4','gano_dev','GanoDev2024!Secure');
$p = 'wp_6ce773b45f_';

echo "=== LIMPIEZA DE CONTENIDO - GANO STAGING ===\n\n";

// ─── 1. ELIMINAR PÁGINAS DUPLICADAS Y BASURA ───
echo "--- 1. Páginas a eliminar ---\n";

$delete_pages = [
    146,   // "Pages" - placeholder con Lorem ipsum
    1629,  // "Ecosistemas" duplicado (vacío)
    1630,  // "Ecosistemas" duplicado (vacío)
    1215,  // "My Account" duplicado (ya existe 125)
    3,     // "Privacy Policy" draft duplicado (existe 1588)
    1595,  // revision de Propuesta Comercial
    1597,  // customize_changeset trash
    1598,  // custom_css royal-elementor-kit (revisar si es necesario)
    1599,  // revision royal-elementor-kit
    1600,  // revision Hosting V1 Header
    1618,  // customize_changeset trash
    1619,  // revision royal-elementor-kit
    1620,  // customize_changeset trash
    1621,  // revision royal-elementor-kit
    1623,  // customize_changeset trash
    1624,  // revision royal-elementor-kit
];

foreach($delete_pages as $id) {
    $title = $pdo->query("SELECT post_title, post_type FROM {$p}posts WHERE ID=$id")->fetchColumn();
    $pdo->exec("DELETE FROM {$p}postmeta WHERE post_id=$id");
    $pdo->exec("DELETE FROM {$p}posts WHERE ID=$id");
    echo "  [DELETED] ID:$id - $title\n";
}

// ─── 2. ELIMINAR BLOG POSTS PLACEHOLDER ───
echo "\n--- 2. Blog posts placeholder ---\n";
$fake_posts = [140, 141, 142, 143]; // Blog 1, Blog 2, Blog 3, Blog 4
foreach($fake_posts as $id) {
    $title = $pdo->query("SELECT post_title FROM {$p}posts WHERE ID=$id")->fetchColumn();
    $pdo->exec("DELETE FROM {$p}postmeta WHERE post_id=$id");
    $pdo->exec("DELETE FROM {$p}posts WHERE ID=$id");
    echo "  [DELETED] ID:$id - $title\n";
}

// ─── 3. ELIMINAR PRODUCTOS GENÉRICOS/DUPLICADOS ───
echo "\n--- 3. Productos genéricos ---\n";

// Productos con nombres genéricos (Product1-4)
$generic = $pdo->query("SELECT ID, post_title FROM {$p}posts WHERE post_type='product' AND post_title IN ('Product1','Product2','Product3','Product4')")->fetchAll(PDO::FETCH_ASSOC);
foreach($generic as $r) {
    $pdo->exec("DELETE FROM {$p}postmeta WHERE post_id={$r['ID']}");
    $pdo->exec("DELETE FROM {$p}posts WHERE ID={$r['ID']}");
    echo "  [DELETED] ID:{$r['ID']} - {$r['post_title']}\n";
}

// Productos duplicados: hay múltiples "Business", "Premium", "Single" de distintas variaciones
// Mantener solo los más recientes (IDs más altos = más nuevos)
$dup_names = ['Business', 'Premium', 'Single'];
foreach($dup_names as $name) {
    $rows = $pdo->query("SELECT ID FROM {$p}posts WHERE post_type='product' AND post_title='$name' ORDER BY ID DESC")->fetchAll(PDO::FETCH_ASSOC);
    // Keep first (highest ID), delete the rest
    $keep = array_shift($rows);
    foreach($rows as $r) {
        $pdo->exec("DELETE FROM {$p}postmeta WHERE post_id={$r['ID']}");
        $pdo->exec("DELETE FROM {$p}posts WHERE ID={$r['ID']}");
        echo "  [DELETED] ID:{$r['ID']} - $name (duplicado, se mantiene ID:{$keep['ID']})\n";
    }
}

// ─── 4. PUBLICAR LAS 20 PÁGINAS SOTA ───
echo "\n--- 4. Publicando 20 páginas SOTA ---\n";
$sota_ids = [1636,1638,1639,1640,1641,1642,1644,1645,1646,1647,1648,1649,1650,1651,1652,1653,1654,1655];
foreach($sota_ids as $id) {
    $title = $pdo->query("SELECT post_title FROM {$p}posts WHERE ID=$id")->fetchColumn();
    if($title) {
        $pdo->exec("UPDATE {$p}posts SET post_status='publish' WHERE ID=$id");
        echo "  [PUBLISHED] ID:$id - $title\n";
    }
}

// ─── 5. LIMPIAR POSTMETA HUÉRFANO ───
echo "\n--- 5. Limpiando postmeta huérfano ---\n";
$deleted = $pdo->exec("DELETE FROM {$p}postmeta WHERE post_id NOT IN (SELECT ID FROM {$p}posts)");
echo "  [DELETED] $deleted registros huérfanos en postmeta\n";

// ─── 6. LIMPIAR TERM_RELATIONSHIPS HUÉRFANAS ───
$deleted2 = $pdo->exec("DELETE FROM {$p}term_relationships WHERE object_id NOT IN (SELECT ID FROM {$p}posts)");
echo "  [DELETED] $deleted2 term_relationships huérfanas\n";

// ─── RESUMEN FINAL ───
echo "\n=== RESUMEN FINAL ===\n";
$pages = $pdo->query("SELECT COUNT(*) FROM {$p}posts WHERE post_type='page' AND post_status='publish'")->fetchColumn();
$posts = $pdo->query("SELECT COUNT(*) FROM {$p}posts WHERE post_type='post' AND post_status='publish'")->fetchColumn();
$products = $pdo->query("SELECT COUNT(*) FROM {$p}posts WHERE post_type='product' AND post_status='publish'")->fetchColumn();
echo "  Páginas publicadas: $pages\n";
echo "  Blog posts: $posts\n";
echo "  Productos: $products\n";

echo "\n=== PÁGINAS PUBLICADAS AHORA ===\n";
$rows = $pdo->query("SELECT ID, post_title FROM {$p}posts WHERE post_type='page' AND post_status='publish' ORDER BY menu_order, post_title")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  [{$r['ID']}] {$r['post_title']}\n";

echo "\n=== PRODUCTOS RESTANTES ===\n";
$rows = $pdo->query("SELECT ID, post_title FROM {$p}posts WHERE post_type='product' AND post_status='publish' ORDER BY post_title")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  [{$r['ID']}] {$r['post_title']}\n";
