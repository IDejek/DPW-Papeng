<?php
/**
 * Search Results Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<section class="dpw-page-header">
    <div class="container">
        <div class="dpw-page-header-inner dpw-animate-on-scroll">
            <?php dpw_psi_breadcrumbs(); ?>
            <h1 class="dpw-page-title">
                <?php printf( esc_html__( 'Hasil Pencarian: %s', 'dpw-psi-papeng' ), '<span class="text-danger">' . esc_html( get_search_query() ) . '</span>' ); ?>
            </h1>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
        <p class="text-muted mb-4"><?php printf( esc_html__( 'Ditemukan %d hasil', 'dpw-psi-papeng' ), absint( $GLOBALS['wp_query']->found_posts ) ); ?></p>
        <div class="row g-4">
            <?php while ( have_posts() ) : the_post();
                $img = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'news-card' ) : dpw_psi_placeholder( 600, 400 );
            ?>
            <div class="col-lg-4 col-md-6 dpw-animate-on-scroll">
                <article class="card h-100 border-0 shadow-sm overflow-hidden">
                    <a href="<?php the_permalink(); ?>" class="d-block">
                        <div class="dpw-news-img-wrapper">
                            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="card-img-top" loading="lazy">
                        </div>
                    </a>
                    <div class="card-body p-3">
                        <h5 class="card-title fw-bold fs-6">
                            <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none"><?php echo esc_html( get_the_title() ); ?></a>
                        </h5>
                        <?php if ( has_excerpt() ) : ?>
                        <p class="card-text text-muted small"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <?php endif; ?>
                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?php echo get_the_date( 'd M Y' ); ?></small>
                    </div>
                </article>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-5"><?php dpw_psi_pagination(); ?></div>
        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-search fs-1 d-block mb-3"></i>
            <p><?php esc_html_e( 'Tidak ada hasil yang ditemukan untuk pencarian tersebut.', 'dpw-psi-papeng' ); ?></p>
            <?php get_search_form(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
