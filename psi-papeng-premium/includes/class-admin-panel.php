<?php
/**
 * Premium Admin Panel
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Admin_Panel {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menus' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'admin_init', [ $this, 'register_smtp_settings' ] );
    }

    public function register_menus(): void {
        // Main menu
        add_menu_page(
            __( 'PSI Papeng Premium', 'psi-papeng-premium' ),
            __( 'PSI Papeng', 'psi-papeng-premium' ),
            'manage_options',
            'psi-papeng',
            [ $this, 'render_dashboard_page' ],
            'dashicons-shield-alt',
            30
        );

        // Sub-menus
        add_submenu_page( 'psi-papeng', __( 'Dashboard', 'psi-papeng-premium' ), __( 'Dashboard', 'psi-papeng-premium' ), 'manage_options', 'psi-papeng', [ $this, 'render_dashboard_page' ] );
        add_submenu_page( 'psi-papeng', __( 'Anggota', 'psi-papeng-premium' ), __( 'Anggota', 'psi-papeng-premium' ), 'manage_options', 'psi-papeng-members', [ $this, 'render_members_page' ] );
        add_submenu_page( 'psi-papeng', __( 'Statistik', 'psi-papeng-premium' ), __( 'Statistik', 'psi-papeng-premium' ), 'manage_options', 'psi-papeng-statistics', [ $this, 'render_statistics_page' ] );
        add_submenu_page( 'psi-papeng', __( 'Aktivitas', 'psi-papeng-premium' ), __( 'Aktivitas', 'psi-papeng-premium' ), 'manage_options', 'psi-papeng-activity', [ $this, 'render_activity_page' ] );
        add_submenu_page( 'psi-papeng', __( 'Pengaturan SMTP', 'psi-papeng-premium' ), __( 'SMTP', 'psi-papeng-premium' ), 'manage_options', 'psi-papeng-smtp', [ $this, 'render_smtp_page' ] );
    }

    public function enqueue_assets( string $hook ): void {
        if ( strpos( $hook, 'psi-papeng' ) === false ) return;

        wp_enqueue_style(
            'psi-papeng-admin',
            PSI_PAPENG_URI . 'assets/css/admin.css',
            [],
            PSI_PAPENG_VERSION
        );
        wp_enqueue_script(
            'psi-papeng-admin',
            PSI_PAPENG_URI . 'assets/js/admin.js',
            [ 'jquery' ],
            PSI_PAPENG_VERSION,
            true
        );
        wp_localize_script( 'psi-papeng-admin', 'psiPapengAdmin', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'psi_papeng_admin_nonce' ),
            'i18n'    => [
                'confirm_delete' => __( 'Yakin ingin menghapus anggota ini?', 'psi-papeng-premium' ),
                'confirm_verify' => __( 'Yakin ingin memverifikasi anggota ini?', 'psi-papeng-premium' ),
                'confirm_reject' => __( 'Yakin ingin menolak anggota ini?', 'psi-papeng-premium' ),
            ],
        ] );
    }

    public function register_smtp_settings(): void {
        // Settings are registered in SEO class, but we add the settings page section here
        add_settings_section( 'psi_smtp_section', __( 'Konfigurasi SMTP', 'psi-papeng-premium' ), '__return_false', 'psi_papeng_smtp' );

        add_settings_field( 'psi_smtp_host', __( 'SMTP Host', 'psi-papeng-premium' ), [ $this, 'render_text_field' ], 'psi_papeng_smtp', 'psi_smtp_section', [ 'field' => 'psi_smtp_host', 'placeholder' => 'smtp.gmail.com' ] );
        add_settings_field( 'psi_smtp_port', __( 'SMTP Port', 'psi-papeng-premium' ), [ $this, 'render_text_field' ], 'psi_papeng_smtp', 'psi_smtp_section', [ 'field' => 'psi_smtp_port', 'placeholder' => '587' ] );
        add_settings_field( 'psi_smtp_secure', __( 'Enkripsi', 'psi-papeng-premium' ), [ $this, 'render_select_field' ], 'psi_papeng_smtp', 'psi_smtp_section', [ 'field' => 'psi_smtp_secure', 'options' => [ 'tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None' ] ] );
        add_settings_field( 'psi_smtp_auth', __( 'Autentikasi', 'psi-papeng-premium' ), [ $this, 'render_checkbox_field' ], 'psi_papeng_smtp', 'psi_smtp_section', [ 'field' => 'psi_smtp_auth' ] );
        add_settings_field( 'psi_smtp_user', __( 'Username SMTP', 'psi-papeng-premium' ), [ $this, 'render_text_field' ], 'psi_papeng_smtp', 'psi_smtp_section', [ 'field' => 'psi_smtp_user' ] );
        add_settings_field( 'psi_smtp_pass', __( 'Password SMTP', 'psi-papeng-premium' ), [ $this, 'render_password_field' ], 'psi_papeng_smtp', 'psi_smtp_section', [ 'field' => 'psi_smtp_pass' ] );
        add_settings_field( 'psi_smtp_from', __( 'Email Pengirim', 'psi-papeng-premium' ), [ $this, 'render_text_field' ], 'psi_papeng_smtp', 'psi_smtp_section', [ 'field' => 'psi_smtp_from' ] );
        add_settings_field( 'psi_smtp_from_name', __( 'Nama Pengirim', 'psi-papeng-premium' ), [ $this, 'render_text_field' ], 'psi_papeng_smtp', 'psi_smtp_section', [ 'field' => 'psi_smtp_from_name' ] );
    }

    public function render_text_field( array $args ): void {
        $field = $args['field'] ?? '';
        $value = esc_attr( get_option( $field, '' ) );
        $placeholder = $args['placeholder'] ?? '';
        echo '<input type="text" name="' . esc_attr( $field ) . '" value="' . $value . '" class="regular-text" placeholder="' . esc_attr( $placeholder ) . '">';
    }

    public function render_password_field( array $args ): void {
        $field = $args['field'] ?? '';
        $value = esc_attr( get_option( $field, '' ) );
        echo '<input type="password" name="' . esc_attr( $field ) . '" value="' . $value . '" class="regular-text" autocomplete="new-password">';
    }

    public function render_select_field( array $args ): void {
        $field   = $args['field'] ?? '';
        $options = $args['options'] ?? [];
        $current = get_option( $field, '' );
        echo '<select name="' . esc_attr( $field ) . '">';
        foreach ( $options as $val => $label ) {
            echo '<option value="' . esc_attr( $val ) . '"' . selected( $current, $val, false ) . '>' . esc_html( $label ) . '</option>';
        }
        echo '</select>';
    }

    public function render_checkbox_field( array $args ): void {
        $field = $args['field'] ?? '';
        $value = get_option( $field, true );
        echo '<input type="checkbox" name="' . esc_attr( $field ) . '" value="1" ' . checked( $value, true, false ) . '>';
    }

    /* ═══ Dashboard Page ═════════════════════════════════════ */
    public function render_dashboard_page(): void {
        $total    = PSI_Papeng_Member_Management::get_total_count();
        $pending  = PSI_Papeng_Member_Management::get_total_count( 'pending' );
        $verified = PSI_Papeng_Member_Management::get_total_count( 'verified' );
        $rejected = PSI_Papeng_Member_Management::get_total_count( 'rejected' );
        $kab_stats = PSI_Papeng_Member_Management::get_kab_stats();
        ?>
        <div class="wrap psi-admin-wrap">
            <div class="psi-admin-header">
                <div class="psi-admin-logo">
                    <span class="psi-admin-logo-icon">PSI</span>
                </div>
                <div>
                    <h1 class="psi-admin-title"><?php esc_html_e( 'PSI Papeng Premium Dashboard', 'psi-papeng-premium' ); ?></h1>
                    <p class="psi-admin-subtitle"><?php esc_html_e( 'Sistem Manajemen DPW PSI Papua Pegunungan', 'psi-papeng-premium' ); ?></p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="psi-stats-row">
                <div class="psi-stat-card psi-stat-total">
                    <div class="psi-stat-icon"><span class="dashicons dashicons-groups"></span></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo number_format_i18n( $total ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Total Anggota', 'psi-papeng-premium' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-pending">
                    <div class="psi-stat-icon"><span class="dashicons dashicons-clock"></span></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo number_format_i18n( $pending ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Menunggu Verifikasi', 'psi-papeng-premium' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-verified">
                    <div class="psi-stat-icon"><span class="dashicons dashicons-yes-alt"></span></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo number_format_i18n( $verified ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Terverifikasi', 'psi-papeng-premium' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-rejected">
                    <div class="psi-stat-icon"><span class="dashicons dashicons-no-alt"></span></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo number_format_i18n( $rejected ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Ditolak', 'psi-papeng-premium' ); ?></span>
                    </div>
                </div>
            </div>

            <!-- Kabupaten Stats -->
            <?php if ( ! empty( $kab_stats ) ) : ?>
            <div class="psi-admin-card">
                <div class="psi-card-header">
                    <h2><span class="dashicons dashicons-location-alt" style="color:#D6001C"></span> <?php esc_html_e( 'Statistik Per Kabupaten', 'psi-papeng-premium' ); ?></h2>
                </div>
                <div class="psi-card-body">
                    <div class="psi-kab-stats-grid">
                        <?php foreach ( $kab_stats as $ks ) :
                            $pct = $verified > 0 ? round( ( (int) $ks->member_count / $verified ) * 100, 1 ) : 0;
                        ?>
                        <div class="psi-kab-stat-item">
                            <div class="psi-kab-stat-header">
                                <span class="psi-kab-name"><?php echo esc_html( $ks->kabupaten ); ?></span>
                                <span class="psi-kab-count"><?php echo number_format_i18n( (int) $ks->member_count ); ?></span>
                            </div>
                            <div class="psi-kab-bar">
                                <div class="psi-kab-bar-fill" style="width:<?php echo esc_attr( $pct ); ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Links -->
            <div class="psi-admin-card">
                <div class="psi-card-header">
                    <h2><span class="dashicons dashicons-admin-links" style="color:#D6001C"></span> <?php esc_html_e( 'Akses Cepat', 'psi-papeng-premium' ); ?></h2>
                </div>
                <div class="psi-card-body">
                    <div class="psi-quick-links">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-members' ) ); ?>" class="psi-quick-link-btn">
                            <span class="dashicons dashicons-admin-users"></span> <?php esc_html_e( 'Kelola Anggota', 'psi-papeng-premium' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-activity' ) ); ?>" class="psi-quick-link-btn">
                            <span class="dashicons dashicons-history"></span> <?php esc_html_e( 'Log Aktivitas', 'psi-papeng-premium' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-smtp' ); ?>" class="psi-quick-link-btn">
                            <span class="dashicons dashicons-email-alt"></span> <?php esc_html_e( 'Pengaturan SMTP', 'psi-papeng-premium' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=slider' ) ); ?>" class="psi-quick-link-btn">
                            <span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e( 'Tambah Slide', 'psi-papeng-premium' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=leadership' ) ); ?>" class="psi-quick-link-btn">
                            <span class="dashicons dashicons-id-alt"></span> <?php esc_html_e( 'Tambah Pimpinan', 'psi-papeng-premium' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=dpd' ) ); ?>" class="psi-quick-link-btn">
                            <span class="dashicons dashicons-location-alt"></span> <?php esc_html_e( 'Tambah DPD', 'psi-papeng-premium' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /* ═══ Members Page ════════════════════════════════════════ */
    public function render_members_page(): void {
        $paged  = absint( $_GET['paged'] ?? 1 );
        $status = sanitize_text_field( $_GET['status'] ?? '' );
        $search = sanitize_text_field( $_GET['s'] ?? '' );
        $kab    = sanitize_text_field( $_GET['kab'] ?? '' );

        $data = PSI_Papeng_Member_Management::get_members( [
            'paged'    => $paged,
            'status'   => $status,
            'search'   => $search,
            'kabupaten'=> $kab,
            'per_page' => 20,
        ] );
        ?>
        <div class="wrap psi-admin-wrap">
            <div class="psi-admin-header">
                <h1 class="psi-admin-title"><?php esc_html_e( 'Manajemen Anggota', 'psi-papeng-premium' ); ?></h1>
            </div>

            <!-- Filters -->
            <div class="psi-admin-card">
                <div class="psi-card-body">
                    <form method="get" class="psi-filter-row">
                        <input type="hidden" name="page" value="psi-papeng-members">
                        <div class="psi-filter-group">
                            <input type="text" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Cari nama, email, telepon...', 'psi-papeng-premium' ); ?>" class="psi-filter-input">
                        </div>
                        <div class="psi-filter-group">
                            <select name="status" class="psi-filter-select">
                                <option value=""><?php esc_html_e( 'Semua Status', 'psi-papeng-premium' ); ?></option>
                                <option value="pending" <?php selected( $status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'psi-papeng-premium' ); ?></option>
                                <option value="verified" <?php selected( $status, 'verified' ); ?>><?php esc_html_e( 'Terverifikasi', 'psi-papeng-premium' ); ?></option>
                                <option value="rejected" <?php selected( $status, 'rejected' ); ?>><?php esc_html_e( 'Ditolak', 'psi-papeng-premium' ); ?></option>
                            </select>
                        </div>
                        <div class="psi-filter-group">
                            <input type="text" name="kab" value="<?php echo esc_attr( $kab ); ?>" placeholder="<?php esc_attr_e( 'Kabupaten', 'psi-papeng-premium' ); ?>" class="psi-filter-input">
                        </div>
                        <button type="submit" class="button button-primary psi-filter-btn"><?php esc_html_e( 'Filter', 'psi-papeng-premium' ); ?></button>
                        <?php if ( $status || $search || $kab ) : ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-members' ) ); ?>" class="button"><?php esc_html_e( 'Reset', 'psi-papeng-premium' ); ?></a>
                        <?php endif; ?>
                    </form>

                    <!-- Export -->
                    <div class="psi-export-row">
                        <button type="button" class="button psi-export-btn" id="psiExportCsv">
                            <span class="dashicons dashicons-download" style="vertical-align:middle;margin-right:4px;"></span>
                            <?php esc_html_e( 'Ekspor CSV', 'psi-papeng-premium' ); ?>
                        </button>
                        <span class="psi-result-count">
                            <?php printf( esc_html__( 'Menampilkan %d dari %d data', 'psi-papeng-premium' ), count( $data['rows'] ), $data['total'] ); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="psi-admin-card">
                <div class="psi-table-wrapper">
                    <table class="wp-list-table widefat fixed striped psi-member-table">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th><?php esc_html_e( 'Nama', 'psi-papeng-premium' ); ?></th>
                                <th><?php esc_html_e( 'Email', 'psi-papeng-premium' ); ?></th>
                                <th><?php esc_html_e( 'Telepon', 'psi-papeng-premium' ); ?></th>
                                <th><?php esc_html_e( 'Kabupaten', 'psi-papeng-premium' ); ?></th>
                                <th><?php esc_html_e( 'Status', 'psi-papeng-premium' ); ?></th>
                                <th><?php esc_html_e( 'Tgl Daftar', 'psi-papeng-premium' ); ?></th>
                                <th style="width:140px"><?php esc_html_e( 'Aksi', 'psi-papeng-premium' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( ! empty( $data['rows'] ) ) :
                                $offset = ( $data['paged'] - 1 ) * $data['per_page'];
                                foreach ( $data['rows'] as $i => $m ) :
                                    $status_badges = [
                                        'pending'  => '<span class="psi-badge psi-badge-pending">Pending</span>',
                                        'verified' => '<span class="psi-badge psi-badge-verified">Terverifikasi</span>',
                                        'rejected' => '<span class="psi-badge psi-badge-rejected">Ditolak</span>',
                                    ];
                            ?>
                            <tr data-id="<?php echo absint( $m->id ); ?>">
                                <td><strong><?php echo absint( $offset + $i + 1 ); ?></strong></td>
                                <td>
                                    <strong><?php echo esc_html( $m->full_name ); ?></strong>
                                    <?php if ( $m->nik ) : ?><br><small class="psi-muted">NIK: <?php echo esc_html( $m->nik ); ?></small><?php endif; ?>
                                </td>
                                <td><?php echo esc_html( $m->email ); ?></td>
                                <td><?php echo esc_html( $m->phone ); ?></td>
                                <td><?php echo esc_html( $m->kabupaten ); ?></td>
                                <td><?php echo $status_badges[ $m->status ] ?? $m->status; ?></td>
                                <td><small><?php echo esc_html( $m->registered_at ); ?></small></td>
                                <td>
                                    <?php if ( $m->status === 'pending' ) : ?>
                                    <button class="button button-small psi-btn-verify" data-id="<?php echo absint( $m->id ); ?>" data-action="verified" title="<?php esc_attr_e( 'Verifikasi', 'psi-papeng-premium' ); ?>">
                                        <span class="dashicons dashicons-yes-alt" style="color:#155724;vertical-align:middle;"></span>
                                    </button>
                                    <button class="button button-small psi-btn-reject" data-id="<?php echo absint( $m->id ); ?>" data-action="rejected" title="<?php esc_attr_e( 'Tolak', 'psi-papeng-premium' ); ?>">
                                        <span class="dashicons dashicons-no-alt" style="color:#dc3545;vertical-align:middle;"></span>
                                    </button>
                                    <?php endif; ?>
                                    <button class="button button-small psi-btn-delete" data-id="<?php echo absint( $m->id ); ?>" title="<?php esc_attr_e( 'Hapus', 'psi-papeng-premium' ); ?>">
                                        <span class="dashicons dashicons-trash" style="color:#dc3545;vertical-align:middle;"></span>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr><td colspan="8" class="psi-empty-state"><?php esc_html_e( 'Tidak ada data anggota.', 'psi-papeng-premium' ); ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ( $data['total_pages'] > 1 ) : ?>
                <div class="psi-pagination">
                    <?php for ( $p = 1; $p <= $data['total_pages']; $p++ ) :
                        $url = add_query_arg( array_merge( [
                            'page'  => 'psi-papeng-members',
                            'paged' => $p,
                        ], array_filter( [
                            'status' => $status,
                            's'      => $search,
                            'kab'    => $kab,
                        ] ) ), admin_url( 'admin.php' ) );
                    ?>
                    <a href="<?php echo esc_url( $url ); ?>" class="button <?php echo $p === $data['paged'] ? 'button-primary psi-pag-active' : ''; ?>"><?php echo absint( $p ); ?></a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /* ═══ Statistics Page ═════════════════════════════════════ */
    public function render_statistics_page(): void {
        $total    = PSI_Papeng_Member_Management::get_total_count();
        $pending  = PSI_Papeng_Member_Management::get_total_count( 'pending' );
        $verified = PSI_Papeng_Member_Management::get_total_count( 'verified' );
        $rejected = PSI_Papeng_Member_Management::get_total_count( 'rejected' );
        $kab_stats = PSI_Papeng_Member_Management::get_kab_stats();
        ?>
        <div class="wrap psi-admin-wrap">
            <div class="psi-admin-header">
                <h1 class="psi-admin-title"><?php esc_html_e( 'Statistik Anggota', 'psi-papeng-premium' ); ?></h1>
            </div>

            <div class="psi-stats-row">
                <div class="psi-stat-card psi-stat-total">
                    <div class="psi-stat-icon"><span class="dashicons dashicons-groups"></span></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo number_format_i18n( $total ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Total', 'psi-papeng-premium' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-pending">
                    <div class="psi-stat-icon"><span class="dashicons dashicons-clock"></span></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo number_format_i18n( $pending ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Pending', 'psi-papeng-premium' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-verified">
                    <div class="psi-stat-icon"><span class="dashicons dashicons-yes-alt"></span></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo number_format_i18n( $verified ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Terverifikasi', 'psi-papeng-premium' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-rejected">
                    <div class="psi-stat-icon"><span class="dashicons dashicons-no-alt"></span></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo number_format_i18n( $rejected ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Ditolak', 'psi-papeng-premium' ); ?></span>
                    </div>
                </div>
            </div>

            <!-- Bar Chart (CSS-only) -->
            <div class="psi-admin-card">
                <div class="psi-card-header">
                    <h2><span class="dashicons dashicons-chart-bar" style="color:#D6001C"></span> <?php esc_html_e( 'Distribusi Per Kabupaten', 'psi-papeng-premium' ); ?></h2>
                </div>
                <div class="psi-card-body">
                    <?php if ( ! empty( $kab_stats ) ) :
                        $max_count = max( array_map( function( $k ) { return (int) $k->member_count; }, $kab_stats ) );
                        $max_count = max( $max_count, 1 );
                    ?>
                    <div class="psi-chart-bars">
                        <?php foreach ( $kab_stats as $ks ) :
                            $pct = round( ( (int) $ks->member_count / $max_count ) * 100, 1 );
                        ?>
                        <div class="psi-chart-bar-item">
                            <div class="psi-chart-bar-label"><?php echo esc_html( $ks->kabupaten ); ?></div>
                            <div class="psi-chart-bar-track">
                                <div class="psi-chart-bar-fill" style="width:<?php echo esc_attr( $pct ); ?>%">
                                    <span class="psi-chart-bar-value"><?php echo number_format_i18n( (int) $ks->member_count ); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else : ?>
                    <p class="psi-empty-state"><?php esc_html_e( 'Belum ada data statistik.', 'psi-papeng-premium' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /* ═══ Activity Log Page ═══════════════════════════════════ */
    public function render_activity_page(): void {
        $paged  = absint( $_GET['paged'] ?? 1 );
        $action = sanitize_text_field( $_GET['action_filter'] ?? '' );

        // Handle prune
        if ( isset( $_GET['psi_prune'] ) && wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'psi_prune_logs' ) && current_user_can( 'manage_options' ) ) {
            PSI_Papeng_Activity_Log::prune( 90 );
            wp_safe_redirect( admin_url( 'admin.php?page=psi-papeng-activity&psi_pruned=1' ) );
            exit;
        }

        $data = PSI_Papeng_Activity_Log::get_logs( [
            'paged'    => $paged,
            'action'   => $action,
            'per_page' => 50,
        ] );
        ?>
        <div class="wrap psi-admin-wrap">
            <div class="psi-admin-header">
                <h1 class="psi-admin-title"><?php esc_html_e( 'Log Aktivitas', 'psi-papeng-premium' ); ?></h1>
                <div class="psi-header-actions">
                    <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=psi-papeng-activity&psi_prune=1' ), 'psi_prune_logs' ) ); ?>" class="button" onclick="return confirm('<?php esc_attr_e( 'Hapus log lebih dari 90 hari?', 'psi-papeng-premium' ); ?>')">
                        <span class="dashicons dashicons-trash" style="vertical-align:middle;margin-right:4px;color:#dc3545;"></span>
                        <?php esc_html_e( 'Bersihkan Log Lama', 'psi-papeng-premium' ); ?>
                    </a>
                </div>
            </div>

            <?php if ( isset( $_GET['psi_pruned'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Log lama berhasil dibersihkan.', 'psi-papeng-premium' ); ?></p></div>
            <?php endif; ?>

            <!-- Filter -->
            <div class="psi-admin-card">
                <div class="psi-card-body">
                    <form method="get" class="psi-filter-row">
                        <input type="hidden" name="page" value="psi-papeng-activity">
                        <div class="psi-filter-group">
                            <select name="action_filter" class="psi-filter-select">
                                <option value=""><?php esc_html_e( 'Semua Aksi', 'psi-papeng-premium' ); ?></option>
                                <option value="member_registered" <?php selected( $action, 'member_registered' ); ?>>member_registered</option>
                                <option value="member_verified" <?php selected( $action, 'member_verified' ); ?>>member_verified</option>
                                <option value="member_rejected" <?php selected( $action, 'member_rejected' ); ?>>member_rejected</option>
                                <option value="member_deleted" <?php selected( $action, 'member_deleted' ); ?>>member_deleted</option>
                                <option value="wa_notification" <?php selected( $action, 'wa_notification' ); ?>>wa_notification</option>
                            </select>
                        </div>
                        <button type="submit" class="button button-primary psi-filter-btn"><?php esc_html_e( 'Filter', 'psi-papeng-premium' ); ?></button>
                        <?php if ( $action ) : ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-activity' ) ); ?>" class="button"><?php esc_html_e( 'Reset', 'psi-papeng-premium' ); ?></a>
                        <?php endif; ?>
                        <span class="psi-result-count" style="margin-left:auto;">
                            <?php printf( esc_html__( '%d total log', 'psi-papeng-premium' ), $data['total'] ); ?>
                        </span>
                    </form>
                </div>
            </div>

            <!-- Log Table -->
            <div class="psi-admin-card">
                <div class="psi-table-wrapper">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th style="width:180px"><?php esc_html_e( 'Waktu', 'psi-papeng-premium' ); ?></th>
                                <th style="width:180px"><?php esc_html_e( 'Aksi', 'psi-papeng-premium' ); ?></th>
                                <th><?php esc_html_e( 'Deskripsi', 'psi-papeng-premium' ); ?></th>
                                <th style="width:120px"><?php esc_html_e( 'User', 'psi-papeng-premium' ); ?></th>
                                <th style="width:130px"><?php esc_html_e( 'IP Address', 'psi-papeng-premium' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( ! empty( $data['rows'] ) ) :
                                $offset = ( $data['paged'] - 1 ) * $data['per_page'];
                                foreach ( $data['rows'] as $i => $log ) :
                            ?>
                            <tr>
                                <td><?php echo absint( $offset + $i + 1 ); ?></td>
                                <td><small><?php echo esc_html( $log->created_at ); ?></small></td>
                                <td><code class="psi-action-code"><?php echo esc_html( $log->action ); ?></code></td>
                                <td><?php echo esc_html( $log->description ); ?></td>
                                <td><?php echo esc_html( $log->user_login ?: '-'); ?></td>
                                <td><code><?php echo esc_html( $log->ip_address ); ?></code></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr><td colspan="6" class="psi-empty-state"><?php esc_html_e( 'Tidak ada log.', 'psi-papeng-premium' ); ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ( $data['total_pages'] > 1 ) : ?>
                <div class="psi-pagination">
                    <?php
                    $start = max( 1, $data['paged'] - 2 );
                    $end   = min( $data['total_pages'], $data['paged'] + 2 );
                    for ( $p = $start; $p <= $end; $p++ ) :
                        $url = add_query_arg( array_merge( [ 'page' => 'psi-papeng-activity', 'paged' => $p ], array_filter( [ 'action_filter' => $action ] ) ), admin_url( 'admin.php' ) );
                    ?>
                    <a href="<?php echo esc_url( $url ); ?>" class="button <?php echo $p === $data['paged'] ? 'button-primary psi-pag-active' : ''; ?>"><?php echo absint( $p ); ?></a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /* ═══ SMTP Settings Page ══════════════════════════════════ */
    public function render_smtp_page(): void {
        ?>
        <div class="wrap psi-admin-wrap">
            <div class="psi-admin-header">
                <h1 class="psi-admin-title"><?php esc_html_e( 'Pengaturan SMTP Email', 'psi-papeng-premium' ); ?></h1>
            </div>

            <div class="psi-admin-card">
                <div class="psi-card-body" style="max-width:700px;">
                    <p class="description" style="margin-bottom:20px;"><?php esc_html_e( 'Konfigurasikan pengaturan SMTP untuk mengirim email notifikasi. Biarkan kosong jika menggunakan pengaturan default server.', 'psi-papeng-premium' ); ?></p>
                    <form method="post" action="options.php">
                        <?php settings_fields( 'psi_papeng_smtp' ); ?>
                        <?php do_settings_sections( 'psi_papeng_smtp' ); ?>
                        <?php submit_button( __( 'Simpan Pengaturan', 'psi-papeng-premium' ), 'primary', 'submit', true, [ 'style' => 'background:#D6001C;border-color:#D6001C;' ] ); ?>
                    </form>

                    <!-- Test Email -->
                    <hr style="margin:30px 0;">
                    <h3><?php esc_html_e( 'Kirim Email Tes', 'psi-papeng-premium' ); ?></h3>
                    <form id="psi-smtp-test" class="psi-smtp-test-form">
                        <div class="psi-filter-row">
                            <div class="psi-filter-group" style="flex:1;">
                                <input type="email" id="psi_test_email" class="regular-text" placeholder="<?php esc_attr_e( 'Masukkan email tujuan', 'psi-papeng-premium' ); ?>" required>
                            </div>
                            <button type="submit" class="button"><?php esc_html_e( 'Kirim Tes', 'psi-papeng-premium' ); ?></button>
                        </div>
                        <div id="psi-smtp-test-result" style="margin-top:10px;"></div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
