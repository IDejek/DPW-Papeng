<?php
/**
 * Activity Log System
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Activity_Log {

    private string $table;

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'psi_activity_log';

        // Create table on the fly if not exists
        add_action( 'admin_init', [ $this, 'ensure_table' ] );
    }

    public static function create_table(): void {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$wpdb->prefix}psi_activity_log (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            action varchar(100) NOT NULL,
            description text DEFAULT NULL,
            user_id bigint(20) UNSIGNED DEFAULT NULL,
            ip_address varchar(45) DEFAULT '',
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY action (action),
            KEY created_at (created_at)
        ) $charset;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    public function ensure_table(): void {
        global $wpdb;
        $table = $this->table;
        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table}'" ) !== $table ) {
            self::create_table();
        }
    }

    public static function log( string $action, string $description = '', int $user_id = 0 ): void {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_activity_log';

        if ( ! $user_id && is_user_logged_in() ) {
            $user_id = get_current_user_id();
        }

        $ip = '';
        if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
        }

        $wpdb->insert(
            $table,
            [
                'action'      => sanitize_text_field( $action ),
                'description' => sanitize_textarea_field( $description ),
                'user_id'     => absint( $user_id ),
                'ip_address'  => $ip,
            ],
            [ '%s', '%s', '%d', '%s' ]
        );
    }

    public static function get_logs( array $args = [] ): array {
        global $wpdb;
        $table    = $wpdb->prefix . 'psi_activity_log';
        $per_page = absint( $args['per_page'] ?? 50 );
        $paged    = max( 1, absint( $args['paged'] ?? 1 ) );
        $offset   = ( $paged - 1 ) * $per_page;
        $action   = sanitize_text_field( $args['action'] ?? '' );

        $where = '1=1';
        $vals  = [];
        if ( $action ) {
            $where .= ' AND action = %s';
            $vals[] = $action;
        }

        $total = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE {$where}", $vals ) );
        $sql   = "SELECT l.*, u.user_login FROM {$table} l LEFT JOIN {$wpdb->users} u ON l.user_id = u.ID WHERE {$where} ORDER BY l.created_at DESC LIMIT %d OFFSET %d";
        $vals[] = $per_page;
        $vals[] = $offset;
        $rows   = $wpdb->get_results( $wpdb->prepare( $sql, $vals ) );

        return [
            'rows'         => $rows,
            'total'        => $total,
            'per_page'     => $per_page,
            'paged'        => $paged,
            'total_pages'  => (int) ceil( $total / max( $per_page, 1 ) ),
        ];
    }

    public static function prune( int $days = 90 ): int {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_activity_log';
        return (int) $wpdb->query( $wpdb->prepare(
            "DELETE FROM {$table} WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
            $days
        ) );
    }
}
