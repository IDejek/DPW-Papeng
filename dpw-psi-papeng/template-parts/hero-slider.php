<?php
/**
 * Hero Slider Template Part
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $slides = get_posts( [
    'post_type'      => 'slider',
    'posts_per_page' => 10,
    'meta_key'       => '_dpw_slider_order',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
] );
?>

<section class="dpw-hero-slider" id="dpwHeroSlider" aria-label="Hero Slider">
    <?php if ( ! empty( $slides ) ) : ?>
    <div class="dpw-slider-track" id="dpwSliderTrack">
        <?php foreach ( $slides as $i => $slide ) :
            $subtitle = dpw_psi_get_meta( $slide->ID, 'slider_subtitle', '' );
            $btn_text = dpw_psi_get_meta( $slide->ID, 'slider_btn_text', 'Selengkapnya' );
            $btn_url  = dpw_psi_get_meta( $slide->ID, 'slider_btn_url', '#' );
            $img      = has_post_thumbnail( $slide ) ? get_the_post_thumbnail_url( $slide, 'slider-full' ) : dpw_psi_placeholder( 1920, 800 );
        ?>
        <div class="dpw-slide" data-index="<?php echo $i; ?>">
            <div class="dpw-slide-bg" style="background-image:url('<?php echo esc_url( $img ); ?>')"></div>
            <div class="dpw-slide-overlay"></div>
            <div class="dpw-slide-content container position-relative">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="dpw-slide-title dpw-animate-on-scroll"><?php echo esc_html( get_the_title( $slide ) ); ?></h1>
                        <?php if ( $subtitle ) : ?>
                        <p class="dpw-slide-subtitle dpw-animate-on-scroll" data-delay="200"><?php echo esc_html( $subtitle ); ?></p>
                        <?php endif; ?>
                        <?php if ( $btn_text && $btn_url !== '#' ) : ?>
                        <a href="<?php echo esc_url( $btn_url ); ?>" class="btn btn-danger btn-lg fw-bold px-5 mt-3 dpw-animate-on-scroll" data-delay="400">
                            <?php echo esc_html( $btn_text ); ?> <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Slider Controls -->
    <div class="dpw-slider-controls">
        <button class="dpw-slider-btn dpw-slider-prev" aria-label="<?php esc_attr_e( 'Sebelumnya', 'dpw-psi-papeng' ); ?>"><i class="bi bi-chevron-left"></i></button>
        <div class="dpw-slider-dots" id="dpwSliderDots"></div>
        <button class="dpw-slider-btn dpw-slider-next" aria-label="<?php esc_attr_e( 'Selanjutnya', 'dpw-psi-papeng' ); ?>"><i class="bi bi-chevron-right"></i></button>
    </div>
    <?php else : ?>
    <!-- Default Hero when no slides -->
    <div class="dpw-slide dpw-slide-default">
        <div class="dpw-slide-overlay"></div>
        <div class="dpw-slide-content container position-relative">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="dpw-slide-title">DPW PSI Papua Pegunungan</h1>
                    <p class="dpw-slide-subtitle">Partai Solidaritas Indonesia — Membangun Papua Pegunungan yang Berkeadilan</p>
                    <a href="<?php echo esc_url( dpw_psi_get_option( 'membership_url', 'https://psi.id/menjadi-anggota' ) ); ?>" class="btn btn-danger btn-lg fw-bold px-5 mt-3">
                        <?php echo esc_html( dpw_psi_get_option( 'membership_text', 'Daftar Anggota' ) ); ?> <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>
