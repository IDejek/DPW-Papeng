<?php
/**
 * Custom Meta Boxes
 * @package DPW_PSIPapeng
 */

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', 'dpw_psi_register_meta_boxes' );
function dpw_psi_register_meta_boxes(): void {

    /* Slider */
    add_meta_box( 'dpw_slider_meta', __( 'Pengaturan Slide', 'dpw-psi-papeng' ), 'dpw_psi_slider_meta_cb', 'slider', 'normal', 'high' );

    /* Leadership */
    add_meta_box( 'dpw_leadership_meta', __( 'Detail Pimpinan', 'dpw-psi-papeng' ), 'dpw_psi_leadership_meta_cb', 'leadership', 'normal', 'high' );

    /* Division */
    add_meta_box( 'dpw_division_meta', __( 'Detail Bidang', 'dpw-psi-papeng' ), 'dpw_psi_division_meta_cb', 'division', 'normal', 'high' );

    /* DPD */
    add_meta_box( 'dpw_dpd_meta', __( 'Detail DPD', 'dpw-psi-papeng' ), 'dpw_psi_dpd_meta_cb', 'dpd', 'normal', 'high' );

    /* Video */
    add_meta_box( 'dpw_video_meta', __( 'Detail Video', 'dpw-psi-papeng' ), 'dpw_psi_video_meta_cb', 'video', 'normal', 'high' );
}

/* ── Slider Meta ───────────────────────────────────────────── */
function dpw_psi_slider_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_slider_nonce' );
    $subtitle = dpw_psi_get_meta( $post->ID, 'slider_subtitle', '' );
    $btn_text = dpw_psi_get_meta( $post->ID, 'slider_btn_text', 'Selengkapnya' );
    $btn_url  = dpw_psi_get_meta( $post->ID, 'slider_btn_url', '#' );
    $order    = dpw_psi_get_meta( $post->ID, 'slider_order', 0 );
    ?>
    <div class="dpw-meta-field">
        <label for="dpw_slider_subtitle"><?php esc_html_e( 'Subtitle', 'dpw-psi-papeng' ); ?></label>
        <input type="text" id="dpw_slider_subtitle" name="dpw_slider_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="widefat">
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_slider_btn_text"><?php esc_html_e( 'Teks Tombol', 'dpw-psi-papeng' ); ?></label>
        <input type="text" id="dpw_slider_btn_text" name="dpw_slider_btn_text" value="<?php echo esc_attr( $btn_text ); ?>" class="widefat">
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_slider_btn_url"><?php esc_html_e( 'URL Tombol', 'dpw-psi-papeng' ); ?></label>
        <input type="url" id="dpw_slider_btn_url" name="dpw_slider_btn_url" value="<?php echo esc_attr( $btn_url ); ?>" class="widefat">
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_slider_order"><?php esc_html_e( 'Urutan', 'dpw-psi-papeng' ); ?></label>
        <input type="number" id="dpw_slider_order" name="dpw_slider_order" value="<?php echo esc_attr( $order ); ?>" class="widefat" min="0">
    </div>
    <?php
}

/* ── Leadership Meta ───────────────────────────────────────── */
function dpw_psi_leadership_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_leadership_nonce' );
    $position   = dpw_psi_get_meta( $post->ID, 'position', '' );
    $facebook   = dpw_psi_get_meta( $post->ID, 'facebook', '' );
    $instagram  = dpw_psi_get_meta( $post->ID, 'instagram', '' );
    $twitter    = dpw_psi_get_meta( $post->ID, 'twitter', '' );
    $is_primary = dpw_psi_get_meta( $post->ID, 'is_primary', '0' );
    ?>
    <div class="dpw-meta-field">
        <label for="dpw_position"><?php esc_html_e( 'Jabatan', 'dpw-psi-papeng' ); ?></label>
        <input type="text" id="dpw_position" name="dpw_position" value="<?php echo esc_attr( $position ); ?>" class="widefat" required>
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_facebook"><?php esc_html_e( 'Facebook URL', 'dpw-psi-papeng' ); ?></label>
        <input type="url" id="dpw_facebook" name="dpw_facebook" value="<?php echo esc_attr( $facebook ); ?>" class="widefat">
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_instagram"><?php esc_html_e( 'Instagram URL', 'dpw-psi-papeng' ); ?></label>
        <input type="url" id="dpw_instagram" name="dpw_instagram" value="<?php echo esc_attr( $instagram ); ?>" class="widefat">
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_twitter"><?php esc_html_e( 'Twitter/X URL', 'dpw-psi-papeng' ); ?></label>
        <input type="url" id="dpw_twitter" name="dpw_twitter" value="<?php echo esc_attr( $twitter ); ?>" class="widefat">
    </div>
    <div class="dpw-meta-field">
        <label><input type="checkbox" name="dpw_is_primary" value="1" <?php checked( $is_primary, '1' ); ?>> <?php esc_html_e( 'Tampilkan di halaman utama (Pimpinan Utama)', 'dpw-psi-papeng' ); ?></label>
    </div>
    <?php
}

