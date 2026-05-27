<?php
/**
 * Additional Bootstrap Hooks (CSV Export, SMTP Test, Custom Login)
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Bootstrap_Hooks {

    public function __construct() {
        add_action( 'wp_ajax_psi_member_export_csv', [ $this, 'ajax_export_csv' ] );
        add_action( 'wp_ajax_psi_smtp_test', [ $this, 'ajax_smtp_test' ] );
        add_action( 'login_enqueue_scripts', [ $this, 'login_styles' ] );
        add_action( 'login_header', [ $this, 'login_branding' ] );
    }

    public function ajax_export_csv(): void {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => 'Unauthorized' ] );
        }

        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';
        $rows  = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY id DESC" );

        if ( empty( $rows ) ) {
            wp_send_json_error( [ 'message' => __( 'Tidak ada data untuk diekspor.', 'psi-papeng-premium' ) ] );
        }

        $headers = [ 'ID', 'Nama Lengkap', 'Email', 'Telepon', 'NIK', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Alamat', 'Kabupaten', 'Kecamatan', 'Kelurahan', 'Pekerjaan', 'Pendidikan', 'Status', 'Tgl Daftar', 'Tgl Verifikasi' ];
        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csv .= implode( ';', $headers ) . "\n";

        foreach ( $rows as $r ) {
            $fields = [
                $r->id,
                $this->csv_safe( $r->full_name ),
                $this->csv_safe( $r->email ),
                $this->csv_safe( $r->phone ),
                $this->csv_safe( $r->nik ),
                $this->csv_safe( $r->birth_place ),
                $r->birth_date ?: '',
                $this->csv_safe( $r->gender ),
                $this->csv_safe( $r->address ),
                $this->csv_safe( $r->kabupaten ),
                $this->csv_safe( $r->kecamatan ),
                $this->csv_safe( $r->kelurahan ),
                $this->csv_safe( $r->occupation ),
                $this->csv_safe( $r->education ),
                $this->csv_safe( $r->status ),
                $r->registered_at ?: '',
                $r->verified_at ?: '',
            ];
            $csv .= implode( ';', $fields ) . "\n";
        }

        wp_send_json_success( [ 'csv' => $csv ] );
    }

    private function csv_safe( string $value ): string {
        $value = str_replace( '"', '""', $value );
        if ( strpos( $value, ';' ) !== false || strpos( $value, '"' ) !== false || strpos( $value, "\n" ) !== false ) {
            $value = '"' . $value . '"';
        }
        return $value;
    }

    public function ajax_smtp_test(): void {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => 'Unauthorized' ] );
        }

        $to = sanitize_email( $_POST['email'] ?? '' );
        if ( ! $to ) {
            wp_send_json_error( [ 'message' => __( 'Masukkan email yang valid.', 'psi-papeng-premium' ) ] );
        }

        $sent = wp_mail( $to, 'PSI Papeng — Email Test', '<p>Ini adalah email tes dari <strong>DPW PSI Papua Pegunungan</strong>. Jika Anda menerima email ini, konfigurasi SMTP sudah berfungsi dengan baik.</p>' );

        if ( $sent ) {
            wp_send_json_success( [ 'message' => __( 'Email tes berhasil dikirim ke ' . $to, 'psi-papeng-premium' ) ] );
        } else {
            wp_send_json_error( [ 'message' => __( 'Gagal mengirim email. Periksa kembali pengaturan SMTP.', 'psi-papeng-premium' ) ] );
        }
    }

    public function login_styles(): void {
        wp_enqueue_style( 'psi-papeng-login', PSI_PAPENG_URI . 'assets/css/admin-login.css', [], PSI_PAPENG_VERSION );
    }

    public function login_branding(): void {
        ?>
        <div class="psi-login-branding">
            <svg class="psi-login-logo" viewBox="0 0 60 60" style="display:inline-block;margin-bottom:8px;">
                <circle cx="30" cy="30" r="28" fill="#D6001C" stroke="#D4AF37" stroke-width="2"/>
                <text x="30" y="37" text-anchor="middle" fill="#FFF" font-family="Poppins,sans-serif" font-weight="800" font-size="16">PSI</text>
            </svg>
            <div class="psi-login-title">DPW PSI</div>
            <div class="psi-login-subtitle">Papua Pegunungan</div>
        </div>
        <?php
    }
}
