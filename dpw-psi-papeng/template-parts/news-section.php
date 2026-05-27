<?php
/**
 * News Section Template Part
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $news = get_posts( [
    'post_type'      => 'post',
    'posts_per_page' => 4,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );
?>

<section class="dpw-section dpw-news-section" id="dpwNews">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5 dpw-animate-on-scroll">
            <div>
                <span class="dpw-section-badge"><?php esc_html_e( 'Berita', 'dpw-psi-papeng' ); ?></span>
                <h2 class="dpw-section-title mb-0"><?php esc_html_e( 'Berita Terkini', 'dpw-psi-papeng' ); ?></h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/category/berita/' ) ); ?>" class="btn btn-outline-danger btn-sm fw-bold d-none d-md-inline-flex">
                <?php esc_html_e( 'Lihat Semua', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <?php if ( ! empty( $news ) ) : ?>
        <div class="row g-4">
            <?php foreach ( $news as $i => $post ) :
                setup_postdata( $post );
                $categories = get_the_category();
                $cat_name   = ! empty( $categories ) ? $categories[0]->name : '';
                $cat_link   = ! empty( $categories ) ? get_category_link( $categories[0]->term_id ) : '';
                $img        = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'news-card' ) : dpw_psi_placeholder( 600, 400 );
                $is_first   = $i === 0;
            ?>
            <div class="<?php echo $is_first ? 'col-lg-6' : 'col-lg-3 col-md-6'; ?> dpw-animate-on-scroll" data-delay="<?php echo $i * 100; ?>">
                <article class="dpw-news-card card h-100 border-0 shadow-sm overflow-hidden">
                    <a href="<?php the_permalink(); ?>" class="dpw-news-img-link d-block">
                        <div class="dpw-news-img-wrapper <?php echo $is_first ? 'dpw-news-img-lg' : ''; ?>">
                            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="card-img-top" loading="lazy">
                        </div>
                    </a>
                    <div class="card-body p-3">
                        <?php if ( $cat_name ) : ?>
                        <a href="<?php echo esc_url( $cat_link ); ?>" class="dpw-news-cat badge bg-danger text-decoration-none mb-2"><?php echo esc_html( $cat_name ); ?></a>
                        <?php endif; ?>
                        <h5 class="card-title fw-bold <?php echo $is_first ? 'fs-5' : 'fs-6'; ?>">
                            <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none dpw-news-title-link"><?php echo esc_html( get_the_title() ); ?></a>
                        </h5>
                        <p class="card-text text-muted small"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <div class="dpw-news-meta d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?php echo get_the_date( 'd M Y' ); ?></small>
                            <a href="<?php the_permalink(); ?>" class="text-danger small fw-bold text-decoration-none"><?php esc_html_e( 'Baca', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="<?php echo esc_url( home_url( '/category/berita/' ) ); ?>" class="btn btn-outline-danger fw-bold">
                <?php esc_html_e( 'Lihat Semua Berita', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-newspaper fs-1 d-block mb-3"></i>
            <p><?php esc_html_e( 'Belum ada berita.', 'dpw-psi-papeng' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>
