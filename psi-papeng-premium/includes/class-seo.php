<?php
/**
 * SEO System
 * @package PSI_Papeng_Premium
 */

namespace PSI_Papeng;

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_SEO {

    public function __construct() {
        add_action( 'init', [ $this, 'add_rewrite_rules' ] );
        add_filter( 'robots_txt', [ $this, 'robots_txt' ], 10, 2 );
        add_action( 'wp_head', [ $this, 'canonical_url' ], 1 );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_rewrite_rules(): void {
        add_rewrite_rule( '^sitemap\.xml$', 'index.php?psi_sitemap=1', 'top' );
        add_rewrite_tag( '%psi_sitemap%', '([^?]+)' );
        add_action( 'template_redirect', [ $this, 'handle_sitemap_request' ] );
    }

    public function handle_sitemap_request(): void {
        if ( ! get_query_var( 'psi_sitemap' ) ) return;

        header( 'Content-Type: application/xml; charset=utf-8' );
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $this->sitemap_url( home_url( '/' ), 'daily', '1.0' );

        // Pages
        $pages = get_posts( [
            'post_type'      => 'page',
            'posts_per_page' => 50,
            'post_status'    => 'publish',
        ] );
        foreach ( $pages as $page ) {
            $this->sitemap_url( get_permalink( $page ), 'weekly', '0.8' );
        }

        // Posts
        $posts = get_posts( [
            'post_type'      => 'post',
            'posts_per_page' => 100,
            'post_status'    => 'publish',
        ] );
        foreach ( $posts as $post ) {
            $this->sitemap_url( get_permalink( $post ), 'daily', '0.7', get_the_date( 'Y-m-d', $post ) );
        }

        // Custom post types
        $cpts = [ 'leadership', 'division', 'dpd', 'video', 'gallery' ];
        foreach ( $cpts as $cpt ) {
            $items = get_posts( [
                'post_type'      => $cpt,
                'posts_per_page' => 100,
                'post_status'    => 'publish',
            ] );
            foreach ( $items as $item ) {
                $this->sitemap_url( get_permalink( $item ), 'weekly', '0.6' );
            }
        }

        echo '</urlset>';
        exit;
    }

    private function sitemap_url( string $url, string $freq, string $priority, string $mod = '' ): void {
        echo '<url><loc>' . esc_url( $url ) . '</loc>';
        if ( $mod ) echo '<lastmod>' . esc_html( $mod ) . '</lastmod>';
        echo '<changefreq>' . esc_html( $freq ) . '</changefreq>';
        echo '<priority>' . esc_html( $priority ) . '</priority>';
        echo '</url>' . "\n";
    }

    public function robots_txt( string $output, bool $public ): string {
        if ( ! $public ) return $output;
        $output  = "User-agent: *\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Disallow: /wp-includes/\n";
        $output .= "Disallow: /?s=\n";
        $output .= "Sitemap: " . home_url( '/sitemap.xml' ) . "\n";
        return $output;
    }

    public function canonical_url(): void {
        if ( is_singular() ) {
            echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
        }
    }

    public function register_settings(): void {
        register_setting( 'psi_papeng_smtp', 'psi_smtp_host', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'psi_papeng_smtp', 'psi_smtp_port', [ 'sanitize_callback' => 'absint' ] );
        register_setting( 'psi_papeng_smtp', 'psi_smtp_auth', [ 'sanitize_callback' => 'wp_validate_boolean' ] );
        register_setting( 'psi_papeng_smtp', 'psi_smtp_user', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'psi_papeng_smtp', 'psi_smtp_pass', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'psi_papeng_smtp', 'psi_smtp_secure', [ 'sanitize_callback' => 'sanitize_text_field' ] );
        register_setting( 'psi_papeng_smtp', 'psi_smtp_from', [ 'sanitize_callback' => 'sanitize_email' ] );
        register_setting( 'psi_papeng_smtp', 'psi_smtp_from_name', [ 'sanitize_callback' => 'sanitize_text_field' ] );
    }
}
