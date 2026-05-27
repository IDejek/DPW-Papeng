<?php
/**
 * DPD Section Template Part
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $dpd_items = get_posts( [
    'post_type'      => 'dpd',
    'posts_per_page' => 4,
    'orderby'        => 'title',
    'order'          => 'ASC',
] );
?>

<?php if ( ! empty( $dpd_items ) ) : ?>
<section class="dpw-section dpw-dpd-section" id="dpwDPD">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5 dpw-animate-on-scroll">
            <div>
                <span class="dpw-section-badge"><?php esc_html_e( 'DPD', 'dpw-psi-papeng' ); ?></span>
                <h2 class="dpw-section-title mb-0"><?php esc_html_e( 'DPD Kabupaten', 'dpw-psi-papeng' ); ?></h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/dpd-kabupaten/' ) ); ?>" class="btn btn-outline-danger btn-sm fw-bold d-none d-md-inline-flex">
                <?php esc_html_e( 'Lihat Semua', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            <?php foreach ( $dpd_items as $i => $dpd ) :
                $kab   = dpw_psi_get_meta( $dpd->ID, 'kabupaten', get_the_title( $dpd ) );
                $ketua = dpw_psi_get_meta( $dpd->ID, 'ketua_name', '' );
                $photo = dpw_psi_get_meta( $dpd->ID, 'ketua_photo', '' );
                if ( ! $photo && has_post_thumbnail( $dpd ) ) $photo = get_the_post_thumbnail_url( $dpd, 'dpd-card' );
                if ( ! $photo ) $photo = dpw_psi_placeholder( 500, 600 );
            ?>
            <div class="col-lg-3 col-md-6 dpw-animate-on-scroll" data-delay="<?php echo $i * 100; ?>">
                <a href="<?php echo esc_url( get_permalink( $dpd ) ); ?>" class="text-decoration-none">
                    <div class="dpw-dpd-card card h-100 border-0 shadow-lg overflow-hidden">
                        <div class="dpw-dpd-img-wrapper">
                            <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $ketua ); ?>" class="card-img-top" loading="lazy">
                        </div>
                        <div class="card-body text-center">
                            <span class="badge bg-danger mb-2"><?php echo esc_html( $kab ); ?></span>
                            <h6 class="fw-bold text-dark mb-1"><?php echo esc_html( $ketua ); ?></h6>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="<?php echo esc_url( home_url( '/dpd-kabupaten/' ) ); ?>" class="btn btn-outline-danger fw-bold">
                <?php esc_html_e( 'Lihat Semua DPD', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>
