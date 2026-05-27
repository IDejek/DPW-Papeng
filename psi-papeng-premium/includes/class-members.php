<?php
/**
 * Member Management Class
 *
 * @package PSI_Papeng_Premium
 */

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Members {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // AJAX handlers
        add_action( 'wp_ajax_psi_member_add', array( $this, 'ajax_add_member' ) );
        add_action( 'wp_ajax_psi_member_update', array( $this, 'ajax_update_member' ) );
        add_action( 'wp_ajax_psi_member_delete', array( $this, 'ajax_delete_member' ) );
        add_action( 'wp_ajax_psi_member_verify', array( $this, 'ajax_verify_member' ) );
        add_action( 'wp_ajax_psi_member_export', array( $this, 'ajax_export_members' ) );
        add_action( 'wp_ajax_psi_member_stats', array( $this, 'ajax_get_stats' ) );
    }

    /**
     * Add Members admin page
     */
    public function add_menu_page() {
        add_menu_page(
            __( 'Anggota PSI', 'psi-papeng-premium' ),
            __( 'Anggota PSI', 'psi-papeng-premium' ),
            'manage_options',
            'psi-members',
            array( $this, 'render_page' ),
            'dashicons-id',
            31
        );

        add_submenu_page(
            'psi-members',
            __( 'Daftar Anggota', 'psi-papeng-premium' ),
            __( 'Daftar Anggota', 'psi-papeng-premium' ),
            'manage_options',
            'psi-members',
            array( $this, 'render_page' )
        );

        add_submenu_page(
            'psi-members',
            __( 'Statistik', 'psi-papeng-premium' ),
            __( 'Statistik', 'psi-papeng-premium' ),
            'manage_options',
            'psi-members-stats',
            array( $this, 'render_stats_page' )
        );
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'psi-members' ) === false ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style( 'psi-papeng-admin', PSI_PAPENG_URI . 'assets/css/admin.css', array(), PSI_PAPENG_VER );
        wp_enqueue_script( 'psi-papeng-members-js', PSI_PAPENG_URI . 'assets/js/admin-members.js', array( 'jquery' ), PSI_PAPENG_VER, true );

        wp_localize_script( 'psi-papeng-members-js', 'psiMembers', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'psi_papeng_admin_nonce' ),
        ) );
    }

    /**
     * Get table name safely
     */
    private function get_table() {
        global $wpdb;
        return $wpdb->prefix . 'psi_members';
    }

    /**
     * Render Members List Page
     */
    public function render_page() {
        global $wpdb;
        $table = $this->get_table();

        // Check if table exists
        $table_exists = $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) === $table;

        if ( ! $table_exists ) {
            echo '<div class="wrap"><h1>Anggota PSI</h1><div class="notice notice-error"><p>Tabel database belum ada. Silakan deaktivasi dan aktifkan kembali plugin ini.</p></div></div>';
            return;
        }

        // Handle search and filter
        $search    = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
        $status    = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
        $kabupaten = isset( $_GET['kab'] ) ? sanitize_text_field( wp_unslash( $_GET['kab'] ) ) : '';
        $paged     = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
        $per_page  = 20;

        $where = '1=1';
        $args  = array();

        if ( $search ) {
            $where .= ' AND (full_name LIKE %s OR email LIKE %s OR nik LIKE %s)';
            $args[] = '%' . $wpdb->esc_like( $search ) . '%';
            $args[] = '%' . $wpdb->esc_like( $search ) . '%';
            $args[] = '%' . $wpdb->esc_like( $search ) . '%';
        }

        if ( $status ) {
            $where .= ' AND status = %s';
            $args[] = $status;
        }

        if ( $kabupaten ) {
            $where .= ' AND kabupaten = %s';
            $args[] = $kabupaten;
        }

        $offset = ( $paged - 1 ) * $per_page;

        if ( ! empty( $args ) ) {
            $total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE $where", $args ) );
            $members = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE $where ORDER BY registered_at DESC LIMIT $per_page OFFSET $offset", $args ) );
        } else {
            $total = $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE $where" );
            $members = $wpdb->get_results( "SELECT * FROM $table WHERE $where ORDER BY registered_at DESC LIMIT $per_page OFFSET $offset" );
        }

        $total_pages = ceil( $total / $per_page );

        // Get unique kabupaten for filter
        $kab_list = $wpdb->get_col( "SELECT DISTINCT kabupaten FROM $table WHERE kabupaten != '' ORDER BY kabupaten" );

        ?>
        <div class="wrap psi-admin-wrap">
            <div class="psi-admin-header" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <h1 style="margin:0;">Anggota PSI <span style="font-size:0.8em;color:#646970;">(<?php echo esc_html( $total ); ?> total)</span></h1>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button class="button" id="exportMembersBtn"><i class="dashicons dashicons-download" style="vertical-align:middle;"></i> Export CSV</button>
                        <button class="button button-primary" id="addMemberBtn"><i class="dashicons dashicons-plus-alt2" style="vertical-align:middle;"></i> Tambah Anggota</button>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <form method="get" style="margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                <input type="hidden" name="page" value="psi-members">
                <input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="Cari nama, email, NIK..." class="regular-text" style="min-width:250px;">
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="pending" <?php selected( $status, 'pending' ); ?>>Pending</option>
                    <option value="verified" <?php selected( $status, 'verified' ); ?>>Terverifikasi</option>
                    <option value="rejected" <?php selected( $status, 'rejected' ); ?>>Ditolak</option>
                </select>
                <?php if ( ! empty( $kab_list ) ) : ?>
                <select name="kab">
                    <option value="">Semua Kabupaten</option>
                    <?php foreach ( $kab_list as $kab ) : ?>
                        <option value="<?php echo esc_attr( $kab ); ?>" <?php selected( $kabupaten, $kab ); ?>><?php echo esc_html( $kab ); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>
                <button type="submit" class="button">Filter</button>
                <?php if ( $search || $status || $kabupaten ) : ?>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-members' ) ); ?>" class="button">Reset</a>
                <?php endif; ?>
            </form>

            <!-- Members Table -->
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:50px;">ID</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Email</th>
                        <th>WhatsApp</th>
                        <th>Kabupaten</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $members ) ) : ?>
                        <tr><td colspan="9" style="text-align:center; padding:40px;">Tidak ada data anggota.</td></tr>
                    <?php else : ?>
                        <?php foreach ( $members as $m ) : ?>
                            <tr>
                                <td><?php echo esc_html( $m->id ); ?></td>
                                <td><strong><?php echo esc_html( $m->full_name ); ?></strong></td>
                                <td><?php echo esc_html( $m->nik ); ?></td>
                                <td><?php echo esc_html( $m->email ); ?></td>
                                <td><?php echo esc_html( $m->phone ); ?></td>
                                <td><?php echo esc_html( $m->kabupaten ); ?></td>
                                <td>
                                    <?php
                                    $status_colors = array(
                                        'pending'  => '#d4af37',
                                        'verified' => '#16a34a',
                                        'rejected' => '#dc2626',
                                    );
                                    $color = $status_colors[ $m->status ] ?? '#666';
                                    echo '<span style="color:' . esc_attr( $color ) . ';font-weight:600;text-transform:capitalize;">' . esc_html( $m->status ) . '</span>';
                                    ?>
                                </td>
                                <td><?php echo esc_html( date( 'd M Y', strtotime( $m->registered_at ) ) ); ?></td>
                                <td>
                                    <?php if ( $m->status === 'pending' ) : ?>
                                        <button class="button button-small psi-verify-member" data-id="<?php echo esc_attr( $m->id ); ?>" title="Verifikasi" style="color:#16a34a;"><i class="dashicons dashicons-yes-alt"></i></button>
                                    <?php endif; ?>
                                    <button class="button button-small psi-edit-member" data-id="<?php echo esc_attr( $m->id ); ?>" title="Edit"><i class="dashicons dashicons-edit"></i></button>
                                    <button class="button button-small psi-delete-member" data-id="<?php echo esc_attr( $m->id ); ?>" title="Hapus" style="color:#dc2626;"><i class="dashicons dashicons-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ( $total_pages > 1 ) : ?>
            <div class="tablenav bottom" style="margin-top: 16px;">
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo esc_html( $total ); ?> item</span>
                    <span class="pagination-links">
                        <?php
                        for ( $p = 1; $p <= $total_pages; $p++ ) {
                            $active = $p === $paged ? 'button button-primary' : 'button';
                            echo '<a href="' . esc_url( add_query_arg( array( 'page' => 'psi-members', 'paged' => $p, 's' => $search, 'status' => $status, 'kab' => $kabupaten ), admin_url( 'admin.php' ) ) ) . '" class="' . esc_attr( $active ) . '" style="margin:0 2px;">' . esc_html( $p ) . '</a>';
                        }
                        ?>
                    </span>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Add/Edit Member Modal -->
        <div id="memberModal" class="psi-modal" style="display:none;">
            <div class="psi-modal-backdrop"></div>
            <div class="psi-modal-content" style="max-width:600px;">
                <div class="psi-modal-header">
                    <h2 id="memberModalTitle">Tambah Anggota</h2>
                    <button class="psi-modal-close">&times;</button>
                </div>
                <div class="psi-modal-body">
                    <input type="hidden" id="memberEditId" value="">
                    <div class="psi-field-row">
                        <div class="psi-field">
                            <label>Nama Lengkap <span style="color:red;">*</span></label>
                            <input type="text" id="memberName" class="large-text" required>
                        </div>
                        <div class="psi-field">
                            <label>NIK <span style="color:red;">*</span></label>
                            <input type="text" id="memberNik" class="large-text" maxlength="16" required>
                        </div>
                    </div>
                    <div class="psi-field-row">
                        <div class="psi-field">
                            <label>Email <span style="color:red;">*</span></label>
                            <input type="email" id="memberEmail" class="large-text" required>
                        </div>
                        <div class="psi-field">
                            <label>No. WhatsApp <span style="color:red;">*</span></label>
                            <input type="text" id="memberPhone" class="large-text" required>
                        </div>
                    </div>
                    <div class="psi-field-row">
                        <div class="psi-field">
                            <label>Kabupaten <span style="color:red;">*</span></label>
                            <input type="text" id="memberKab" class="large-text" required>
                        </div>
                        <div class="psi-field">
                            <label>Kecamatan</label>
                            <input type="text" id="memberKec" class="large-text">
                        </div>
                    </div>
                    <div class="psi-field">
                        <label>Alamat</label>
                        <textarea id="memberAddress" class="large-text" rows="2"></textarea>
                    </div>
                    <div class="psi-field-row">
                        <div class="psi-field">
                            <label>Tanggal Lahir</label>
                            <input type="date" id="memberDob" class="large-text">
                        </div>
                        <div class="psi-field">
                            <label>Jenis Kelamin</label>
                            <select id="memberGender" class="large-text">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="psi-field">
                        <label>Pekerjaan</label>
                        <input type="text" id="memberOccupation" class="large-text">
                    </div>
                    <div class="psi-field">
                        <label>Catatan</label>
                        <textarea id="memberNotes" class="large-text" rows="2"></textarea>
                    </div>
                </div>
                <div class="psi-modal-footer">
                    <button class="button" id="memberModalCancel">Batal</button>
                    <button class="button button-primary" id="memberModalSave">Simpan</button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render Stats Page
     */
    public function render_stats_page() {
        global $wpdb;
        $table = $this->get_table();
        $table_exists = $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) === $table;

        if ( ! $table_exists ) {
            echo '<div class="wrap"><h1>Statistik Anggota</h1><div class="notice notice-error"><p>Tabel database belum ada.</p></div></div>';
            return;
        }

        $total     = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
        $verified  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'verified'" );
        $pending   = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'pending'" );
        $rejected  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'rejected'" );

        $kab_stats = $wpdb->get_results( "SELECT kabupaten, COUNT(*) as total, SUM(status='verified') as verified FROM $table GROUP BY kabupaten ORDER BY total DESC" );

        ?>
        <div class="wrap psi-admin-wrap">
            <h1 style="margin-bottom: 24px;">Statistik Anggota</h1>

            <div class="psi-stats-grid">
                <div class="psi-stat-card" style="border-left: 4px solid #D6001C;">
                    <div class="psi-stat-number"><?php echo esc_html( $total ); ?></div>
                    <div class="psi-stat-label">Total Anggota</div>
                </div>
                <div class="psi-stat-card" style="border-left: 4px solid #16a34a;">
                    <div class="psi-stat-number"><?php echo esc_html( $verified ); ?></div>
                    <div class="psi-stat-label">Terverifikasi</div>
                </div>
                <div class="psi-stat-card" style="border-left: 4px solid #d4af37;">
                    <div class="psi-stat-number"><?php echo esc_html( $pending ); ?></div>
                    <div class="psi-stat-label">Pending</div>
                </div>
                <div class="psi-stat-card" style="border-left: 4px solid #dc2626;">
                    <div class="psi-stat-number"><?php echo esc_html( $rejected ); ?></div>
                    <div class="psi-stat-label">Ditolak</div>
                </div>
            </div>

            <?php if ( ! empty( $kab_stats ) ) : ?>
            <h2 style="margin-top: 40px;">Statistik per Kabupaten</h2>
            <table class="wp-list-table widefat fixed striped" style="margin-top: 16px;">
                <thead>
                    <tr>
                        <th>Kabupaten</th>
                        <th>Total</th>
                        <th>Terverifikasi</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $kab_stats as $row ) :
                        $pct = $row->total > 0 ? round( ( $row->verified / $row->total ) * 100 ) : 0;
                    ?>
                        <tr>
                            <td><strong><?php echo esc_html( $row->kabupaten ); ?></strong></td>
                            <td><?php echo esc_html( $row->total ); ?></td>
                            <td><?php echo esc_html( $row->verified ); ?></td>
                            <td>
                                <div style="background:#e5e7eb;border-radius:4px;height:8px;width:150px;">
                                    <div style="background:#16a34a;border-radius:4px;height:8px;width:<?php echo esc_attr( $pct ); ?>%;"></div>
                                </div>
                                <span style="font-size:0.8em;color:#646970;"><?php echo esc_html( $pct ); ?>%</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * AJAX: Add Member
     */
    public function ajax_add_member() {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );

        global $wpdb;
        $table = $this->get_table();

        $name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
        $nik     = sanitize_text_field( wp_unslash( $_POST['nik'] ?? '' ) );
        $email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
        $phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
        $kab     = sanitize_text_field( wp_unslash( $_POST['kabupaten'] ?? '' ) );

        if ( empty( $name ) || empty( $nik ) || empty( $email ) || empty( $phone ) || empty( $kab ) ) {
            wp_send_json_error( array( 'message' => 'Mohon lengkapi kolom wajib.' ) );
        }

        // Check duplicate email
        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE email = %s", $email ) );
        if ( $exists ) {
            wp_send_json_error( array( 'message' => 'Email sudah terdaftar.' ) );
        }

        $result = $wpdb->insert( $table, array(
            'full_name'  => $name,
            'nik'        => $nik,
            'email'      => $email,
            'phone'      => $phone,
            'kabupaten'  => $kab,
            'kecamatan'  => sanitize_text_field( wp_unslash( $_POST['kecamatan'] ?? '' ) ),
            'kelurahan'  => sanitize_text_field( wp_unslash( $_POST['kelurahan'] ?? '' ) ),
            'address'    => sanitize_textarea_field( wp_unslash( $_POST['address'] ?? '' ) ),
            'birth_date' => ! empty( $_POST['dob'] ) ? sanitize_text_field( wp_unslash( $_POST['dob'] ) ) : null,
            'gender'     => sanitize_text_field( wp_unslash( $_POST['gender'] ?? '' ) ),
            'occupation' => sanitize_text_field( wp_unslash( $_POST['occupation'] ?? '' ) ),
            'status'     => 'pending',
            'notes'      => sanitize_textarea_field( wp_unslash( $_POST['notes'] ?? '' ) ),
        ), array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) );

        if ( $result ) {
            wp_send_json_success( array( 'message' => 'Anggota berhasil ditambahkan.' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Gagal menambahkan anggota.' ) );
        }
    }

    /**
     * AJAX: Update Member
     */
    public function ajax_update_member() {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );

        global $wpdb;
        $table = $this->get_table();
        $id = absint( $_POST['id'] ?? 0 );

        if ( ! $id ) wp_send_json_error( array( 'message' => 'ID tidak valid.' ) );

        $result = $wpdb->update( $table, array(
            'full_name'  => sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) ),
            'nik'        => sanitize_text_field( wp_unslash( $_POST['nik'] ?? '' ) ),
            'email'      => sanitize_email( wp_unslash( $_POST['email'] ?? '' ) ),
            'phone'      => sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) ),
            'kabupaten'  => sanitize_text_field( wp_unslash( $_POST['kabupaten'] ?? '' ) ),
            'kecamatan'  => sanitize_text_field( wp_unslash( $_POST['kecamatan'] ?? '' ) ),
            'address'    => sanitize_textarea_field( wp_unslash( $_POST['address'] ?? '' ) ),
            'birth_date' => ! empty( $_POST['dob'] ) ? sanitize_text_field( wp_unslash( $_POST['dob'] ) ) : null,
            'gender'     => sanitize_text_field( wp_unslash( $_POST['gender'] ?? '' ) ),
            'occupation' => sanitize_text_field( wp_unslash( $_POST['occupation'] ?? '' ) ),
            'notes'      => sanitize_textarea_field( wp_unslash( $_POST['notes'] ?? '' ) ),
        ), array( 'id' => $id ) );

        if ( false !== $result ) {
            wp_send_json_success( array( 'message' => 'Data anggota berhasil diperbarui.' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Gagal memperbarui data.' ) );
        }
    }

    /**
     * AJAX: Delete Member
     */
    public function ajax_delete_member() {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );

        global $wpdb;
        $table = $this->get_table();
        $id = absint( $_POST['id'] ?? 0 );

        if ( ! $id ) wp_send_json_error( array( 'message' => 'ID tidak valid.' ) );

        $result = $wpdb->delete( $table, array( 'id' => $id ), array( '%d' ) );

        if ( $result ) {
            wp_send_json_success( array( 'message' => 'Anggota berhasil dihapus.' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Gagal menghapus anggota.' ) );
        }
    }

    /**
     * AJAX: Verify Member
     */
    public function ajax_verify_member() {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );

        global $wpdb;
        $table = $this->get_table();
        $id = absint( $_POST['id'] ?? 0 );

        if ( ! $id ) wp_send_json_error( array( 'message' => 'ID tidak valid.' ) );

        $result = $wpdb->update( $table, array(
            'status'      => 'verified',
            'verified_at' => current_time( 'mysql' ),
        ), array( 'id' => $id ) );

        if ( false !== $result ) {
            wp_send_json_success( array( 'message' => 'Anggota berhasil diverifikasi.' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Gagal memverifikasi.' ) );
        }
    }

    /**
     * AJAX: Export Members CSV
     */
    public function ajax_export_members() {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );

        global $wpdb;
        $table = $this->get_table();
        $members = $wpdb->get_results( "SELECT * FROM $table ORDER BY registered_at DESC" );

        if ( empty( $members ) ) {
            wp_send_json_error( array( 'message' => 'Tidak ada data untuk diekspor.' ) );
        }

        // Generate CSV
        $filename = 'anggota-psi-' . date( 'Y-m-d' ) . '.csv';
        $filepath = wp_upload_dir()['path'] . '/' . $filename;

        $fh = fopen( $filepath, 'w' );
        // BOM for UTF-8 Excel compatibility
        fwrite( $fh, "\xEF\xBB\xBF" );
        fputcsv( $fh, array( 'ID', 'Nama Lengkap', 'NIK', 'Email', 'WhatsApp', 'Kabupaten', 'Kecamatan', 'Alamat', 'Tanggal Lahir', 'Jenis Kelamin', 'Pekerjaan', 'Status', 'Tanggal Daftar', 'Tanggal Verifikasi' ) );

        foreach ( $members as $m ) {
            fputcsv( $fh, array(
                $m->id, $m->full_name, $m->nik, $m->email, $m->phone,
                $m->kabupaten, $m->kecamatan, $m->address, $m->birth_date,
                $m->gender, $m->occupation, $m->status, $m->registered_at, $m->verified_at,
            ) );
        }
        fclose( $fh );

        wp_send_json_success( array(
            'message'  => 'Export berhasil.',
            'url'      => wp_upload_dir()['url'] . '/' . $filename,
            'filename' => $filename,
        ) );
    }

        /**
     * AJAX: Get Stats
     */
    public function ajax_get_stats() {
        check_ajax_referer('psi_papeng_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
            return; // WSOD Fix: Selalu return setelah wp_send_json_error
        }

        global $wpdb;
        $table = $this->get_table();

        // WSOD Fix: Gunakan isset check karena table bisa null di PHP 8
        if (empty($table)) {
            wp_send_json_error(array('message' => 'Table not found'));
            return;
        }

        $total = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
        $verified = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'verified'");
        $pending = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'pending'");

        wp_send_json_success(array(
            'total' => $total,
            'verified' => $verified,
            'pending' => $pending,
        ));
    }
}
