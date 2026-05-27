<?php
/**
 * Theme Footer
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $social_links  = get_option( 'dpw_psi_social', array() );
 $contact_info  = get_option( 'dpw_psi_contact', array() );
 $wa_number     = ! empty( $contact_info['whatsapp'] ) ? $contact_info['whatsapp'] : '+6282267218125';
?>

<!-- Footer -->
<footer class="psi-footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                <!-- Column 1: About -->
                <div>
                    <div class="footer-brand">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url( DPW_PSI_URI . '/assets/images/logo.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                        <?php endif; ?>
                        <div class="footer-brand-text">
                            <span class="brand-name">DPW PSI</span>
                            <span class="brand-sub">Papua Pegunungan</span>
                        </div>
                    </div>
                    <div class="footer-about">
                        <p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
                    </div>
                    <div class="footer-social">
                        <?php if ( ! empty( $social_links['facebook'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <?php endif; ?>
                        <?php if ( ! empty( $social_links['instagram'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['instagram'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ( ! empty( $social_links['youtube'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                        <?php endif; ?>
                        <?php if ( ! empty( $social_links['tiktok'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['tiktok'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h4 class="footer-heading"><?php esc_html_e( 'Tautan Cepat', 'dpw-psi-papeng' ); ?></h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'footer-links',
                        'fallback_cb'    => function() {
                            echo '<ul class="footer-links">';
                            echo '<li><a href="' . esc_url( home_url( '/profil/visi-misi/' ) ) . '">Visi & Misi</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/profil/sejarah/' ) ) . '">Sejarah PSI</a></li>';
                            echo '<li><a href="' . esc_url( get_post_type_archive_link( 'psi-news' ) ) . '">Berita</a></li>';
                            echo '<li><a href="' . esc_url( get_post_type_archive_link( 'psi-dpd' ) ) . '">DPD PSI</a></li>';
                            echo '<li><a href="' . esc_url( home_url( '/kontak/' ) ) . '">Kontak</a></li>';
                            echo '</ul>';
                        },
                        'depth'          => 1,
                    ) );
                    ?>
                </div>

                <!-- Column 3: Program -->
                <div>
                    <h4 class="footer-heading"><?php esc_html_e( 'Program', 'dpw-psi-papeng' ); ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url( home_url( '/bidang/hubungan-lembaga/' ) ); ?>"><?php esc_html_e( 'Hubungan Lembaga & HAM', 'dpw-psi-papeng' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/bidang/umkm/' ) ); ?>"><?php esc_html_e( 'UMKM & Koperasi', 'dpw-psi-papeng' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/bidang/teknologi/' ) ); ?>"><?php esc_html_e( 'Media & Teknologi', 'dpw-psi-papeng' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/bidang/pemuda/' ) ); ?>"><?php esc_html_e( 'Pemuda & Olahraga', 'dpw-psi-papeng' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/bidang/kesehatan/' ) ); ?>"><?php esc_html_e( 'Kesehatan', 'dpw-psi-papeng' ); ?></a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact -->
                <div>
                    <h4 class="footer-heading"><?php esc_html_e( 'Hubungi Kami', 'dpw-psi-papeng' ); ?></h4>
                    <?php if ( ! empty( $contact_info['address'] ) ) : ?>
                        <div class="footer-contact-item">
                            <i class="bi bi-geo-alt"></i>
                            <p><?php echo esc_html( $contact_info['address'] ); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ( ! empty( $contact_info['email'] ) ) : ?>
                        <div class="footer-contact-item">
                            <i class="bi bi-envelope"></i>
                            <p><a href="mailto:<?php echo esc_attr( $contact_info['email'] ); ?>"><?php echo esc_html( $contact_info['email'] ); ?></a></p>
                        </div>
                    <?php endif; ?>
                    <div class="footer-contact-item">
                        <i class="bi bi-whatsapp"></i>
                        <p><a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $wa_number ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $wa_number ); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> DPW PSI Papua Pegunungan. <?php esc_html_e( 'Seluruh hak cipta dilindungi.', 'dpw-psi-papeng' ); ?></p>
            <div class="footer-bottom-links">
                <a href="<?php echo esc_url( home_url( '/kebijakan-privasi/' ) ); ?>"><?php esc_html_e( 'Kebijakan Privasi', 'dpw-psi-papeng' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/syarat-ketentuan/' ) ); ?>"><?php esc_html_e( 'Syarat & Ketentuan', 'dpw-psi-papeng' ); ?></a>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Float -->
<?php if ( ! empty( $wa_number ) ) : ?>
<div class="whatsapp-float">
    <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $wa_number ) ); ?>?text=<?php echo esc_attr( rawurlencode( 'Halo DPW PSI Papua Pegunungan, saya ingin bertanya.' ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
        <i class="bi bi-whatsapp"></i>
        <span class="whatsapp-tooltip"><?php esc_html_e( 'Chat WhatsApp', 'dpw-psi-papeng' ); ?></span>
    </a>
</div>
<?php endif; ?>

<!-- Back to Top -->
<button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="bi bi-chevron-up"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
