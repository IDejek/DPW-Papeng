<?php
/**
 * Gallery Archive Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();

 $gallery_cats = get_terms( array(
    'taxonomy'   => 'gallery-category',
    'hide_empty' => true,
) );
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <?php dpw_psi_breadcrumb(); ?>
            <h1><?php esc_html_e( 'Galeri Foto', 'dpw-psi-papeng' ); ?></h1>
        </div>
    </div>
</section>

<!-- Gallery -->
<section class="profile-content">
    <div class="container">
        <?php if ( ! empty( $gallery_cats ) && ! is_wp_error( $gallery_cats ) ) : ?>
            <div class="gallery-filters">
                <button class="gallery-filter-btn active" data-filter="*"><?php esc_html_e( 'Semua', 'dpw-psi-papeng' ); ?></button>
                <?php foreach ( $gallery_cats as $cat ) : ?>
                    <button class="gallery-filter-btn" data-filter=".cat-<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>
            <div class="gallery-masonry" id="galleryMasonry">
                <?php while ( have_posts() ) : the_post(); 
                    $cats = get_the_terms( get_the_ID(), 'gallery-category' );
                    $cat_classes = '';
                    if ( $cats ) {
                        foreach ( $cats as $cat ) {
                            $cat_classes .= ' cat-' . $cat->slug;
                        }
                    }
                    $full_img = get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?: DPW_PSI_URI . '/assets/images/placeholder-gallery.jpg';
                ?>
                    <div class="gallery-item <?php echo esc_attr( $cat_classes ); ?>" data-full="<?php echo esc_url( $full_img ); ?>">
                        <?php the_post_thumbnail( 'dpw-gallery' ); ?>
                        <div class="gallery-item-overlay">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="psi-pagination" style="margin-top: 48px;">
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
                <i class="bi bi-images" style="font-size: 3rem; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                <h3><?php esc_html_e( 'Belum ada galeri', 'dpw-psi-papeng' ); ?></h3>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox-overlay" id="lightbox">
    <button class="lightbox-close" id="lightboxClose"><i class="bi bi-x-lg"></i></button>
    <img src="" alt="Gallery Image" id="lightboxImg">
</div>

<?php get_footer(); ?>
