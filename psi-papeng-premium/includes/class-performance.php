<?php
/**
 * Performance Optimization
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Performance {

    public function __construct() {
        add_filter( 'wp_get_attachment_image_attributes', [ $this, 'lazy_load_attrs' ], 10, 3 );
        add_filter( 'script_loader_tag', [ $this, 'defer_js' ], 10, 3 );
        add_action( 'wp_head', [ $this, 'preload_fonts' ], 1 );
        add_action( 'wp_head', [ $this, 'dns_prefetch' ], 2 );
        add_action( 'init', [ $this, 'clean_head' ] );
        add_action( 'send_headers', [ $this, 'security_headers' ] );
    }

    public function lazy_load_attrs( array $attrs, \WP_Post $attachment, $size ): array {
        if ( ! is_admin() ) {
            $attrs['loading']  = 'lazy';
            $attrs['decoding'] = 'async';
        }
        return $attrs;
    }

    public function defer_js( string $tag, string $handle, string $src ): string {
        $defer_handles = [
            'bootstrap-bundle',
            'dpw-psi-clock',
            'dpw-psi-theme',
        ];
        if ( in_array( $handle, $defer_handles, true ) && false === strpos( $tag, 'defer' ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }
        return $tag;
    }

    public function preload_fonts(): void {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    }

    public function dns_prefetch(): void {
        $prefetch = [
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
            'https://i0.wp.com',
            'https://img.youtube.com',
        ];
        foreach ( $prefetch as $url ) {
            printf( '<link rel="dns-prefetch" href="%s">' . "\n", esc_url( $url ) );
        }
    }

    public function clean_head(): void {
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    }

    public function security_headers(): void {
        if ( is_admin() ) return;

        $headers = [
            'X-Content-Type-Options'  => 'nosniff',
            'X-Frame-Options'         => 'SAMEORIGIN',
            'X-XSS-Protection'        => '1; mode=block',
            'Referrer-Policy'         => 'strict-origin-when-cross-origin',
            'Permissions-Policy'      => 'geolocation=(), microphone=(), camera=()',
        ];

        foreach ( $headers as $name => $value ) {
            header( $name . ': ' . $value );
        }

        if ( is_ssl() ) {
            header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
        }
    }
}
