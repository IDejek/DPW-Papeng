<?php
/**
 * Member Statistics Dashboard Widget
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Member_Statistics {

    public function __construct() {
        add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widget' ] );
    }

    public function add_dashboard_widget(): void {
        if ( ! current_user_can( 'manage_options' ) ) return;
        wp_add_dashboard_widget(
            'psi_papeng_stats',
            '<span class="dashicons dashicons-groups" style="color:#D6001C;margin-right:6px;vertical-align:middle;"></span> ' . __( 'Statistik Anggota PSI', 'psi-papeng-premium' ),
            [ $this, 'render_widget' ]
        );
    }

    public function render_widget(): void {
        $total    = PSI_Papeng_Member_Management::get_total_count();
        $pending  = PSI_Papeng_Member_Management::get_total_count( 'pending' );
        $verified = PSI_Papeng_Member_Management::get_total_count( 'verified' );
        $rejected = PSI_Papeng_Member_Management::get_total_count( 'rejected' );
        $kab_stats = PSI_Papeng_Member_Management::get_kab_stats();
        ?>
        <div class="psi-stats-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
            <div style="background:#f8f9fa;border-radius:8px;padding:14px;text-align:center;">
                <div style="font-size:28px;font-weight:800;color:#111;"><?php echo number_format_i18n( $total ); ?></div>
                <div style="font-size:12px;color:#6c757d;font-weight:600;">Total</div>
            </div>
            <div style="background:#fff3cd;border-radius:8px;padding:14px;text-align:center;">
                <div style="font-size:28px;font-weight:800;color:#856404;"><?php echo number_format_i18n( $pending ); ?></div>
                <div style="font-size:12px;color:#856404;font-weight:600;">Pending</div>
            </div>
            <div style="background:#d4edda;border-radius:8px;padding:14px;text-align:center;">
                <div style="font-size:28px;font-weight:800;color:#155724;"><?php echo number_format_i18n( $verified ); ?></div>
                <div style="font-size:12px;color:#155724;font-weight:600;">Terverifikasi</div>
            </div>
        </div>
        <?php if ( ! empty( $kab_stats ) ) : ?>
        <h4 style="font-size:13px;font-weight:700;margin:0 0 10px;"><?php esc_html_e( 'Per Kabupaten (Terverifikasi)', 'psi-papeng-premium' ); ?></h4>
        <div style="max-height:200px;overflow-y:auto;">
        <?php foreach ( array_slice( $kab_stats, 0, 8 ) as $kab ) : ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #f1f1f1;font-size:13px;">
                <span><?php echo esc_html( $kab->kabupaten ); ?></span>
                <strong style="color:#D6001C;"><?php echo number_format_i18n( (int) $kab->member_count ); ?></strong>
            </div>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div style="margin-top:12px;text-align:center;">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-members' ) ); ?>" class="button button-primary" style="background:#D6001C;border-color:#D6001C;">
                <span class="dashicons dashicons-admin-users" style="vertical-align:middle;margin-right:4px;"></span>
                <?php esc_html_e( 'Kelola Anggota', 'psi-papeng-premium' ); ?>
            </a>
        </div>
        <?php
    }
}
