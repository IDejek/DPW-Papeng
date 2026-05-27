<?php
/**
 * News Archive Template
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
            <h1><?php esc_html_e( 'Berita & Artikel', 'dpw-psi-papeng' ); ?></h1>
            <p><?php esc_html_e( 'Informasi terbaru seputar kegiatan dan program DPW PSI Papua Pegunungan.', 'dpw-psi-papeng' ); ?></p>
        </div>
    </div>
</section>

<!-- News Archive -->
<section class="profile-content">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="news-archive-grid">
                <?php while ( have_posts() ) : the_post(); 
                    $cats = get_the_terms( get_the_ID(), 'news-category' );
                    $cat_name = $cats ? $cats[0]->name : '';
                ?>
                    <div class="news-card psi-animate">
                        <div class="news-card-image">
                            <?php if ( $cat_name ) : ?>
                                <span class="news-category"><?php echo esc_html( $cat_name ); ?></span>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'dpw-news' ) ?: DPW_PSI_URI . '/assets/images/placeholder-news.jpg' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            </a>
                        </div>
                        <div class="news-card-body">
                            <div class="news-meta">
                                <span><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span>
                                <span><i class="bi bi-person"></i> <?php the_author(); ?></span>
                            </div>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="news-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Baca Selengkapnya', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="psi-pagination">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<i class="bi bi-chevron-left"></i>',
                    'next_text' => '<i class="bi bi-chevron-right"></i>',
                    'class'     => 'page-numbers',
                ) );
                ?>
            </div>
        <?php else : ?>
            <div style="text-align: center; padding: 60px 0;">
                <i class="bi bi-newspaper" style="font-size: 3rem; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                <h3><?php esc_html_e( 'Belum ada berita', 'dpw-psi-papeng' ); ?></h3>
                <p><?php esc_html_e( 'Berita terbaru akan segera tersedia.', 'dpw-psi-papeng' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
