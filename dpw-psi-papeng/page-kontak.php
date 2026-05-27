<?php
/**
 * Template Name: Halaman Kontak
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $phone   = dpw_psi_get_option( 'contact_phone', '+62 822 6721 8125' );
 $email   = dpw_psi_get_option( 'contact_email', 'tombinawaiqbal@gmail.com' );
 $address = dpw_psi_get_option( 'contact_address', 'Papua Pegunungan, Indonesia' );
 $map     = dpw_psi_get_option( 'map_embed', '' );
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
        <!-- Contact Info Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4 dpw-animate-on-scroll">
                <div class="dpw-contact-info-card card border-0 shadow-sm text-center p-4 h-100">
                    <div class="dpw-contact-icon mb-3"><i class="bi bi-geo-alt-fill"></i></div>
                    <h6 class="fw-bold mb-2"><?php esc_html_e( 'Alamat', 'dpw-psi-papeng' ); ?></h6>
                    <p class="text-muted small mb-0"><?php echo esc_html( $address ); ?></p>
                </div>
            </div>
            <div class="col-md-4 dpw-animate-on-scroll" data-delay="100">
                <div class="dpw-contact-info-card card border-0 shadow-sm text-center p-4 h-100">
                    <div class="dpw-contact-icon mb-3"><i class="bi bi-telephone-fill"></i></div>
                    <h6 class="fw-bold mb-2"><?php esc_html_e( 'Telepon', 'dpw-psi-papeng' ); ?></h6>
                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="text-muted small text-decoration-none"><?php echo esc_html( $phone ); ?></a>
                </div>
            </div>
            <div class="col-md-4 dpw-animate-on-scroll" data-delay="200">
                <div class="dpw-contact-info-card card border-0 shadow-sm text-center p-4 h-100">
                    <div class="dpw-contact-icon mb-3"><i class="bi bi-envelope-fill"></i></div>
                    <h6 class="fw-bold mb-2"><?php esc_html_e( 'Email', 'dpw-psi-papeng' ); ?></h6>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>" class="text-muted small text-decoration-none"><?php echo esc_html( $email ); ?></a>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-6 dpw-animate-on-scroll">
                <h3 class="fw-bold mb-4"><?php esc_html_e( 'Kirim Pesan', 'dpw-psi-papeng' ); ?></h3>
                <?php echo do_shortcode( '[psi_contact_form]' ); ?>
            </div>

            <!-- Map & WhatsApp -->
            <div class="col-lg-6 dpw-animate-on-scroll" data-delay="200">
                <h3 class="fw-bold mb-4"><?php esc_html_e( 'Lokasi Kami', 'dpw-psi-papeng' ); ?></h3>
                <?php if ( $map ) : ?>
                <div class="dpw-map-wrapper rounded-3 overflow-hidden shadow-sm mb-4">
                    <?php echo dpw_psi_safe_html( $map ); ?>
                </div>
                <?php else : ?>
                <div class="dpw-map-placeholder bg-light rounded-3 d-flex align-items-center justify-content-center mb-4" style="height:300px">
                    <div class="text-center text-muted">
                        <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                        <small><?php esc_html_e( 'Peta akan ditampilkan setelah konfigurasi Google Maps.', 'dpw-psi-papeng' ); ?></small>
                    </div>
                </div>
                <?php endif; ?>

                <!-- WhatsApp Quick Contact -->
                <a href="<?php echo esc_url( dpw_psi_wa_link( '', 'Halo, saya ingin bertanya tentang DPW PSI Papua Pegunungan.' ) ); ?>" target="_blank" rel="noopener" class="btn btn-success btn-lg w-100 fw-bold mb-3">
                    <i class="bi bi-whatsapp me-2"></i><?php esc_html_e( 'Chat via WhatsApp', 'dpw-psi-papeng' ); ?>
                </a>

                <!-- Social Links -->
                <div class="dpw-contact-social">
                    <h6 class="fw-bold mb-3"><?php esc_html_e( 'Ikuti Kami', 'dpw-psi-papeng' ); ?></h6>
                    <div class="d-flex gap-2">
                        <?php
                        $socials = [
                            'facebook'  => [ 'icon' => 'bi-facebook', 'color' => '#1877F2' ],
                            'twitter'   => [ 'icon' => 'bi-twitter-x', 'color' => '#000' ],
                            'instagram' => [ 'icon' => 'bi-instagram', 'color' => '#E4405F' ],
                            'youtube'   => [ 'icon' => 'bi-youtube', 'color' => '#FF0000' ],
                            'tiktok'    => [ 'icon' => 'bi-tiktok', 'color' => '#000' ],
                        ];
                        foreach ( $socials as $key => $s ) :
                            $url = dpw_psi_get_option( 'social_' . $key );
                            if ( $url ) :
                        ?>
                        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="btn btn-lg rounded-circle text-white shadow-sm" style="background-color:<?php echo esc_attr( $s['color'] ); ?>" aria-label="<?php echo esc_attr( ucfirst( $key ) ); ?>">
                            <i class="bi <?php echo esc_attr( $s['icon'] ); ?>"></i>
                        </a>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content (if any) -->
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
            $content = get_the_content();
            if ( ! empty( trim( $content ) ) ) :
        ?>
        <div class="mt-5 pt-4 border-top dpw-page-content lh-lg">
            <?php echo dpw_psi_safe_html( $content ); ?>
        </div>
        <?php endif; endwhile; endif; ?>
    </div>
</section>

<?php get_footer(); ?>
