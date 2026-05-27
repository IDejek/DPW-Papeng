<?php
/**
 * Default Page Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<section class="dpw-page-header">
    <div class="container">
        <div class="dpw-page-header-inner dpw-animate-on-scroll">
            <h1 class="dpw-page-title"><?php echo esc_html( get_the_title() ); ?></h1>
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php dpw_psi_breadcrumbs(); ?>
                <div class="dpw-page-content">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        the_content();
                        wp_link_pages( [
                            'before' => '<div class="page-links">',
                            'after'  => '</div>',
                        ] );
                    endwhile;
                    ?>
                </div>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
