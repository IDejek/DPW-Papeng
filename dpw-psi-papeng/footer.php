<?php
/**
 * Footer Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
?>

<!-- ════════ FOOTER ════════ -->
<footer class="dpw-footer" id="dpwFooter">
    <!-- Main Footer -->
    <div class="dpw-footer-main">
        <div class="container">
            <div class="row g-4 py-5">
                <!-- Column 1: About -->
                <div class="col-lg-4 col-md-6">
                    <div class="dpw-footer-brand mb-3">
                        <?php if ( has_custom_logo() ) : ?>
                            <div class="dpw-footer-logo mb-3"><?php the_custom_logo(); ?></div>
                        <?php else : ?>
                            <?php dpw_psi_icon( 'psi-logo', 'dpw-footer-logo-icon' ); ?>
                        <?php endif; ?>
                        <h5 class="text-white fw-bold mb-1">DPW PSI</h5>
                        <small class="text-white-50">Papua Pegunungan</small>
                    </div>
                    <p class="text-white-50 small lh-lg">
                        <?php echo esc_html( dpw_psi_get_option( 'footer_text', 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan' ) ); ?>
                    </p>
                    <div class="dpw-footer-social d-flex gap-2 mt-3">
                        <?php
                        $socials = [
                            'facebook'  => 'bi-facebook',
                            'twitter'   => 'bi-twitter-x',
                            'instagram' => 'bi-instagram',
                            'youtube'   => 'bi-youtube',
                            'tiktok'    => 'bi-tiktok',
                            'linkedin'  => 'bi-linkedin',
                        ];
                        foreach ( $socials as $key => $icon ) :
                            $url = dpw_psi_get_option( 'social_' . $key );
                            if ( $url ) :
                        ?>
                            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="dpw-social-btn" aria-label="<?php echo esc_attr( ucfirst( $key ) ); ?>">
                                <i class="bi <?php echo esc_attr( $icon ); ?>"></i>
                            </a>
                        <?php endif; endforeach; ?>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : dynamic_sidebar( 'footer-1' ); else : ?>
                    <h5 class="text-white fw-bold mb-3"><?php esc_html_e( 'Tautan Cepat', 'dpw-psi-papeng' ); ?></h5>
                    <ul class="dpw-footer-links list-unstyled">
                        <?php
                        wp_nav_menu( [
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'list-unstyled',
                            'fallback_cb'    => 'dpw_psi_footer_links_fallback',
                            'depth'          => 1,
                        ] );
                        ?>
                    </ul>
                    <?php endif; ?>
                </div>

                <!-- Column 3: More Links -->
                <div class="col-lg-2 col-md-6">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : dynamic_sidebar( 'footer-2' ); else : ?>
                    <h5 class="text-white fw-bold mb-3"><?php esc_html_e( 'Informasi', 'dpw-psi-papeng' ); ?></h5>
                    <ul class="dpw-footer-links list-unstyled">
                        <li><a href="<?php echo esc_url( home_url( '/profil/sejarah-psi/' ) ); ?>"><?php esc_html_e( 'Sejarah PSI', 'dpw-psi-papeng' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/profil/visi-misi/' ) ); ?>"><?php esc_html_e( 'Visi & Misi', 'dpw-psi-papeng' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/struktur-organisasi/' ) ); ?>"><?php esc_html_e( 'Struktur Organisasi', 'dpw-psi-papeng' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/dpd-kabupaten/' ) ); ?>"><?php esc_html_e( 'DPD Kabupaten', 'dpw-psi-papeng' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/galeri/' ) ); ?>"><?php esc_html_e( 'Galeri', 'dpw-psi-papeng' ); ?></a></li>
                    </ul>
                    <?php endif; ?>
                </div>

                <!-- Column 4: Contact -->
                <div class="col-lg-4 col-md-6">
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : dynamic_sidebar( 'footer-3' ); else : ?>
                    <h5 class="text-white fw-bold mb-3"><?php esc_html_e( 'Hubungi Kami', 'dpw-psi-papeng' ); ?></h5>
                    <ul class="dpw-footer-contact list-unstyled">
                        <li class="d-flex gap-2 mb-3">
                            <i class="bi bi-geo-alt-fill text-danger flex-shrink-0 mt-1"></i>
                            <span class="text-white-50 small"><?php echo esc_html( dpw_psi_get_option( 'contact_address', 'Papua Pegunungan, Indonesia' ) ); ?></span>
                        </li>
                        <li class="d-flex gap-2 mb-3">
                            <i class="bi bi-telephone-fill text-danger flex-shrink-0 mt-1"></i>
                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', dpw_psi_get_option( 'contact_phone', '+62 822 6721 8125' ) ) ); ?>" class="text-white-50 small text-decoration-none"><?php echo esc_html( dpw_psi_get_option( 'contact_phone', '+62 822 6721 8125' ) ); ?></a>
                        </li>
                        <li class="d-flex gap-2 mb-3">
                            <i class="bi bi-envelope-fill text-danger flex-shrink-0 mt-1"></i>
                            <a href="mailto:<?php echo esc_attr( dpw_psi_get_option( 'contact_email', 'tombinawaiqbal@gmail.com' ) ); ?>" class="text-white-50 small text-decoration-none"><?php echo esc_html( dpw_psi_get_option( 'contact_email', 'tombinawaiqbal@gmail.com' ) ); ?></a>
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-whatsapp text-danger flex-shrink-0 mt-1"></i>
                            <a href="<?php echo esc_url( dpw_psi_wa_link() ); ?>" target="_blank" rel="noopener" class="text-white-50 small text-decoration-none">WhatsApp Kami</a>
                        </li>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="dpw-footer-bottom">
        <div class="container">
            <div class="row align-items-center py-3">
                <div class="col-md-6 text-center text-md-start">
                    <small class="dpw-copyright">
                        <?php echo dpw_psi_safe_html( dpw_psi_get_option( 'footer_copyright', '&copy; 2026 DPW PSI Papua Pegunungan. All rights reserved.' ) ); ?>
                    </small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small class="text-white-50">
                        <?php printf( esc_html__( 'Dikembangkan oleh %s', 'dpw-psi-papeng' ), '<a href="mailto:tombinawaiqbal@gmail.com" class="text-danger text-decoration-none fw-bold">Iqbal Tombinawa</a>' ); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top -->
<button class="dpw-back-to-top" id="dpwBackToTop" aria-label="<?php esc_attr_e( 'Kembali ke atas', 'dpw-psi-papeng' ); ?>">
    <i class="bi bi-chevron-up"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>

<?php
function dpw_psi_footer_links_fallback() {
    $links = [
        [ 'label' => 'Beranda', 'url' => home_url( '/' ) ],
        [ 'label' => 'Berita', 'url' => home_url( '/category/berita/' ) ],
        [ 'label' => 'Video', 'url' => home_url( '/video/' ) ],
        [ 'label' => 'Kontak', 'url' => home_url( '/kontak/' ) ],
        [ 'label' => 'Keanggotaan', 'url' => dpw_psi_get_option( 'membership_url', 'https://psi.id/menjadi-anggota' ) ],
    ];
    foreach ( $links as $l ) {
        echo '<li><a href="' . esc_url( $l['url'] ) . '">' . esc_html( $l['label'] ) . '</a></li>';
    }
}
