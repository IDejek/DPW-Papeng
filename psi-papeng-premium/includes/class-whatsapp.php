<?php
/**
 * WhatsApp Integration
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_WhatsApp {

    private string $number;

    public function __construct() {
        $this->number = get_theme_mod( 'dpw_psi_whatsapp_number', '6282267218125' );
        add_action( 'wp_footer', [ $this, 'render_floating_button' ] );
    }

    public function render_floating_button(): void {
        if ( is_admin() ) return;
        $url = 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $this->number );
        $message = apply_filters( 'psi_papeng_wa_greeting', 'Halo, saya ingin bertanya tentang DPW PSI Papua Pegunungan.' );
        $url .= '?text=' . urlencode( $message );
        ?>
        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="psi-wa-float" id="psiWaFloat" aria-label="WhatsApp">
            <svg viewBox="0 0 32 32" width="28" height="28" fill="#FFF"><path d="M16.004 0h-.008C7.174 0 0 7.176 0 16.004c0 3.5 1.128 6.744 3.046 9.378L1.054 31.29l6.118-1.958A15.924 15.924 0 0016.004 32C24.826 32 32 24.826 32 16.004S24.826 0 16.004 0zm9.31 22.606c-.39 1.1-1.932 2.014-3.17 2.28-.846.18-1.95.322-5.66-1.216-4.75-1.97-7.804-6.81-8.04-7.126-.23-.316-1.932-2.574-1.932-4.91s1.224-3.482 1.658-3.958c.39-.432 1.036-.648 1.652-.648.2 0 .378.01.54.018.434.018.652.044.938.726.348.83 1.2 2.928 1.304 3.142.108.214.216.5.072.804-.14.308-.264.444-.478.718-.216.27-.404.478-.618.768-.194.258-.412.536-.168.998.24.462 1.07 1.766 2.296 2.862 1.578 1.406 2.908 1.842 3.372 2.05.464.208.734.174 1.004-.118.274-.294 1.176-1.372 1.49-1.844.308-.472.618-.394 1.042-.236.428.158 2.71 1.278 3.176 1.512.464.234.774.352.89.546.114.194.114 1.124-.276 2.226z"/></svg>
            <span class="psi-wa-tooltip"><?php esc_html_e( 'Chat WhatsApp', 'psi-papeng-premium' ); ?></span>
        </a>
        <style>
            .psi-wa-float {
                position: fixed;
                bottom: 28px;
                left: 28px;
                width: 58px;
                height: 58px;
                border-radius: 50%;
                background: #25D366;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 998;
                box-shadow: 0 4px 20px rgba(37,211,102,0.4);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                text-decoration: none;
            }
            .psi-wa-float:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 25px rgba(37,211,102,0.5);
                color: #FFF !important;
            }
            .psi-wa-tooltip {
                position: absolute;
                left: 68px;
                background: #333;
                color: #fff;
                padding: 6px 14px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 500;
                white-space: nowrap;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                pointer-events: none;
            }
            .psi-wa-tooltip::before {
                content: '';
                position: absolute;
                left: -6px;
                top: 50%;
                transform: translateY(-50%);
                border: 6px solid transparent;
                border-right-color: #333;
                border-left: none;
            }
            .psi-wa-float:hover .psi-wa-tooltip {
                opacity: 1;
                visibility: visible;
            }
            @media (max-width: 576px) {
                .psi-wa-float { bottom: 20px; left: 20px; width: 52px; height: 52px; }
            }
        </style>
        <?php
    }

    public static function notify_new_member( array $member ): void {
        $number = get_theme_mod( 'dpw_psi_whatsapp_number', '6282267218125' );
        $number = preg_replace( '/[^0-9]/', '', $number );
        $msg = sprintf(
            "*Pendaftaran Anggota Baru*\n\nNama: %s\nEmail: %s\nHP: %s\nKabupaten: %s\nPendidikan: %s\n\n_Silakan verifikasi di admin panel._",
            $member['full_name'],
            $member['email'],
            $member['phone'],
            $member['kabupaten'],
            $member['education'] ?: '-'
        );
        $url = 'https://wa.me/' . $number . '?text=' . urlencode( $msg );
        // Log the notification URL (actual sending requires WhatsApp Business API)
        if ( class_exists( 'PSI_Papeng_Activity_Log' ) ) {
            PSI_Papeng_Activity_Log::log( 'wa_notification', 'WhatsApp notification logged for: ' . $member['full_name'] );
        }
    }
}
