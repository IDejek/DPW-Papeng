<?php
/**
 * Sidebar Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
?>

<aside class="col-lg-4 dpw-sidebar">
    <!-- Search Widget -->
    <div class="dpw-sidebar-widget mb-4">
        <?php get_search_form(); ?>
    </div>

    <!-- Recent Posts -->
    <div class="dpw-sidebar-widget card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 pb-2 border-bottom border-danger"><?php esc_html_e( 'Berita Terbaru', 'dpw-psi-papeng' ); ?></h5>
            <?php
            $recent = get_posts( [
                'post_type'      => 'post',
                'posts_per_page' => 5,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ] );
            if ( ! empty( $recent ) ) :
                foreach ( $recent as $rp ) :
                    $rimg = has_post_thumbnail( $rp ) ? get_the_post_thumbnail_url( $rp, 'thumbnail' ) : '';
            ?>
            <div class="dpw-recent-post d-flex gap-3 mb-3 pb-3 border-bottom">
                <?php if ( $rimg ) : ?>
                <a href="<?php echo esc_url( get_permalink( $rp ) ); ?>" class="flex-shrink-0">
                    <img src="<?php echo esc_url( $rimg ); ?>" alt="<?php echo esc_attr( get_the_title( $rp ) ); ?>" class="rounded-2" width="70" height="70" style="object-fit:cover" loading="lazy">
                </a>
                <?php endif; ?>
                <div>
                    <h6 class="small fw-bold mb-1"><a href="<?php echo esc_url( get_permalink( $rp ) ); ?>" class="text-dark text-decoration-none"><?php echo esc_html( wp_trim_words( get_the_title( $rp ), 8 ) ); ?></a></h6>
                    <small class="text-muted"><?php echo get_the_date( 'd M Y', $rp ); ?></small>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- Categories -->
    <div class="dpw-sidebar-widget card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 pb-2 border-bottom border-danger"><?php esc_html_e( 'Kategori', 'dpw-psi-papeng' ); ?></h5>
            <ul class="list-unstyled dpw-category-list">
                <?php
                $cats = get_categories( [
                    'taxonomy'   => 'category',
                    'hide_empty' => true,
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                ] );
                foreach ( $cats as $cat ) :
                ?>
                <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="text-dark text-decoration-none small"><?php echo esc_html( $cat->name ); ?></a>
                    <span class="badge bg-danger rounded-pill"><?php echo absint( $cat->count ); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Dynamic Sidebar -->
    <?php if ( is_active_sidebar( 'sidebar-main' ) ) : dynamic_sidebar( 'sidebar-main' ); endif; ?>
</aside>
