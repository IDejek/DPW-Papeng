<?php
/**
 * Welcome Section Template Part
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $title   = dpw_psi_get_option( 'welcome_title', 'Sambutan Ketua DPW' );
 $text    = dpw_psi_get_option( 'welcome_text', '' );
 $image   = dpw_psi_get_option( 'welcome_image', '' );
if ( ! $text ) $text = 'Selamat datang di website resmi Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan. Kami berkomitmen untuk memperjuangkan keadilan, solidaritas, dan kemajuan bagi seluruh masyarakat Papua Pegunungan.';
?>

<section class="dpw-section dpw-welcome-section" id="dpwWelcome">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 dpw-animate-on-scroll">
                <div class="dpw-welcome-img-wrapper">
                    <?php if ( $image ) : ?>
                        <img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( 'Ketua DPW PSI Papua Pegunungan', 'dpw-psi-papeng' ); ?>" class="dpw-welcome-img" loading="lazy">
                    <?php else : ?>
                        <img src="<?php echo esc_url( dpw_psi_placeholder( 500, 650 ) ); ?>" alt="Ketua DPW" class="dpw-welcome-img" loading="lazy">
                    <?php endif; ?>
                    <div class="dpw-welcome-img-accent"></div>
                </div>
            </div>
            <div class="col-lg-7 dpw-animate-on-scroll" data-delay="200">
                <span class="dpw-section-badge"><?php esc_html_e( 'Sambutan', 'dpw-psi-papeng' ); ?></span>
                <h2 class="dpw-section-title"><?php echo esc_html( $title ); ?></h2>
                <div class="dpw-welcome-text text-muted lh-lg">
                    <?php echo dpw_psi_safe_html( wpautop( $text ) ); ?>
                </div>
                <a href="<?php echo esc_url( home_url( '/pimpinan/' ) ); ?>" class="btn btn-outline-danger fw-bold px-4 mt-3">
                    <?php esc_html_e( 'Lihat Pimpinan', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>
