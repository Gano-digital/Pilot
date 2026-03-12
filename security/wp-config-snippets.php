<?php
/**
 * wp-config-snippets.php
 * WordPress security hardening constants for wp-config.php.
 *
 * HOW TO USE:
 *   Do NOT upload this file directly to the server.
 *   Instead, copy the relevant define() lines from each section below
 *   into your live ~/public_html/wp-config.php, BEFORE the line that reads:
 *
 *       /* That's all, stop editing! Happy publishing. */
 *
 * These constants harden the WordPress installation running on this GoDaddy
 * server (WordPress 6.9.1 / PHP 8.3 / gano.digital, IP: 160.153.0.23).
 */

// ── 1. Disable the in-dashboard theme and plugin file editor ─────────────────
// WHY: Prevents an attacker who has obtained admin credentials from using the
//      WordPress dashboard to plant PHP backdoors directly in theme/plugin files.
define( 'DISALLOW_FILE_EDIT', true );

// ── 2. Prevent plugin/theme installation via the dashboard ───────────────────
// WHY: Stops an attacker from installing a malicious plugin through the admin UI.
//      Remove (or comment out) this constant if you need to install updates
//      through the dashboard rather than via WP-CLI / rsync.
define( 'DISALLOW_FILE_MODS', true );

// ── 3. Disable the built-in PHP error display in production ──────────────────
// WHY: PHP error messages can reveal directory paths, database names, and code
//      structure — valuable reconnaissance data for attackers.
define( 'WP_DEBUG',         false );
define( 'WP_DEBUG_LOG',     false );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// ── 4. Force WordPress to use HTTPS for admin and logins ─────────────────────
// WHY: Ensures authentication cookies are only sent over encrypted connections.
//      Safe to enable because CDN/SSL is confirmed active on this account.
define( 'FORCE_SSL_ADMIN', true );

// ── 5. Shorten the authentication cookie lifetime ────────────────────────────
// WHY: Reduces the window of opportunity for stolen cookie attacks.
//      Default is 14 days (logged in) / 2 days (not "remember me").
// HOW: WordPress has no built-in constant for this — add the filter below
//      to your theme's functions.php or a site-specific functionality plugin
//      (do NOT place it directly in wp-config.php where hooks are unavailable):
//
//   add_filter( 'auth_cookie_expiration', function( $expiration, $user_id, $remember ) {
//       return $remember ? 8 * HOUR_IN_SECONDS : 2 * HOUR_IN_SECONDS;
//   }, 10, 3 );

// ── 6. Disable the WordPress cron simulation via HTTP requests ───────────────
// WHY: The default WP-Cron fires on every page load, adding overhead and a
//      potential DoS vector. Replace with a real server cron job:
//        * * * * * php ~/public_html/wp-cron.php > /dev/null 2>&1
define( 'DISABLE_WP_CRON', true );

// ── 7. Limit post revisions stored in the database ───────────────────────────
// WHY: Unbounded revisions bloat the database, slowing queries and backup sizes.
define( 'WP_POST_REVISIONS', 5 );

// ── 8. Move wp-content outside the default location (optional, advanced) ─────
// WHY: Obscures the standard wp-content path, making automated scanners miss it.
// CAUTION: Only apply to fresh installations; migrating an existing site
//          requires updating all media URLs in the database.
// define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
// define( 'WP_CONTENT_URL', 'https://gano.digital/content' );

// ── 9. Security keys and salts ───────────────────────────────────────────────
// WHY: Unique, random keys invalidate all existing sessions when changed and
//      harden cookie security.  Generate fresh values at:
//        https://api.wordpress.org/secret-key/1.1/salt/
// REMINDER: Rotate these keys every 90 days or immediately after a suspected breach.
//
// (Paste the generated block here, replacing the placeholder lines below)
// define( 'AUTH_KEY',         'put your unique phrase here' );
// define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
// define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
// define( 'NONCE_KEY',        'put your unique phrase here' );
// define( 'AUTH_SALT',        'put your unique phrase here' );
// define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
// define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
// define( 'NONCE_SALT',       'put your unique phrase here' );