/* ── Division Meta ─────────────────────────────────────────── */
function dpw_psi_division_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_division_nonce' );
    $head_name  = dpw_psi_get_meta( $post->ID, 'head_name', '' );
    $head_photo = dpw_psi_get_meta( $post->ID, 'head_photo', '' );
    ?>
    <div class="dpw-meta-field">
        <label for="dpw_head_name"><?php esc_html_e( 'Nama Ketua Bidang', 'dpw-psi-papeng' ); ?></label>
        <input type="text" id="dpw_head_name" name="dpw_head_name" value="<?php echo esc_attr( $head_name ); ?>" class="widefat">
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_head_photo"><?php esc_html_e( 'Foto Ketua Bidang (URL)', 'dpw-psi-papeng' ); ?></label>
        <input type="url" id="dpw_head_photo" name="dpw_head_photo" value="<?php echo esc_attr( $head_photo ); ?>" class="widefat">
        <button type="button" class="button dpw-media-upload" data-target="#dpw_head_photo"><?php esc_html_e( 'Pilih Media', 'dpw-psi-papeng' ); ?></button>
    </div>
    <?php
}

/* ── DPD Meta ──────────────────────────────────────────────── */
function dpw_psi_dpd_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_dpd_nonce' );
    $ketua_name   = dpw_psi_get_meta( $post->ID, 'ketua_name', '' );
    $ketua_photo  = dpw_psi_get_meta( $post->ID, 'ketua_photo', '' );
    $kabupaten    = dpw_psi_get_meta( $post->ID, 'kabupaten', '' );
    $member_count = dpw_psi_get_meta( $post->ID, 'member_count', '0' );
    $address      = dpw_psi_get_meta( $post->ID, 'address', '' );
    $phone        = dpw_psi_get_meta( $post->ID, 'phone', '' );
    ?>
    <div class="dpw-meta-field">
        <label for="dpw_kabupaten"><?php esc_html_e( 'Nama Kabupaten', 'dpw-psi-papeng' ); ?></label>
        <input type="text" id="dpw_kabupaten" name="dpw_kabupaten" value="<?php echo esc_attr( $kabupaten ); ?>" class="widefat" required>
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_ketua_name"><?php esc_html_e( 'Nama Ketua DPD', 'dpw-psi-papeng' ); ?></label>
        <input type="text" id="dpw_ketua_name" name="dpw_ketua_name" value="<?php echo esc_attr( $ketua_name ); ?>" class="widefat" required>
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_ketua_photo"><?php esc_html_e( 'Foto Ketua DPD (URL)', 'dpw-psi-papeng' ); ?></label>
        <input type="url" id="dpw_ketua_photo" name="dpw_ketua_photo" value="<?php echo esc_attr( $ketua_photo ); ?>" class="widefat">
        <button type="button" class="button dpw-media-upload" data-target="#dpw_ketua_photo"><?php esc_html_e( 'Pilih Media', 'dpw-psi-papeng' ); ?></button>
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_member_count"><?php esc_html_e( 'Jumlah Anggota', 'dpw-psi-papeng' ); ?></label>
        <input type="number" id="dpw_member_count" name="dpw_member_count" value="<?php echo esc_attr( $member_count ); ?>" class="widefat" min="0">
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_address"><?php esc_html_e( 'Alamat Sekretariat', 'dpw-psi-papeng' ); ?></label>
        <textarea id="dpw_address" name="dpw_address" class="widefat" rows="3"><?php echo esc_textarea( $address ); ?></textarea>
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_phone"><?php esc_html_e( 'Telepon/WhatsApp', 'dpw-psi-papeng' ); ?></label>
        <input type="text" id="dpw_phone" name="dpw_phone" value="<?php echo esc_attr( $phone ); ?>" class="widefat">
    </div>
    <?php
}

