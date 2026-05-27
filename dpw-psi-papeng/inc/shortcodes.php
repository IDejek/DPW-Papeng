<?php
/**
 * Shortcodes
 * @package DPW_PSIPapeng
 */

defined( 'ABSPATH' ) || exit;

/* ── Leadership Cards Shortcode ────────────────────────────── */
add_shortcode( 'psi_leaders', 'dpw_psi_sc_leaders' );
function dpw_psi_sc_leaders( $atts ): string {
    $atts = shortcode_atts( [ 'limit' => 3, 'primary' => true ], $atts, 'psi_leaders' );
    $args = [
        'post_type'      => 'leadership',
        'posts_per_page' => absint( $atts['limit'] ),
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ];
    if ( filter_var( $atts['primary'], FILTER_VALIDATE_BOOLEAN ) ) {
        $args['meta_key']   = '_dpw_is_primary';
        $args['meta_value'] = '1';
    }
    $q = new WP_Query( $args );
    if ( ! $q->have_posts() ) return '';

    ob_start();
    echo '<div class="row g-4">';
    while ( $q->have_posts() ) {
        $q->the_post();
        $position = dpw_psi_get_meta( get_the_ID(), 'position', '' );
        $fb       = dpw_psi_get_meta( get_the_ID(), 'facebook', '' );
        $ig       = dpw_psi_get_meta( get_the_ID(), 'instagram', '' );
        $tw       = dpw_psi_get_meta( get_the_ID(), 'twitter', '' );
        $photo    = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'leader-card' ) : dpw_psi_placeholder( 400, 500 );
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="dpw-leader-card card h-100 border-0 shadow-lg">
                <div class="dpw-leader-img-wrapper">
                    <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="card-img-top" loading="lazy">
                </div>
                <div class="card-body text-center">
                    <h5 class="fw-bold mb-1"><?php echo esc_html( get_the_title() ); ?></h5>
                    <p class="text-danger fw-semibold small mb-2"><?php echo esc_html( $position ); ?></p>
                    <div class="dpw-leader-social">
                        <?php if ( $fb ) : ?><a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener" class="me-2"><i class="bi bi-facebook"></i></a><?php endif; ?>
                        <?php if ( $ig ) : ?><a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener" class="me-2"><i class="bi bi-instagram"></i></a><?php endif; ?>
                        <?php if ( $tw ) : ?><a href="<?php echo esc_url( $tw ); ?>" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                    </div>
                    <?php if ( is_singular( 'leadership' ) || get_the_content() ) : ?>
                    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-danger mt-2"><?php esc_html_e( 'Profil Lengkap', 'dpw-psi-papeng' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}

/* ── DPD Cards Shortcode ───────────────────────────────────── */
add_shortcode( 'psi_dpd_list', 'dpw_psi_sc_dpd' );
function dpw_psi_sc_dpd( $atts ): string {
    $atts = shortcode_atts( [ 'per_page' => 8 ], $atts, 'psi_dpd_list' );
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $q = new WP_Query( [
        'post_type'      => 'dpd',
        'posts_per_page' => absint( $atts['per_page'] ),
        'paged'          => $paged,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ] );
    if ( ! $q->have_posts() ) return '<p class="text-muted">Belum ada data DPD.</p>';

    ob_start();
    echo '<div class="row g-4">';
    while ( $q->have_posts() ) {
        $q->the_post();
        $kab   = dpw_psi_get_meta( get_the_ID(), 'kabupaten', get_the_title() );
        $ketua = dpw_psi_get_meta( get_the_ID(), 'ketua_name', '' );
        $photo = dpw_psi_get_meta( get_the_ID(), 'ketua_photo', '' );
        $count = dpw_psi_get_meta( get_the_ID(), 'member_count', '0' );
        if ( ! $photo && has_post_thumbnail() ) {
            $photo = get_the_post_thumbnail_url( get_the_ID(), 'dpd-card' );
        }
        if ( ! $photo ) {
            $photo = dpw_psi_placeholder( 500, 600 );
        }
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                <div class="dpw-dpd-card card h-100 border-0 shadow-lg overflow-hidden">
                    <div class="dpw-dpd-img-wrapper">
                        <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $ketua ); ?>" class="card-img-top" loading="lazy">
                    </div>
                    <div class="card-body text-center">
                        <span class="badge bg-danger mb-2"><?php echo esc_html( $kab ); ?></span>
                        <h6 class="fw-bold text-dark mb-1"><?php echo esc_html( $ketua ); ?></h6>
                        <p class="text-muted small mb-0"><?php printf( esc_html__( '%s Anggota', 'dpw-psi-papeng' ), number_format_i18n( absint( $count ) ) ); ?></p>
                    </div>
                </div>
            </a>
        </div>
        <?php
    }
    echo '</div>';
    dpw_psi_pagination();
    wp_reset_postdata();
    return ob_get_clean();
}

