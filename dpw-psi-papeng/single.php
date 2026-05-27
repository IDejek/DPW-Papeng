<?php
/**
 * Single Post Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<section class="dpw-page-header">
    <div class="container">
        <div class="dpw-page-header-inner dpw-animate-on-scroll">
            <?php dpw_psi_breadcrumbs(); ?>
            <h1 class="dpw-page-title"><?php echo esc_html( get_the_title() ); ?></h1>
            <div class="dpw-post-meta text-white-50">
                <span><i class="bi bi-calendar3 me-1"></i><?php echo get_the_date( 'd F Y' ); ?></span>
                <span class="ms-3"><i class="bi bi-person me-1"></i><?php echo esc_html( get_the_author() ); ?></span>
                <?php if ( has_category() ) : ?>
                <span class="ms-3"><i class="bi bi-folder me-1"></i><?php the_category( ', ' ); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="dpw-single-post dpw-animate-on-scroll">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="dpw-post-featured mb-4">
                        <?php the_post_thumbnail( 'large', [ 'class' => 'w-100 rounded-3 shadow' ] ); ?>
                    </div>
                    <?php endif; ?>

                    <div class="dpw-post-content lh-lg">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            the_content();
                            wp_link_pages( [ 'before' => '<div class="page-links">', 'after' => '</div>' ] );
                        endwhile;
                        ?>
                    </div>

                    <!-- Tags -->
                    <?php if ( has_tag() ) : ?>
                    <div class="dpw-post-tags mt-4 pt-3 border-top">
                        <i class="bi bi-tags text-muted me-2"></i>
                        <?php the_tags( '', '', '' ); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Share -->
                    <div class="dpw-post-share mt-4 pt-3 border-top">
                        <?php dpw_psi_share_buttons(); ?>
                    </div>

                    <!-- Comments -->
                    <?php if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif; ?>
                </article>

                <!-- Related Posts -->
                <?php
                $related = get_posts( [
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'post__not_in'   => [ get_the_ID() ],
                    'category__in'   => wp_list_pluck( get_the_category(), 'term_id' ),
                    'orderby'        => 'rand',
                ] );
                if ( ! empty( $related ) ) :
                ?>
                <div class="dpw-related-posts mt-5 pt-4 border-top">
                    <h4 class="fw-bold mb-4"><?php esc_html_e( 'Berita Terkait', 'dpw-psi-papeng' ); ?></h4>
                    <div class="row g-4">
                        <?php foreach ( $related as $rp ) :
                            $rimg = has_post_thumbnail( $rp ) ? get_the_post_thumbnail_url( $rp, 'news-card' ) : dpw_psi_placeholder( 600, 400 );
                        ?>
                        <div class="col-md-4">
                            <a href="<?php echo esc_url( get_permalink( $rp ) ); ?>" class="text-decoration-none">
                                <div class="card border-0 shadow-sm overflow-hidden h-100">
                                    <img src="<?php echo esc_url( $rimg ); ?>" alt="<?php echo esc_attr( get_the_title( $rp ) ); ?>" class="card-img-top" loading="lazy">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-dark fw-bold small"><?php echo esc_html( get_the_title( $rp ) ); ?></h6>
                                        <small class="text-muted"><?php echo get_the_date( 'd M Y', $rp ); ?></small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
