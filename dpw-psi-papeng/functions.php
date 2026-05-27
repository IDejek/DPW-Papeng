<?php
/**
 * DPW PSI Papua Pegunungan Theme Functions
 *
 * @package DPW_PSIPapeng
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'DPW_PSI_VERSION', '1.0.0' );
define( 'DPW_PSI_DIR', get_template_directory() );
define( 'DPW_PSI_URI', get_template_directory_uri() );

/* ── Theme Setup ───────────────────────────────────────────── */
add_action( 'after_setup_theme', 'dpw_psi_theme_setup' );
function dpw_psi_theme_setup(): void {
    load_theme_textdomain( 'dpw-psi-papeng', DPW_PSI_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );

    register_nav_menus( [
        'primary'   => __( 'Primary Menu', 'dpw-psi-papeng' ),
        'footer'    => __( 'Footer Menu', 'dpw-psi-papeng' ),
        'mobile'    => __( 'Mobile Menu', 'dpw-psi-papeng' ),
    ] );

    add_image_size( 'slider-full', 1920, 800, true );
    add_image_size( 'leader-card', 400, 500, true );
    add_image_size( 'news-card',   600, 400, true );
    add_image_size( 'gallery-masonry', 600, 600, false );
    add_image_size( 'dpd-card',    500, 600, true );
}

/* ── Enqueue Assets ────────────────────────────────────────── */
add_action( 'wp_enqueue_scripts', 'dpw_psi_enqueue_assets' );
function dpw_psi_enqueue_assets(): void {
    /* Google Fonts */
    wp_enqueue_style(
        'dpw-psi-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap',
        [],
        null
    );

    /* Bootstrap 5 — CDN */
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        [],
        '5.3.3'
    );

    /* Bootstrap Icons — CDN */
    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
        [],
        '1.11.3'
    );

    /* Theme CSS */
    wp_enqueue_style(
        'dpw-psi-theme',
        DPW_PSI_URI . '/assets/css/theme.css',
        [ 'bootstrap', 'bootstrap-icons', 'dpw-psi-fonts' ],
        DPW_PSI_VERSION
    );

    /* Bootstrap JS Bundle — CDN */
    wp_enqueue_script(
        'bootstrap-bundle',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        [],
        '5.3.3',
        true
    );

    /* Clock JS */
    wp_enqueue_script(
        'dpw-psi-clock',
        DPW_PSI_URI . '/assets/js/clock.js',
        [],
        DPW_PSI_VERSION,
        true
    );

    /* Theme JS */
    wp_enqueue_script(
        'dpw-psi-theme',
        DPW_PSI_URI . '/assets/js/theme.js',
        [ 'bootstrap-bundle', 'dpw-psi-clock' ],
        DPW_PSI_VERSION,
        true
    );

    /* Localize */
    wp_localize_script( 'dpw-psi-theme', 'dpwPsi', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'dpw_psi_nonce' ),
        'homeUrl' => home_url( '/' ),
    ] );

    /* Comments reply */
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

/* ── Admin Assets ──────────────────────────────────────────── */
add_action( 'admin_enqueue_scripts', 'dpw_psi_admin_assets' );
function dpw_psi_admin_assets( $hook ): void {
    if ( in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        wp_enqueue_style(
            'dpw-psi-admin-meta',
            DPW_PSI_URI . '/assets/css/admin-meta.css',
            [],
            DPW_PSI_VERSION
        );
        wp_enqueue_script(
            'dpw-psi-admin-meta',
            DPW_PSI_URI . '/assets/js/admin-meta.js',
            [ 'jquery' ],
            DPW_PSI_VERSION,
            true
        );
    }
}

/* ── Include Files ─────────────────────────────────────────── */
require_once DPW_PSI_DIR . '/inc/helpers.php';
require_once DPW_PSI_DIR . '/inc/post-types.php';
require_once DPW_PSI_DIR . '/inc/meta-boxes.php';
require_once DPW_PSI_DIR . '/inc/customizer.php';
require_once DPW_PSI_DIR . '/inc/shortcodes.php';

