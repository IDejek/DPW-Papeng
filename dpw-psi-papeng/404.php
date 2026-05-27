<?php
/**
 * 404 Error Page
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<section class="dpw-404-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center dpw-animate-on-scroll">
                <div class="dpw-404-code">404</div>
                <h1 class="fw-bold mb-3"><?php esc_html_e( 'Halaman Tidak Ditemukan', 'dpw-psi-papeng' ); ?></h1>
                <p class="text-muted mb-4 lh-lg"><?php esc_html_e( 'Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan. Silakan gunakan menu navigasi atau tombol di bawah ini.', 'dpw-psi-papeng' ); ?></p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-danger fw-bold px-4">
                        <i class="bi bi-house me-2"></i><?php esc_html_e( 'Ke Beranda', 'dpw-psi-papeng' ); ?>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/kontak/' ) ); ?>" class="btn btn-outline-dark fw-bold px-4">
                        <i class="bi bi-envelope me-2"></i><?php esc_html_e( 'Hubungi Kami', 'dpw-psi-papeng' ); ?>
                    </a>
                </div>
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
