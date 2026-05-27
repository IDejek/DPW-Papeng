<?php
/**
 * Member Management System
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Member_Management {

    private string $table;
    private string $table_kab;

    public function __construct() {
        global $wpdb;
        $this->table     = $wpdb->prefix . 'psi_members';
        $this->table_kab = $wpdb->prefix . 'psi_member_kabupaten';

        add_action( 'wp_ajax_psi_member_register', [ $this, 'ajax_register' ] );
        add_action( 'wp_ajax_nopriv_psi_member_register', [ $this, 'ajax_register' ] );
        add_action( 'wp_ajax_psi_member_verify', [ $this, 'ajax_verify' ] );
        add_action( 'wp_ajax_psi_member_delete', [ $this, 'ajax_delete' ] );
        add_shortcode( 'psi_member_form', [ $this, 'render_form' ] );
    }

    public static function create_tables(): void {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE {$wpdb->prefix}psi_members (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            full_name varchar(150) NOT NULL,
            email varchar(150) NOT NULL,
            phone varchar(30) NOT NULL,
            nik varchar(20) DEFAULT '',
            birth_place varchar(100) DEFAULT '',
            birth_date date DEFAULT NULL,
            gender varchar(10) DEFAULT '',
            address text DEFAULT NULL,
            kabupaten varchar(150) DEFAULT '',
            kecamatan varchar(150) DEFAULT '',
            kelurahan varchar(150) DEFAULT '',
            occupation varchar(150) DEFAULT '',
            education varchar(100) DEFAULT '',
            photo varchar(255) DEFAULT '',
            status varchar(20) NOT NULL DEFAULT 'pending',
            registered_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            verified_at datetime DEFAULT NULL,
            verified_by bigint(20) UNSIGNED DEFAULT NULL,
            notes text DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY email (email),
            KEY status (status),
            KEY kabupaten (kabupaten)
        ) $charset;";

        $sql2 = "CREATE TABLE {$wpdb->prefix}psi_member_kabupaten (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            kabupaten varchar(150) NOT NULL,
            member_count int(11) UNSIGNED NOT NULL DEFAULT 0,
            last_updated datetime DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY kabupaten (kabupaten)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql1 );
        dbDelta( $sql2 );
    }

    public function render_form( $atts ): string {
        $atts = shortcode_atts( [ 'redirect' => '' ], $atts, 'psi_member_form' );
        ob_start();
        ?>
        <div class="psi-member-form-wrapper">
            <form id="psi-member-form" class="row g-3" novalidate>
                <?php wp_nonce_field( 'psi_member_register', 'psi_member_nonce' ); ?>
                <div class="col-md-6">
                    <label for="psi-fullname" class="form-label fw-bold small"><?php esc_html_e( 'Nama Lengkap *', 'psi-papeng-premium' ); ?></label>
                    <input type="text" name="full_name" id="psi-fullname" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="psi-email" class="form-label fw-bold small"><?php esc_html_e( 'Email *', 'psi-papeng-premium' ); ?></label>
                    <input type="email" name="email" id="psi-email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="psi-phone" class="form-label fw-bold small"><?php esc_html_e( 'No. HP/WhatsApp *', 'psi-papeng-premium' ); ?></label>
                    <input type="text" name="phone" id="psi-phone" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="psi-nik" class="form-label fw-bold small"><?php esc_html_e( 'NIK', 'psi-papeng-premium' ); ?></label>
                    <input type="text" name="nik" id="psi-nik" class="form-control" maxlength="16">
                </div>
                <div class="col-md-4">
                    <label for="psi-birthplace" class="form-label fw-bold small"><?php esc_html_e( 'Tempat Lahir', 'psi-papeng-premium' ); ?></label>
                    <input type="text" name="birth_place" id="psi-birthplace" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="psi-birthdate" class="form-label fw-bold small"><?php esc_html_e( 'Tanggal Lahir', 'psi-papeng-premium' ); ?></label>
                    <input type="date" name="birth_date" id="psi-birthdate" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="psi-gender" class="form-label fw-bold small"><?php esc_html_e( 'Jenis Kelamin', 'psi-papeng-premium' ); ?></label>
                    <select name="gender" id="psi-gender" class="form-select">
                        <option value=""><?php esc_html_e( 'Pilih', 'psi-papeng-premium' ); ?></option>
                        <option value="L"><?php esc_html_e( 'Laki-laki', 'psi-papeng-premium' ); ?></option>
                        <option value="P"><?php esc_html_e( 'Perempuan', 'psi-papeng-premium' ); ?></option>
                    </select>
                </div>
                <div class="col-12">
                    <label for="psi-address" class="form-label fw-bold small"><?php esc_html_e( 'Alamat Lengkap', 'psi-papeng-premium' ); ?></label>
                    <textarea name="address" id="psi-address" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-4">
                    <label for="psi-kabupaten" class="form-label fw-bold small"><?php esc_html_e( 'Kabupaten *', 'psi-papeng-premium' ); ?></label>
                    <input type="text" name="kabupaten" id="psi-kabupaten" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="psi-kecamatan" class="form-label fw-bold small"><?php esc_html_e( 'Kecamatan', 'psi-papeng-premium' ); ?></label>
                    <input type="text" name="kecamatan" id="psi-kecamatan" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="psi-kelurahan" class="form-label fw-bold small"><?php esc_html_e( 'Kelurahan/Desa', 'psi-papeng-premium' ); ?></label>
                    <input type="text" name="kelurahan" id="psi-kelurahan" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="psi-occupation" class="form-label fw-bold small"><?php esc_html_e( 'Pekerjaan', 'psi-papeng-premium' ); ?></label>
                    <input type="text" name="occupation" id="psi-occupation" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="psi-education" class="form-label fw-bold small"><?php esc_html_e( 'Pendidikan Terakhir', 'psi-papeng-premium' ); ?></label>
                    <select name="education" id="psi-education" class="form-select">
                        <option value=""><?php esc_html_e( 'Pilih', 'psi-papeng-premium' ); ?></option>
                        <option value="SD"><?php esc_html_e( 'SD', 'psi-papeng-premium' ); ?></option>
                        <option value="SMP"><?php esc_html_e( 'SMP', 'psi-papeng-premium' ); ?></option>
                        <option value="SMA"><?php esc_html_e( 'SMA/SMK', 'psi-papeng-premium' ); ?></option>
                        <option value="D3"><?php esc_html_e( 'Diploma', 'psi-papeng-premium' ); ?></option>
                        <option value="S1"><?php esc_html_e( 'Sarjana (S1)', 'psi-papeng-premium' ); ?></option>
                        <option value="S2"><?php esc_html_e( 'Magister (S2)', 'psi-papeng-premium' ); ?></option>
                        <option value="S3"><?php esc_html_e( 'Doktor (S3)', 'psi-papeng-premium' ); ?></option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-danger btn-lg fw-bold px-5">
                        <i class="bi bi-person-plus me-2"></i><?php esc_html_e( 'Daftar Sekarang', 'psi-papeng-premium' ); ?>
                    </button>
                </div>
                <input type="hidden" name="redirect" value="<?php echo esc_url( $atts['redirect'] ); ?>">
            </form>
            <div id="psi-member-result" class="mt-3"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function ajax_register(): void {
        check_ajax_referer( 'psi_member_register', 'nonce' );

        global $wpdb;
        $table = $this->table;

        $data = [
            'full_name'   => sanitize_text_field( $_POST['full_name'] ?? '' ),
            'email'       => sanitize_email( $_POST['email'] ?? '' ),
            'phone'       => sanitize_text_field( $_POST['phone'] ?? '' ),
            'nik'         => preg_replace( '/[^0-9]/', '', $_POST['nik'] ?? '' ),
            'birth_place' => sanitize_text_field( $_POST['birth_place'] ?? '' ),
            'birth_date'  => ! empty( $_POST['birth_date'] ) ? sanitize_text_field( $_POST['birth_date'] ) : null,
            'gender'      => in_array( $_POST['gender'] ?? '', [ 'L', 'P' ], true ) ? $_POST['gender'] : '',
            'address'     => sanitize_textarea_field( $_POST['address'] ?? '' ),
            'kabupaten'   => sanitize_text_field( $_POST['kabupaten'] ?? '' ),
            'kecamatan'   => sanitize_text_field( $_POST['kecamatan'] ?? '' ),
            'kelurahan'   => sanitize_text_field( $_POST['kelurahan'] ?? '' ),
            'occupation'  => sanitize_text_field( $_POST['occupation'] ?? '' ),
            'education'   => sanitize_text_field( $_POST['education'] ?? '' ),
            'status'      => 'pending',
        ];

        if ( empty( $data['full_name'] ) || empty( $data['email'] ) || empty( $data['phone'] ) || empty( $data['kabupaten'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Field bertanda * wajib diisi.', 'psi-papeng-premium' ) ] );
        }
        if ( ! is_email( $data['email'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Format email tidak valid.', 'psi-papeng-premium' ) ] );
        }
        if ( $data['nik'] && strlen( $data['nik'] ) !== 16 ) {
            wp_send_json_error( [ 'message' => __( 'NIK harus 16 digit.', 'psi-papeng-premium' ) ] );
        }

        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE email = %s", $data['email'] ) );
        if ( $exists ) {
            wp_send_json_error( [ 'message' => __( 'Email sudah terdaftar.', 'psi-papeng-premium' ) ] );
        }

        $format = [ '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ];
        $inserted = $wpdb->insert( $table, $data, $format );

        if ( ! $inserted ) {
            wp_send_json_error( [ 'message' => __( 'Gagal mendaftar. Silakan coba lagi.', 'psi-papeng-premium' ) ] );
        }

        $member_id = $wpdb->insert_id;

        // Update kabupaten stats
        $this->update_kab_stats( $data['kabupaten'] );

        // Log activity
        if ( class_exists( 'PSI_Papeng_Activity_Log' ) ) {
            PSI_Papeng_Activity_Log::log( 'member_registered', sprintf( 'Pendaftaran anggota baru: %s (%s)', $data['full_name'], $data['email'] ) );
        }

        // Send email
        if ( class_exists( 'PSI_Papeng_Email' ) ) {
            PSI_Papeng_Email::send_registration_notification( $data, $member_id );
        }

        // WhatsApp notification
        if ( class_exists( 'PSI_Papeng_WhatsApp' ) ) {
            PSI_Papeng_WhatsApp::notify_new_member( $data );
        }

        wp_send_json_success( [
            'message'  => __( 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi.', 'psi-papeng-premium' ),
            'redirect' => esc_url_raw( $_POST['redirect'] ?? '' ),
        ] );
    }

    public function ajax_verify(): void {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => 'Unauthorized' ] );
        }

        global $wpdb;
        $id     = absint( $_POST['member_id'] ?? 0 );
        $status = sanitize_text_field( $_POST['status'] ?? 'verified' );

        if ( ! $id ) wp_send_json_error( [ 'message' => 'Invalid ID' ] );
        if ( ! in_array( $status, [ 'verified', 'rejected' ], true ) ) {
            wp_send_json_error( [ 'message' => 'Invalid status' ] );
        }

        $updated = $wpdb->update(
            $this->table,
            [
                'status'      => $status,
                'verified_at' => current_time( 'mysql' ),
                'verified_by' => get_current_user_id(),
            ],
            [ 'id' => $id ],
            [ '%s', '%s', '%d' ],
            [ '%d' ]
        );

        if ( false === $updated ) {
            wp_send_json_error( [ 'message' => __( 'Gagal memperbarui status.', 'psi-papeng-premium' ) ] );
        }

        // Send verification email
        if ( $status === 'verified' && class_exists( 'PSI_Papeng_Email' ) ) {
            $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE id = %d", $id ) );
            if ( $member ) {
                PSI_Papeng_Email::send_verification_email( (array) $member );
            }
        }

        PSI_Papeng_Activity_Log::log( 'member_' . $status, sprintf( 'Anggota ID %d di-%s', $id, $status ) );

        wp_send_json_success( [ 'message' => $status === 'verified' ? __( 'Anggota berhasil diverifikasi.', 'psi-papeng-premium' ) : __( 'Anggota ditolak.', 'psi-papeng-premium' ) ] );
    }

    public function ajax_delete(): void {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => 'Unauthorized' ] );
        }

        global $wpdb;
        $id = absint( $_POST['member_id'] ?? 0 );
        if ( ! $id ) wp_send_json_error( [ 'message' => 'Invalid ID' ] );

        $member = $wpdb->get_row( $wpdb->prepare( "SELECT kabupaten FROM {$this->table} WHERE id = %d", $id ) );
        $deleted = $wpdb->delete( $this->table, [ 'id' => $id ], [ '%d' ] );

        if ( $deleted && $member && $member->kabupaten ) {
            $this->update_kab_stats( $member->kabupaten );
        }

        PSI_Papeng_Activity_Log::log( 'member_deleted', sprintf( 'Anggota ID %d dihapus', $id ) );

        wp_send_json_success( [ 'message' => __( 'Anggota berhasil dihapus.', 'psi-papeng-premium' ) ] );
    }

    private function update_kab_stats( string $kabupaten ): void {
        global $wpdb;
        $count = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table} WHERE kabupaten = %s AND status = 'verified'",
            $kabupaten
        ) );
        $wpdb->replace(
            $this->table_kab,
            [
                'kabupaten'     => $kabupaten,
                'member_count'  => $count,
                'last_updated'  => current_time( 'mysql' ),
            ],
            [ '%s', '%d', '%s' ]
        );
    }

    public static function get_members( array $args = [] ): array {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';
        $per_page = absint( $args['per_page'] ?? 20 );
        $paged    = absint( $args['paged'] ?? 1 );
        $offset   = ( $paged - 1 ) * $per_page;
        $status   = sanitize_text_field( $args['status'] ?? '' );
        $search   = sanitize_text_field( $args['search'] ?? '' );
        $kab      = sanitize_text_field( $args['kabupaten'] ?? '' );
        $orderby  = in_array( $args['orderby'] ?? '', [ 'id', 'full_name', 'kabupaten', 'registered_at' ], true ) ? $args['orderby'] : 'id';
        $order    = in_array( strtoupper( $args['order'] ?? '' ), [ 'ASC', 'DESC' ], true ) ? strtoupper( $args['order'] ) : 'DESC';

        $where = '1=1';
        $values = [];

        if ( $status ) { $where .= ' AND status = %s'; $values[] = $status; }
        if ( $kab )    { $where .= ' AND kabupaten = %s'; $values[] = $kab; }
        if ( $search ) {
            $where .= ' AND (full_name LIKE %s OR email LIKE %s OR phone LIKE %s)';
            $like = '%' . $wpdb->esc_like( $search ) . '%';
            $values[] = $like;
            $values[] = $like;
            $values[] = $like;
        }

        $total = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE {$where}", $values ) );
        $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d";
        $values[] = $per_page;
        $values[] = $offset;
        $rows = $wpdb->get_results( $wpdb->prepare( $sql, $values ) );

        return [
            'rows'      => $rows,
            'total'     => $total,
            'per_page'  => $per_page,
            'paged'     => $paged,
            'total_pages' => (int) ceil( $total / max( $per_page, 1 ) ),
        ];
    }

    public static function get_total_count( string $status = '' ): int {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';
        if ( $status ) {
            return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE status = %s", $status ) );
        }
        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );
    }

    public static function get_kab_stats(): array {
        global $wpdb;
        return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}psi_member_kabupaten ORDER BY member_count DESC" );
    }
}
