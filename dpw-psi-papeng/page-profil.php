<?php
/**
 * Template Name: Profil
 * Description: Halaman profil induk untuk sub-halaman Sejarah, Visi Misi, dll.
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
        </div>
    </div>
</section>

<section class="dpw-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="dpw-page-content lh-lg dpw-animate-on-scroll">
                    <?php while ( have_posts() ) : the_post();
                        the_content();
                        wp_link_pages( [ 'before' => '<div class="page-links">', 'after' => '</div>' ] );
                    endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
