<?php
/**
 * Video Archive Template
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
            <h1><?php esc_html_e( 'Video Kegiatan', 'dpw-psi-papeng' ); ?></h1>
            <p><?php esc_html_e( 'Dokumentasi visual kegiatan dan program kerja DPW PSI Papua Pegunungan.', 'dpw-psi-papeng' ); ?></p>
        </div>
    </div>
</section>

<!-- Video Archive -->
<section class="profile-content">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="videos-archive-grid">
                <?php while ( have_posts() ) : the_post(); 
                    $video_url = get_post_meta( get_the_ID(), '_psi_video_url', true );
                    $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'dpw-news' ) ?: DPW_PSI_URI . '/assets/images/placeholder-video.jpg';
                ?>
                    <div class="video-archive-card psi-animate" data-video-url="<?php echo esc_attr( $video_url ); ?>">
                        <div class="video-card-thumbnail">
                            <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <div class="video-play-btn"><i class="bi bi-play-fill"></i></div>
                        </div>
                        <div class="video-card-body">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="video-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            <div class="video-meta"><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="psi-pagination">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<i class="bi bi-chevron-left"></i>',
                    'next_text' => '<i class="bi bi-chevron-right"></i>',
                ) );
                ?>
            </div>
        <?php else : ?>
            <div style="text-align: center; padding: 60px 0;">
                <i class="bi bi-camera-video" style="font-size: 3rem; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                <h3><?php esc_html_e( 'Belum ada video', 'dpw-psi-papeng' ); ?></h3>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Video Modal -->
<div class="video-modal-overlay" id="videoModal">
    <div class="video-modal-content">
        <button class="video-modal-close" id="videoModalClose"><i class="bi bi-x-lg"></i></button>
        <iframe id="videoModalIframe" src="" allowfullscreen allow="autoplay; encrypted-media"></iframe>
    </div>
</div>

<?php get_footer(); ?>
