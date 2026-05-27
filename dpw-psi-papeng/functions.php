<?php
/**
 * DPW PSI Papua Pegunungan Theme Functions
 *
 * @package DPW_PSI_Papeng
 * @version 1.0.0
 * @author Iqbal Tombinawa <tombinawaiqbal@gmail.com>
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Theme Constants
define( 'DPW_PSI_VERSION', '1.0.0' );
define( 'DPW_PSI_DIR', get_template_directory() );
define( 'DPW_PSI_URI', get_template_directory_uri() );

/**
 * Theme Setup
 */
function dpw_psi_setup() {
    // Load text domain
    load_theme_textdomain( 'dpw-psi-papeng', DPW_PSI_DIR . '/languages' );

    // Title tag support
    add_theme_support( 'title-tag' );

    // Post thumbnails
    add_theme_support( 'post-thumbnails' );

    // Custom image sizes
    add_image_size( 'dpw-hero', 1920, 1080, true );
    add_image_size( 'dpw-leader', 600, 800, true );
    add_image_size( 'dpw-news', 800, 500, true );
    add_image_size( 'dpw-news-thumb', 400, 250, true );
    add_image_size( 'dpw-gallery', 600, 600, true );
    add_image_size( 'dpw-dpd', 400, 400, true );

    // HTML5 support
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Register nav menus
    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu', 'dpw-psi-papeng' ),
        'footer'    => esc_html__( 'Footer Menu', 'dpw-psi-papeng' ),
    ) );

    // Custom background
    add_theme_support( 'custom-background', array(
        'default-color' => 'ffffff',
    ) );

    // Widget support
    add_theme_support( 'widgets' );
}
add_action( 'after_setup_theme', 'dpw_psi_setup' );

/**
 * Enqueue Styles and Scripts
 */
function dpw_psi_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'dpw-psi-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Bootstrap Icons
    wp_enqueue_style(
        'bootstrap-icons',
        DPW_PSI_URI . '/assets/css/bootstrap-icons.min.css',
        array(),
        '1.11.3'
    );

    // Theme stylesheet
    wp_enqueue_style(
        'dpw-psi-style',
        get_stylesheet_uri(),
        array( 'bootstrap-icons' ),
        DPW_PSI_VERSION
    );

    // Main JS
    wp_enqueue_script(
        'dpw-psi-main',
        DPW_PSI_URI . '/assets/js/main.js',
        array(),
        DPW_PSI_VERSION,
        true
    );

    // Localize script
    wp_localize_script( 'dpw-psi-main', 'dpwPsi', array(
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'dpw_psi_nonce' ),
        'themeUri' => DPW_PSI_URI,
    ) );

    // Comments reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'dpw_psi_scripts' );

/**
 * Register Widget Areas
 */
function dpw_psi_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'dpw-psi-papeng' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'dpw-psi-papeng' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Article Sidebar', 'dpw-psi-papeng' ),
        'id'            => 'article-sidebar',
        'description'   => esc_html__( 'Widgets for single article pages.', 'dpw-psi-papeng' ),
        'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Column 1', 'dpw-psi-papeng' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Footer widget area 1.', 'dpw-psi-papeng' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-heading">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Column 2', 'dpw-psi-papeng' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Footer widget area 2.', 'dpw-psi-papeng' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-heading">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'dpw_psi_widgets_init' );

/**
 * Custom Nav Walker for Bootstrap-style dropdowns
 */
class DPW_PSI_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "\n{$indent}<ul class=\"dropdown-menu\" role=\"menu\">\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= "\n{$indent}<li{$class_names} role=\"none\">";

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target )     . '"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn )        . '"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url )        . '"' : '';

        $has_children = in_array( 'menu-item-has-children', $classes );
        $dropdown_attr = $has_children ? ' class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : '';
        $arrow = $has_children ? ' <i class="bi bi-chevron-down dropdown-arrow"></i>' : '';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . $dropdown_attr . ' role="menuitem">';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= $arrow . '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/**
 * Register Custom Post Types
 */
