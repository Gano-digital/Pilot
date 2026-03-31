<?php
$pdo = new PDO('mysql:host=localhost;dbname=gano_staging;charset=utf8mb4','gano_dev','GanoDev2024!Secure');
$p = 'wp_6ce773b45f_';

echo "=== PAGINAS PUBLICADAS ===\n";
$rows = $pdo->query("SELECT ID, post_title, post_status FROM {$p}posts WHERE post_type='page' AND post_status='publish' ORDER BY menu_order, post_title")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  [{$r['ID']}] {$r['post_title']}\n";

echo "\n=== LOREM IPSUM (paginas con placeholder text) ===\n";
$rows = $pdo->query("SELECT ID, post_title FROM {$p}posts WHERE post_type='page' AND post_status='publish' AND post_content LIKE '%Lorem ipsum%'")->fetchAll(PDO::FETCH_ASSOC);
echo count($rows) ? implode("\n", array_map(fn($r)=>"  [{$r['ID']}] {$r['post_title']}", $rows)) : "  Ninguna - OK";
echo "\n";

echo "\n=== MENU ITEMS (nombre -> URL) ===\n";
$rows = $pdo->query("SELECT m.post_title, pm.meta_value as url, pm2.meta_value as obj_id FROM {$p}posts m LEFT JOIN {$p}postmeta pm ON pm.post_id=m.ID AND pm.meta_key='_menu_item_url' LEFT JOIN {$p}postmeta pm2 ON pm2.post_id=m.ID AND pm2.meta_key='_menu_item_object_id' WHERE m.post_type='nav_menu_item' AND m.post_status='publish' ORDER BY m.menu_order")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  {$r['post_title']} -> url:{$r['url']} | page_id:{$r['obj_id']}\n";

echo "\n=== BLOG POSTS ===\n";
$rows = $pdo->query("SELECT ID, post_title FROM {$p}posts WHERE post_type='post' AND post_status='publish' ORDER BY post_date DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  [{$r['ID']}] {$r['post_title']}\n";

echo "\n=== PRODUCTOS WOOCOMMERCE ===\n";
$rows = $pdo->query("SELECT ID, post_title FROM {$p}posts WHERE post_type='product' AND post_status='publish' ORDER BY menu_order, post_title LIMIT 25")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  [{$r['ID']}] {$r['post_title']}\n";

echo "\n=== 20 PAGES SOTA ===\n";
$rows = $pdo->query("SELECT ID, post_title, post_status FROM {$p}posts WHERE post_type='page' AND ID >= 1636 ORDER BY ID")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  [{$r['ID']}] {$r['post_title']} ({$r['post_status']})\n";

echo "\n=== PAGES CORTAS O VACIAS ===\n";
$rows = $pdo->query("SELECT ID, post_title, LENGTH(post_content) as len FROM {$p}posts WHERE post_type='page' AND post_status='publish' AND LENGTH(post_content) < 300 ORDER BY len")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  [{$r['ID']}] {$r['post_title']} ({$r['len']} chars)\n";

echo "\n=== PAGES DUPLICADAS (mismo titulo) ===\n";
$rows = $pdo->query("SELECT post_title, COUNT(*) as n FROM {$p}posts WHERE post_type='page' AND post_status='publish' GROUP BY post_title HAVING n > 1 ORDER BY n DESC")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r) echo "  '{$r['post_title']}' aparece {$r['n']} veces\n";
if(!count($rows)) echo "  Ninguna - OK\n";
