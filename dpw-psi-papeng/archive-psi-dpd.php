<?php
/**
 * DPD Archive Template
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
            <h1><?php esc_html_e( 'DPD PSI Se-Papua Pegunungan', 'dpw-psi-papeng' ); ?></h1>
            <p><?php esc_html_e( 'Data dan informasi Dewan Pimpinan Daerah PSI di 8 kabupaten wilayah Papua Pegunungan.', 'dpw-psi-papeng' ); ?></p>
        </div>
    </div>
</section>

<!-- DPD Archive -->
<section class="profile-content">
    <div class="container">
        <!-- Search & Filter -->
        <div style="margin-bottom: 40px; display: flex; gap: 16px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="flex: 1; min-width: 280px; max-width: 400px; position: relative;">
                <i class="bi bi-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9CA3AF;"></i>
                <input type="text" id="dpdSearch" class="form-control" placeholder="<?php esc_attr_e( 'Cari kabupaten atau ketua...', 'dpw-psi-papeng' ); ?>" style="padding-left: 44px;">
            </div>
            <p style="margin: 0; color: #6B7280; font-size: 0.9rem;">
                <?php 
                global $wp_query;
                printf( esc_html__( 'Menampilkan %d DPD', 'dpw-psi-papeng' ), $wp_query->found_posts ); 
                ?>
            </p>
        </div>

        <?php if ( have_posts() ) : ?>
            <div class="dpd-grid" id="dpdGrid">
                <?php while ( have_posts() ) : the_post(); 
                    $ketua = get_post_meta( get_the_ID(), '_psi_dpd_ketua', true );
                    $photo = get_post_meta( get_the_ID(), '_psi_dpd_photo', true );
                ?>
                    <div class="dpd-card psi-animate" data-search="<?php echo esc_attr( strtolower( get_the_title() . ' ' . $ketua ) ); ?>">
                        <div class="dpd-card-photo">
                            <img src="<?php echo esc_url( $photo ?: DPW_PSI_URI . '/assets/images/placeholder-user.png' ); ?>" alt="<?php echo esc_attr( $ketua ?: get_the_title() ); ?>" loading="lazy">
                        </div>
                        <div class="dpd-card-body">
                            <h4><?php echo esc_html( $ketua ?: '-' ); ?></h4>
                            <div class="dpd-kabupaten"><?php the_title(); ?></div>
                            <p class="dpd-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="dpd-card-link"><?php esc_html_e( 'Lihat Detail', 'dpw-psi-papeng' ); ?> <i class="bi bi-arrow-right"></i></a>
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
                <i class="bi bi-building" style="font-size: 3rem; color: #D1D5DB; margin-bottom: 16px; display: block;"></i>
                <h3><?php esc_html_e( 'Belum ada data DPD', 'dpw-psi-papeng' ); ?></h3>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