function dpw_psi_register_post_types() {
    // News CPT
    $news_labels = array(
        'name'               => esc_html__( 'Berita', 'dpw-psi-papeng' ),
        'singular_name'      => esc_html__( 'Berita', 'dpw-psi-papeng' ),
        'menu_name'          => esc_html__( 'Berita & Artikel', 'dpw-psi-papeng' ),
        'add_new'            => esc_html__( 'Tambah Berita', 'dpw-psi-papeng' ),
        'add_new_item'       => esc_html__( 'Tambah Berita Baru', 'dpw-psi-papeng' ),
        'edit_item'          => esc_html__( 'Edit Berita', 'dpw-psi-papeng' ),
        'view_item'          => esc_html__( 'Lihat Berita', 'dpw-psi-papeng' ),
        'all_items'          => esc_html__( 'Semua Berita', 'dpw-psi-papeng' ),
        'search_items'       => esc_html__( 'Cari Berita', 'dpw-psi-papeng' ),
        'not_found'          => esc_html__( 'Tidak ditemukan', 'dpw-psi-papeng' ),
        'not_found_in_trash' => esc_html__( 'Tidak ada di sampah', 'dpw-psi-papeng' ),
    );

    register_post_type( 'psi-news', array(
        'labels'             => $news_labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'berita', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments' ),
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-media-document',
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ) );

    // Video CPT
    register_post_type( 'psi-video', array(
        'labels'             => array(
            'name'               => esc_html__( 'Video', 'dpw-psi-papeng' ),
            'singular_name'      => esc_html__( 'Video', 'dpw-psi-papeng' ),
            'menu_name'          => esc_html__( 'Video Kegiatan', 'dpw-psi-papeng' ),
            'add_new'            => esc_html__( 'Tambah Video', 'dpw-psi-papeng' ),
            'add_new_item'       => esc_html__( 'Tambah Video Baru', 'dpw-psi-papeng' ),
            'edit_item'          => esc_html__( 'Edit Video', 'dpw-psi-papeng' ),
            'all_items'          => esc_html__( 'Semua Video', 'dpw-psi-papeng' ),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'video', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-video-alt3',
        'show_in_rest'       => true,
    ) );

    // Gallery CPT
    register_post_type( 'psi-gallery', array(
        'labels'             => array(
            'name'               => esc_html__( 'Galeri', 'dpw-psi-papeng' ),
            'singular_name'      => esc_html__( 'Galeri', 'dpw-psi-papeng' ),
            'menu_name'          => esc_html__( 'Galeri Foto', 'dpw-psi-papeng' ),
            'add_new'            => esc_html__( 'Tambah Foto', 'dpw-psi-papeng' ),
            'add_new_item'       => esc_html__( 'Tambah Foto Baru', 'dpw-psi-papeng' ),
            'all_items'          => esc_html__( 'Semua Foto', 'dpw-psi-papeng' ),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'galeri', 'with_front' => false ),
        'supports'           => array( 'title', 'thumbnail', 'excerpt' ),
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-format-gallery',
        'show_in_rest'       => true,
    ) );

    // DPD CPT
    register_post_type( 'psi-dpd', array(
        'labels'             => array(
            'name'               => esc_html__( 'DPD PSI', 'dpw-psi-papeng' ),
            'singular_name'      => esc_html__( 'DPD PSI', 'dpw-psi-papeng' ),
            'menu_name'          => esc_html__( 'Data DPD', 'dpw-psi-papeng' ),
            'add_new'            => esc_html__( 'Tambah DPD', 'dpw-psi-papeng' ),
            'add_new_item'       => esc_html__( 'Tambah DPD Baru', 'dpw-psi-papeng' ),
            'edit_item'          => esc_html__( 'Edit DPD', 'dpw-psi-papeng' ),
            'all_items'          => esc_html__( 'Semua DPD', 'dpw-psi-papeng' ),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'dpd', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_position'      => 8,
        'menu_icon'          => 'dashicons-building',
        'show_in_rest'       => true,
    ) );
}
add_action( 'init', 'dpw_psi_register_post_types' );

/**
 * Register Taxonomies
 */
function dpw_psi_register_taxonomies() {
    // News Categories
    register_taxonomy( 'news-category', 'psi-news', array(
        'labels'       => array(
            'name'          => esc_html__( 'Kategori Berita', 'dpw-psi-papeng' ),
            'singular_name' => esc_html__( 'Kategori Berita', 'dpw-psi-papeng' ),
            'menu_name'     => esc_html__( 'Kategori Berita', 'dpw-psi-papeng' ),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'kategori-berita' ),
        'show_in_rest' => true,
    ) );

    // News Tags
    register_taxonomy( 'news-tag', 'psi-news', array(
        'labels'       => array(
            'name'          => esc_html__( 'Tag Berita', 'dpw-psi-papeng' ),
            'singular_name' => esc_html__( 'Tag Berita', 'dpw-psi-papeng' ),
        ),
        'hierarchical' => false,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'tag-berita' ),
        'show_in_rest' => true,
    ) );

    // Video Categories
    register_taxonomy( 'video-category', 'psi-video', array(
        'labels'       => array(
            'name'          => esc_html__( 'Kategori Video', 'dpw-psi-papeng' ),
            'singular_name' => esc_html__( 'Kategori Video', 'dpw-psi-papeng' ),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'kategori-video' ),
        'show_in_rest' => true,
    ) );

    // Gallery Categories
    register_taxonomy( 'gallery-category', 'psi-gallery', array(
        'labels'       => array(
            'name'          => esc_html__( 'Kategori Galeri', 'dpw-psi-papeng' ),
            'singular_name' => esc_html__( 'Kategori Galeri', 'dpw-psi-papeng' ),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'kategori-galeri' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'dpw_psi_register_taxonomies' );

/**
 * Custom Meta Boxes
 */
function dpw_psi_register_meta_boxes() {
    // Video URL meta box
    add_meta_box( 'psi_video_url', esc_html__( 'Video URL', 'dpw-psi-papeng' ), 'dpw_psi_video_url_callback', 'psi-video', 'normal', 'high' );

    // DPD Details meta box
    add_meta_box( 'psi_dpd_details', esc_html__( 'Detail DPD', 'dpw-psi-papeng' ), 'dpw_psi_dpd_details_callback', 'psi-dpd', 'normal', 'high' );

    // Leader Profile meta box (for organization structure pages)
    add_meta_box( 'psi_leader_profile', esc_html__( 'Profil Pimpinan', 'dpw-psi-papeng' ), 'dpw_psi_leader_profile_callback', 'page', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'dpw_psi_register_meta_boxes' );

function dpw_psi_video_url_callback( $post ) {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_video_nonce' );
    $video_url = get_post_meta( $post->ID, '_psi_video_url', true );
    echo '<p><label for="psi_video_url">' . esc_html__( 'YouTube Embed URL:', 'dpw-psi-papeng' ) . '</label></p>';
    echo '<input type="url" id="psi_video_url" name="psi_video_url" value="' . esc_attr( $video_url ) . '" class="large-text" placeholder="https://www.youtube.com/embed/VIDEO_ID" />';
    echo '<p class="description">' . esc_html__( 'Masukkan URL embed YouTube. Contoh: https://www.youtube.com/embed/dQw4w9WgXcQ', 'dpw-psi-papeng' ) . '</p>';
}

function dpw_psi_dpd_details_callback( $post ) {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_dpd_nonce' );
    $ketua_name  = get_post_meta( $post->ID, '_psi_dpd_ketua', true );
    $ketua_photo = get_post_meta( $post->ID, '_psi_dpd_photo', true );
    $phone       = get_post_meta( $post->ID, '_psi_dpd_phone', true );
    $email       = get_post_meta( $post->ID, '_psi_dpd_email', true );
    $address     = get_post_meta( $post->ID, '_psi_dpd_address', true );

    echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">';
    echo '<p><label for="psi_dpd_ketua">' . esc_html__( 'Nama Ketua DPD:', 'dpw-psi-papeng' ) . '</label>';
    echo '<input type="text" id="psi_dpd_ketua" name="psi_dpd_ketua" value="' . esc_attr( $ketua_name ) . '" class="large-text" /></p>';

    echo '<p><label for="psi_dpd_phone">' . esc_html__( 'Telepon:', 'dpw-psi-papeng' ) . '</label>';
    echo '<input type="text" id="psi_dpd_phone" name="psi_dpd_phone" value="' . esc_attr( $phone ) . '" class="large-text" /></p>';

    echo '<p><label for="psi_dpd_email">' . esc_html__( 'Email:', 'dpw-psi-papeng' ) . '</label>';
    echo '<input type="email" id="psi_dpd_email" name="psi_dpd_email" value="' . esc_attr( $email ) . '" class="large-text" /></p>';

    echo '<p><label for="psi_dpd_address">' . esc_html__( 'Alamat:', 'dpw-psi-papeng' ) . '</label>';
    echo '<input type="text" id="psi_dpd_address" name="psi_dpd_address" value="' . esc_attr( $address ) . '" class="large-text" /></p>';
    echo '</div>';

    echo '<p><label for="psi_dpd_photo">' . esc_html__( 'Foto Ketua:', 'dpw-psi-papeng' ) . '</label></p>';
    echo '<div style="display:flex;gap:16px;align-items:center;">';
    if ( $ketua_photo ) {
        echo '<img src="' . esc_url( $ketua_photo ) . '" style="width:80px;height:80px;object-fit:cover;border-radius:50%;" />';
    }
    echo '<input type="hidden" id="psi_dpd_photo" name="psi_dpd_photo" value="' . esc_attr( $ketua_photo ) . '" />';
    echo '<button type="button" class="button" id="dpd_photo_upload">' . esc_html__( 'Pilih Foto', 'dpw-psi-papeng' ) . '</button>';
    echo '<button type="button" class="button" id="dpd_photo_remove" style="color:#a00;">' . esc_html__( 'Hapus', 'dpw-psi-papeng' ) . '</button>';
    echo '</div>';
}

function dpw_psi_leader_profile_callback( $post ) {
    if ( ! in_array( get_page_template_slug( $post->ID ), array( 'page-profile.php', 'page-structure.php' ), true ) ) {
        echo '<p>' . esc_html__( 'Meta box ini hanya untuk halaman profil dan struktur organisasi.', 'dpw-psi-papeng' ) . '</p>';
        return;
    }
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_leader_nonce' );
    $position = get_post_meta( $post->ID, '_psi_leader_position', true );
    $photo    = get_post_meta( $post->ID, '_psi_leader_photo', true );
    $order    = get_post_meta( $post->ID, '_psi_leader_order', true );

    echo '<p><label for="psi_leader_position">' . esc_html__( 'Jabatan:', 'dpw-psi-papeng' ) . '</label>';
    echo '<input type="text" id="psi_leader_position" name="psi_leader_position" value="' . esc_attr( $position ) . '" class="large-text" /></p>';

    echo '<p><label for="psi_leader_order">' . esc_html__( 'Urutan (nomor):', 'dpw-psi-papeng' ) . '</label>';
    echo '<input type="number" id="psi_leader_order" name="psi_leader_order" value="' . esc_attr( $order ) . '" class="small-text" min="0" /></p>';

    echo '<p><label for="psi_leader_photo">' . esc_html__( 'Foto:', 'dpw-psi-papeng' ) . '</label></p>';
    echo '<div style="display:flex;gap:16px;align-items:center;">';
    if ( $photo ) {
        echo '<img src="' . esc_url( $photo ) . '" style="width:80px;height:80px;object-fit:cover;border-radius:12px;" />';
    }
    echo '<input type="hidden" id="psi_leader_photo" name="psi_leader_photo" value="' . esc_attr( $photo ) . '" />';
    echo '<button type="button" class="button" id="leader_photo_upload">' . esc_html__( 'Pilih Foto', 'dpw-psi-papeng' ) . '</button>';
    echo '</div>';
}

/**
 * Save Meta Boxes
 */
function dpw_psi_save_meta_boxes( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Video URL
    if ( isset( $_POST['dpw_psi_video_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dpw_psi_video_nonce'] ) ), 'dpw_psi_save_meta' ) ) {
        if ( isset( $_POST['psi_video_url'] ) ) {
            update_post_meta( $post_id, '_psi_video_url', esc_url_raw( wp_unslash( $_POST['psi_video_url'] ) ) );
        }
    }

    // DPD Details
    if ( isset( $_POST['dpw_psi_dpd_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dpw_psi_dpd_nonce'] ) ), 'dpw_psi_save_meta' ) ) {
        if ( isset( $_POST['psi_dpd_ketua'] ) ) {
            update_post_meta( $post_id, '_psi_dpd_ketua', sanitize_text_field( wp_unslash( $_POST['psi_dpd_ketua'] ) ) );
        }
        if ( isset( $_POST['psi_dpd_phone'] ) ) {
            update_post_meta( $post_id, '_psi_dpd_phone', sanitize_text_field( wp_unslash( $_POST['psi_dpd_phone'] ) ) );
        }
        if ( isset( $_POST['psi_dpd_email'] ) ) {
            update_post_meta( $post_id, '_psi_dpd_email', sanitize_email( wp_unslash( $_POST['psi_dpd_email'] ) ) );
        }
        if ( isset( $_POST['psi_dpd_address'] ) ) {
            update_post_meta( $post_id, '_psi_dpd_address', sanitize_textarea_field( wp_unslash( $_POST['psi_dpd_address'] ) ) );
        }
        if ( isset( $_POST['psi_dpd_photo'] ) ) {
            update_post_meta( $post_id, '_psi_dpd_photo', esc_url_raw( wp_unslash( $_POST['psi_dpd_photo'] ) ) );
        }
    }

    // Leader Profile
    if ( isset( $_POST['dpw_psi_leader_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dpw_psi_leader_nonce'] ) ), 'dpw_psi_save_meta' ) ) {
        if ( isset( $_POST['psi_leader_position'] ) ) {
            update_post_meta( $post_id, '_psi_leader_position', sanitize_text_field( wp_unslash( $_POST['psi_leader_position'] ) ) );
        }
        if ( isset( $_POST['psi_leader_order'] ) ) {
            update_post_meta( $post_id, '_psi_leader_order', absint( wp_unslash( $_POST['psi_leader_order'] ) ) );
        }
        if ( isset( $_POST['psi_leader_photo'] ) ) {
            update_post_meta( $post_id, '_psi_leader_photo', esc_url_raw( wp_unslash( $_POST['psi_leader_photo'] ) ) );
        }
    }
}
add_action( 'save_post', 'dpw_psi_save_meta_boxes' );

