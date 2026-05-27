<?php
/**
 * Template Name: Struktur Organisasi
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $all_leaders = get_posts( [
    'post_type'      => 'leadership',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );
?>

<section class="dpw-page-header">
    <div class="container">
        <div class="dpw-page-header-inner dpw-animate-on-scroll">
            <?php dpw_psi_breadcrumbs(); ?>
            <h1 class="dpw-page-title"><?php echo esc_html( get_the_title() ); ?></h1>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <?php if ( ! empty( $all_leaders ) ) : ?>
        <!-- Primary Leaders -->
        <?php
        $primary = array_filter( $all_leaders, function( $p ) {
            return dpw_psi_get_meta( $p->ID, 'is_primary', '0' ) === '1';
        } );
        $others  = array_filter( $all_leaders, function( $p ) {
            return dpw_psi_get_meta( $p->ID, 'is_primary', '0' ) !== '1';
        } );
        ?>

        <?php if ( ! empty( $primary ) ) : ?>
        <h3 class="fw-bold text-center mb-4 dpw-animate-on-scroll"><?php esc_html_e( 'Pimpinan Utama', 'dpw-psi-papeng' ); ?></h3>
        <div class="row g-4 justify-content-center mb-5">
            <?php foreach ( $primary as $i => $pl ) :
                $pos  = dpw_psi_get_meta( $pl->ID, 'position', '' );
                $pimg = has_post_thumbnail( $pl ) ? get_the_post_thumbnail_url( $pl, 'leader-card' ) : dpw_psi_placeholder( 400, 500 );
            ?>
            <div class="col-lg-4 col-md-6 dpw-animate-on-scroll" data-delay="<?php echo $i * 150; ?>">
                <a href="<?php echo esc_url( get_permalink( $pl ) ); ?>" class="text-decoration-none">
                    <div class="dpw-leader-card card h-100 border-0 shadow-lg">
                        <div class="dpw-leader-img-wrapper">
                            <img src="<?php echo esc_url( $pimg ); ?>" alt="<?php echo esc_attr( get_the_title( $pl ) ); ?>" class="card-img-top" loading="lazy">
                        </div>
                        <div class="card-body text-center">
                            <h5 class="fw-bold text-dark mb-1"><?php echo esc_html( get_the_title( $pl ) ); ?></h5>
                            <p class="text-danger fw-semibold small mb-0"><?php echo esc_html( $pos ); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ( ! empty( $others ) ) : ?>
        <h3 class="fw-bold text-center mb-4 dpw-animate-on-scroll"><?php esc_html_e( 'Pengurus Lainnya', 'dpw-psi-papeng' ); ?></h3>
        <div class="row g-4">
            <?php foreach ( $others as $i => $ol ) :
                $pos  = dpw_psi_get_meta( $ol->ID, 'position', '' );
                $oimg = has_post_thumbnail( $ol ) ? get_the_post_thumbnail_url( $ol, 'leader-card' ) : dpw_psi_placeholder( 400, 500 );
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 dpw-animate-on-scroll" data-delay="<?php echo ($i % 4) * 100; ?>">
                <a href="<?php echo esc_url( get_permalink( $ol ) ); ?>" class="text-decoration-none">
                    <div class="dpw-leader-card card h-100 border-0 shadow-sm">
                        <div class="dpw-leader-img-wrapper">
                            <img src="<?php echo esc_url( $oimg ); ?>" alt="<?php echo esc_attr( get_the_title( $ol ) ); ?>" class="card-img-top" loading="lazy">
                        </div>
                        <div class="card-body text-center">
                            <h6 class="fw-bold text-dark mb-1 small"><?php echo esc_html( get_the_title( $ol ) ); ?></h6>
                            <p class="text-danger small mb-0"><?php echo esc_html( $pos ); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-people fs-1 d-block mb-3"></i>
            <p><?php esc_html_e( 'Data struktur organisasi belum tersedia.', 'dpw-psi-papeng' ); ?></p>
        </div>
        <?php endif; ?>

        <!-- Page Content if any -->
        <?php while ( have_posts() ) : the_post();
            $content = get_the_content();
            if ( ! empty( trim( $content ) ) ) :
        ?>
        <div class="mt-5 pt-4 border-top dpw-page-content lh-lg dpw-animate-on-scroll">
            <?php echo dpw_psi_safe_html( $content ); ?>
        </div>
        <?php endif; endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
