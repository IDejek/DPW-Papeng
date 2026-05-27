<?php
/**
 * Plugin Name: PSI Papeng Premium
 * Plugin URI: https://psipapeng.id
 * Description: Plugin premium untuk DPW PSI Papua Pegunungan.
 * Version: 1.0.0
 * Author: Iqbal Tombinawa
 * Author URI: https://psipapeng.id
 * License: GPL v2 or later
 * Text Domain: psi-papeng-premium
 * Domain Path: /languages
 * Requires PHP: 8.0
 */

defined( 'ABSPATH' ) || exit;

define( 'PSI_PAPENG_VERSION', '1.0.0' );
define( 'PSI_PAPENG_FILE', __FILE__ );
define( 'PSI_PAPENG_DIR', plugin_dir_path( __FILE__ ) );
define( 'PSI_PAPENG_URI', plugin_dir_url( __FILE__ ) );
define( 'PSI_PAPENG_BASENAME', plugin_basename( __FILE__ ) );

// Aktifkan dulu, baru load semuanya
register_activation_hook( __FILE__, function() {
    require_once PSI_PAPENG_DIR . 'includes/class-member-management.php';
    if ( class_exists( 'PSI_Papeng_Member_Management' ) ) {
        PSI_Papeng_Member_Management::create_tables();
    }
    require_once PSI_PAPENG_DIR . 'includes/class-activity-log.php';
    if ( class_exists( 'PSI_Papeng_Activity_Log' ) ) {
        PSI_Papeng_Activity_Log::create_table();
    }
    flush_rewrite_rules();
});

register_deactivation_hook( __FILE__, function() {
    flush_rewrite_rules();
});

// Boot hanya jika fully loaded
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'psi-papeng-premium', false, dirname( PSI_PAPENG_BASENAME ) . '/languages' );
});

add_action( 'init', function() {
    $files = array(
        'includes/class-member-management.php',
        'includes/class-member-dashboard.php',
        'includes/class-whatsapp.php',
        'includes/class-email.php',
        'includes/class-seo.php',
        'includes/class-performance.php',
        'includes/class-activity-log.php',
        'includes/class-admin-panel.php',
        'includes/class-member-statistics.php',
        'includes/class-bootstrap-hooks.php',
    );

    foreach ( $files as $file ) {
        $path = PSI_PAPENG_DIR . $file;
        if ( file_exists( $path ) ) {
            require_once $path;
        }
    }

    // Instansiasi class satu per satu, dengan cek keberadaan
    if ( is_admin() ) {
        if ( class_exists( 'PSI_Papeng_Admin_Panel' ) ) new PSI_Papeng_Admin_Panel();
        if ( class_exists( 'PSI_Papeng_Member_Statistics' ) ) new PSI_Papeng_Member_Statistics();
    }
    if ( class_exists( 'PSI_Papeng_Member_Management' ) ) new PSI_Papeng_Member_Management();
    if ( class_exists( 'PSI_Papeng_Member_Dashboard' ) ) new PSI_Papeng_Member_Dashboard();
    if ( class_exists( 'PSI_Papeng_WhatsApp' ) ) new PSI_Papeng_WhatsApp();
    if ( class_exists( 'PSI_Papeng_Email' ) ) new PSI_Papeng_Email();
    if ( class_exists( 'PSI_Papeng_SEO' ) ) new PSI_Papeng_SEO();
    if ( class_exists( 'PSI_Papeng_Performance' ) ) new PSI_Papeng_Performance();
    if ( class_exists( 'PSI_Papeng_Activity_Log' ) ) new PSI_Papeng_Activity_Log();
    if ( class_exists( 'PSI_Papeng_Bootstrap_Hooks' ) ) new PSI_Papeng_Bootstrap_Hooks();
}, 20 ); // Prioritas 20, setelah semua plugin lain siap