/* ── Contact Form Shortcode ────────────────────────────────── */
add_shortcode( 'psi_contact_form', 'dpw_psi_sc_contact_form' );
function dpw_psi_sc_contact_form(): string {
    ob_start();
    ?>
    <form id="dpw-contact-form" class="dpw-contact-form" novalidate>
        <?php wp_nonce_field( 'dpw_psi_contact', 'dpw_contact_nonce' ); ?>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" name="name" id="contact-name" class="form-control" placeholder="<?php esc_attr_e( 'Nama Lengkap', 'dpw-psi-papeng' ); ?>" required>
                    <label for="contact-name"><?php esc_html_e( 'Nama Lengkap', 'dpw-psi-papeng' ); ?></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="email" name="email" id="contact-email" class="form-control" placeholder="<?php esc_attr_e( 'Email', 'dpw-psi-papeng' ); ?>" required>
                    <label for="contact-email"><?php esc_html_e( 'Email', 'dpw-psi-papeng' ); ?></label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating">
                    <input type="text" name="subject" id="contact-subject" class="form-control" placeholder="<?php esc_attr_e( 'Subjek', 'dpw-psi-papeng' ); ?>" required>
                    <label for="contact-subject"><?php esc_html_e( 'Subjek', 'dpw-psi-papeng' ); ?></label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating">
                    <textarea name="message" id="contact-message" class="form-control" style="height:150px" placeholder="<?php esc_attr_e( 'Pesan', 'dpw-psi-papeng' ); ?>" required></textarea>
                    <label for="contact-message"><?php esc_html_e( 'Pesan', 'dpw-psi-papeng' ); ?></label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-danger btn-lg px-5 fw-bold">
                    <i class="bi bi-send me-2"></i><?php esc_html_e( 'Kirim Pesan', 'dpw-psi-papeng' ); ?>
                </button>
            </div>
        </div>
        <div id="contact-form-result" class="mt-3"></div>
    </form>
    <?php
    return ob_get_clean();
}

/* ── AJAX Contact Handler ──────────────────────────────────── */
add_action( 'wp_ajax_dpw_contact_send', 'dpw_psi_contact_send' );
add_action( 'wp_ajax_nopriv_dpw_contact_send', 'dpw_psi_contact_send' );
function dpw_psi_contact_send(): void {
    check_ajax_referer( 'dpw_psi_contact', 'nonce' );

    /* Rate limiting */
    $ip        = ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
    $transient = 'psi_rl_contact_' . md5( $ip );
    $count     = (int) get_transient( $transient );
    if ( $count >= 3 ) {
        wp_send_json_error( [ 'message' => __( 'Terlalu banyak permintaan. Silakan tunggu 1 menit.', 'dpw-psi-papeng' ) ] );
    }
    set_transient( $transient, $count + 1, 60 );

    $name    = sanitize_text_field( $_POST['name'] ?? '' );
    $email   = sanitize_email( $_POST['email'] ?? '' );
    $subject = sanitize_text_field( $_POST['subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    if ( empty( $name ) || empty( $email ) || empty( $subject ) || empty( $message ) ) {
        wp_send_json_error( [ 'message' => __( 'Semua field wajib diisi.', 'dpw-psi-papeng' ) ] );
    }
    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Format email tidak valid.', 'dpw-psi-papeng' ) ] );
    }

    $to      = dpw_psi_get_option( 'contact_email', get_option( 'admin_email' ) );
    $headers = [ 'Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $email ];
    $body    = '<h2>' . esc_html( $subject ) . '</h2>';
    $body   .= '<p><strong>' . __( 'Nama', 'dpw-psi-papeng' ) . ':</strong> ' . esc_html( $name ) . '</p>';
    $body   .= '<p><strong>' . __( 'Email', 'dpw-psi-papeng' ) . ':</strong> ' . esc_html( $email ) . '</p>';
    $body   .= '<p><strong>' . __( 'Pesan', 'dpw-psi-papeng' ) . ':</strong><br>' . nl2br( esc_html( $message ) ) . '</p>';

    $sent = wp_mail( $to, '[' . get_bloginfo( 'name' ) . '] ' . $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => __( 'Pesan berhasil dikirim. Terima kasih!', 'dpw-psi-papeng' ) ] );
    } else {
        wp_send_json_error( [ 'message' => __( 'Gagal mengirim pesan. Silakan coba lagi.', 'dpw-psi-papeng' ) ] );
    }
}
