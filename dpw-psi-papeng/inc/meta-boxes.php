<?php
/**
 * Custom Meta Boxes
 * @package DPW_PSIPapeng
 */

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', 'dpw_psi_register_meta_boxes' );
function dpw_psi_register_meta_boxes(): void {

    add_meta_box( 'dpw_slider_meta', 'Pengaturan Slide', 'dpw_psi_slider_meta_cb', 'slider', 'normal', 'high' );
    add_meta_box( 'dpw_leadership_meta', 'Detail Pimpinan', 'dpw_psi_leadership_meta_cb', 'leadership', 'normal', 'high' );
    add_meta_box( 'dpw_division_meta', 'Detail Bidang', 'dpw_psi_division_meta_cb', 'division', 'normal', 'high' );
    add_meta_box( 'dpw_dpd_meta', 'Detail DPD', 'dpw_psi_dpd_meta_cb', 'dpd', 'normal', 'high' );
    add_meta_box( 'dpw_video_meta', 'Detail Video', 'dpw_psi_video_meta_cb', 'video', 'normal', 'high' );
}

function dpw_psi_slider_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_slider_nonce' );
    $subtitle = get_post_meta( $post->ID, '_dpw_slider_subtitle', true );
    $btn_text = get_post_meta( $post->ID, '_dpw_slider_btn_text', true );
    $btn_url  = get_post_meta( $post->ID, '_dpw_slider_btn_url', true );
    $order    = get_post_meta( $post->ID, '_dpw_slider_order', true );
    echo '<div class="dpw-meta-field"><label for="dpw_slider_subtitle">Subtitle</label><input type="text" id="dpw_slider_subtitle" name="dpw_slider_subtitle" value="' . esc_attr( $subtitle ) . '" class="widefat"></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_slider_btn_text">Teks Tombol</label><input type="text" id="dpw_slider_btn_text" name="dpw_slider_btn_text" value="' . esc_attr( $btn_text ) . '" class="widefat"></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_slider_btn_url">URL Tombol</label><input type="url" id="dpw_slider_btn_url" name="dpw_slider_btn_url" value="' . esc_attr( $btn_url ) . '" class="widefat"></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_slider_order">Urutan</label><input type="number" id="dpw_slider_order" name="dpw_slider_order" value="' . esc_attr( $order ) . '" class="widefat" min="0"></div>';
}

function dpw_psi_leadership_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_leadership_nonce' );
    $position   = get_post_meta( $post->ID, '_dpw_position', true );
    $facebook   = get_post_meta( $post->ID, '_dpw_facebook', true );
    $instagram  = get_post_meta( $post->ID, '_dpw_instagram', true );
    $twitter    = get_post_meta( $post->ID, '_dpw_twitter', true );
    $is_primary = get_post_meta( $post->ID, '_dpw_is_primary', true );
    echo '<div class="dpw-meta-field"><label for="dpw_position">Jabatan</label><input type="text" id="dpw_position" name="dpw_position" value="' . esc_attr( $position ) . '" class="widefat" required></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_facebook">Facebook URL</label><input type="url" id="dpw_facebook" name="dpw_facebook" value="' . esc_attr( $facebook ) . '" class="widefat"></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_instagram">Instagram URL</label><input type="url" id="dpw_instagram" name="dpw_instagram" value="' . esc_attr( $instagram ) . '" class="widefat"></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_twitter">Twitter/X URL</label><input type="url" id="dpw_twitter" name="dpw_twitter" value="' . esc_attr( $twitter ) . '" class="widefat"></div>';
    echo '<div class="dpw-meta-field"><label><input type="checkbox" name="dpw_is_primary" value="1" ' . checked( $is_primary, '1', false ) . '> Tampilkan di halaman utama (Pimpinan Utama)</label></div>';
}

function dpw_psi_division_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_division_nonce' );
    $head_name  = get_post_meta( $post->ID, '_dpw_head_name', true );
    $head_photo = get_post_meta( $post->ID, '_dpw_head_photo', true );
    echo '<div class="dpw-meta-field"><label for="dpw_head_name">Nama Ketua Bidang</label><input type="text" id="dpw_head_name" name="dpw_head_name" value="' . esc_attr( $head_name ) . '" class="widefat"></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_head_photo">Foto Ketua Bidang (URL)</label><input type="url" id="dpw_head_photo" name="dpw_head_photo" value="' . esc_attr( $head_photo ) . '" class="widefat"><button type="button" class="button dpw-media-upload" data-target="#dpw_head_photo" style="margin-top:6px">Pilih Media</button></div>';
}

