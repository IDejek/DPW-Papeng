<?php
/**
 * Leadership Section Template Part
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $leaders = get_posts( [
    'post_type'      => 'leadership',
    'posts_per_page' => 3,
    'meta_key'       => '_dpw_is_primary',
    'meta_value'     => '1',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );
?>

<section class="dpw-section dpw-leadership-section" id="dpwLeadership">
    <div class="container">
        <div class="text-center mb-5 dpw-animate-on-scroll">
            <span class="dpw-section-badge"><?php esc_html_e( 'Pimpinan', 'dpw-psi-papeng' ); ?></span>
            <h2 class="dpw-section-title"><?php esc_html_e( 'Pimpinan DPW PSI Papua Pegunungan', 'dpw-psi-papeng' ); ?></h2>
            <p class="dpw-section-desc mx-auto"><?php esc_html_e( 'Mengabdikan diri untuk masyarakat Papua Pegunungan dengan penuh integritas dan dedikasi.', 'dpw-psi-papeng' ); ?></p>
        </div>

        <?php if ( ! empty( $leaders ) ) : ?>
        <div class="row g-4 justify-content-center">
            <?php foreach ( $leaders as $i => $leader ) :
                $position = dpw_psi_get_meta( $leader->ID, 'position', '' );
                $fb       = dpw_psi_get_meta( $leader->ID, 'facebook', '' );
                $ig       = dpw_psi_get_meta( $leader->ID, 'instagram', '' );
                $tw       = dpw_psi_get_meta( $leader->ID, 'twitter', '' );
                $photo    = has_post_thumbnail( $leader ) ? get_the_post_thumbnail_url( $leader, 'leader-card' ) : dpw_psi_placeholder( 400, 500 );
            ?>
            <div class="col-lg-4 col-md-6 dpw-animate-on-scroll" data-delay="<?php echo $i * 150; ?>">
                <div class="dpw-leader-card card h-100 border-0 shadow-lg">
                    <div class="dpw-leader-img-wrapper">
                        <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( get_the_title( $leader ) ); ?>" class="card-img-top" loading="lazy">
                        <div class="dpw-leader-overlay">
                            <a href="<?php echo esc_url( get_permalink( $leader ) ); ?>" class="btn btn-danger btn-sm fw-bold">
                                <i class="bi bi-person me-1"></i><?php esc_html_e( 'Profil Lengkap', 'dpw-psi-papeng' ); ?>
                            </a>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-1"><?php echo esc_html( get_the_title( $leader ) ); ?></h5>
                        <p class="text-danger fw-semibold small mb-2"><?php echo esc_html( $position ); ?></p>
                        <div class="dpw-leader-social">
                            <?php if ( $fb ) : ?><a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a><?php endif; ?>
                            <?php if ( $ig ) : ?><a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a><?php endif; ?>
                            <?php if ( $tw ) : ?><a href="<?php echo esc_url( $tw ); ?>" target="_blank" rel="noopener" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-people fs-1 d-block mb-3"></i>
            <p><?php esc_html_e( 'Data pimpinan belum tersedia.', 'dpw-psi-papeng' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>
