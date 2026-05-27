<?php
/**
 * Video Category Archive
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $term = get_queried_object();
?>

<section class="dpw-page-header">
    <div class="container">
        <div class="dpw-page-header-inner dpw-animate-on-scroll">
            <?php dpw_psi_breadcrumbs(); ?>
            <h1 class="dpw-page-title"><?php echo esc_html( $term->name ); ?></h1>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
        <div class="row g-4">
            <?php while ( have_posts() ) : the_post();
                $yt_url = dpw_psi_get_meta( get_the_ID(), 'video_url', '' );
                $yt_id  = dpw_psi_youtube_id( $yt_url );
                $thumb  = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'news-card' ) : ( $yt_id ? 'https://img.youtube.com/vi/' . $yt_id . '/hqdefault.jpg' : dpw_psi_placeholder( 600, 400 ) );
            ?>
            <div class="col-lg-4 col-md-6 dpw-animate-on-scroll">
                <div class="dpw-video-card card h-100 border-0 shadow-sm overflow-hidden">
                    <div class="dpw-video-thumb-wrapper" data-video-url="<?php echo esc_url( $yt_url ); ?>" role="button" tabindex="0">
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="card-img-top" loading="lazy">
                        <div class="dpw-video-play-btn"><i class="bi bi-play-circle-fill"></i></div>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-1"><a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none"><?php echo esc_html( get_the_title() ); ?></a></h6>
                        <p class="text-muted small mb-1"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?php echo get_the_date( 'd M Y' ); ?></small>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-5"><?php dpw_psi_pagination(); ?></div>
        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-video fs-1 d-block mb-3"></i>
            <p><?php esc_html_e( 'Belum ada video.', 'dpw-psi-papeng' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