function dpw_psi_dpd_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_dpd_nonce' );
    $ketua_name   = get_post_meta( $post->ID, '_dpw_ketua_name', true );
    $ketua_photo  = get_post_meta( $post->ID, '_dpw_ketua_photo', true );
    $kabupaten    = get_post_meta( $post->ID, '_dpw_kabupaten', true );
    $member_count = get_post_meta( $post->ID, '_dpw_member_count', true );
    $address      = get_post_meta( $post->ID, '_dpw_address', true );
    $phone        = get_post_meta( $post->ID, '_dpw_phone', true );
    echo '<div class="dpw-meta-field"><label for="dpw_kabupaten">Nama Kabupaten</label><input type="text" id="dpw_kabupaten" name="dpw_kabupaten" value="' . esc_attr( $kabupaten ) . '" class="widefat" required></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_ketua_name">Nama Ketua DPD</label><input type="text" id="dpw_ketua_name" name="dpw_ketua_name" value="' . esc_attr( $ketua_name ) . '" class="widefat" required></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_ketua_photo">Foto Ketua DPD (URL)</label><input type="url" id="dpw_ketua_photo" name="dpw_ketua_photo" value="' . esc_attr( $ketua_photo ) . '" class="widefat"><button type="button" class="button dpw-media-upload" data-target="#dpw_ketua_photo" style="margin-top:6px">Pilih Media</button></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_member_count">Jumlah Anggota</label><input type="number" id="dpw_member_count" name="dpw_member_count" value="' . esc_attr( $member_count ) . '" class="widefat" min="0"></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_address">Alamat Sekretariat</label><textarea id="dpw_address" name="dpw_address" class="widefat" rows="3">' . esc_textarea( $address ) . '</textarea></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_phone">Telepon/WhatsApp</label><input type="text" id="dpw_phone" name="dpw_phone" value="' . esc_attr( $phone ) . '" class="widefat"></div>';
}

function dpw_psi_video_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_video_nonce' );
    $video_url   = get_post_meta( $post->ID, '_dpw_video_url', true );
    $video_file  = get_post_meta( $post->ID, '_dpw_video_file', true );
    $is_featured = get_post_meta( $post->ID, '_dpw_is_featured', true );
    echo '<div class="dpw-meta-field"><label for="dpw_video_url">URL YouTube (untuk video embed)</label><input type="url" id="dpw_video_url" name="dpw_video_url" value="' . esc_attr( $video_url ) . '" class="widefat" placeholder="https://youtube.com/watch?v=..."></div>';
    echo '<div class="dpw-meta-field"><label for="dpw_video_file">File Video (self-hosted, URL)</label><input type="url" id="dpw_video_file" name="dpw_video_file" value="' . esc_attr( $video_file ) . '" class="widefat"><button type="button" class="button dpw-media-upload" data-target="#dpw_video_file" style="margin-top:6px">Pilih Media</button></div>';
    echo '<div class="dpw-meta-field"><label><input type="checkbox" name="dpw_is_featured" value="1" ' . checked( $is_featured, '1', false ) . '> Tampilkan sebagai video unggulan</label></div>';
}

add_action( 'save_post', 'dpw_psi_save_meta_boxes', 10, 2 );
function dpw_psi_save_meta_boxes( $post_id, $post ): void {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $nonce_fields = array(
        'slider'     => 'dpw_psi_slider_nonce',
        'leadership' => 'dpw_psi_leadership_nonce',
        'division'   => 'dpw_psi_division_nonce',
        'dpd'        => 'dpw_psi_dpd_nonce',
        'video'      => 'dpw_psi_video_nonce',
    );

    $pt = $post->post_type;
    if ( ! isset( $nonce_fields[ $pt ] ) ) return;

    if ( ! isset( $_POST[ $nonce_fields[ $pt ] ] ) || ! wp_verify_nonce( $_POST[ $nonce_fields[ $pt ] ], 'dpw_psi_save_meta' ) ) return;

    if ( $pt === 'slider' ) {
        update_post_meta( $post_id, '_dpw_slider_subtitle', sanitize_text_field( $_POST['dpw_slider_subtitle'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_slider_btn_text', sanitize_text_field( $_POST['dpw_slider_btn_text'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_slider_btn_url', esc_url_raw( $_POST['dpw_slider_btn_url'] ?? '#' ) );
        update_post_meta( $post_id, '_dpw_slider_order', absint( $_POST['dpw_slider_order'] ?? 0 ) );
    }

    if ( $pt === 'leadership' ) {
        update_post_meta( $post_id, '_dpw_position', sanitize_text_field( $_POST['dpw_position'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_facebook', esc_url_raw( $_POST['dpw_facebook'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_instagram', esc_url_raw( $_POST['dpw_instagram'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_twitter', esc_url_raw( $_POST['dpw_twitter'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_is_primary', absint( $_POST['dpw_is_primary'] ?? 0 ) );
    }

    if ( $pt === 'division' ) {
        update_post_meta( $post_id, '_dpw_head_name', sanitize_text_field( $_POST['dpw_head_name'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_head_photo', esc_url_raw( $_POST['dpw_head_photo'] ?? '' ) );
    }

    if ( $pt === 'dpd' ) {
        update_post_meta( $post_id, '_dpw_kabupaten', sanitize_text_field( $_POST['dpw_kabupaten'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_ketua_name', sanitize_text_field( $_POST['dpw_ketua_name'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_ketua_photo', esc_url_raw( $_POST['dpw_ketua_photo'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_member_count', absint( $_POST['dpw_member_count'] ?? 0 ) );
        update_post_meta( $post_id, '_dpw_address', sanitize_textarea_field( $_POST['dpw_address'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_phone', sanitize_text_field( $_POST['dpw_phone'] ?? '' ) );
    }

    if ( $pt === 'video' ) {
        update_post_meta( $post_id, '_dpw_video_url', esc_url_raw( $_POST['dpw_video_url'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_video_file', esc_url_raw( $_POST['dpw_video_file'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_is_featured', absint( $_POST['dpw_is_featured'] ?? 0 ) );
    }
}
