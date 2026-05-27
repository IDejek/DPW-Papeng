<?php
/**
 * Header Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo esc_attr( is_singular() ? wp_strip_all_tags( get_the_excerpt() ) : get_bloginfo( 'description' ) ); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( dpw_psi_get_option( 'show_topbar', '1' ) === '1' ) : ?>
<!-- ════════ TOPBAR ════════ -->
<div class="dpw-topbar" id="dpwTopbar">
    <div class="container">
        <div class="row align-items-center py-1">
            <div class="col-md-6 d-none d-md-block">
                <small class="dpw-topbar-text">
                    <i class="bi bi-broadcast-pin me-1"></i>
                    <?php echo esc_html( dpw_psi_get_option( 'topbar_text', 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan' ) ); ?>
                </small>
            </div>
            <div class="col-md-6 text-md-end text-center">
                <div class="dpw-clocks d-inline-flex gap-3">
                    <span class="dpw-clock-item">
                        <span class="dpw-clock-label">WIT</span>
                        <span class="dpw-clock-time" id="clockWIT" data-utc-offset="9">--:--:--</span>
                    </span>
                    <span class="dpw-clock-divider">|</span>
                    <span class="dpw-clock-item">
                        <span class="dpw-clock-label">WIB</span>
                        <span class="dpw-clock-time" id="clockWIB" data-utc-offset="7">--:--:--</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ════════ MAIN NAVBAR ════════ -->
<nav class="navbar navbar-expand-lg dpw-navbar sticky-top" id="dpwNavbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <?php dpw_psi_icon( 'psi-logo', 'dpw-nav-logo' ); ?>
            <?php endif; ?>
            <div class="dpw-brand-text d-none d-sm-block">
                <span class="dpw-brand-name">DPW PSI</span>
                <span class="dpw-brand-sub">Papua Pegunungan</span>
            </div>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#dpwMobileMenu" aria-controls="dpwMobileMenu" aria-label="<?php esc_attr_e( 'Toggle navigation', 'dpw-psi-papeng' ); ?>">
            <span class="dpw-hamburger">
                <span></span><span></span><span></span>
            </span>
        </button>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse" id="dpwDesktopNav">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'navbar-nav ms-auto align-items-lg-center gap-lg-1',
                'fallback_cb'    => 'dpw_psi_fallback_menu',
                'depth'          => 2,
                'walker'         => new DPW_PSI_Nav_Walker(),
            ] );
            ?>
            <a href="<?php echo esc_url( dpw_psi_get_option( 'membership_url', 'https://psi.id/menjadi-anggota' ) ); ?>" class="btn btn-danger btn-sm ms-lg-3 fw-bold px-3 py-2 dpw-cta-nav">
                <i class="bi bi-person-plus me-1"></i><?php echo esc_html( dpw_psi_get_option( 'membership_text', 'Daftar Anggota' ) ); ?>
            </a>
        </div>
    </div>
</nav>

<!-- ════════ MOBILE OFFCANVAS ════════ -->
<div class="offcanvas offcanvas-end dpw-mobile-menu" tabindex="-1" id="dpwMobileMenu" aria-labelledby="dpwMobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="dpwMobileMenuLabel">
            <span class="text-danger fw-bold">DPW PSI</span>
            <small class="d-block text-muted">Papua Pegunungan</small>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Tutup', 'dpw-psi-papeng' ); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <?php
        wp_nav_menu( [
            'theme_location' => 'mobile',
            'container'      => false,
            'menu_class'     => 'nav flex-column gap-1',
            'fallback_cb'    => function() {
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'nav flex-column gap-1',
                    'depth'          => 1,
                ] );
            },
        ] );
        ?>
        <hr class="border-secondary my-3">
        <a href="<?php echo esc_url( dpw_psi_get_option( 'membership_url', 'https://psi.id/menjadi-anggota' ) ); ?>" class="btn btn-danger w-100 fw-bold">
            <i class="bi bi-person-plus me-2"></i><?php echo esc_html( dpw_psi_get_option( 'membership_text', 'Daftar Anggota' ) ); ?>
        </a>
        <div class="dpw-mobile-social mt-4">
            <?php dpw_psi_social_links_mobile(); ?>
        </div>
    </div>
</div>

<?php
/* ── Fallback Menu Callback ───────────────────────────────── */
function dpw_psi_fallback_menu() {
    $items = [
        [ 'label' => 'Beranda', 'url' => home_url( '/' ) ],
        [ 'label' => 'Profil', 'url' => '#', 'children' => [
            [ 'label' => 'Sejarah PSI', 'url' => home_url( '/profil/sejarah-psi/' ) ],
            [ 'label' => 'Visi & Misi', 'url' => home_url( '/profil/visi-misi/' ) ],
            [ 'label' => 'Struktur Organisasi', 'url' => home_url( '/struktur-organisasi/' ) ],
        ]],
        [ 'label' => 'DPD Kabupaten', 'url' => home_url( '/dpd-kabupaten/' ) ],
        [ 'label' => 'Berita', 'url' => home_url( '/category/berita/' ) ],
        [ 'label' => 'Galeri', 'url' => home_url( '/galeri/' ) ],
        [ 'label' => 'Video', 'url' => home_url( '/video/' ) ],
        [ 'label' => 'Kontak', 'url' => home_url( '/kontak/' ) ],
    ];
    echo '<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">';
    foreach ( $items as $item ) {
        $has_children = ! empty( $item['children'] );
        $classes = $has_children ? 'nav-item dropdown' : 'nav-item';
        echo '<li class="' . esc_attr( $classes ) . '">';
        if ( $has_children ) {
            echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . esc_html( $item['label'] ) . '</a>';
            echo '<ul class="dropdown-menu">';
            foreach ( $item['children'] as $child ) {
                echo '<li><a class="dropdown-item" href="' . esc_url( $child['url'] ) . '">' . esc_html( $child['label'] ) . '</a></li>';
            }
            echo '</ul>';
        } else {
            $active = ( $_SERVER['REQUEST_URI'] === parse_url( $item['url'], PHP_URL_PATH ) ) ? ' active' : '';
            echo '<a class="nav-link' . $active . '" href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
        }
        echo '</li>';
    }
    echo '</ul>';
}

