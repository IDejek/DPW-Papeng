<?php
/**
 * Performance Optimization
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Performance {

    public function __construct() {
        // Lazy loading attributes (native)
        add_filter( 'wp_get_attachment_image_attributes', [ $this, 'lazy_load_attrs' ], 10, 3 );

        // Defer non-critical JS
        add_filter( 'script_loader_tag', [ $this, 'defer_js' ], 10, 3 );

        // Preload key fonts
        add_action( 'wp_head', [ $this, 'preload_fonts' ], 1 );

        // DNS prefetch for external resources
        add_action( 'wp_head', [ $this, 'dns_prefetch' ], 2 );

        // Remove unnecessary head items
        add_action( 'init', [ $this, 'clean_head' ] );

        // Optimize database queries — suppress counted queries in admin
        add_filter( 'posts_pre_query', [ $this, 'cache_posts_query' ], 10, 2 );
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

    public function cache_posts_query( $posts, \WP_Query $query ) {
        // Only for specific repeated queries on front page
        if ( ! is_admin() && $query->is_main_query() && is_front_page() ) {
            $cache_key = 'dpw_psi_fp_' . md5( serialize( $query->query_vars ) );
            $cached = wp_cache_get( $cache_key, 'dpw_psi' );
            if ( false !== $cached ) {
                return $cached;
            }
        }
        return null;
    }
}
