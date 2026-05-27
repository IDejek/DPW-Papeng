<?php
/**
 * Plugin Name: PSI Papeng Premium
 * Plugin URI: https://psipapeng.id
 * Description: Plugin premium untuk DPW PSI Papua Pegunungan — Manajemen anggota, WhatsApp, email, SEO, dan optimasi performa.
 * Version: 1.0.0
 * Author: Iqbal Tombinawa
 * Author URI: https://psipapeng.id
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: psi-papeng-premium
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

defined( 'ABSPATH' ) || exit;

define( 'PSI_PAPENG_VERSION', '1.0.0' );
define( 'PSI_PAPENG_FILE', __FILE__ );
define( 'PSI_PAPENG_DIR', plugin_dir_path( __FILE__ ) );
define( 'PSI_PAPENG_URI', plugin_dir_url( __FILE__ ) );
define( 'PSI_PAPENG_BASENAME', plugin_basename( __FILE__ ) );

/* ── Activation / Deactivation ──────────────────────────────── */
register_activation_hook( __FILE__, 'psi_papeng_activate' );
function psi_papeng_activate(): void {
    require_once PSI_PAPENG_DIR . 'includes/class-member-management.php';
    PSI_Papeng_Member_Management::create_tables();
    flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'psi_papeng_deactivate' );
function psi_papeng_deactivate(): void {
    flush_rewrite_rules();
}

/* ── Load Includes ──────────────────────────────────────────── */
require_once PSI_PAPENG_DIR . 'includes/class-member-management.php';
require_once PSI_PAPENG_DIR . 'includes/class-member-dashboard.php';
require_once PSI_PAPENG_DIR . 'includes/class-whatsapp.php';
require_once PSI_PAPENG_DIR . 'includes/class-email.php';
require_once PSI_PAPENG_DIR . 'includes/class-seo.php';
require_once PSI_PAPENG_DIR . 'includes/class-performance.php';
require_once PSI_PAPENG_DIR . 'includes/class-activity-log.php';
require_once PSI_PAPENG_DIR . 'includes/class-admin-panel.php';
require_once PSI_PAPENG_DIR . 'includes/class-member-statistics.php';

/* ── Initialize ─────────────────────────────────────────────── */
add_action( 'plugins_loaded', 'psi_papeng_init' );
function psi_papeng_init(): void {
    load_plugin_textdomain( 'psi-papeng-premium', false, dirname( PSI_PAPENG_BASENAME ) . '/languages' );
}

add_action( 'init', 'psi_papeng_boot' );
function psi_papeng_boot(): void {
    if ( is_admin() ) {
        new PSI_Papeng_Admin_Panel();
        new PSI_Papeng_Member_Statistics();
    }
    new PSI_Papeng_Member_Management();
    new PSI_Papeng_Member_Dashboard();
    new PSI_Papeng_WhatsApp();
    new PSI_Papeng_Email();
    new PSI_Papeng_SEO();
    new PSI_Papeng_Performance();
    new PSI_Papeng_Activity_Log();
}

/* ── Plugin Row Meta ────────────────────────────────────────── */
add_filter( 'plugin_row_meta', 'psi_papeng_plugin_row_meta', 10, 2 );
function psi_papeng_plugin_row_meta( $links, $file ): array {
    if ( PSI_PAPENG_BASENAME !== $file ) return $links;
    $links[] = '<a href="mailto:tombinawaiqbal@gmail.com">' . esc_html__( 'Dukungan Teknis', 'psi-papeng-premium' ) . '</a>';
    return $links;
}
