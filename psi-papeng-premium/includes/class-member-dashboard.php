<?php
/**
 * Member Dashboard (Front-end)
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Member_Dashboard {

    public function __construct() {
        add_shortcode( 'psi_member_dashboard', [ $this, 'render_dashboard' ] );
        add_action( 'wp_ajax_psi_member_check', [ $this, 'ajax_check_status' ] );
        add_action( 'wp_ajax_nopriv_psi_member_check', [ $this, 'ajax_check_status' ] );
    }

    public function render_dashboard( $atts ): string {
        ob_start();
        ?>
        <div class="psi-member-dashboard">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i><?php esc_html_e( 'Cek Status Keanggotaan', 'psi-papeng-premium' ); ?></h5>
                </div>
                <div class="card-body p-4">
                    <form id="psi-check-form" class="row g-3">
                        <?php wp_nonce_field( 'psi_member_check', 'psi_check_nonce' ); ?>
                        <div class="col-md-8">
                            <input type="email" name="check_email" id="psi-check-email" class="form-control" placeholder="<?php esc_attr_e( 'Masukkan email yang didaftarkan', 'psi-papeng-premium' ); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-danger w-100 fw-bold">
                                <i class="bi bi-search me-1"></i><?php esc_html_e( 'Cek Status', 'psi-papeng-premium' ); ?>
                            </button>
                        </div>
                    </form>
                    <div id="psi-check-result" class="mt-3"></div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function ajax_check_status(): void {
        check_ajax_referer( 'psi_member_check', 'nonce' );

        global $wpdb;
        $email = sanitize_email( $_POST['check_email'] ?? '' );
        if ( ! $email || ! is_email( $email ) ) {
            wp_send_json_error( [ 'message' => __( 'Masukkan email yang valid.', 'psi-papeng-premium' ) ] );
        }

        $table = $wpdb->prefix . 'psi_members';
        $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE email = %s", $email ) );

        if ( ! $member ) {
            wp_send_json_error( [ 'message' => __( 'Email tidak ditemukan dalam database keanggotaan.', 'psi-papeng-premium' ) ] );
        }

        $status_labels = [
            'pending'  => '<span class="badge bg-warning text-dark">' . __( 'Menunggu Verifikasi', 'psi-papeng-premium' ) . '</span>',
            'verified' => '<span class="badge bg-success">' . __( 'Terverifikasi', 'psi-papeng-premium' ) . '</span>',
            'rejected' => '<span class="badge bg-danger">' . __( 'Ditolak', 'psi-papeng-premium' ) . '</span>',
        ];

        $html = '<div class="alert alert-light border">';
        $html .= '<h6 class="fw-bold mb-3">' . esc_html( $member->full_name ) . '</h6>';
        $html .= '<table class="table table-sm small mb-0">';
        $html .= '<tr><td class="fw-bold text-muted" style="width:140px">Status</td><td>' . ($status_labels[$member->status] ?? $member->status) . '</td></tr>';
        $html .= '<tr><td class="fw-bold text-muted">Email</td><td>' . esc_html( $member->email ) . '</td></tr>';
        $html .= '<tr><td class="fw-bold text-muted">Telepon</td><td>' . esc_html( $member->phone ) . '</td></tr>';
        $html .= '<tr><td class="fw-bold text-muted">Kabupaten</td><td>' . esc_html( $member->kabupaten ) . '</td></tr>';
        $html .= '<tr><td class="fw-bold text-muted">Tgl Daftar</td><td>' . esc_html( $member->registered_at ) . '</td></tr>';
        if ( $member->verified_at ) {
            $html .= '<tr><td class="fw-bold text-muted">Tgl Verifikasi</td><td>' . esc_html( $member->verified_at ) . '</td></tr>';
        }
        $html .= '</table></div>';

        wp_send_json_success( [ 'html' => $html ] );
    }
}
