<?php
/**
 * Custom Post Types Registration
 * @package DPW_PSIPapeng
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'dpw_psi_register_post_types' );
function dpw_psi_register_post_types(): void {

    register_post_type( 'slider', array(
        'labels'       => array(
            'name'               => 'Slider',
            'singular_name'      => 'Slide',
            'add_new_item'       => 'Tambah Slide Baru',
            'edit_item'          => 'Edit Slide',
            'all_items'          => 'Semua Slide',
        ),
        'public'       => false,
        'show_ui'      => true,
        'supports'     => array( 'title', 'thumbnail' ),
        'menu_icon'    => 'dashicons-images-alt2',
        'rewrite'      => false,
        'query_var'    => false,
        'show_in_rest' => false,
    ) );

    register_post_type( 'leadership', array(
        'labels'       => array(
            'name'               => 'Pimpinan',
            'singular_name'      => 'Pimpinan',
            'add_new_item'       => 'Tambah Pimpinan',
            'edit_item'          => 'Edit Pimpinan',
            'all_items'          => 'Semua Pimpinan',
            'view_item'          => 'Lihat Profil',
        ),
        'public'       => true,
        'has_archive'  => false,
        'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
        'menu_icon'    => 'dashicons-id-alt',
        'rewrite'      => array( 'slug' => 'pimpinan', 'with_front' => false ),
        'show_in_rest' => true,
    ) );

    register_post_type( 'division', array(
        'labels'       => array(
            'name'               => 'Bidang Organisasi',
            'singular_name'      => 'Bidang',
            'add_new_item'       => 'Tambah Bidang',
            'edit_item'          => 'Edit Bidang',
            'all_items'          => 'Semua Bidang',
        ),
        'public'       => true,
        'has_archive'  => true,
        'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'    => 'dashicons-networking',
        'rewrite'      => array( 'slug' => 'bidang', 'with_front' => false ),
        'show_in_rest' => true,
    ) );

    register_post_type( 'dpd', array(
        'labels'       => array(
            'name'               => 'DPD Kabupaten',
            'singular_name'      => 'DPD Kabupaten',
            'add_new_item'       => 'Tambah DPD Kabupaten',
            'edit_item'          => 'Edit DPD',
            'all_items'          => 'Semua DPD',
            'view_item'          => 'Lihat DPD',
        ),
        'public'       => true,
        'has_archive'  => true,
        'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
        'menu_icon'    => 'dashicons-location-alt',
        'rewrite'      => array( 'slug' => 'dpd-kabupaten', 'with_front' => false ),
        'show_in_rest' => true,
    ) );

    register_post_type( 'video', array(
        'labels'       => array(
            'name'               => 'Video',
            'singular_name'      => 'Video',
            'add_new_item'       => 'Tambah Video',
            'edit_item'          => 'Edit Video',
            'all_items'          => 'Semua Video',
        ),
        'public'       => true,
        'has_archive'  => true,
        'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'    => 'dashicons-video-alt3',
        'rewrite'      => array( 'slug' => 'video', 'with_front' => false ),
        'show_in_rest' => true,
    ) );

    register_post_type( 'gallery', array(
        'labels'       => array(
            'name'               => 'Galeri',
            'singular_name'      => 'Galeri',
            'add_new_item'       => 'Tambah Galeri',
            'edit_item'          => 'Edit Galeri',
            'all_items'          => 'Semua Galeri',
        ),
        'public'       => true,
        'has_archive'  => true,
        'supports'     => array( 'title', 'thumbnail', 'excerpt' ),
        'menu_icon'    => 'dashicons-format-gallery',
        'rewrite'      => array( 'slug' => 'galeri', 'with_front' => false ),
        'show_in_rest' => true,
    ) );
}

add_action( 'init', 'dpw_psi_register_taxonomies' );
function dpw_psi_register_taxonomies(): void {

    register_taxonomy( 'video_category', 'video', array(
        'labels'       => array(
            'name'          => 'Kategori Video',
            'singular_name' => 'Kategori Video',
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'kategori-video' ),
        'show_in_rest' => true,
    ) );

    register_taxonomy( 'gallery_category', 'gallery', array(
        'labels'       => array(
            'name'          => 'Kategori Galeri',
            'singular_name' => 'Kategori Galeri',
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array( 'slug' => 'kategori-galeri' ),
        'show_in_rest' => true,
    ) );
}
