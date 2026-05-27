<?php
/**
 * Security Class
 *
 * @package PSI_Papeng_Premium
 */

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Security {

    public function __construct() {
        // Hide WordPress version
        remove_action( 'wp_head', 'wp_generator' );
        add_filter( 'the_generator', '__return_empty_string' );

        // Disable XML-RPC (common attack vector)
        add_filter( 'xmlrpc_enabled', '__return_false' );

        // Remove REST API links from head
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

        // Disable login errors leaking info
        add_filter( 'login_errors', array( $this, 'hide_login_errors' ) );

        // Limit login attempts (basic)
        add_action( 'wp_login_failed', array( $this, 'track_failed_login' ) );
        add_filter( 'authenticate', array( $this, 'check_login_attempts' ), 30, 3 );

        // Remove unnecessary head info
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );

        // Disable unfiltered uploads for non-admins
        add_filter( 'user_has_cap', array( $this, 'restrict_uploads' ), 10, 3 );

        // Force strong passwords (visual hint)
        add_action( 'user_profile_update_errors', array( $this, 'validate_password_strength' ), 10, 3 );
    }

    /**
     * Hide detailed login errors
     */
    public function hide_login_errors() {
        return esc_html__( 'Kombinasi username dan password salah. Silakan coba lagi.', 'psi-papeng-premium' );
    }

    /**
     * Track failed logins in transient
     */
    public function track_failed_login( $username ) {
        if ( empty( $username ) ) return;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $key = 'psi_fail_login_' . md5( $ip );
        $attempts = (int) get_transient( $key );
        set_transient( $key, $attempts + 1, 15 * MINUTE_IN_SECONDS );
    }

    /**
     * Block login after 5 failed attempts
     */
    public function check_login_attempts( $user, $username, $password ) {
        if ( is_wp_error( $user ) ) return $user;

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $key = 'psi_fail_login_' . md5( $ip );
        $attempts = (int) get_transient( $key );

        if ( $attempts >= 5 ) {
            return new WP_Error(
                'too_many_attempts',
                esc_html__( 'Terlalu banyak percobaan login. Silakan tunggu 15 menit.', 'psi-papeng-premium' )
            );
        }

        // Clear attempts on successful login
        delete_transient( $key );
        return $user;
    }

    /**
     * Restrict unfiltered file uploads
     */
    public function restrict_uploads( $allcaps, $caps, $args ) {
        if ( isset( $caps[0] ) && $caps[0] === 'unfiltered_upload' && ! current_user_can( 'administrator' ) ) {
            $allcaps['unfiltered_upload'] = false;
        }
        return $allcaps;
    }

    /**
     * Validate password strength for administrators
     */
    public function validate_password_strength( $errors, $update, $user ) {
        if ( ! in_array( 'administrator', $user->roles, true ) ) {
            return;
        }

        if ( empty( $_POST['pass1'] ) ) {
            return;
        }

        $password = $_POST['pass1'];
        if ( strlen( $password ) < 12 ) {
            $errors->add( 'pass_too_short', esc_html__( 'Password administrator minimal 12 karakter.', 'psi-papeng-premium' ) );
        }
    }
}
