<?php
/**
 * Single DPD Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $kabupaten    = dpw_psi_get_meta( get_the_ID(), 'kabupaten', get_the_title() );
 $ketua_name   = dpw_psi_get_meta( get_the_ID(), 'ketua_name', '' );
 $ketua_photo  = dpw_psi_get_meta( get_the_ID(), 'ketua_photo', '' );
 $member_count = dpw_psi_get_meta( get_the_ID(), 'member_count', '0' );
 $address      = dpw_psi_get_meta( get_the_ID(), 'address', '' );
 $phone        = dpw_psi_get_meta( get_the_ID(), 'phone', '' );

if ( ! $ketua_photo && has_post_thumbnail() ) {
    $ketua_photo = get_the_post_thumbnail_url( get_the_ID(), 'dpd-card' );
}
if ( ! $ketua_photo ) {
    $ketua_photo = dpw_psi_placeholder( 500, 600 );
}
?>

<section class="dpw-page-header">
    <div class="container">
        <div class="dpw-page-header-inner dpw-animate-on-scroll">
            <?php dpw_psi_breadcrumbs(); ?>
            <h1 class="dpw-page-title">DPD PSI <?php echo esc_html( $kabupaten ); ?></h1>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <div class="row g-5">
            <!-- Left: Photo & Info -->
            <div class="col-lg-4 dpw-animate-on-scroll">
                <div class="dpw-dpd-profile-card card border-0 shadow-lg overflow-hidden">
                    <img src="<?php echo esc_url( $ketua_photo ); ?>" alt="<?php echo esc_attr( $ketua_name ); ?>" class="w-100" loading="lazy">
                    <div class="card-body text-center p-4">
                        <span class="badge bg-danger mb-2"><?php echo esc_html( $kabupaten ); ?></span>
                        <h4 class="fw-bold mb-1"><?php echo esc_html( $ketua_name ); ?></h4>
                        <p class="text-muted small mb-3"><?php esc_html_e( 'Ketua DPD', 'dpw-psi-papeng' ); ?></p>
                        <div class="dpw-dpd-stats row g-2 text-center">
                            <div class="col-6">
                                <div class="dpw-stat-box bg-light rounded-3 p-2">
                                    <span class="fw-bold text-danger fs-5 d-block"><?php echo number_format_i18n( absint( $member_count ) ); ?></span>
                                    <small class="text-muted"><?php esc_html_e( 'Anggota', 'dpw-psi-papeng' ); ?></small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="dpw-stat-box bg-light rounded-3 p-2">
                                    <span class="fw-bold text-danger fs-5 d-block">1</span>
                                    <small class="text-muted"><?php esc_html_e( 'Kabupaten', 'dpw-psi-papeng' ); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info Card -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-danger me-2"></i><?php esc_html_e( 'Informasi Kontak', 'dpw-psi-papeng' ); ?></h6>
                        <?php if ( $address ) : ?>
                        <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-2 text-danger"></i><?php echo esc_html( $address ); ?></p>
                        <?php endif; ?>
                        <?php if ( $phone ) : ?>
                        <p class="mb-0">
                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="text-muted small text-decoration-none"><i class="bi bi-telephone me-2 text-danger"></i><?php echo esc_html( $phone ); ?></a>
                        </p>
                        <?php endif; ?>
                        <?php if ( $phone ) : ?>
                        <p class="mt-2 mb-0">
                            <a href="<?php echo esc_url( dpw_psi_wa_link( preg_replace( '/[^0-9]/', '', $phone ), 'Halo, saya ingin bertanya tentang DPD PSI ' . $kabupaten ) ); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-success w-100 fw-bold"><i class="bi bi-whatsapp me-1"></i><?php esc_html_e( 'WhatsApp', 'dpw-psi-papeng' ); ?></a>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right: Content -->
            <div class="col-lg-8 dpw-animate-on-scroll" data-delay="200">
                <div class="dpw-dpd-content lh-lg">
                    <?php while ( have_posts() ) : the_post();
                        the_content();
                    endwhile; ?>
                </div>
                <?php dpw_psi_share_buttons(); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
