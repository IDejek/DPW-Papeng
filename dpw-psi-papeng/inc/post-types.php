<?php
/**
 * Custom Post Types Registration
 * @package DPW_PSIPapeng
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'dpw_psi_register_post_types' );
function dpw_psi_register_post_types(): void {

    /* ── Slider ────────────────────────────────────────────── */
    register_post_type( 'slider', [
        'labels'       => [
            'name'               => __( 'Slider', 'dpw-psi-papeng' ),
            'singular_name'      => __( 'Slide', 'dpw-psi-papeng' ),
            'add_new_item'       => __( 'Tambah Slide Baru', 'dpw-psi-papeng' ),
            'edit_item'          => __( 'Edit Slide', 'dpw-psi-papeng' ),
            'all_items'          => __( 'Semua Slide', 'dpw-psi-papeng' ),
        ],
        'public'       => false,
        'show_ui'      => true,
        'supports'     => [ 'title', 'thumbnail' ],
        'menu_icon'    => 'dashicons-images-alt2',
        'rewrite'      => false,
        'query_var'    => false,
        'show_in_rest' => false,
    ] );

    /* ── Leadership ────────────────────────────────────────── */
    register_post_type( 'leadership', [
        'labels'       => [
            'name'               => __( 'Pimpinan', 'dpw-psi-papeng' ),
            'singular_name'      => __( 'Pimpinan', 'dpw-psi-papeng' ),
            'add_new_item'       => __( 'Tambah Pimpinan', 'dpw-psi-papeng' ),
            'edit_item'          => __( 'Edit Pimpinan', 'dpw-psi-papeng' ),
            'all_items'          => __( 'Semua Pimpinan', 'dpw-psi-papeng' ),
            'view_item'          => __( 'Lihat Profil', 'dpw-psi-papeng' ),
        ],
        'public'       => true,
        'has_archive'  => false,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
        'menu_icon'    => 'dashicons-id-alt',
        'rewrite'      => [ 'slug' => 'pimpinan', 'with_front' => false ],
        'show_in_rest' => true,
    ] );

    /* ── Division / Bidang ─────────────────────────────────── */
    register_post_type( 'division', [
        'labels'       => [
            'name'               => __( 'Bidang Organisasi', 'dpw-psi-papeng' ),
            'singular_name'      => __( 'Bidang', 'dpw-psi-papeng' ),
            'add_new_item'       => __( 'Tambah Bidang', 'dpw-psi-papeng' ),
            'edit_item'          => __( 'Edit Bidang', 'dpw-psi-papeng' ),
            'all_items'          => __( 'Semua Bidang', 'dpw-psi-papeng' ),
        ],
        'public'       => true,
        'has_archive'  => true,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'    => 'dashicons-networking',
        'rewrite'      => [ 'slug' => 'bidang', 'with_front' => false ],
        'show_in_rest' => true,
    ] );

    /* ── DPD ───────────────────────────────────────────────── */
    register_post_type( 'dpd', [
        'labels'       => [
            'name'               => __( 'DPD Kabupaten', 'dpw-psi-papeng' ),
            'singular_name'      => __( 'DPD Kabupaten', 'dpw-psi-papeng' ),
            'add_new_item'       => __( 'Tambah DPD Kabupaten', 'dpw-psi-papeng' ),
            'edit_item'          => __( 'Edit DPD', 'dpw-psi-papeng' ),
            'all_items'          => __( 'Semua DPD', 'dpw-psi-papeng' ),
            'view_item'          => __( 'Lihat DPD', 'dpw-psi-papeng' ),
        ],
        'public'       => true,
        'has_archive'  => true,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
        'menu_icon'    => 'dashicons-location-alt',
        'rewrite'      => [ 'slug' => 'dpd-kabupaten', 'with_front' => false ],
        'show_in_rest' => true,
    ] );

    /* ── Video ─────────────────────────────────────────────── */
    register_post_type( 'video', [
        'labels'       => [
            'name'               => __( 'Video', 'dpw-psi-papeng' ),
            'singular_name'      => __( 'Video', 'dpw-psi-papeng' ),
            'add_new_item'       => __( 'Tambah Video', 'dpw-psi-papeng' ),
            'edit_item'          => __( 'Edit Video', 'dpw-psi-papeng' ),
            'all_items'          => __( 'Semua Video', 'dpw-psi-papeng' ),
        ],
        'public'       => true,
        'has_archive'  => true,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'    => 'dashicons-video-alt3',
        'rewrite'      => [ 'slug' => 'video', 'with_front' => false ],
        'show_in_rest' => true,
    ] );

    /* ── Gallery ───────────────────────────────────────────── */
    register_post_type( 'gallery', [
        'labels'       => [
            'name'               => __( 'Galeri', 'dpw-psi-papeng' ),
            'singular_name'      => __( 'Galeri', 'dpw-psi-papeng' ),
            'add_new_item'       => __( 'Tambah Galeri', 'dpw-psi-papeng' ),
            'edit_item'          => __( 'Edit Galeri', 'dpw-psi-papeng' ),
            'all_items'          => __( 'Semua Galeri', 'dpw-psi-papeng' ),
        ],
        'public'       => true,
        'has_archive'  => true,
        'supports'     => [ 'title', 'thumbnail', 'excerpt' ],
        'menu_icon'    => 'dashicons-format-gallery',
        'rewrite'      => [ 'slug' => 'galeri', 'with_front' => false ],
        'show_in_rest' => true,
    ] );
}

/* ── Taxonomies ─────────────────────────────────────────────── */
add_action( 'init', 'dpw_psi_register_taxonomies' );
function dpw_psi_register_taxonomies(): void {

    register_taxonomy( 'video_category', 'video', [
        'labels'       => [
            'name'          => __( 'Kategori Video', 'dpw-psi-papeng' ),
            'singular_name' => __( 'Kategori Video', 'dpw-psi-papeng' ),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'kategori-video' ],
        'show_in_rest' => true,
    ] );

    register_taxonomy( 'gallery_category', 'gallery', [
        'labels'       => [
            'name'          => __( 'Kategori Galeri', 'dpw-psi-papeng' ),
            'singular_name' => __( 'Kategori Galeri', 'dpw-psi-papeng' ),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'kategori-galeri' ],
        'show_in_rest' => true,
    ] );
}
