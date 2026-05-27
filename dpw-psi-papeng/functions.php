<?php
/**
 * DPW PSI Papua Pegunungan Theme Functions
 * @package DPW_PSIPapeng
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'DPW_PSI_VERSION', '1.0.0' );
define( 'DPW_PSI_DIR', get_template_directory() );
define( 'DPW_PSI_URI', get_template_directory_uri() );

add_action( 'after_setup_theme', 'dpw_psi_theme_setup' );
function dpw_psi_theme_setup() {
    load_theme_textdomain( 'dpw-psi-papeng', DPW_PSI_DIR . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 300, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );

    register_nav_menus( array(
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu',
        'mobile'  => 'Mobile Menu',
    ) );

    add_image_size( 'slider-full', 1920, 800, true );
    add_image_size( 'leader-card', 400, 500, true );
    add_image_size( 'news-card', 600, 400, true );
    add_image_size( 'gallery-masonry', 600, 600, false );
    add_image_size( 'dpd-card', 500, 600, true );
}

add_action( 'wp_enqueue_scripts', 'dpw_psi_enqueue_assets' );
function dpw_psi_enqueue_assets() {
    wp_enqueue_style( 'dpw-psi-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap', array(), null );
    wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );
    wp_enqueue_style( 'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3' );
    wp_enqueue_style( 'dpw-psi-theme', DPW_PSI_URI . '/assets/css/theme.css', array( 'bootstrap', 'bootstrap-icons', 'dpw-psi-fonts' ), DPW_PSI_VERSION );
    wp_enqueue_script( 'bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true );
    wp_enqueue_script( 'dpw-psi-clock', DPW_PSI_URI . '/assets/js/clock.js', array(), DPW_PSI_VERSION, true );
    wp_enqueue_script( 'dpw-psi-theme', DPW_PSI_URI . '/assets/js/theme.js', array( 'bootstrap-bundle', 'dpw-psi-clock' ), DPW_PSI_VERSION, true );
    wp_localize_script( 'dpw-psi-theme', 'dpwPsi', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'dpw_psi_nonce' ), 'homeUrl' => home_url( '/' ) ) );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}

add_action( 'admin_enqueue_scripts', 'dpw_psi_admin_assets' );
function dpw_psi_admin_assets( $hook ) {
    if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        wp_enqueue_style( 'dpw-psi-admin-meta', DPW_PSI_URI . '/assets/css/admin-meta.css', array(), DPW_PSI_VERSION );
        wp_enqueue_script( 'dpw-psi-admin-meta', DPW_PSI_URI . '/assets/js/admin-meta.js', array( 'jquery' ), DPW_PSI_VERSION, true );
    }
}

require_once DPW_PSI_DIR . '/inc/helpers.php';
require_once DPW_PSI_DIR . '/inc/post-types.php';
require_once DPW_PSI_DIR . '/inc/meta-boxes.php';
require_once DPW_PSI_DIR . '/inc/customizer.php';
require_once DPW_PSI_DIR . '/inc/shortcodes.php';

add_action( 'widgets_init', 'dpw_psi_widgets_init' );
function dpw_psi_widgets_init() {
    register_sidebar( array( 'name' => 'Sidebar Utama', 'id' => 'sidebar-main', 'before_widget' => '<div id="%1$s" class="widget mb-4 %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title fw-bold mb-3 pb-2 border-bottom border-danger">', 'after_title' => '</h4>' ) );
    register_sidebar( array( 'name' => 'Footer Kolom 1', 'id' => 'footer-1', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h5 class="widget-title fw-bold text-white mb-3">', 'after_title' => '</h5>' ) );
    register_sidebar( array( 'name' => 'Footer Kolom 2', 'id' => 'footer-2', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h5 class="widget-title fw-bold text-white mb-3">', 'after_title' => '</h5>' ) );
    register_sidebar( array( 'name' => 'Footer Kolom 3', 'id' => 'footer-3', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h5 class="widget-title fw-bold text-white mb-3">', 'after_title' => '</h5>' ) );
}

add_filter( 'excerpt_length', function( $length ) { return is_front_page() ? 20 : 40; } );
add_filter( 'excerpt_more', function() { return '...'; } );

add_filter( 'body_class', function( $classes ) {
    if ( is_front_page() ) $classes[] = 'home-page';
    return $classes;
} );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

add_action( 'wp_head', function() {
    if ( ! is_singular( 'post' ) ) return;
    echo '<style>#reading-progress{position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#D6001C,#D4AF37);z-index:99999;transition:width .1s linear;width:0}</style>';
} );

add_action( 'wp_footer', function() {
    if ( ! is_singular( 'post' ) ) return;
    echo '<div id="reading-progress"></div><script>(function(){var b=document.getElementById("reading-progress");if(!b)return;window.addEventListener("scroll",function(){var h=document.documentElement.scrollHeight-window.innerHeight;b.style.width=(h>0?(window.scrollY/h)*100:0)+"%";});})();</script>';
} );

add_action( 'wp_head', function() {
    $logo = has_custom_logo() ? wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) : '';
    $schema = array('@context' => 'https://schema.org', '@type' => 'Organization', 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ), 'logo' => esc_url( $logo ) );
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
} );

function dpw_psi_breadcrumbs() {
    if ( is_front_page() ) return;
    echo '<nav aria-label="breadcrumb" class="dpw-breadcrumb mb-4"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="' . esc_url( home_url( '/' ) ) . '"><i class="bi bi-house-door"></i> Beranda</a></li>';
    if ( is_singular() ) {
        $cats = get_the_category();
        if ( $cats ) echo '<li class="breadcrumb-item"><a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a></li>';
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html( get_the_title() ) . '</li>';
    } elseif ( is_page() || is_post_type_archive() ) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html( get_the_title() ? get_the_title() : post_type_archive_title( '', false ) ) . '</li>';
    }
    echo '</ol></nav>';
}

function dpw_psi_pagination() {
    the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '<i class="bi bi-chevron-left"></i>', 'next_text' => '<i class="bi bi-chevron-right"></i>', 'class' => 'dpw-pagination' ) );
}

add_action( 'wp_body_open', function() {
    echo '<div id="dpw-preloader" aria-hidden="true"><div class="dpw-preloader-inner"><div class="dpw-preloader-spinner"></div><span class="dpw-preloader-text">PSI</span></div></div>';
} );