/**
 * Enqueue admin scripts for media uploader
 */
function dpw_psi_admin_scripts( $hook ) {
    if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        wp_enqueue_media();
        wp_enqueue_script(
            'dpw-psi-admin',
            DPW_PSI_URI . '/assets/js/admin.js',
            array( 'jquery' ),
            DPW_PSI_VERSION,
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'dpw_psi_admin_scripts' );

/**
 * Custom excerpt length
 */
function dpw_psi_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'dpw_psi_excerpt_length' );

function dpw_psi_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'dpw_psi_excerpt_more' );

/**
 * Breadcrumb
 */
function dpw_psi_breadcrumb() {
    if ( is_front_page() ) return;

    echo '<nav class="breadcrumb" aria-label="Breadcrumb">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '"><i class="bi bi-house-door"></i> Beranda</a>';
    echo '<span class="separator"><i class="bi bi-chevron-right"></i></span>';

    if ( is_singular( 'psi-news' ) ) {
        echo '<a href="' . esc_url( get_post_type_archive_link( 'psi-news' ) ) . '">Berita</a>';
        echo '<span class="separator"><i class="bi bi-chevron-right"></i></span>';
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_singular( 'psi-video' ) ) {
        echo '<a href="' . esc_url( get_post_type_archive_link( 'psi-video' ) ) . '">Video</a>';
        echo '<span class="separator"><i class="bi bi-chevron-right"></i></span>';
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_singular( 'psi-gallery' ) ) {
        echo '<a href="' . esc_url( get_post_type_archive_link( 'psi-gallery' ) ) . '">Galeri</a>';
        echo '<span class="separator"><i class="bi bi-chevron-right"></i></span>';
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_singular( 'psi-dpd' ) ) {
        echo '<a href="' . esc_url( get_post_type_archive_link( 'psi-dpd' ) ) . '">DPD PSI</a>';
        echo '<span class="separator"><i class="bi bi-chevron-right"></i></span>';
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_page() ) {
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_post_type_archive( 'psi-news' ) ) {
        echo '<span class="current">Berita & Artikel</span>';
    } elseif ( is_post_type_archive( 'psi-video' ) ) {
        echo '<span class="current">Video Kegiatan</span>';
    } elseif ( is_post_type_archive( 'psi-gallery' ) ) {
        echo '<span class="current">Galeri Foto</span>';
    } elseif ( is_post_type_archive( 'psi-dpd' ) ) {
        echo '<span class="current">DPD PSI</span>';
    } elseif ( is_category() || is_tax() ) {
        echo '<span class="current">' . esc_html( single_term_title( '', false ) ) . '</span>';
    } elseif ( is_search() ) {
        echo '<span class="current">Hasil Pencarian</span>';
    } elseif ( is_404() ) {
        echo '<span class="current">Halaman Tidak Ditemukan</span>';
    }

    echo '</nav>';
}

