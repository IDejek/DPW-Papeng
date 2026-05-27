<?php
/**
 * Single DPD Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();

 $ketua  = get_post_meta( get_the_ID(), '_psi_dpd_ketua', true );
 $photo  = get_post_meta( get_the_ID(), '_psi_dpd_photo', true );
 $phone  = get_post_meta( get_the_ID(), '_psi_dpd_phone', true );
 $email  = get_post_meta( get_the_ID(), '_psi_dpd_email', true );
 $addr   = get_post_meta( get_the_ID(), '_psi_dpd_address', true );
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

<!-- DPD Detail -->
<section class="profile-content">
    <div class="container">
        <div class="article-layout">
            <main class="article-main">
                <div style="background: #fff; border-radius: 20px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); margin-bottom: 40px;">
                    <div style="display: flex; gap: 32px; align-items: flex-start; flex-wrap: wrap;">
                        <div style="width: 180px; min-width: 180px;">
                            <img src="<?php echo esc_url( $photo ?: DPW_PSI_URI . '/assets/images/placeholder-user.png' ); ?>" alt="<?php echo esc_attr( $ketua ?: get_the_title() ); ?>" style="width: 180px; height: 180px; object-fit: cover; border-radius: 20px; border: 4px solid #D4AF37;">
                        </div>
                        <div style="flex: 1; min-width: 280px;">
                            <h2 style="margin-top: 0;"><?php echo esc_html( $ketua ?: '-' ); ?></h2>
                            <p style="color: #D6001C; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;"><?php esc_html_e( 'Ketua DPD', 'dpw-psi-papeng' ); ?> - <?php the_title(); ?></p>
                            <?php if ( $phone ) : ?>
                                <p style="margin-bottom: 8px;"><i class="bi bi-telephone" style="color: #D6001C; margin-right: 8px;"></i> <?php echo esc_html( $phone ); ?></p>
                            <?php endif; ?>
                            <?php if ( $email ) : ?>
                                <p style="margin-bottom: 8px;"><i class="bi bi-envelope" style="color: #D6001C; margin-right: 8px;"></i> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
                            <?php endif; ?>
                            <?php if ( $addr ) : ?>
                                <p style="margin-bottom: 0;"><i class="bi bi-geo-alt" style="color: #D6001C; margin-right: 8px;"></i> <?php echo esc_html( $addr ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="article-body">
                    <?php the_content(); ?>
                </div>
            </main>
            <aside class="article-sidebar">
                <div class="sidebar-widget">
                    <h4 class="widget-title"><?php esc_html_e( 'DPD Lainnya', 'dpw-psi-papeng' ); ?></h4>
                    <?php
                    $other_dpd = new WP_Query( array(
                        'post_type'      => 'psi-dpd',
                        'posts_per_page' => 8,
                        'post__not_in'   => array( get_the_ID() ),
                        'post_status'    => 'publish',
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                    ) );
                    if ( $other_dpd->have_posts() ) :
                        while ( $other_dpd->have_posts() ) : $other_dpd->the_post(); ?>
                            <div class="sidebar-recent-post">
                                <?php 
                                $oth_photo = get_post_meta( get_the_ID(), '_psi_dpd_photo', true );
                                $oth_ketua = get_post_meta( get_the_ID(), '_psi_dpd_ketua', true );
                                ?>
                                <img src="<?php echo esc_url( $oth_photo ?: DPW_PSI_URI . '/assets/images/placeholder-user.png' ); ?>" alt="<?php echo esc_attr( $oth_ketua ?: get_the_title() ); ?>" loading="lazy" style="border-radius: 50%; width: 56px; height: 56px; min-width: 56px;">
                                <div>
                                    <h5><a href="<?php the_permalink(); ?>"><?php echo esc_html( $oth_ketua ?: get_the_title() ); ?></a></h5>
                                    <span class="post-date"><?php the_title(); ?></span>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php get_footer(); ?>