/* ── Social Links Mobile ──────────────────────────────────── */
function dpw_psi_social_links_mobile(): void {
    $socials = [
        'facebook'  => 'bi-facebook',
        'twitter'   => 'bi-twitter-x',
        'instagram' => 'bi-instagram',
        'youtube'   => 'bi-youtube',
        'tiktok'    => 'bi-tiktok',
    ];
    echo '<div class="d-flex gap-2 flex-wrap">';
    foreach ( $socials as $key => $icon ) {
        $url = dpw_psi_get_option( 'social_' . $key );
        if ( $url ) {
            echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" class="btn btn-sm btn-outline-light rounded-circle" aria-label="' . esc_attr( ucfirst( $key ) ) . '"><i class="bi ' . esc_attr( $icon ) . '"></i></a>';
        }
    }
    echo '</div>';
}

/* ── Custom Nav Walker ────────────────────────────────────── */
class DPW_PSI_Nav_Walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void {
        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;
        $classes[] = 'nav-item';
        if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current_page_item', $classes, true ) ) {
            $classes[] = 'active';
        }
        $class_names = implode( ' ', array_filter( $classes ) );
        $output .= '<li class="' . esc_attr( $class_names ) . '">';

        $atts = [];
        $atts['class'] = 'nav-link';
        if ( $args->has_children ) {
            $atts['class']      .= ' dropdown-toggle';
            $atts['href']       = '#';
            $atts['role']       = 'button';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded']  = 'false';
        } else {
            $atts['href'] = ! empty( $item->url ) ? $item->url : '#';
        }
        $attributes = '';
        foreach ( $atts as $attr => $val ) {
            if ( ! empty( $val ) ) {
                $attributes .= ' ' . $attr . '="' . esc_attr( $val ) . '"';
            }
        }
        $output .= '<a' . $attributes . '>';
        $output .= esc_html( $item->title );
        $output .= '</a>';
    }

    function start_lvl( &$output, $depth = 0, $args = null ): void {
        $output .= '<ul class="dropdown-menu dpw-dropdown-menu">';
    }

    function end_lvl( &$output, $depth = 0, $args = null ): void {
        $output .= '</ul>';
    }
}
