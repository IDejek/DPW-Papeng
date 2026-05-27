<?php
/**
 * Theme Customizer
 * @package DPW_PSIPapeng
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'dpw_psi_customize_register' );
function dpw_psi_customize_register( $wp_customize ): void {

    $wp_customize->add_section( 'dpw_topbar', array(
        'title'    => 'Topbar & Jam',
        'priority' => 20,
    ) );
    $wp_customize->add_setting( 'dpw_psi_show_topbar', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
    $wp_customize->add_control( 'dpw_psi_show_topbar', array( 'section' => 'dpw_topbar', 'label' => 'Tampilkan Topbar', 'type' => 'checkbox' ) );
    $wp_customize->add_setting( 'dpw_psi_topbar_text', array( 'default' => 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_psi_topbar_text', array( 'section' => 'dpw_topbar', 'label' => 'Teks Topbar', 'type' => 'text' ) );

    $wp_customize->add_section( 'dpw_contact', array(
        'title'    => 'Informasi Kontak',
        'priority' => 25,
    ) );
    $wp_customize->add_setting( 'dpw_psi_contact_phone', array( 'default' => '+62 822 6721 8125', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_psi_contact_phone', array( 'section' => 'dpw_contact', 'label' => 'Telepon', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_psi_contact_email', array( 'default' => 'tombinawaiqbal@gmail.com', 'sanitize_callback' => 'sanitize_email' ) );
    $wp_customize->add_control( 'dpw_psi_contact_email', array( 'section' => 'dpw_contact', 'label' => 'Email', 'type' => 'email' ) );
    $wp_customize->add_setting( 'dpw_psi_contact_address', array( 'default' => 'Papua Pegunungan, Indonesia', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'dpw_psi_contact_address', array( 'section' => 'dpw_contact', 'label' => 'Alamat', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'dpw_psi_whatsapp_number', array( 'default' => '6282267218125', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_psi_whatsapp_number', array( 'section' => 'dpw_contact', 'label' => 'Nomor WhatsApp (tanpa +)', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_psi_map_embed', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
    $wp_customize->add_control( 'dpw_psi_map_embed', array( 'section' => 'dpw_contact', 'label' => 'Google Maps Embed Code', 'type' => 'textarea' ) );

    $wp_customize->add_section( 'dpw_social', array(
        'title'    => 'Media Sosial',
        'priority' => 30,
    ) );
    $socials = array(
        'facebook'  => array( 'label' => 'Facebook', 'placeholder' => 'https://facebook.com/' ),
        'twitter'   => array( 'label' => 'Twitter/X', 'placeholder' => 'https://twitter.com/' ),
        'instagram' => array( 'label' => 'Instagram', 'placeholder' => 'https://instagram.com/' ),
        'youtube'   => array( 'label' => 'YouTube', 'placeholder' => 'https://youtube.com/' ),
        'tiktok'    => array( 'label' => 'TikTok', 'placeholder' => 'https://tiktok.com/' ),
        'linkedin'  => array( 'label' => 'LinkedIn', 'placeholder' => 'https://linkedin.com/' ),
    );
    foreach ( $socials as $key => $s ) {
        $wp_customize->add_setting( 'dpw_psi_social_' . $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( 'dpw_psi_social_' . $key, array(
            'section'     => 'dpw_social',
            'label'       => $s['label'],
            'type'        => 'url',
            'input_attrs' => array( 'placeholder' => $s['placeholder'] ),
        ) );
    }

    $wp_customize->add_section( 'dpw_welcome', array(
        'title'    => 'Sambutan Ketua',
        'priority' => 35,
    ) );
    $wp_customize->add_setting( 'dpw_psi_welcome_title', array( 'default' => 'Sambutan Ketua DPW', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_psi_welcome_title', array( 'section' => 'dpw_welcome', 'label' => 'Judul', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_psi_welcome_text', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
    $wp_customize->add_control( 'dpw_psi_welcome_text', array( 'section' => 'dpw_welcome', 'label' => 'Isi Sambutan', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'dpw_psi_welcome_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dpw_psi_welcome_image', array(
        'section' => 'dpw_welcome',
        'label'   => 'Foto Ketua',
    ) ) );

    $wp_customize->add_section( 'dpw_footer', array(
        'title'    => 'Footer',
        'priority' => 40,
    ) );
    $wp_customize->add_setting( 'dpw_psi_footer_text', array( 'default' => 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_psi_footer_text', array( 'section' => 'dpw_footer', 'label' => 'Deskripsi Footer', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_psi_footer_copyright', array( 'default' => '&copy; 2026 DPW PSI Papua Pegunungan. All rights reserved.', 'sanitize_callback' => 'wp_kses_post' ) );
    $wp_customize->add_control( 'dpw_psi_footer_copyright', array( 'section' => 'dpw_footer', 'label' => 'Teks Copyright', 'type' => 'textarea' ) );

    $wp_customize->add_section( 'dpw_membership', array(
        'title'    => 'Keanggotaan',
        'priority' => 45,
    ) );
    $wp_customize->add_setting( 'dpw_psi_membership_url', array( 'default' => 'https://psi.id/menjadi-anggota', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'dpw_psi_membership_url', array( 'section' => 'dpw_membership', 'label' => 'URL Pendaftaran Anggota', 'type' => 'url' ) );
    $wp_customize->add_setting( 'dpw_psi_membership_text', array( 'default' => 'Daftar Anggota', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_psi_membership_text', array( 'section' => 'dpw_membership', 'label' => 'Teks Tombol Daftar', 'type' => 'text' ) );
}
