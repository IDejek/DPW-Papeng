<?php
/**
 * DPW PSI Papua Pegunungan Theme Functions
 * @package DPW_PSI_Papeng
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('DPW_PSI_VERSION', '1.0.0');
define('DPW_PSI_DIR', get_template_directory());
define('DPW_PSI_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function dpw_psi_setup() {
    load_theme_textdomain('dpw-psi-papeng', DPW_PSI_DIR . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('dpw-hero', 1920, 1080, true);
    add_image_size('dpw-leader', 600, 800, true);
    add_image_size('dpw-news', 800, 500, true);
    add_image_size('dpw-news-thumb', 400, 250, true);
    add_image_size('dpw-gallery', 600, 600, true);
    add_image_size('dpw-dpd', 400, 400, true);
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('custom-logo', array('height' => 80, 'width' => 300, 'flex-height' => true, 'flex-width' => true));
    register_nav_menus(array('primary' => esc_html__('Primary Menu', 'dpw-psi-papeng'), 'footer' => esc_html__('Footer Menu', 'dpw-psi-papeng')));
    add_theme_support('custom-background', array('default-color' => 'ffffff'));
}
add_action('after_setup_theme', 'dpw_psi_setup');

/**
 * Enqueue Scripts & Styles
 */
function dpw_psi_scripts() {
    wp_enqueue_style('dpw-psi-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap', array(), null);
    wp_enqueue_style('bootstrap-icons', DPW_PSI_URI . '/assets/css/bootstrap-icons.min.css', array(), '1.11.3');
    wp_enqueue_style('dpw-psi-style', get_stylesheet_uri(), array('bootstrap-icons'), DPW_PSI_VERSION);
    
    wp_enqueue_script('dpw-psi-main', DPW_PSI_URI . '/assets/js/main.js', array(), DPW_PSI_VERSION, true);
    wp_localize_script('dpw-psi-main', 'dpwPsi', array('ajaxUrl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('dpw_psi_nonce'), 'themeUri' => DPW_PSI_URI));
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'dpw_psi_scripts');

/**
 * Register Widgets
 */
function dpw_psi_widgets_init() {
    register_sidebar(array('name' => esc_html__('Article Sidebar', 'dpw-psi-papeng'), 'id' => 'article-sidebar', 'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
}
add_action('widgets_init', 'dpw_psi_widgets_init');

/**
 * Custom Nav Walker
 */
class DPW_PSI_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= "\n<ul class=\"dropdown-menu\" role=\"menu\">\n";
    }
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        $attributes = '';
        if (!empty($item->url)) $attributes .= ' href="' . esc_attr($item->url) . '"';
        if (!empty($item->target)) $attributes .= ' target="' . esc_attr($item->target) . '"';
        if (!empty($item->xfn)) $attributes .= ' rel="' . esc_attr($item->xfn) . '"';
        $dropdown_attr = $has_children ? ' class="dropdown-toggle"' : '';
        $arrow = $has_children ? ' <i class="bi bi-chevron-down dropdown-arrow"></i>' : '';
        $output .= "<li><a{$attributes}{$dropdown_attr} role=\"menuitem\">" . esc_html($item->title) . "{$arrow}</a>";
    }
}

/**
 * Register CPTs
 */
function dpw_psi_register_post_types() {
    register_post_type('psi-news', array('labels' => array('name' => 'Berita & Artikel', 'singular_name' => 'Berita', 'add_new' => 'Tambah Berita', 'all_items' => 'Semua Berita'), 'public' => true, 'has_archive' => true, 'rewrite' => array('slug' => 'berita', 'with_front' => false), 'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'), 'menu_position' => 5, 'menu_icon' => 'dashicons-media-document', 'show_in_rest' => true));
    register_post_type('psi-video', array('labels' => array('name' => 'Video Kegiatan', 'singular_name' => 'Video', 'add_new' => 'Tambah Video', 'all_items' => 'Semua Video'), 'public' => true, 'has_archive' => true, 'rewrite' => array('slug' => 'video', 'with_front' => false), 'supports' => array('title', 'editor', 'thumbnail', 'excerpt'), 'menu_position' => 6, 'menu_icon' => 'dashicons-video-alt3', 'show_in_rest' => true));
    register_post_type('psi-gallery', array('labels' => array('name' => 'Galeri Foto', 'singular_name' => 'Galeri', 'add_new' => 'Tambah Foto', 'all_items' => 'Semua Foto'), 'public' => true, 'has_archive' => true, 'rewrite' => array('slug' => 'galeri', 'with_front' => false), 'supports' => array('title', 'thumbnail', 'excerpt'), 'menu_position' => 7, 'menu_icon' => 'dashicons-format-gallery', 'show_in_rest' => true));
    register_post_type('psi-dpd', array('labels' => array('name' => 'Data DPD', 'singular_name' => 'DPD', 'add_new' => 'Tambah DPD', 'all_items' => 'Semua DPD'), 'public' => true, 'has_archive' => true, 'rewrite' => array('slug' => 'dpd', 'with_front' => false), 'supports' => array('title', 'editor', 'thumbnail', 'excerpt'), 'menu_position' => 8, 'menu_icon' => 'dashicons-building', 'show_in_rest' => true));
}
add_action('init', 'dpw_psi_register_post_types');