/* ── Widget Areas ──────────────────────────────────────────── */
add_action( 'widgets_init', 'dpw_psi_widgets_init' );
function dpw_psi_widgets_init(): void {
    register_sidebar( [
        'name'          => __( 'Sidebar Utama', 'dpw-psi-papeng' ),
        'id'            => 'sidebar-main',
        'description'   => __( 'Sidebar untuk halaman berita dan arsip.', 'dpw-psi-papeng' ),
        'before_widget' => '<div id="%1$s" class="widget mb-4 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title fw-bold mb-3 pb-2 border-bottom border-danger">',
        'after_title'   => '</h4>',
    ] );
    register_sidebar( [
        'name'          => __( 'Footer Kolom 1', 'dpw-psi-papeng' ),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title fw-bold text-white mb-3">',
        'after_title'   => '</h5>',
    ] );
    register_sidebar( [
        'name'          => __( 'Footer Kolom 2', 'dpw-psi-papeng' ),
        'id'            => 'footer-2',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title fw-bold text-white mb-3">',
        'after_title'   => '</h5>',
    ] );
    register_sidebar( [
        'name'          => __( 'Footer Kolom 3', 'dpw-psi-papeng' ),
        'id'            => 'footer-3',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title fw-bold text-white mb-3">',
        'after_title'   => '</h5>',
    ] );
}

/* ── Excerpt Length ────────────────────────────────────────── */
add_filter( 'excerpt_length', 'dpw_psi_excerpt_length' );
function dpw_psi_excerpt_length( $length ): int {
    return is_front_page() ? 20 : 40;
}

add_filter( 'excerpt_more', 'dpw_psi_excerpt_more' );
function dpw_psi_excerpt_more( $more ): string {
    return '...';
}

/* ── Body Classes ──────────────────────────────────────────── */
add_filter( 'body_class', 'dpw_psi_body_classes' );
function dpw_psi_body_classes( $classes ): array {
    if ( is_front_page() ) {
        $classes[] = 'home-page';
    }
    if ( is_singular( 'leadership' ) ) {
        $classes[] = 'leadership-page';
    }
    if ( is_singular( 'dpd' ) ) {
        $classes[] = 'dpd-page';
    }
    $classes[] = 'dpw-psi-' . DPW_PSI_VERSION;
    return $classes;
}

/* ── Disable Emoji ─────────────────────────────────────────── */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* ── Reading Progress Bar (single posts) ───────────────────── */
add_action( 'wp_head', 'dpw_psi_reading_progress_style' );
function dpw_psi_reading_progress_style(): void {
    if ( ! is_singular( 'post' ) ) return;
    echo '<style>#reading-progress{position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#D6001C,#D4AF37);z-index:99999;transition:width .1s linear;width:0}</style>';
}

add_action( 'wp_footer', 'dpw_psi_reading_progress_script' );
function dpw_psi_reading_progress_script(): void {
    if ( ! is_singular( 'post' ) ) return;
    ?>
    <div id="reading-progress"></div>
    <script>
    (function(){
        var bar=document.getElementById('reading-progress');
        if(!bar)return;
        window.addEventListener('scroll',function(){
            var h=document.documentElement.scrollHeight-window.innerHeight;
            var p=h>0?(window.scrollY/h)*100:0;
            bar.style.width=p+'%';
        });
    })();
    </script>
    <?php
}

/* ── Schema Markup ─────────────────────────────────────────── */
add_action( 'wp_head', 'dpw_psi_organization_schema' );
function dpw_psi_organization_schema(): void {
    $org_name = get_bloginfo( 'name' );
    $org_desc = get_bloginfo( 'description' );
    $org_url  = home_url( '/' );
    $logo     = has_custom_logo() ? wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) : '';
    $phone    = dpw_psi_get_option( 'contact_phone', '+62 822 6721 8125' );
    $email    = dpw_psi_get_option( 'contact_email', 'tombinawaiqbal@gmail.com' );
    $address  = dpw_psi_get_option( 'contact_address', 'Papua Pegunungan, Indonesia' );

    $schema = [
        '@context'       => 'https://schema.org',
        '@type'          => 'Organization',
        'name'           => esc_html( $org_name ),
        'alternateName'  => 'DPW PSI Papua Pegunungan',
        'url'            => esc_url( $org_url ),
        'logo'           => esc_url( $logo ),
        'description'    => esc_html( $org_desc ),
        'telephone'      => esc_html( $phone ),
        'email'          => esc_html( $email ),
        'address'        => [
            '@type'         => 'PostalAddress',
            'streetAddress' => esc_html( $address ),
            'addressCountry'=> 'ID',
        ],
        'sameAs'         => dpw_psi_get_social_links_array(),
    ];

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}

