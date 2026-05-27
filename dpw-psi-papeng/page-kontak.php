<?php
/**
 * Template Name: Kontak
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();

 $contact_info = get_option( 'dpw_psi_contact', array() );
 $wa_number    = ! empty( $contact_info['whatsapp'] ) ? $contact_info['whatsapp'] : '+62 822 6721 8125';
 $map_url      = ! empty( $contact_info['map_url'] ) ? $contact_info['map_url'] : 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127711.50101711885!2d140.55!3d-4.0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sPapua+Pegunungan!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <?php dpw_psi_breadcrumb(); ?>
            <h1><?php the_title(); ?></h1>
            <p><?php esc_html_e( 'Hubungi kami untuk informasi lebih lanjut.', 'dpw-psi-papeng' ); ?></p>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="profile-content">
    <div class="container">
        <div class="contact-grid">
            <!-- Left: Info -->
            <div class="psi-animate-left">
                <h2 style="margin-bottom: 8px;"><?php esc_html_e( 'Informasi Kontak', 'dpw-psi-papeng' ); ?></h2>
                <p style="margin-bottom: 32px;"><?php esc_html_e( 'Kami siap membantu Anda. Silakan hubungi kami melalui salah satu kanal berikut.', 'dpw-psi-papeng' ); ?></p>

                <div class="contact-info-cards">
                    <div class="contact-info-card">
                        <div class="icon-box"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <h4><?php esc_html_e( 'Alamat Kantor', 'dpw-psi-papeng' ); ?></h4>
                            <p><?php echo esc_html( ! empty( $contact_info['address'] ) ? $contact_info['address'] : 'Papua Pegunungan, Indonesia' ); ?></p>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="icon-box"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <h4><?php esc_html_e( 'Email Resmi', 'dpw-psi-papeng' ); ?></h4>
                            <p><a href="mailto:<?php echo esc_attr( ! empty( $contact_info['email'] ) ? $contact_info['email'] : 'info@psipapeng.id' ); ?>"><?php echo esc_html( ! empty( $contact_info['email'] ) ? $contact_info['email'] : 'info@psipapeng.id' ); ?></a></p>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="icon-box" style="background: rgba(37,211,102,0.1); color: #25D366;"><i class="bi bi-whatsapp"></i></div>
                        <div>
                            <h4><?php esc_html_e( 'WhatsApp', 'dpw-psi-papeng' ); ?></h4>
                            <p><a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $wa_number ) ); ?>"><?php echo esc_html( $wa_number ); ?></a></p>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="contact-map">
                    <iframe src="<?php echo esc_url( $map_url ); ?>" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="psi-animate-right">
                <div class="contact-form-card" id="contactFormCard">
                    <h3><?php esc_html_e( 'Kirim Pesan', 'dpw-psi-papeng' ); ?></h3>
                    <p><?php esc_html_e( 'Isi formulir di bawah ini dan kami akan segera merespons.', 'dpw-psi-papeng' ); ?></p>
                    
                    <form id="psiContactForm" method="post">
                        <?php wp_nonce_field( 'dpw_psi_nonce', 'contact_nonce' ); ?>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contactName"><?php esc_html_e( 'Nama Lengkap', 'dpw-psi-papeng' ); ?> <span class="required">*</span></label>
                                <input type="text" id="contactName" name="name" class="form-control" placeholder="<?php esc_attr_e( 'Masukkan nama Anda', 'dpw-psi-papeng' ); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contactEmail"><?php esc_html_e( 'Email', 'dpw-psi-papeng' ); ?> <span class="required">*</span></label>
                                <input type="email" id="contactEmail" name="email" class="form-control" placeholder="<?php esc_attr_e( 'Masukkan email Anda', 'dpw-psi-papeng' ); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contactSubject"><?php esc_html_e( 'Subjek', 'dpw-psi-papeng' ); ?></label>
                            <input type="text" id="contactSubject" name="subject" class="form-control" placeholder="<?php esc_attr_e( 'Subjek pesan', 'dpw-psi-papeng' ); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contactMessage"><?php esc_html_e( 'Pesan', 'dpw-psi-papeng' ); ?> <span class="required">*</span></label>
                            <textarea id="contactMessage" name="message" class="form-control" rows="6" placeholder="<?php esc_attr_e( 'Tulis pesan Anda di sini...', 'dpw-psi-papeng' ); ?>" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg" id="contactSubmitBtn" style="width: 100%; justify-content: center;">
                            <i class="bi bi-send"></i> <?php esc_html_e( 'Kirim Pesan', 'dpw-psi-papeng' ); ?>
                        </button>
                    </form>
                    <div id="contactFormMsg" style="margin-top: 16px; display: none;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