/**
 * Schema markup
 */
function dpw_psi_schema_org() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'DPW PSI Papua Pegunungan',
        'url'      => home_url( '/' ),
        'logo'     => DPW_PSI_URI . '/assets/images/logo.png',
        'address'  => array(
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Papua Pegunungan',
            'addressCountry'  => 'ID',
        ),
        'sameAs'   => array(
            'https://www.facebook.com/psipapeng',
            'https://www.instagram.com/psipapeng',
            'https://www.youtube.com/@psipapeng',
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dpw_psi_schema_org' );

/**
 * Open Graph tags
 */
function dpw_psi_open_graph() {
    if ( is_singular() ) {
        global $post;
        $title       = get_the_title();
        $description = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30 );
        $image       = has_post_thumbnail() ? get_the_post_thumbnail_url( $post->ID, 'dpw-news' ) : DPW_PSI_URI . '/assets/images/og-default.jpg';
        $url         = get_permalink();
    } else {
        $title       = get_bloginfo( 'name' );
        $description = get_bloginfo( 'description' );
        $image       = DPW_PSI_URI . '/assets/images/og-default.jpg';
        $url         = home_url( '/' );
    }

    echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
    echo '<meta property="og:type" content="' . ( is_singular() ? 'article' : 'website' ) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";

    // Twitter Cards
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
}
add_action( 'wp_head', 'dpw_psi_open_graph' );