/* ── Video Meta ────────────────────────────────────────────── */
function dpw_psi_video_meta_cb( $post ): void {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_video_nonce' );
    $video_url   = dpw_psi_get_meta( $post->ID, 'video_url', '' );
    $video_file  = dpw_psi_get_meta( $post->ID, 'video_file', '' );
    $is_featured = dpw_psi_get_meta( $post->ID, 'is_featured', '0' );
    ?>
    <div class="dpw-meta-field">
        <label for="dpw_video_url"><?php esc_html_e( 'URL YouTube (untuk video embed)', 'dpw-psi-papeng' ); ?></label>
        <input type="url" id="dpw_video_url" name="dpw_video_url" value="<?php echo esc_attr( $video_url ); ?>" class="widefat" placeholder="https://youtube.com/watch?v=...">
    </div>
    <div class="dpw-meta-field">
        <label for="dpw_video_file"><?php esc_html_e( 'File Video (self-hosted, URL)', 'dpw-psi-papeng' ); ?></label>
        <input type="url" id="dpw_video_file" name="dpw_video_file" value="<?php echo esc_attr( $video_file ); ?>" class="widefat">
        <button type="button" class="button dpw-media-upload" data-target="#dpw_video_file"><?php esc_html_e( 'Pilih Media', 'dpw-psi-papeng' ); ?></button>
    </div>
    <div class="dpw-meta-field">
        <label><input type="checkbox" name="dpw_is_featured" value="1" <?php checked( $is_featured, '1' ); ?>> <?php esc_html_e( 'Tampilkan sebagai video unggulan', 'dpw-psi-papeng' ); ?></label>
    </div>
    <?php
}

/* ── Save Meta Boxes ───────────────────────────────────────── */
add_action( 'save_post', 'dpw_psi_save_meta_boxes', 10, 2 );
function dpw_psi_save_meta_boxes( $post_id, $post ): void {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $nonce_fields = [
        'slider'     => 'dpw_psi_slider_nonce',
        'leadership' => 'dpw_psi_leadership_nonce',
        'division'   => 'dpw_psi_division_nonce',
        'dpd'        => 'dpw_psi_dpd_nonce',
        'video'      => 'dpw_psi_video_nonce',
    ];

    $pt = $post->post_type;
    if ( ! isset( $nonce_fields[ $pt ] ) ) return;

    if ( ! isset( $_POST[ $nonce_fields[ $pt ] ] ) || ! wp_verify_nonce( $_POST[ $nonce_fields[ $pt ] ], 'dpw_psi_save_meta' ) ) return;

    /* Slider fields */
    if ( $pt === 'slider' ) {
        update_post_meta( $post_id, '_dpw_slider_subtitle', sanitize_text_field( $_POST['dpw_slider_subtitle'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_slider_btn_text', sanitize_text_field( $_POST['dpw_slider_btn_text'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_slider_btn_url', esc_url_raw( $_POST['dpw_slider_btn_url'] ?? '#' ) );
        update_post_meta( $post_id, '_dpw_slider_order', absint( $_POST['dpw_slider_order'] ?? 0 ) );
    }

    /* Leadership fields */
    if ( $pt === 'leadership' ) {
        update_post_meta( $post_id, '_dpw_position', sanitize_text_field( $_POST['dpw_position'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_facebook', esc_url_raw( $_POST['dpw_facebook'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_instagram', esc_url_raw( $_POST['dpw_instagram'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_twitter', esc_url_raw( $_POST['dpw_twitter'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_is_primary', absint( $_POST['dpw_is_primary'] ?? 0 ) );
    }

    /* Division fields */
    if ( $pt === 'division' ) {
        update_post_meta( $post_id, '_dpw_head_name', sanitize_text_field( $_POST['dpw_head_name'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_head_photo', esc_url_raw( $_POST['dpw_head_photo'] ?? '' ) );
    }

    /* DPD fields */
    if ( $pt === 'dpd' ) {
        update_post_meta( $post_id, '_dpw_kabupaten', sanitize_text_field( $_POST['dpw_kabupaten'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_ketua_name', sanitize_text_field( $_POST['dpw_ketua_name'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_ketua_photo', esc_url_raw( $_POST['dpw_ketua_photo'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_member_count', absint( $_POST['dpw_member_count'] ?? 0 ) );
        update_post_meta( $post_id, '_dpw_address', sanitize_textarea_field( $_POST['dpw_address'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_phone', sanitize_text_field( $_POST['dpw_phone'] ?? '' ) );
    }

    /* Video fields */
    if ( $pt === 'video' ) {
        update_post_meta( $post_id, '_dpw_video_url', esc_url_raw( $_POST['dpw_video_url'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_video_file', esc_url_raw( $_POST['dpw_video_file'] ?? '' ) );
        update_post_meta( $post_id, '_dpw_is_featured', absint( $_POST['dpw_is_featured'] ?? 0 ) );
    }
}