/* ── Open Graph Tags ───────────────────────────────────────── */
add_action( 'wp_head', 'dpw_psi_og_tags' );
function dpw_psi_og_tags(): void {
    if ( is_singular() ) {
        global $post;
        $title       = get_the_title( $post );
        $description = has_excerpt( $post ) ? wp_strip_all_tags( get_the_excerpt( $post ) ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
        $url         = get_permalink( $post );
        $image       = has_post_thumbnail( $post ) ? get_the_post_thumbnail_url( $post, 'large' ) : '';
        $type        = 'article';
    } else {
        $title       = get_bloginfo( 'name' );
        $description = get_bloginfo( 'description' );
        $url         = home_url( '/' );
        $image       = has_custom_logo() ? wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) : '';
        $type        = 'website';
    }

    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
    if ( $image ) {
        echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
    }
}

/* ── Breadcrumbs ───────────────────────────────────────────── */
function dpw_psi_breadcrumbs(): void {
    if ( is_front_page() ) return;

    echo '<nav aria-label="breadcrumb" class="dpw-breadcrumb mb-4"><ol class="breadcrumb">';
    echo '<li class="breadcrumb-item"><a href="' . esc_url( home_url( '/' ) ) . '"><i class="bi bi-house-door"></i> Beranda</a></li>';

    if ( is_category() || is_single() ) {
        $categories = get_the_category();
        if ( $categories ) {
            echo '<li class="breadcrumb-item"><a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a></li>';
        }
        if ( is_single() ) {
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html( get_the_title() ) . '</li>';
        }
    } elseif ( is_page() ) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html( get_the_title() ) . '</li>';
    } elseif ( is_search() ) {
        echo '<li class="breadcrumb-item active" aria-current="page">Hasil Pencarian</li>';
    } elseif ( is_404() ) {
        echo '<li class="breadcrumb-item active" aria-current="page">Halaman Tidak Ditemukan</li>';
    } elseif ( is_post_type_archive() ) {
        $pt = get_post_type_object( get_post_type() );
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html( $pt->labels->name ) . '</li>';
    }

    echo '</ol></nav>';
}

/* ── Social Share Buttons ──────────────────────────────────── */
function dpw_psi_share_buttons(): void {
    $url   = urlencode( get_permalink() );
    $title = urlencode( get_the_title() );
    ?>
    <div class="dpw-share-buttons">
        <span class="share-label fw-bold text-muted small text-uppercase">Bagikan:</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-circle mx-1" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-circle mx-1" aria-label="X/Twitter"><i class="bi bi-twitter-x"></i></a>
        <a href="https://wa.me/?text=<?php echo $title; ?>%20<?php echo $url; ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success rounded-circle mx-1" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
        <a href="https://t.me/share/url?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary rounded-circle mx-1" aria-label="Telegram"><i class="bi bi-telegram"></i></a>
    </div>
    <?php
}

/* ── Pagination ────────────────────────────────────────────── */
function dpw_psi_pagination(): void {
    the_posts_pagination( [
        'mid_size'  => 2,
        'prev_text' => '<i class="bi bi-chevron-left"></i>',
        'next_text' => '<i class="bi bi-chevron-right"></i>',
        'class'     => 'dpw-pagination',
    ] );
}

/* ── Preloader ─────────────────────────────────────────────── */
add_action( 'wp_body_open', 'dpw_psi_preloader' );
function dpw_psi_preloader(): void {
    ?>
    <div id="dpw-preloader" aria-hidden="true">
        <div class="dpw-preloader-inner">
            <div class="dpw-preloader-spinner"></div>
            <span class="dpw-preloader-text">PSI</span>
        </div>
    </div>
    <?php
}