/**
 * Register Taxonomies
 */
function dpw_psi_register_taxonomies() {
    register_taxonomy('news-category', 'psi-news', array('labels' => array('name' => 'Kategori Berita'), 'hierarchical' => true, 'public' => true, 'rewrite' => array('slug' => 'kategori-berita'), 'show_in_rest' => true));
    register_taxonomy('news-tag', 'psi-news', array('labels' => array('name' => 'Tag Berita'), 'hierarchical' => false, 'public' => true, 'rewrite' => array('slug' => 'tag-berita'), 'show_in_rest' => true));
    register_taxonomy('video-category', 'psi-video', array('labels' => array('name' => 'Kategori Video'), 'hierarchical' => true, 'public' => true, 'rewrite' => array('slug' => 'kategori-video'), 'show_in_rest' => true));
    register_taxonomy('gallery-category', 'psi-gallery', array('labels' => array('name' => 'Kategori Galeri'), 'hierarchical' => true, 'public' => true, 'rewrite' => array('slug' => 'kategori-galeri'), 'show_in_rest' => true));
}
add_action('init', 'dpw_psi_register_taxonomies');

/**
 * Meta Boxes
 */
function dpw_psi_register_meta_boxes() {
    add_meta_box('psi_video_url', 'Video URL', 'dpw_psi_video_url_cb', 'psi-video', 'normal', 'high');
    add_meta_box('psi_dpd_details', 'Detail DPD', 'dpw_psi_dpd_details_cb', 'psi-dpd', 'normal', 'high');
}
add_action('add_meta_boxes', 'dpw_psi_register_meta_boxes');

function dpw_psi_video_url_cb($post) {
    wp_nonce_field('dpw_psi_save_meta', 'dpw_psi_video_nonce');
    $val = get_post_meta($post->ID, '_psi_video_url', true);
    echo '<input type="url" id="psi_video_url" name="psi_video_url" value="' . esc_attr($val) . '" class="large-text" placeholder="https://www.youtube.com/embed/VIDEO_ID">';
}

function dpw_psi_dpd_details_cb($post) {
    wp_nonce_field('dpw_psi_save_meta', 'dpw_psi_dpd_nonce');
    $fields = array('_psi_dpd_ketua' => 'Nama Ketua', '_psi_dpd_phone' => 'Telepon', '_psi_dpd_email' => 'Email', '_psi_dpd_address' => 'Alamat');
    foreach ($fields as $key => $label) {
        $val = get_post_meta($post->ID, $key, true);
        echo '<p><label>' . esc_html($label) . ':</label><br><input type="text" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '" class="large-text"></p>';
    }
    $photo = get_post_meta($post->ID, '_psi_dpd_photo', true);
    echo '<p><label>Foto Ketua:</label><br>';
    if ($photo) echo '<img src="' . esc_url($photo) . '" style="width:80px;height:80px;object-fit:cover;border-radius:50%;margin-bottom:8px;display:block;"><br>';
    echo '<input type="hidden" name="_psi_dpd_photo" value="' . esc_attr($photo) . '"><button type="button" class="button" id="dpd_photo_upload">Pilih Foto</button></p>';
}

function dpw_psi_save_meta_boxes($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $fields_map = array(
        'dpw_psi_video_nonce' => array('_psi_video_url' => 'url'),
        'dpw_psi_dpd_nonce' => array('_psi_dpd_ketua' => 'text', '_psi_dpd_phone' => 'text', '_psi_dpd_email' => 'email', '_psi_dpd_address' => 'text', '_psi_dpd_photo' => 'url')
    );

    foreach ($fields_map as $nonce_key => $fields) {
        if (isset($_POST[$nonce_key]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_key])), 'dpw_psi_save_meta')) {
            foreach ($fields as $field_key => $type) {
                if (isset($_POST[$field_key])) {
                    $val = wp_unslash($_POST[$field_key]);
                    if ($type === 'url') $val = esc_url_raw($val);
                    elseif ($type === 'email') $val = sanitize_email($val);
                    else $val = sanitize_text_field($val);
                    update_post_meta($post_id, $field_key, $val);
                }
            }
        }
    }
}
add_action('save_post', 'dpw_psi_save_meta_boxes');

