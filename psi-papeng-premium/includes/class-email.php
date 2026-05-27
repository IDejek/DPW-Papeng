<?php
/**
 * Email Notification System
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Email {

    public function __construct() {
        add_filter( 'wp_mail_content_type', [ $this, 'set_html_content_type' ] );
        add_action( 'phpmailer_init', [ $this, 'configure_smtp' ], 999 );
    }

    public function set_html_content_type(): string {
        return 'text/html';
    }

    public function configure_smtp( \PHPMailer\PHPMailer $phpmailer ): void {
        $smtp_host = get_option( 'psi_smtp_host', '' );
        if ( empty( $smtp_host ) ) return;

        $phpmailer->isSMTP();
        $phpmailer->Host       = $smtp_host;
        $phpmailer->Port       = absint( get_option( 'psi_smtp_port', 587 ) );
        $phpmailer->SMTPAuth   = (bool) get_option( 'psi_smtp_auth', true );
        $phpmailer->Username   = get_option( 'psi_smtp_user', '' );
        $phpmailer->Password   = get_option( 'psi_smtp_pass', '' );
        $phpmailer->SMTPSecure = in_array( get_option( 'psi_smtp_secure', 'tls' ), [ 'tls', 'ssl' ], true ) ? get_option( 'psi_smtp_secure', 'tls' ) : 'tls';
        $phpmailer->From       = get_option( 'psi_smtp_from', get_option( 'admin_email' ) );
        $phpmailer->FromName   = get_option( 'psi_smtp_from_name', get_bloginfo( 'name' ) );
    }

    public static function send_registration_notification( array $member, int $member_id ): bool {
        $to      = get_option( 'admin_email' );
        $subject = sprintf( __( '[%s] Pendaftaran Anggota Baru: %s', 'psi-papeng-premium' ), get_bloginfo( 'name' ), $member['full_name'] );
        $body    = self::get_template( 'registration-admin', $member );

        $sent = wp_mail( $to, $subject, $body );

        // Also send confirmation to member
        $member_subject = __( 'Pendaftaran Berhasil — DPW PSI Papua Pegunungan', 'psi-papeng-premium' );
        $member_body    = self::get_template( 'registration-member', $member );
        wp_mail( $member['email'], $member_subject, $member_body );

        return $sent;
    }

    public static function send_verification_email( array $member ): bool {
        $subject = __( 'Selamat! Keanggotaan Anda Telah Diverifikasi — DPW PSI Papua Pegunungan', 'psi-papeng-premium' );
        $body    = self::get_template( 'verified', $member );
        return wp_mail( $member->email, $subject, $body );
    }

    private static function get_template( string $type, $data ): string {
        $site = get_bloginfo( 'name' );
        $url  = home_url( '/' );

        $header = '<div style="background:linear-gradient(135deg,#111,#D6001C);padding:30px 20px;text-align:center;border-radius:12px 12px 0 0;">';
        $header .= '<h1 style="color:#fff;margin:0;font-family:sans-serif;font-size:24px;">DPW PSI</h1>';
        $header .= '<p style="color:rgba(255,255,255,0.7);margin:4px 0 0;font-size:14px;">Papua Pegunungan</p>';
        $header .= '</div>';

        $footer = '<div style="background:#111;padding:20px;text-align:center;border-radius:0 0 12px 12px;">';
        $footer .= '<p style="color:rgba(255,255,255,0.5);margin:0;font-size:12px;">&copy; ' . date( 'Y' ) . ' ' . esc_html( $site ) . ' — ' . esc_url( $url ) . '</p>';
        $footer .= '</div>';

        $wrap = '<div style="max-width:560px;margin:20px auto;font-family:sans-serif;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);">';

        $content = '';
        $name    = is_object( $data ) ? $data->full_name : ( $data['full_name'] ?? '' );
        $email   = is_object( $data ) ? $data->email : ( $data['email'] ?? '' );
        $kab     = is_object( $data ) ? $data->kabupaten : ( $data['kabupaten'] ?? '' );

        switch ( $type ) {
            case 'registration-admin':
                $content = '<div style="padding:30px 20px;background:#fff;">';
                $content .= '<h2 style="color:#111;margin:0 0 16px;">Pendaftaran Anggota Baru</h2>';
                $content .= '<table style="width:100%;font-size:14px;border-collapse:collapse;">';
                $content .= self::email_row( 'Nama', $name );
                $content .= self::email_row( 'Email', $email );
                $content .= self::email_row( 'Telepon', is_object( $data ) ? $data->phone : ( $data['phone'] ?? '' ) );
                $content .= self::email_row( 'Kabupaten', $kab );
                $content .= self::email_row( 'Pendidikan', is_object( $data ) ? $data->education : ( $data['education'] ?? '-' ) );
                $content .= '</table>';
                $content .= '<p style="margin-top:20px;"><a href="' . admin_url( 'admin.php?page=psi-papeng-members' ) . '" style="background:#D6001C;color:#fff;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;">Kelola Anggota</a></p>';
                $content .= '</div>';
                break;

            case 'registration-member':
                $content = '<div style="padding:30px 20px;background:#fff;">';
                $content .= '<h2 style="color:#111;margin:0 0 12px;">Halo, ' . esc_html( $name ) . '!</h2>';
                $content .= '<p style="color:#495057;line-height:1.7;">Terima kasih telah mendaftar sebagai anggota DPW PSI Papua Pegunungan. Data Anda sedang dalam proses verifikasi oleh tim kami.</p>';
                $content .= '<p style="color:#495057;line-height:1.7;">Anda akan menerima email notifikasi setelah status keanggotaan Anda diperbarui.</p>';
                $content .= '<div style="background:#f8f9fa;border-left:4px solid #D6001C;padding:16px;border-radius:0 8px 8px 0;margin-top:16px;">';
                $content .= '<p style="margin:0;font-size:14px;color:#6c757d;"><strong>Email terdaftar:</strong> ' . esc_html( $email ) . '</p>';
                $content .= '</div>';
                $content .= '</div>';
                break;

            case 'verified':
                $content = '<div style="padding:30px 20px;background:#fff;">';
                $content .= '<div style="text-align:center;margin-bottom:20px;">';
                $content .= '<div style="width:60px;height:60px;border-radius:50%;background:#28a745;display:inline-flex;align-items:center;justify-content:center;"><span style="color:#fff;font-size:28px;">&#10003;</span></div>';
                $content .= '</div>';
                $content .= '<h2 style="color:#111;text-align:center;margin:0 0 12px;">Selamat, ' . esc_html( $name ) . '!</h2>';
                $content .= '<p style="color:#495057;line-height:1.7;text-align:center;">Keanggotaan Anda di DPW PSI Papua Pegunungan telah <strong style="color:#28a745;">diverifikasi</strong>. Selamat bergabung dalam perjuangan untuk Papua Pegunungan yang lebih baik!</p>';
                $content .= '</div>';
                break;
        }

        return $wrap . $header . $content . $footer . '</div>';
    }

    private static function email_row( string $label, string $value ): string {
        return '<tr><td style="padding:8px 0;border-bottom:1px solid #eee;color:#6c757d;width:140px;font-weight:600;">' . esc_html( $label ) . '</td><td style="padding:8px 0;border-bottom:1px solid #eee;color:#212529;">' . esc_html( $value ) . '</td></tr>';
    }
}
