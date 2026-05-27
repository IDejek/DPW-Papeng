<?php
/**
 * Theme Customizer
 * @package DPW_PSIPapeng
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'dpw_psi_customize_register' );
function dpw_psi_customize_register( WP_Customize_Manager $wp_customize ): void {

    /* ── Topbar Section ────────────────────────────────────── */
    $wp_customize->add_section( 'dpw_topbar', [
        'title'    => __( 'Topbar & Jam', 'dpw-psi-papeng' ),
        'priority' => 20,
    ] );
    $wp_customize->add_setting( 'dpw_psi_show_topbar', [ 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'dpw_psi_show_topbar', [
        'section' => 'dpw_topbar',
        'label'   => __( 'Tampilkan Topbar', 'dpw-psi-papeng' ),
        'type'    => 'checkbox',
    ] );
    $wp_customize->add_setting( 'dpw_psi_topbar_text', [ 'default' => 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'dpw_psi_topbar_text', [
        'section' => 'dpw_topbar',
        'label'   => __( 'Teks Topbar', 'dpw-psi-papeng' ),
        'type'    => 'text',
    ] );

    /* ── Contact Section ───────────────────────────────────── */
    $wp_customize->add_section( 'dpw_contact', [
        'title'    => __( 'Informasi Kontak', 'dpw-psi-papeng' ),
        'priority' => 25,
    ] );
    $wp_customize->add_setting( 'dpw_psi_contact_phone', [ 'default' => '+62 822 6721 8125', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'dpw_psi_contact_phone', [ 'section' => 'dpw_contact', 'label' => __( 'Telepon', 'dpw-psi-papeng' ), 'type' => 'text' ] );
    $wp_customize->add_setting( 'dpw_psi_contact_email', [ 'default' => 'tombinawaiqbal@gmail.com', 'sanitize_callback' => 'sanitize_email' ] );
    $wp_customize->add_control( 'dpw_psi_contact_email', [ 'section' => 'dpw_contact', 'label' => __( 'Email', 'dpw-psi-papeng' ), 'type' => 'email' ] );
    $wp_customize->add_setting( 'dpw_psi_contact_address', [ 'default' => 'Papua Pegunungan, Indonesia', 'sanitize_callback' => 'sanitize_textarea_field' ] );
    $wp_customize->add_control( 'dpw_psi_contact_address', [ 'section' => 'dpw_contact', 'label' => __( 'Alamat', 'dpw-psi-papeng' ), 'type' => 'textarea' ] );
    $wp_customize->add_setting( 'dpw_psi_whatsapp_number', [ 'default' => '6282267218125', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'dpw_psi_whatsapp_number', [ 'section' => 'dpw_contact', 'label' => __( 'Nomor WhatsApp (tanpa +)', 'dpw-psi-papeng' ), 'type' => 'text' ] );
    $wp_customize->add_setting( 'dpw_psi_map_embed', [ 'default' => '', 'sanitize_callback' => 'wp_kses_post' ] );
    $wp_customize->add_control( 'dpw_psi_map_embed', [ 'section' => 'dpw_contact', 'label' => __( 'Google Maps Embed Code', 'dpw-psi-papeng' ), 'type' => 'textarea' ] );

    /* ── Social Media Section ──────────────────────────────── */
    $wp_customize->add_section( 'dpw_social', [
        'title'    => __( 'Media Sosial', 'dpw-psi-papeng' ),
        'priority' => 30,
    ] );
    $socials = [
        'facebook'  => [ 'label' => 'Facebook', 'placeholder' => 'https://facebook.com/...' ],
        'twitter'   => [ 'label' => 'Twitter/X', 'placeholder' => 'https://twitter.com/...' ],
        'instagram' => [ 'label' => 'Instagram', 'placeholder' => 'https://instagram.com/...' ],
        'youtube'   => [ 'label' => 'YouTube', 'placeholder' => 'https://youtube.com/...' ],
        'tiktok'    => [ 'label' => 'TikTok', 'placeholder' => 'https://tiktok.com/...' ],
        'linkedin'  => [ 'label' => 'LinkedIn', 'placeholder' => 'https://linkedin.com/...' ],
    ];
    foreach ( $socials as $key => $s ) {
        $wp_customize->add_setting( 'dpw_psi_social_' . $key, [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( 'dpw_psi_social_' . $key, [
            'section'     => 'dpw_social',
            'label'       => $s['label'],
            'type'        => 'url',
            'input_attrs' => [ 'placeholder' => $s['placeholder'] ],
        ] );
    }

    /* ── Welcome Section ───────────────────────────────────── */
    $wp_customize->add_section( 'dpw_welcome', [
        'title'    => __( 'Sambutan Ketua', 'dpw-psi-papeng' ),
        'priority' => 35,
    ] );
    $wp_customize->add_setting( 'dpw_psi_welcome_title', [ 'default' => 'Sambutan Ketua DPW', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'dpw_psi_welcome_title', [ 'section' => 'dpw_welcome', 'label' => __( 'Judul', 'dpw-psi-papeng' ), 'type' => 'text' ] );
    $wp_customize->add_setting( 'dpw_psi_welcome_text', [ 'default' => '', 'sanitize_callback' => 'wp_kses_post' ] );
    $wp_customize->add_control( 'dpw_psi_welcome_text', [ 'section' => 'dpw_welcome', 'label' => __( 'Isi Sambutan', 'dpw-psi-papeng' ), 'type' => 'textarea' ] );
    $wp_customize->add_setting( 'dpw_psi_welcome_image', [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dpw_psi_welcome_image', [
        'section' => 'dpw_welcome',
        'label'   => __( 'Foto Ketua', 'dpw-psi-papeng' ),
    ] ) );

    /* ── Footer Section ────────────────────────────────────── */
    $wp_customize->add_section( 'dpw_footer', [
        'title'    => __( 'Footer', 'dpw-psi-papeng' ),
        'priority' => 40,
    ] );
    $wp_customize->add_setting( 'dpw_psi_footer_text', [ 'default' => 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'dpw_psi_footer_text', [ 'section' => 'dpw_footer', 'label' => __( 'Deskripsi Footer', 'dpw-psi-papeng' ), 'type' => 'text' ] );
    $wp_customize->add_setting( 'dpw_psi_footer_copyright', [ 'default' => '&copy; 2026 DPW PSI Papua Pegunungan. All rights reserved.', 'sanitize_callback' => 'wp_kses_post' ] );
    $wp_customize->add_control( 'dpw_psi_footer_copyright', [ 'section' => 'dpw_footer', 'label' => __( 'Teks Copyright', 'dpw-psi-papeng' ), 'type' => 'textarea' ] );

    /* ── Membership CTA ────────────────────────────────────── */
    $wp_customize->add_section( 'dpw_membership', [
        'title'    => __( 'Keanggotaan', 'dpw-psi-papeng' ),
        'priority' => 45,
    ] );
    $wp_customize->add_setting( 'dpw_psi_membership_url', [ 'default' => 'https://psi.id/menjadi-anggota', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( 'dpw_psi_membership_url', [ 'section' => 'dpw_membership', 'label' => __( 'URL Pendaftaran Anggota', 'dpw-psi-papeng' ), 'type' => 'url' ] );
    $wp_customize->add_setting( 'dpw_psi_membership_text', [ 'default' => 'Daftar Anggota', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'dpw_psi_membership_text', [ 'section' => 'dpw_membership', 'label' => __( 'Teks Tombol Daftar', 'dpw-psi-papeng' ), 'type' => 'text' ] );
}