function dpw_psi_admin_scripts($hook) {
    if (in_array($hook, array('post.php', 'post-new.php'))) {
        wp_enqueue_media();
        wp_enqueue_script('dpw-psi-admin', DPW_PSI_URI . '/assets/js/admin.js', array('jquery'), DPW_PSI_VERSION, true);
    }
}
add_action('admin_enqueue_scripts', 'dpw_psi_admin_scripts');

/**
 * Helper: Reading Time
 */
function dpw_psi_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    if (!$content) return '1 menit baca';
    $count = str_word_count(strip_tags($content));
    return max(1, ceil($count / 200)) . ' menit baca';
}

/**
 * Helper: Breadcrumb
 */
function dpw_psi_breadcrumb() {
    if (is_front_page()) return;
    echo '<nav class="breadcrumb" aria-label="Breadcrumb"><a href="' . esc_url(home_url('/')) . '"><i class="bi bi-house-door"></i> Beranda</a><span class="separator"><i class="bi bi-chevron-right"></i></span>';
    
    $title = '';
    if (is_singular('psi-news')) { echo '<a href="' . esc_url(get_post_type_archive_link('psi-news')) . '">Berita</a><span class="separator"><i class="bi bi-chevron-right"></i></span>'; $title = get_the_title(); }
    elseif (is_singular('psi-video')) { echo '<a href="' . esc_url(get_post_type_archive_link('psi-video')) . '">Video</a><span class="separator"><i class="bi bi-chevron-right"></i></span>'; $title = get_the_title(); }
    elseif (is_singular('psi-gallery')) { echo '<a href="' . esc_url(get_post_type_archive_link('psi-gallery')) . '">Galeri</a><span class="separator"><i class="bi bi-chevron-right"></i></span>'; $title = get_the_title(); }
    elseif (is_singular('psi-dpd')) { echo '<a href="' . esc_url(get_post_type_archive_link('psi-dpd')) . '">DPD</a><span class="separator"><i class="bi bi-chevron-right"></i></span>'; $title = get_the_title(); }
    elseif (is_page()) { $title = get_the_title(); }
    elseif (is_post_type_archive('psi-news')) { $title = 'Berita & Artikel'; }
    elseif (is_post_type_archive('psi-video')) { $title = 'Video Kegiatan'; }
    elseif (is_post_type_archive('psi-gallery')) { $title = 'Galeri Foto'; }
    elseif (is_post_type_archive('psi-dpd')) { $title = 'DPD PSI'; }
    elseif (is_404()) { $title = 'Halaman Tidak Ditemukan'; }
    else { $title = 'Arsip'; }

    echo '<span class="current">' . esc_html($title) . '</span></nav>';
}

/**
 * Schema & Open Graph
 */
function dpw_psi_head_tags() {
    // Schema
    $schema = array('@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'DPW PSI Papua Pegunungan', 'url' => home_url('/'));
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . "</script>\n";

    // OG Tags
    $title = is_singular() ? get_the_title() : get_bloginfo('name');
    $desc = is_singular() ? wp_trim_words(get_the_excerpt(), 30) : get_bloginfo('description');
    $img = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'dpw-news') : DPW_PSI_URI . '/assets/images/og-default.jpg';
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($img) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action('wp_head', 'dpw_psi_head_tags');

/**
 * Contact Form AJAX
 */
function dpw_psi_contact_form_handler() {
    check_ajax_referer('dpw_psi_nonce', 'nonce');
    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $subject = isset($_POST['subject']) ? sanitize_text_field(wp_unslash($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    if (empty($name) || empty($email) || empty($message)) wp_send_json_error(array('message' => 'Mohon lengkapi semua kolom wajib.'));
    if (!is_email($email)) wp_send_json_error(array('message' => 'Email tidak valid.'));

    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$message";
    $sent = wp_mail(get_option('admin_email'), '[ Kontak Website ] ' . $subject, $body);

    if ($sent) wp_send_json_success(array('message' => 'Pesan berhasil dikirim.'));
    else wp_send_json_error(array('message' => 'Gagal mengirim pesan.'));
}
add_action('wp_ajax_dpw_psi_contact', 'dpw_psi_contact_form_handler');
add_action('wp_ajax_nopriv_dpw_psi_contact', 'dpw_psi_contact_form_handler');

/**
 * Performance tweaks
 */
function dpw_psi_preload() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('wp_head', 'dpw_psi_preload', 1);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

function dpw_psi_body_classes($classes) {
    $classes[] = 'dpw-psi-theme';
    return $classes;
}
add_filter('body_class', 'dpw_psi_body_classes');
