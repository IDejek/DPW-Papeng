<?php
/**
 * Video Section Template Part
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $videos = get_posts( [
    'post_type'      => 'video',
    'posts_per_page' => 3,
    'meta_key'       => '_dpw_is_featured',
    'meta_value'     => '1',
    'orderby'        => 'date',
    'order'          => 'DESC',
] );
if ( empty( $videos ) ) {
    $videos = get_posts( [
        'post_type'      => 'video',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
}
?>

<?php if ( ! empty( $videos ) ) : ?>
<section class="dpw-section dpw-video-section bg-dark text-white" id="dpwVideos">
    <div class="container">
        <div class="text-center mb-5 dpw-animate-on-scroll">
            <span class="dpw-section-badge badge bg-danger"><?php esc_html_e( 'Video', 'dpw-psi-papeng' ); ?></span>
            <h2 class="dpw-section-title text-white"><?php esc_html_e( 'Kegiatan Video', 'dpw-psi-papeng' ); ?></h2>
        </div>
        <div class="row g-4">
            <?php foreach ( $videos as $i => $vid ) :
                $yt_url = dpw_psi_get_meta( $vid->ID, 'video_url', '' );
                $yt_id  = dpw_psi_youtube_id( $yt_url );
                $thumb  = has_post_thumbnail( $vid ) ? get_the_post_thumbnail_url( $vid, 'news-card' ) : ( $yt_id ? 'https://img.youtube.com/vi/' . $yt_id . '/hqdefault.jpg' : dpw_psi_placeholder( 600, 400 ) );
            ?>
            <div class="col-lg-4 col-md-6 dpw-animate-on-scroll" data-delay="<?php echo $i * 150; ?>">
                <div class="dpw-video-card card h-100 border-0 bg-transparent">
                    <div class="dpw-video-thumb-wrapper" data-video-url="<?php echo esc_url( $yt_url ); ?>" role="button" tabindex="0" aria-label="<?php echo esc_attr( get_the_title( $vid ) ); ?>">
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title( $vid ) ); ?>" class="card-img-top" loading="lazy">
                        <div class="dpw-video-play-btn">
                            <i class="bi bi-play-circle-fill"></i>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <h6 class="fw-bold text-white"><?php echo esc_html( get_the_title( $vid ) ); ?></h6>
                        <small class="text-white-50"><i class="bi bi-calendar3 me-1"></i><?php echo get_the_date( 'd M Y', $vid ); ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo esc_url( home_url( '/video/' ) ); ?>" class="btn btn-outline-danger fw-bold">
                <?php esc_html_e( 'Lihat Semua Video', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Video Modal -->
<div class="modal fade dpw-video-modal" id="dpwVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-black border-0 rounded-3 overflow-hidden">
            <div class="modal-header border-0 p-2">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Tutup', 'dpw-psi-papeng' ); ?>"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9" id="dpwVideoEmbed"></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
