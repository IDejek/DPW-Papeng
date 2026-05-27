<?php
/**
 * 404 Error Page
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <?php dpw_psi_breadcrumb(); ?>
            <h1><?php esc_html_e( 'Halaman Tidak Ditemukan', 'dpw-psi-papeng' ); ?></h1>
        </div>
    </div>
</section>

<!-- 404 Content -->
<section class="profile-content">
    <div class="container">
        <div style="text-align: center; max-width: 600px; margin: 0 auto; padding: 40px 0;">
            <div style="font-family: 'Poppins', sans-serif; font-size: 8rem; font-weight: 800; background: linear-gradient(135deg, #D6001C, #D4AF37); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1;">404</div>
            <h2 style="margin-top: 16px; margin-bottom: 16px;"><?php esc_html_e( 'Oops! Halaman tidak ditemukan', 'dpw-psi-papeng' ); ?></h2>
            <p><?php esc_html_e( 'Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.', 'dpw-psi-papeng' ); ?></p>
            <div style="margin-top: 32px; display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary btn-lg"><i class="bi bi-house-door"></i> <?php esc_html_e( 'Kembali ke Beranda', 'dpw-psi-papeng' ); ?></a>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'psi-news' ) ); ?>" class="btn btn-outline-dark btn-lg"><?php esc_html_e( 'Lihat Berita', 'dpw-psi-papeng' ); ?></a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
