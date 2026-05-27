<?php
/**
 * Template Name: Keanggotaan
 * Description: Redirects to PSI membership page
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $url = dpw_psi_get_option( 'membership_url', 'https://psi.id/menjadi-anggota' );
wp_safe_redirect( $url, 301 );
exit;
