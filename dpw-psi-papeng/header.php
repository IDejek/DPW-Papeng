<?php
/**
 * Theme Header
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo esc_attr( is_singular() ? wp_trim_words( get_the_excerpt(), 30 ) : get_bloginfo( 'description' ) ); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Topbar -->
<div class="psi-topbar">
    <div class="container">
        <div class="topbar-clocks">
            <div class="topbar-clock">
                <span class="clock-label">WIT</span>
                <span class="clock-time" id="clock-wit">--:--:--</span>
                <span class="clock-date" id="date-wit"></span>
            </div>
            <div class="topbar-divider"></div>
            <div class="topbar-clock">
                <span class="clock-label">WIB</span>
                <span class="clock-time" id="clock-wib">--:--:--</span>
                <span class="clock-date" id="date-wib"></span>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-social">
                <?php
                $social_links = get_option( 'dpw_psi_social', array() );
                if ( ! empty( $social_links['facebook'] ) ) : ?>
                    <a href="<?php echo esc_url( $social_links['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <?php endif; ?>
                <?php if ( ! empty( $social_links['instagram'] ) ) : ?>
                    <a href="<?php echo esc_url( $social_links['instagram'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <?php endif; ?>
                <?php if ( ! empty( $social_links['youtube'] ) ) : ?>
                    <a href="<?php echo esc_url( $social_links['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                <?php endif; ?>
                <?php if ( ! empty( $social_links['tiktok'] ) ) : ?>
                    <a href="<?php echo esc_url( $social_links['tiktok'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="psi-navbar" id="psiNavbar">
    <div class="container">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <img src="<?php echo esc_url( DPW_PSI_URI . '/assets/images/logo.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
            <?php endif; ?>
            <div class="navbar-brand-text">
                <span class="brand-name">DPW PSI</span>
                <span class="brand-sub">Papua Pegunungan</span>
            </div>
        </a>

        <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle Menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <?php
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'navbar-menu',
            'menu_id'        => 'navbarMenu',
            'walker'         => new DPW_PSI_Nav_Walker(),
            'fallback_cb'    => false,
        ) );
        ?>

        <div class="navbar-cta">
            <a href="https://psi.id/menjadi-anggota" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-person-plus"></i> Daftar Anggota
            </a>
        </div>
    </div>
</nav>

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>
