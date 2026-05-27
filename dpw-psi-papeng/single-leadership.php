<?php
/**
 * Single Leadership Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $position = dpw_psi_get_meta( get_the_ID(), 'position', '' );
 $fb       = dpw_psi_get_meta( get_the_ID(), 'facebook', '' );
 $ig       = dpw_psi_get_meta( get_the_ID(), 'instagram', '' );
 $tw       = dpw_psi_get_meta( get_the_ID(), 'twitter', '' );
 $photo    = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'leader-card' ) : dpw_psi_placeholder( 400, 500 );
?>

<section class="dpw-page-header">
    <div class="container">
        <div class="dpw-page-header-inner dpw-animate-on-scroll">
            <?php dpw_psi_breadcrumbs(); ?>
            <h1 class="dpw-page-title"><?php echo esc_html( get_the_title() ); ?></h1>
            <p class="text-white-50 mb-0"><?php echo esc_html( $position ); ?></p>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 dpw-animate-on-scroll">
                <div class="dpw-leader-profile-card card border-0 shadow-lg overflow-hidden">
                    <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-100" loading="lazy">
                    <div class="card-body text-center p-4">
                        <h4 class="fw-bold mb-1"><?php echo esc_html( get_the_title() ); ?></h4>
                        <p class="text-danger fw-semibold"><?php echo esc_html( $position ); ?></p>
                        <div class="dpw-leader-social d-flex justify-content-center gap-2 mt-3">
                            <?php if ( $fb ) : ?><a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-circle"><i class="bi bi-facebook"></i></a><?php endif; ?>
                            <?php if ( $ig ) : ?><a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-circle"><i class="bi bi-instagram"></i></a><?php endif; ?>
                            <?php if ( $tw ) : ?><a href="<?php echo esc_url( $tw ); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-circle"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 dpw-animate-on-scroll" data-delay="200">
                <div class="dpw-leader-bio lh-lg">
                    <?php while ( have_posts() ) : the_post();
                        the_content();
                    endwhile; ?>
                </div> 
              <?php dpw_psi_share_buttons(); ?>
            </div>
        </div>
    </div>
</section>

<!-- Other Leaders -->
<?php
 $other_leaders = get_posts( [
    'post_type'      => 'leadership',
    'posts_per_page' => 3,
    'post__not_in'   => [ get_the_ID() ],
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );
if ( ! empty( $other_leaders ) ) :
?>
<section class="dpw-section bg-light">
    <div class="container">
        <h3 class="fw-bold text-center mb-4"><?php esc_html_e( 'Pimpinan Lainnya', 'dpw-psi-papeng' ); ?></h3>
        <div class="row g-4">
            <?php foreach ( $other_leaders as $ol ) :
                $ol_pos  = dpw_psi_get_meta( $ol->ID, 'position', '' );
                $ol_img  = has_post_thumbnail( $ol ) ? get_the_post_thumbnail_url( $ol, 'leader-card' ) : dpw_psi_placeholder( 400, 500 );
            ?>
            <div class="col-lg-4 col-md-6">
                <a href="<?php echo esc_url( get_permalink( $ol ) ); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <img src="<?php echo esc_url( $ol_img ); ?>" alt="<?php echo esc_attr( get_the_title( $ol ) ); ?>" class="card-img-top" loading="lazy">
                        <div class="card-body text-center">
                            <h6 class="fw-bold text-dark"><?php echo esc_html( get_the_title( $ol ) ); ?></h6>
                            <p class="text-danger small mb-0"><?php echo esc_html( $ol_pos ); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
