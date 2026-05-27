<?php
/**
 * Default Page Template
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
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="profile-content">
    <div class="container">
        <div class="article-layout">
            <main class="article-main">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="article-featured-image">
                                <?php the_post_thumbnail( 'dpw-hero' ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="article-body">
                            <?php the_content(); ?>
                            <?php
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . esc_html__( 'Halaman:', 'dpw-psi-papeng' ),
                                'after'  => '</div>',
                            ) );
                            ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </main>
            <aside class="article-sidebar">
                <?php if ( is_active_sidebar( 'article-sidebar' ) ) : ?>
                    <?php dynamic_sidebar( 'article-sidebar' ); ?>
                <?php else : ?>
                    <div class="sidebar-widget">
                        <h4 class="widget-title"><?php esc_html_e( 'Tentang PSI', 'dpw-psi-papeng' ); ?></h4>
                        <p style="font-size: 0.9rem; color: #6B7280;"><?php esc_html_e( 'Partai Solidaritas Indonesia adalah partai politik yang berkomitmen pada nilai-nilai solidaritas, keadilan, dan kemanusiaan.', 'dpw-psi-papeng' ); ?></p>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>

<?php get_footer(); ?><?php
/**
 * Default Page Template
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
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="profile-content">
    <div class="container">
        <div class="article-layout">
            <main class="article-main">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="article-featured-image">
                                <?php the_post_thumbnail( 'dpw-hero' ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="article-body">
                            <?php the_content(); ?>
                            <?php
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . esc_html__( 'Halaman:', 'dpw-psi-papeng' ),
                                'after'  => '</div>',
                            ) );
                            ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </main>
            <aside class="article-sidebar">
                <?php if ( is_active_sidebar( 'article-sidebar' ) ) : ?>
                    <?php dynamic_sidebar( 'article-sidebar' ); ?>
                <?php else : ?>
                    <div class="sidebar-widget">
                        <h4 class="widget-title"><?php esc_html_e( 'Tentang PSI', 'dpw-psi-papeng' ); ?></h4>
                        <p style="font-size: 0.9rem; color: #6B7280;"><?php esc_html_e( 'Partai Solidaritas Indonesia adalah partai politik yang berkomitmen pada nilai-nilai solidaritas, keadilan, dan kemanusiaan.', 'dpw-psi-papeng' ); ?></p>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>

<?php get_footer(); ?>
