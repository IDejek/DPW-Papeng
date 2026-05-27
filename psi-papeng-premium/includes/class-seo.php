<?php
/**
 * SEO Class - Sitemap, Robots.txt override
 *
 * @package PSI_Papeng_Premium
 */

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_SEO {

    public function __construct() {
        add_action( 'init', array( $this, 'add_sitemap_rewrite' ) );
        add_action( 'template_redirect', array( $this, 'render_sitemap' ) );
        add_action( 'do_robots', array( $this, 'custom_robots_txt' ), 1 );
    }

    /**
     * Add sitemap rewrite rule
     */
    public function add_sitemap_rewrite() {
        add_rewrite_rule( '^sitemap\.xml$', 'index.php?psi_sitemap=1', 'top' );
        add_query_var( 'psi_sitemap', '1' );
    }

    /**
     * Render XML Sitemap
     */
    public function render_sitemap() {
        if ( ! get_query_var( 'psi_sitemap' ) ) {
            return;
        }

        $seo_settings = get_option( 'dpw_psi_seo', array() );
        if ( isset( $seo_settings['enable_sitemap'] ) && $seo_settings['enable_sitemap'] !== 'yes' ) {
            wp_die( 'Sitemap disabled.' );
        }

        header( 'Content-Type: application/xml; charset=utf-8' );
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $this->output_url( home_url( '/' ), 'daily', '1.0' );

        // Pages
        $pages = get_posts( array(
            'post_type'   => 'page',
            'numberposts' => 50,
            'post_status' => 'publish',
        ) );
        foreach ( $pages as $page ) {
            $this->output_url( get_permalink( $page->ID ), 'weekly', '0.8' );
        }

        // News
        $news = get_posts( array(
            'post_type'   => 'psi-news',
            'numberposts' => 100,
            'post_status' => 'publish',
        ) );
        foreach ( $news as $post ) {
            $this->output_url( get_permalink( $post->ID ), 'daily', '0.7', $post->post_modified );
        }

        // Videos
        $videos = get_posts( array(
            'post_type'   => 'psi-video',
            'numberposts' => 50,
            'post_status' => 'publish',
        ) );
        foreach ( $videos as $post ) {
            $this->output_url( get_permalink( $post->ID ), 'weekly', '0.6' );
        }

        // DPD
        $dpd = get_posts( array(
            'post_type'   => 'psi-dpd',
            'numberposts' => 20,
            'post_status' => 'publish',
        ) );
        foreach ( $dpd as $post ) {
            $this->output_url( get_permalink( $post->ID ), 'monthly', '0.5' );
        }

        // Gallery
        $gallery = get_posts( array(
            'post_type'   => 'psi-gallery',
            'numberposts' => 50,
            'post_status' => 'publish',
        ) );
        foreach ( $gallery as $post ) {
            $this->output_url( get_permalink( $post->ID ), 'monthly', '0.4' );
        }

        echo '</urlset>';
        exit;
    }

    /**
     * Helper to output a single URL in sitemap
     */
    private function output_url( $loc, $freq, $prio, $lastmod = '' ) {
        $loc  = esc_url( $loc );
        $freq = esc_xml( $freq );
        $prio = esc_xml( $prio );
        $mod  = $lastmod ? '<lastmod>' . esc_xml( mysql2date( 'Y-m-d\TH:i:s+00:00', $lastmod ) ) . '</lastmod>' : '';
        echo "<url><loc>$loc</loc>$mod<changefreq>$freq</changefreq><priority>$prio</priority></url>\n";
    }

    /**
     * Custom robots.txt
     */
    public function custom_robots_txt() {
        $seo = get_option( 'dpw_psi_seo', array() );
        $robots = $seo['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /wp-admin/\nDisallow: /wp-includes/\n";

        // Ensure sitemap URL is present
        if ( strpos( $robots, 'Sitemap:' ) === false ) {
            $robots .= "\nSitemap: " . home_url( '/sitemap.xml' );
        }

        echo esc_textarea( $robots );
        exit;
    }
}
