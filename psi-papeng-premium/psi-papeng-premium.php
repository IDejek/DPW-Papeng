<?php
/**
 * Plugin Name: PSI Papeng Premium
 * Plugin URI: https://psipapeng.id
 * Description: Plugin premium untuk DPW PSI Papua Pegunungan - Manajemen Anggota, Pengaturan Tema, SEO, Keamanan, dan WhatsApp Integration.
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

// Plugin Constants
define( 'PSI_PAPENG_VER', '1.0.0' );
define( 'PSI_PAPENG_DIR', plugin_dir_path( __FILE__ ) );
define( 'PSI_PAPENG_URI', plugin_dir_url( __FILE__ ) );
define( 'PSI_PAPENG_BASENAME', plugin_basename( __FILE__ ) );

// Require files
require_once PSI_PAPENG_DIR . 'includes/class-settings.php';
require_once PSI_PAPENG_DIR . 'includes/class-members.php';
require_once PSI_PAPENG_DIR . 'includes/class-security.php';
require_once PSI_PAPENG_DIR . 'includes/class-seo.php';

/**
 * Initialize plugin
 */
function psi_papeng_init() {
    // Load text domain
    load_plugin_textdomain( 'psi-papeng-premium', false, dirname( PSI_PAPENG_BASENAME ) . '/languages' );

    // Initialize classes
    if ( class_exists( 'PSI_Papeng_Settings' ) ) {
        new PSI_Papeng_Settings();
    }
    if ( class_exists( 'PSI_Papeng_Members' ) ) {
        new PSI_Papeng_Members();
    }
    if ( class_exists( 'PSI_Papeng_Security' ) ) {
        new PSI_Papeng_Security();
    }
    if ( class_exists( 'PSI_Papeng_SEO' ) ) {
        new PSI_Papeng_SEO();
    }
}
add_action( 'plugins_loaded', 'psi_papeng_init' );

/**
 * Activation hook - Create custom tables
 */
function psi_papeng_activate() {
    global $wpdb;

    $table_members = $wpdb->prefix . 'psi_members';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_members (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        full_name varchar(150) NOT NULL,
        nik varchar(20) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(20) NOT NULL,
        kabupaten varchar(100) NOT NULL,
        kecamatan varchar(100) DEFAULT '',
        kelurahan varchar(100) DEFAULT '',
        address text DEFAULT '',
        birth_date date DEFAULT NULL,
        gender varchar(10) DEFAULT '',
        occupation varchar(100) DEFAULT '',
        photo varchar(255) DEFAULT '',
        status varchar(20) DEFAULT 'pending',
        registered_at datetime DEFAULT CURRENT_TIMESTAMP,
        verified_at datetime DEFAULT NULL,
        notes text DEFAULT '',
        PRIMARY KEY  (id),
        UNIQUE KEY email (email),
        KEY kabupaten (kabupaten),
        KEY status (status)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );

    // Set default options
    $defaults = array(
        'dpw_psi_social'   => array(
            'facebook'  => 'https://www.facebook.com/psipapeng',
            'instagram' => 'https://www.instagram.com/psipapeng',
            'youtube'   => 'https://www.youtube.com/@psipapeng',
            'tiktok'    => '',
        ),
        'dpw_psi_contact'  => array(
            'address'   => 'Papua Pegunungan, Indonesia',
            'email'     => 'info@psipapeng.id',
            'whatsapp'  => '+62 822 6721 8125',
            'map_url'   => '',
        ),
        'dpw_psi_slides'   => array(),
        'dpw_psi_welcome'  => array(
            'image' => '',
            'title' => 'Sambutan Ketua DPW',
            'text'  => 'Selamat datang di website resmi Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan.',
            'name'  => 'Yotam Wonda, S.H., M.Si',
            'role'  => 'Ketua DPW PSI Papua Pegunungan',
        ),
        'dpw_psi_leadership' => array(),
        'dpw_psi_divisions'  => array(),
        'dpw_psi_seo'     => array(
            'enable_sitemap' => 'yes',
            'robots_txt'     => "User-agent: *\nAllow: /\nDisallow: /wp-admin/\nDisallow: /wp-includes/\nSitemap: " . home_url( '/sitemap.xml' ),
        ),
    );

    foreach ( $defaults as $key => $value ) {
        if ( false === get_option( $key ) ) {
            add_option( $key, $value );
        }
    }

    // Flush rewrite rules for CPTs
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'psi_papeng_activate' );

/**
 * Deactivation hook
 */
function psi_papeng_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'psi_papeng_deactivate' );
