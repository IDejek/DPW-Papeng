<?php
/**
 * Archive Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $pt = get_post_type_object( get_post_type() );
 $label = $pt ? $pt->labels->name : __( 'Arsip', 'dpw-psi-papeng' );
?>

<section class="dpw-page-header">
    <div class="container">
        <div class="dpw-page-header-inner dpw-animate-on-scroll">
            <h1 class="dpw-page-title"><?php echo esc_html( $label ); ?></h1>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <?php dpw_psi_breadcrumbs(); ?>

        <?php if ( have_posts() ) : ?>
        <div class="row g-4">
            <?php while ( have_posts() ) : the_post();
                $img = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'news-card' ) : dpw_psi_placeholder( 600, 400 );
            ?>
            <div class="col-lg-4 col-md-6 dpw-animate-on-scroll">
                <article class="dpw-news-card card h-100 border-0 shadow-sm overflow-hidden">
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
        <div class="mt-5">
            <?php dpw_psi_pagination(); ?>
        </div>
        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <p><?php esc_html_e( 'Belum ada konten.', 'dpw-psi-papeng' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
