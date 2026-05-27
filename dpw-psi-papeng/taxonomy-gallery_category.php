<?php
/**
 * Gallery Category Archive
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
            <?php if ( $term->description ) : ?>
            <p class="text-white-50 mb-0"><?php echo esc_html( $term->description ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <!-- Category Filter -->
        <div class="dpw-gallery-filters mb-4 dpw-animate-on-scroll">
            <?php
            $gallery_cats = get_terms( [ 'taxonomy' => 'gallery_category', 'hide_empty' => true ] );
            if ( ! empty( $gallery_cats ) && ! is_wp_error( $gallery_cats ) ) :
            ?>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'gallery' ) ); ?>" class="btn btn-sm <?php echo ! is_tax() ? 'btn-danger' : 'btn-outline-danger'; ?> fw-bold"><?php esc_html_e( 'Semua', 'dpw-psi-papeng' ); ?></a>
                <?php foreach ( $gallery_cats as $gc ) : ?>
                <a href="<?php echo esc_url( get_term_link( $gc ) ); ?>" class="btn btn-sm <?php echo ( is_tax( 'gallery_category', $gc->term_id ) ) ? 'btn-danger' : 'btn-outline-danger'; ?> fw-bold"><?php echo esc_html( $gc->name ); ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if ( have_posts() ) : ?>
        <div class="row g-3 dpw-gallery-masonry">
            <?php while ( have_posts() ) : the_post();
                $gimg = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'gallery-masonry' ) : dpw_psi_placeholder( 600, 600 );
            ?>
            <div class="col-6 col-md-4 col-lg-3 dpw-animate-on-scroll">
                <a href="<?php echo esc_url( $gimg ); ?>" class="dpw-gallery-item d-block rounded-3 overflow-hidden shadow-sm" data-lightbox="gallery" data-title="<?php echo esc_attr( get_the_title() ); ?>">
                    <img src="<?php echo esc_url( $gimg ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-100" style="height:220px;object-fit:cover" loading="lazy">
                    <div class="dpw-gallery-overlay">
                        <i class="bi bi-zoom-in"></i>
                        <span class="small"><?php echo esc_html( get_the_title() ); ?></span>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-5"><?php dpw_psi_pagination(); ?></div>
        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-images fs-1 d-block mb-3"></i>
            <p><?php esc_html_e( 'Belum ada galeri.', 'dpw-psi-papeng' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade dpw-lightbox-modal" id="dpwLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 p-1">
                <h6 class="modal-title text-white small" id="dpwLightboxTitle"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="" alt="" class="img-fluid rounded-3" id="dpwLightboxImg" style="max-height:80vh">
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