/**
 * Contact form AJAX handler
 */
function dpw_psi_contact_form_handler() {
    check_ajax_referer( 'dpw_psi_nonce', 'nonce' );

    $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Mohon lengkapi semua kolom yang wajib diisi.', 'dpw-psi-papeng' ) ) );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Alamat email tidak valid.', 'dpw-psi-papeng' ) ) );
    }

    $to      = get_option( 'admin_email' );
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
    );

    $body = '<h2>' . esc_html__( 'Pesan dari Website', 'dpw-psi-papeng' ) . '</h2>';
    $body .= '<p><strong>' . esc_html__( 'Nama', 'dpw-psi-papeng' ) . ':</strong> ' . esc_html( $name ) . '</p>';
    $body .= '<p><strong>' . esc_html__( 'Email', 'dpw-psi-papeng' ) . ':</strong> ' . esc_html( $email ) . '</p>';
    $body .= '<p><strong>' . esc_html__( 'Subjek', 'dpw-psi-papeng' ) . ':</strong> ' . esc_html( $subject ) . '</p>';
    $body .= '<p><strong>' . esc_html__( 'Pesan', 'dpw-psi-papeng' ) . ':</strong></p>';
    $body .= '<p>' . nl2br( esc_html( $message ) ) . '</p>';

    $sent = wp_mail( $to, '[' . get_bloginfo( 'name' ) . '] ' . $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => esc_html__( 'Pesan Anda berhasil dikirim. Terima kasih!', 'dpw-psi-papeng' ) ) );
    } else {
        wp_send_json_error( array( 'message' => esc_html__( 'Gagal mengirim pesan. Silakan coba lagi.', 'dpw-psi-papeng' ) ) );
    }
}
add_action( 'wp_ajax_dpw_psi_contact', 'dpw_psi_contact_form_handler' );
add_action( 'wp_ajax_nopriv_dpw_psi_contact', 'dpw_psi_contact_form_handler' );

/**
 * Preload critical assets
 */
function dpw_psi_preload_assets() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
    echo '<link rel="dns-prefetch" href="https://www.googletagmanager.com" />' . "\n";
}
add_action( 'wp_head', 'dpw_psi_preload_assets', 1 );

/**
 * Disable emoji scripts for performance
 */
function dpw_psi_disable_emoji() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'dpw_psi_disable_emoji' );

/**
 * Add body classes
 */
function dpw_psi_body_classes( $classes ) {
    if ( is_front_page() ) {
        $classes[] = 'home-page';
    }
    $classes[] = 'dpw-psi-theme';
    return $classes;
}
add_filter( 'body_class', 'dpw_psi_body_classes' );
