<?php
/**
 * Helper Functions
 * @package DPW_PSIPapeng
 */

defined( 'ABSPATH' ) || exit;

function dpw_psi_get_option( $key, $default = '' ): string {
    $val = get_theme_mod( 'dpw_psi_' . $key, $default );
    return is_string( $val ) ? $val : (string) $val;
}

function dpw_psi_get_social_links_array(): array {
    $links = array();
    $fields = array( 'facebook', 'twitter', 'instagram', 'youtube', 'tiktok', 'linkedin' );
    foreach ( $fields as $f ) {
        $url = dpw_psi_get_option( 'social_' . $f );
        if ( $url ) {
            $links[] = esc_url( $url );
        }
    }
    return $links;
}

function dpw_psi_safe_html( $content ): string {
    return wp_kses_post( $content );
}

function dpw_psi_get_meta( $post_id, $key, $default = '' ) {
    $val = get_post_meta( $post_id, '_dpw_' . $key, true );
    return ( $val !== '' && $val !== false ) ? $val : $default;
}

function dpw_psi_icon( $name, $class = '' ): void {
    if ( $name === 'psi-logo' ) {
        echo '<svg viewBox="0 0 60 60" class="' . esc_attr( $class ) . '"><circle cx="30" cy="30" r="28" fill="#D6001C" stroke="#D4AF37" stroke-width="2"/><text x="30" y="37" text-anchor="middle" fill="#FFF" font-family="Poppins,sans-serif" font-weight="800" font-size="18">PSI</text></svg>';
    }
}

function dpw_psi_truncate( $text, $length = 100, $end = '...' ): string {
    $text = wp_strip_all_tags( $text );
    if ( mb_strlen( $text ) <= $length ) return $text;
    return mb_substr( $text, 0, $length ) . $end;
}

function dpw_psi_placeholder( $w = 600, $h = 400 ): string {
    return 'https://placehold.co/' . absint( $w ) . 'x' . absint( $h ) . '/111111/D6001C?text=PSI+Papeng';
}

function dpw_psi_youtube_id( $url ): string {
    if ( empty( $url ) ) return '';
    preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $m );
    return ! empty( $m[1] ) ? $m[1] : '';
}

function dpw_psi_wa_link( $number = '', $message = '' ): string {
    $number = $number ?: dpw_psi_get_option( 'whatsapp_number', '6282267218125' );
    $number = preg_replace( '/[^0-9]/', '', $number );
    $msg    = $message ? urlencode( $message ) : '';
    return 'https://wa.me/' . $number . ( $msg ? '?text=' . $msg : '' );
}
