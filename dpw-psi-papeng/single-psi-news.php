<?php
/**
 * Single News Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<!-- Reading Progress Bar -->
<div class="reading-progress" id="readingProgress"></div>

<!-- Page Header -->
<section class="page-header" style="padding: 60px 0 40px;">
    <div class="container">
        <div class="page-header-content">
            <?php dpw_psi_breadcrumb(); ?>
        </div>
    </div>
</section>

<!-- Single Article -->
<section class="single-article">
    <div class="container">
        <div class="article-layout">
            <main class="article-main">
                <?php while ( have_posts() ) : the_post(); 
                    $cats = get_the_terms( get_the_ID(), 'news-category' );
                    $cat_name = $cats ? $cats[0]->name : '';
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="article-header">
                            <?php if ( $cat_name ) : ?>
                                <span class="article-category"><?php echo esc_html( $cat_name ); ?></span>
                            <?php endif; ?>
                            <h1><?php the_title(); ?></h1>
                            <div class="article-meta">
                                <div class="article-meta-item">
                                    <i class="bi bi-person-circle"></i> <?php the_author(); ?>
                                </div>
                                <div class="article-meta-item">
                                    <i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?>
                                </div>
                                <div class="article-meta-item">
                                    <i class="bi bi-clock"></i> <?php echo esc_html( dpw_psi_reading_time() ); ?>
                                </div>
                            </div>
                        </div>

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="article-featured-image">
                                <?php the_post_thumbnail( 'dpw-hero' ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="article-body">
                            <?php the_content(); ?>
                        </div>

                        <!-- Share Buttons -->
                        <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid #E5E7EB;">
                            <h4 style="margin-bottom: 16px; font-size: 1rem;"><?php esc_html_e( 'Bagikan Artikel Ini:', 'dpw-psi-papeng' ); ?></h4>
                            <div style="display: flex; gap: 10px;">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background:#1877F2;color:#fff;"><i class="bi bi-facebook"></i> Facebook</a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url( get_permalink() ); ?>&text=<?php echo esc_attr( get_the_title() ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background:#000;color:#fff;"><i class="bi bi-twitter-x"></i> Twitter</a>
                                <a href="https://wa.me/?text=<?php echo esc_attr( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background:#25D366;color:#fff;"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                            </div>
                        </div>
                    </article>

                    <!-- Related Posts -->
                    <?php
                    $related = new WP_Query( array(
                        'post_type'      => 'psi-news',
                        'posts_per_page' => 3,
                        'post__not_in'   => array( get_the_ID() ),
                        'post_status'    => 'publish',
                        'orderby'        => 'rand',
                    ) );
                    if ( $related->have_posts() ) :
                    ?>
                    <div style="margin-top: 60px;">
                        <h3 style="margin-bottom: 24px; font-size: 1.3rem;"><?php esc_html_e( 'Berita Terkait', 'dpw-psi-papeng' ); ?></h3>
                        <div class="news-archive-grid" style="grid-template-columns: repeat(3, 1fr);">
                            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                <div class="news-card">
                                    <div class="news-card-image">
                                        <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'dpw-news-thumb' ) ?: DPW_PSI_URI . '/assets/images/placeholder-news.jpg' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                                    </div>
                                    <div class="news-card-body">
                                        <div class="news-meta"><span><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span></div>
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    </div>
                                </div>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Comments -->
                    <?php if ( comments_open() || get_comments_number() ) : ?>
                        <div style="margin-top: 60px;">
                            <?php comments_template(); ?>
                        </div>
                    <?php endif; ?>

                <?php endwhile; ?>
            </main>

            <aside class="article-sidebar">
                <?php if ( is_active_sidebar( 'article-sidebar' ) ) : ?>
                    <?php dynamic_sidebar( 'article-sidebar' ); ?>
                <?php else : ?>
                    <!-- Default Recent Posts Widget -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title"><?php esc_html_e( 'Berita Terbaru', 'dpw-psi-papeng' ); ?></h4>
                        <?php
                        $recent = new WP_Query( array(
                            'post_type'      => 'psi-news',
                            'posts_per_page' => 5,
                            'post__not_in'   => array( get_the_ID() ),
                            'post_status'    => 'publish',
                        ) );
                        if ( $recent->have_posts() ) :
                            while ( $recent->have_posts() ) : $recent->the_post(); ?>
                                <div class="sidebar-recent-post">
                                    <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ?: DPW_PSI_URI . '/assets/images/placeholder-news.jpg' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                                    <div>
                                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                        <span class="post-date"><?php echo get_the_date(); ?></span>
                                    </div>
                                </div>
                            <?php endwhile; wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>

<?php get_footer(); ?>
