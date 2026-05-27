<?php
/**
 * Admin Settings Class
 *
 * @package PSI_Papeng_Premium
 */

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Settings {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_ajax_psi_papeng_save_settings', array( $this, 'ajax_save_settings' ) );
    }

    /**
     * Add admin menu pages
     */
    public function add_menu_pages() {
        // Main Settings Page
        add_menu_page(
            __( 'PSI Papeng Settings', 'psi-papeng-premium' ),
            __( 'PSI Papeng', 'psi-papeng-premium' ),
            'manage_options',
            'psi-papeng-settings',
            array( $this, 'render_settings_page' ),
            'dashicons-building',
            30
        );

        // Sub-pages
        $sub_pages = array(
            array( 'slider',    __( 'Slider', 'psi-papeng-premium' ) ),
            array( 'welcome',   __( 'Sambutan', 'psi-papeng-premium' ) ),
            array( 'leaders',   __( 'Pimpinan', 'psi-papeng-premium' ) ),
            array( 'divisions', __( 'Bidang', 'psi-papeng-premium' ) ),
            array( 'contact',   __( 'Kontak & Sosmed', 'psi-papeng-premium' ) ),
            array( 'seo',       __( 'SEO', 'psi-papeng-premium' ) ),
        );

        foreach ( $sub_pages as $page ) {
            add_submenu_page(
                'psi-papeng-settings',
                $page[1],
                $page[1],
                'manage_options',
                'psi-papeng-' . $page[0],
                array( $this, 'render_' . $page[0] . '_page' )
            );
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'psi-papeng' ) === false ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        wp_enqueue_style(
            'psi-papeng-admin',
            PSI_PAPENG_URI . 'assets/css/admin.css',
            array(),
            PSI_PAPENG_VER
        );

        wp_enqueue_script(
            'psi-papeng-admin-js',
            PSI_PAPENG_URI . 'assets/js/admin-settings.js',
            array( 'jquery', 'wp-color-picker' ),
            PSI_PAPENG_VER,
            true
        );

        wp_localize_script( 'psi-papeng-admin-js', 'psiPapengAdmin', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'psi_papeng_admin_nonce' ),
        ) );
    }

    /**
     * AJAX Save Settings
     */
    public function ajax_save_settings() {
        check_ajax_referer( 'psi_papeng_admin_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        $section = isset( $_POST['section'] ) ? sanitize_text_field( wp_unslash( $_POST['section'] ) ) : '';

        $allowed_sections = array( 'slides', 'welcome', 'leadership', 'divisions', 'social', 'contact', 'seo' );
        if ( ! in_array( $section, $allowed_sections, true ) ) {
            wp_send_json_error( array( 'message' => 'Invalid section' ) );
        }

        $option_key = 'dpw_psi_' . $section;
        $raw_data   = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';

        // Sanitize based on section
        $data = $this->sanitize_section_data( $section, $raw_data );

        update_option( $option_key, $data );
        wp_send_json_success( array( 'message' => 'Settings saved successfully.' ) );
    }

    /**
     * Sanitize data per section
     */
    private function sanitize_section_data( $section, $raw_data ) {
        if ( is_string( $raw_data ) ) {
            $data = json_decode( $raw_data, true );
        } else {
            $data = $raw_data;
        }

        if ( ! is_array( $data ) ) {
            return array();
        }

        switch ( $section ) {
            case 'slides':
                $clean = array();
                foreach ( $data as $slide ) {
                    $clean[] = array(
                        'image'    => esc_url_raw( $slide['image'] ?? '' ),
                        'title'    => sanitize_text_field( $slide['title'] ?? '' ),
                        'subtitle' => sanitize_textarea_field( $slide['subtitle'] ?? '' ),
                        'btn_text' => sanitize_text_field( $slide['btn_text'] ?? '' ),
                        'btn_url'  => esc_url_raw( $slide['btn_url'] ?? '' ),
                    );
                }
                return $clean;

            case 'welcome':
                return array(
                    'image' => esc_url_raw( $data['image'] ?? '' ),
                    'title' => sanitize_text_field( $data['title'] ?? '' ),
                    'text'  => wp_kses_post( $data['text'] ?? '' ),
                    'name'  => sanitize_text_field( $data['name'] ?? '' ),
                    'role'  => sanitize_text_field( $data['role'] ?? '' ),
                );

            case 'leadership':
                $clean = array();
                foreach ( $data as $leader ) {
                    $clean[] = array(
                        'name'   => sanitize_text_field( $leader['name'] ?? '' ),
                        'role'   => sanitize_text_field( $leader['role'] ?? '' ),
                        'photo'  => esc_url_raw( $leader['photo'] ?? '' ),
                        'desc'   => sanitize_text_field( $leader['desc'] ?? '' ),
                        'social' => array(
                            'facebook'  => esc_url_raw( $leader['social']['facebook'] ?? '' ),
                            'instagram' => esc_url_raw( $leader['social']['instagram'] ?? '' ),
                        ),
                    );
                }
                return $clean;

            case 'divisions':
                $clean = array();
                foreach ( $data as $div ) {
                    $clean[] = array(
                        'title' => sanitize_text_field( $div['title'] ?? '' ),
                        'icon'  => sanitize_text_field( $div['icon'] ?? 'bi-briefcase' ),
                        'head'  => sanitize_text_field( $div['head'] ?? '' ),
                        'desc'  => sanitize_text_field( $div['desc'] ?? '' ),
                    );
                }
                return $clean;

            case 'social':
                return array(
                    'facebook'  => esc_url_raw( $data['facebook'] ?? '' ),
                    'instagram' => esc_url_raw( $data['instagram'] ?? '' ),
                    'youtube'   => esc_url_raw( $data['youtube'] ?? '' ),
                    'tiktok'    => esc_url_raw( $data['tiktok'] ?? '' ),
                );

            case 'contact':
                return array(
                    'address'  => sanitize_textarea_field( $data['address'] ?? '' ),
                    'email'    => sanitize_email( $data['email'] ?? '' ),
                    'whatsapp' => sanitize_text_field( $data['whatsapp'] ?? '' ),
                    'map_url'  => esc_url_raw( $data['map_url'] ?? '' ),
                );

            case 'seo':
                return array(
                    'enable_sitemap' => sanitize_text_field( $data['enable_sitemap'] ?? 'yes' ),
                    'robots_txt'     => sanitize_textarea_field( $data['robots_txt'] ?? '' ),
                );

            default:
                return array();
        }
    }

    /**
     * Render wrapper for all settings pages
     */
    private function render_page_wrapper( $page_slug, $content_callback ) {
        ?>
        <div class="wrap psi-admin-wrap">
            <div class="psi-admin-header">
                <div class="psi-admin-logo">
                    <img src="<?php echo esc_url( DPW_PSI_URI . '/assets/images/logo.png' ); ?>" alt="PSI" onerror="this.style.display='none'">
                    <div>
                        <h1>PSI Papeng <span>Premium</span></h1>
                        <p>v<?php echo esc_html( PSI_PAPENG_VER ); ?></p>
                    </div>
                </div>
                <nav class="psi-admin-tabs">
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-slider' ) ); ?>" class="<?php echo $page_slug === 'slider' ? 'active' : ''; ?>">
                        <i class="dashicons dashicons-images-alt2"></i> Slider
                    </a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-welcome' ) ); ?>" class="<?php echo $page_slug === 'welcome' ? 'active' : ''; ?>">
                        <i class="dashicons dashicons-format-quote"></i> Sambutan
                    </a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-leaders' ) ); ?>" class="<?php echo $page_slug === 'leaders' ? 'active' : ''; ?>">
                        <i class="dashicons dashicons-groups"></i> Pimpinan
                    </a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-divisions' ) ); ?>" class="<?php echo $page_slug === 'divisions' ? 'active' : ''; ?>">
                        <i class="dashicons dashicons-networking"></i> Bidang
                    </a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-contact' ) ); ?>" class="<?php echo $page_slug === 'contact' ? 'active' : ''; ?>">
                        <i class="dashicons dashicons-share"></i> Kontak
                    </a>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-papeng-seo' ) ); ?>" class="<?php echo $page_slug === 'seo' ? 'active' : ''; ?>">
                        <i class="dashicons dashicons-search"></i> SEO
                    </a>
                </nav>
            </div>

            <div class="psi-admin-content">
                <div id="psi-admin-notice" style="display:none;"></div>
                <?php call_user_func( $content_callback ); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Main Settings Page (redirect to slider)
     */
    public function render_settings_page() {
        wp_redirect( admin_url( 'admin.php?page=psi-papeng-slider' ) );
        exit;
    }

    /**
     * Slider Settings Page
     */
    public function render_slider_page() {
        $slides = get_option( 'dpw_psi_slides', array() );
        $this->render_page_wrapper( 'slider', function() use ( $slides ) {
            ?>
            <div class="psi-section-header">
                <h2>Kelola Slider Hero</h2>
                <p>Tambah, edit, atau hapus slide pada bagian hero halaman utama.</p>
                <button class="button button-primary button-hero" id="addSlideBtn">
                    <i class="dashicons dashicons-plus-alt2"></i> Tambah Slide Baru
                </button>
            </div>

            <div id="slidesContainer">
                <?php if ( empty( $slides ) ) : ?>
                    <div class="psi-empty-state">
                        <i class="dashicons dashicons-images-alt2"></i>
                        <p>Belum ada slide. Klik tombol di atas untuk menambahkan.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ( $slides as $i => $slide ) : ?>
                    <div class="psi-repeater-item" data-index="<?php echo esc_attr( $i ); ?>">
                        <div class="psi-repeater-header">
                            <span class="psi-repeater-title">Slide <?php echo esc_html( $i + 1 ); ?></span>
                            <div class="psi-repeater-actions">
                                <button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>
                                <button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>
                            </div>
                        </div>
                        <div class="psi-repeater-body">
                            <div class="psi-field">
                                <label>Gambar Slide</label>
                                <div class="psi-image-upload">
                                    <input type="hidden" class="psi-slide-image" value="<?php echo esc_attr( $slide['image'] ); ?>">
                                    <?php if ( $slide['image'] ) : ?>
                                        <img src="<?php echo esc_url( $slide['image'] ); ?>" class="psi-image-preview">
                                    <?php endif; ?>
                                    <button type="button" class="button psi-upload-btn">Pilih Gambar</button>
                                    <button type="button" class="button psi-remove-img-btn" style="color:#a00;">Hapus</button>
                                </div>
                            </div>
                            <div class="psi-field">
                                <label>Judul</label>
                                <input type="text" class="regular-text psi-slide-title" value="<?php echo esc_attr( $slide['title'] ); ?>">
                            </div>
                            <div class="psi-field">
                                <label>Subjudul</label>
                                <textarea class="large-text psi-slide-subtitle" rows="3"><?php echo esc_textarea( $slide['subtitle'] ); ?></textarea>
                            </div>
                            <div class="psi-field-row">
                                <div class="psi-field">
                                    <label>Teks Tombol</label>
                                    <input type="text" class="regular-text psi-slide-btn-text" value="<?php echo esc_attr( $slide['btn_text'] ); ?>">
                                </div>
                                <div class="psi-field">
                                    <label>URL Tombol</label>
                                    <input type="url" class="regular-text psi-slide-btn-url" value="<?php echo esc_attr( $slide['btn_url'] ); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="psi-save-bar">
                <button class="button button-primary button-hero" id="saveSlidesBtn">
                    <i class="dashicons dashicons-saved"></i> Simpan Slider
                </button>
            </div>
            <?php
        });
    }

    /**
     * Welcome/Sambutan Settings Page
     */
    public function render_welcome_page() {
        $welcome = get_option( 'dpw_psi_welcome', array() );
        $this->render_page_wrapper( 'welcome', function() use ( $welcome ) {
            ?>
            <div class="psi-section-header">
                <h2>Kelola Sambutan Ketua</h2>
                <p>Atur teks sambutan, foto, dan informasi Ketua DPW yang tampil di halaman utama.</p>
            </div>

            <div class="psi-card">
                <div class="psi-field">
                    <label>Foto Ketua</label>
                    <div class="psi-image-upload">
                        <input type="hidden" id="welcomeImage" value="<?php echo esc_attr( $welcome['image'] ?? '' ); ?>">
                        <?php if ( ! empty( $welcome['image'] ) ) : ?>
                            <img src="<?php echo esc_url( $welcome['image'] ); ?>" class="psi-image-preview" style="max-width:200px;">
                        <?php endif; ?>
                        <button type="button" class="button psi-upload-btn" data-target="welcomeImage">Pilih Gambar</button>
                        <button type="button" class="button psi-remove-img-btn" data-target="welcomeImage" style="color:#a00;">Hapus</button>
                    </div>
                </div>
                <div class="psi-field">
                    <label>Judul</label>
                    <input type="text" id="welcomeTitle" class="large-text" value="<?php echo esc_attr( $welcome['title'] ?? '' ); ?>">
                </div>
                <div class="psi-field">
                    <label>Isi Sambutan</label>
                    <?php
                    wp_editor( $welcome['text'] ?? '', 'welcomeText', array(
                        'textarea_name' => 'welcomeText',
                        'textarea_rows' => 10,
                        'media_buttons' => false,
                        'teeny'         => true,
                    ) );
                    ?>
                </div>
                <div class="psi-field-row">
                    <div class="psi-field">
                        <label>Nama Ketua</label>
                        <input type="text" id="welcomeName" class="large-text" value="<?php echo esc_attr( $welcome['name'] ?? '' ); ?>">
                    </div>
                    <div class="psi-field">
                        <label>Jabatan</label>
                        <input type="text" id="welcomeRole" class="large-text" value="<?php echo esc_attr( $welcome['role'] ?? '' ); ?>">
                    </div>
                </div>
            </div>

            <div class="psi-save-bar">
                <button class="button button-primary button-hero" id="saveWelcomeBtn" data-section="welcome">
                    <i class="dashicons dashicons-saved"></i> Simpan Sambutan
                </button>
            </div>
            <?php
        });
    }

    /**
     * Leadership Settings Page
     */
    public function render_leaders_page() {
        $leaders = get_option( 'dpw_psi_leadership', array() );
        $this->render_page_wrapper( 'leaders', function() use ( $leaders ) {
            ?>
            <div class="psi-section-header">
                <h2>Kelola Data Pimpinan</h2>
                <p>Tambahkan data pimpinan inti DPW (Ketua, Sekretaris, Bendahara).</p>
                <button class="button button-primary button-hero" id="addLeaderBtn">
                    <i class="dashicons dashicons-plus-alt2"></i> Tambah Pimpinan
                </button>
            </div>

            <div id="leadersContainer">
                <?php if ( empty( $leaders ) ) : ?>
                    <div class="psi-empty-state">
                        <i class="dashicons dashicons-groups"></i>
                        <p>Belum ada data pimpinan.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ( $leaders as $i => $leader ) : ?>
                    <div class="psi-repeater-item" data-index="<?php echo esc_attr( $i ); ?>">
                        <div class="psi-repeater-header">
                            <span class="psi-repeater-title"><?php echo esc_html( $leader['name'] ?: 'Pimpinan ' . ( $i + 1 ) ); ?></span>
                            <div class="psi-repeater-actions">
                                <button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>
                                <button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>
                            </div>
                        </div>
                        <div class="psi-repeater-body">
                            <div class="psi-field">
                                <label>Foto</label>
                                <div class="psi-image-upload">
                                    <input type="hidden" class="psi-leader-photo" value="<?php echo esc_attr( $leader['photo'] ); ?>">
                                    <?php if ( $leader['photo'] ) : ?>
                                        <img src="<?php echo esc_url( $leader['photo'] ); ?>" class="psi-image-preview" style="max-width:120px;">
                                    <?php endif; ?>
                                    <button type="button" class="button psi-upload-btn">Pilih Gambar</button>
                                    <button type="button" class="button psi-remove-img-btn" style="color:#a00;">Hapus</button>
                                </div>
                            </div>
                            <div class="psi-field-row">
                                <div class="psi-field">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="large-text psi-leader-name" value="<?php echo esc_attr( $leader['name'] ); ?>">
                                </div>
                                <div class="psi-field">
                                    <label>Jabatan</label>
                                    <input type="text" class="regular-text psi-leader-role" value="<?php echo esc_attr( $leader['role'] ); ?>">
                                </div>
                            </div>
                            <div class="psi-field">
                                <label>Deskripsi Singkat</label>
                                <textarea class="large-text psi-leader-desc" rows="3"><?php echo esc_textarea( $leader['desc'] ); ?></textarea>
                            </div>
                            <div class="psi-field-row">
                                <div class="psi-field">
                                    <label>Facebook URL</label>
                                    <input type="url" class="large-text psi-leader-fb" value="<?php echo esc_attr( $leader['social']['facebook'] ?? '' ); ?>">
                                </div>
                                <div class="psi-field">
                                    <label>Instagram URL</label>
                                    <input type="url" class="large-text psi-leader-ig" value="<?php echo esc_attr( $leader['social']['instagram'] ?? '' ); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="psi-save-bar">
                <button class="button button-primary button-hero" id="saveLeadersBtn">
                    <i class="dashicons dashicons-saved"></i> Simpan Pimpinan
                </button>
            </div>
            <?php
        });
    }

    /**
     * Divisions Settings Page
     */
    public function render_divisions_page() {
        $divisions = get_option( 'dpw_psi_divisions', array() );
        $icons = array( 'bi-bank','bi-shop','bi-cpu','bi-trophy','bi-tree','bi-heart-pulse','bi-book','bi-people','bi-briefcase','bi-gear','bi-globe','bi-shield-check' );

        $this->render_page_wrapper( 'divisions', function() use ( $divisions, $icons ) {
            ?>
            <div class="psi-section-header">
                <h2>Kelola Bidang Organisasi</h2>
                <p>Atur 8 bidang kerja organisasi DPW PSI Papua Pegunungan.</p>
                <button class="button button-primary button-hero" id="addDivisionBtn">
                    <i class="dashicons dashicons-plus-alt2"></i> Tambah Bidang
                </button>
            </div>

            <div id="divisionsContainer">
                <?php if ( empty( $divisions ) ) : ?>
                    <div class="psi-empty-state">
                        <i class="dashicons dashicons-networking"></i>
                        <p>Belum ada data bidang.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ( $divisions as $i => $div ) : ?>
                    <div class="psi-repeater-item" data-index="<?php echo esc_attr( $i ); ?>">
                        <div class="psi-repeater-header">
                            <span class="psi-repeater-title"><?php echo esc_html( $div['title'] ?: 'Bidang ' . ( $i + 1 ) ); ?></span>
                            <div class="psi-repeater-actions">
                                <button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>
                                <button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>
                            </div>
                        </div>
                        <div class="psi-repeater-body">
                            <div class="psi-field-row">
                                <div class="psi-field">
                                    <label>Nama Bidang</label>
                                    <input type="text" class="large-text psi-div-title" value="<?php echo esc_attr( $div['title'] ); ?>">
                                </div>
                                <div class="psi-field">
                                    <label>Ikon (Bootstrap Icons)</label>
                                    <select class="psi-div-icon">
                                        <?php foreach ( $icons as $icon ) : ?>
                                            <option value="<?php echo esc_attr( $icon ); ?>" <?php selected( $div['icon'] ?? '', $icon ); ?>><?php echo esc_html( $icon ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="psi-field">
                                <label>Nama Ketua Bidang</label>
                                <input type="text" class="large-text psi-div-head" value="<?php echo esc_attr( $div['head'] ?? '' ); ?>">
                            </div>
                            <div class="psi-field">
                                <label>Deskripsi</label>
                                <textarea class="large-text psi-div-desc" rows="3"><?php echo esc_textarea( $div['desc'] ); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="psi-save-bar">
                <button class="button button-primary button-hero" id="saveDivisionsBtn">
                    <i class="dashicons dashicons-saved"></i> Simpan Bidang
                </button>
            </div>
            <?php
        });
    }

    /**
     * Contact & Social Settings Page
     */
    public function render_contact_page() {
        $social  = get_option( 'dpw_psi_social', array() );
        $contact = get_option( 'dpw_psi_contact', array() );
        $this->render_page_wrapper( 'contact', function() use ( $social, $contact ) {
            ?>
            <div class="psi-section-header">
                <h2>Kontak & Media Sosial</h2>
                <p>Atur informasi kontak dan tautan media sosial organisasi.</p>
            </div>

            <div class="psi-cards-grid">
                <div class="psi-card">
                    <h3><i class="dashicons dashicons-share"></i> Media Sosial</h3>
                    <div class="psi-field">
                        <label>Facebook</label>
                        <input type="url" id="socialFacebook" class="large-text" value="<?php echo esc_attr( $social['facebook'] ?? '' ); ?>" placeholder="https://facebook.com/...">
                    </div>
                    <div class="psi-field">
                        <label>Instagram</label>
                        <input type="url" id="socialInstagram" class="large-text" value="<?php echo esc_attr( $social['instagram'] ?? '' ); ?>" placeholder="https://instagram.com/...">
                    </div>
                    <div class="psi-field">
                        <label>YouTube</label>
                        <input type="url" id="socialYoutube" class="large-text" value="<?php echo esc_attr( $social['youtube'] ?? '' ); ?>" placeholder="https://youtube.com/...">
                    </div>
                    <div class="psi-field">
                        <label>TikTok</label>
                        <input type="url" id="socialTiktok" class="large-text" value="<?php echo esc_attr( $social['tiktok'] ?? '' ); ?>" placeholder="https://tiktok.com/...">
                    </div>
                    <button class="button button-primary" id="saveSocialBtn" data-section="social">
                        <i class="dashicons dashicons-saved"></i> Simpan Sosmed
                    </button>
                </div>

                <div class="psi-card">
                    <h3><i class="dashicons dashicons-phone"></i> Informasi Kontak</h3>
                    <div class="psi-field">
                        <label>Alamat Kantor</label>
                        <textarea id="contactAddress" class="large-text" rows="3"><?php echo esc_textarea( $contact['address'] ?? '' ); ?></textarea>
                    </div>
                    <div class="psi-field">
                        <label>Email Resmi</label>
                        <input type="email" id="contactEmail" class="large-text" value="<?php echo esc_attr( $contact['email'] ?? '' ); ?>">
                    </div>
                    <div class="psi-field">
                        <label>Nomor WhatsApp</label>
                        <input type="text" id="contactWhatsapp" class="large-text" value="<?php echo esc_attr( $contact['whatsapp'] ?? '' ); ?>" placeholder="+62 xxx xxxx xxxx">
                    </div>
                    <div class="psi-field">
                        <label>Google Maps Embed URL</label>
                        <input type="url" id="contactMap" class="large-text" value="<?php echo esc_attr( $contact['map_url'] ?? '' ); ?>" placeholder="https://www.google.com/maps/embed?...">
                    </div>
                    <button class="button button-primary" id="saveContactBtn" data-section="contact">
                        <i class="dashicons dashicons-saved"></i> Simpan Kontak
                    </button>
                </div>
            </div>
            <?php
        });
    }

    /**
     * SEO Settings Page
     */
    public function render_seo_page() {
        $seo = get_option( 'dpw_psi_seo', array() );
        $this->render_page_wrapper( 'seo', function() use ( $seo ) {
            ?>
            <div class="psi-section-header">
                <h2>Pengaturan SEO</h2>
                <p>Konfigurasi Search Engine Optimization untuk website.</p>
            </div>

            <div class="psi-card">
                <div class="psi-field">
                    <label>
                        <input type="checkbox" id="seoSitemap" value="yes" <?php checked( $seo['enable_sitemap'] ?? 'yes', 'yes' ); ?>>
                        Aktifkan XML Sitemap
                    </label>
                </div>
                <div class="psi-field">
                    <label>robots.txt</label>
                    <textarea id="seoRobots" class="large-text code" rows="10"><?php echo esc_textarea( $seo['robots_txt'] ?? '' ); ?></textarea>
                    <p class="description">Isi file robots.txt. Sitemap URL akan otomatis ditambahkan.</p>
                </div>
                <button class="button button-primary button-hero" id="saveSeoBtn" data-section="seo">
                    <i class="dashicons dashicons-saved"></i> Simpan SEO
                </button>
            </div>
            <?php
        });
    }
}
