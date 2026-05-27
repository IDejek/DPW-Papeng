<?php
/**
 * Admin Settings Class
 * @package PSI_Papeng_Premium
 * @version 1.0.1 (Anti-WSOD Update)
 */

defined('ABSPATH') || exit;

class PSI_Papeng_Settings {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu_pages'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_psi_papeng_save_settings', array($this, 'ajax_save_settings'));
    }

    public function add_menu_pages() {
        add_menu_page(
            __('PSI Papeng Settings', 'psi-papeng-premium'),
            __('PSI Papeng', 'psi-papeng-premium'),
            'manage_options',
            'psi-papeng-settings',
            array($this, 'render_redirect_page'),
            'dashicons-building',
            30
        );

        $sub_pages = array(
            array('slider', __('Slider', 'psi-papeng-premium')),
            array('welcome', __('Sambutan', 'psi-papeng-premium')),
            array('leaders', __('Pimpinan', 'psi-papeng-premium')),
            array('divisions', __('Bidang', 'psi-papeng-premium')),
            array('contact', __('Kontak & Sosmed', 'psi-papeng-premium')),
            array('seo', __('SEO', 'psi-papeng-premium'))
        );

        foreach ($sub_pages as $page) {
            add_submenu_page(
                'psi-papeng-settings',
                $page[1],
                $page[1],
                'manage_options',
                'psi-papeng-' . $page[0],
                array($this, 'render_' . $page[0] . '_page')
            );
        }
    }

    public function enqueue_assets($hook) {
        if (strpos($hook, 'psi-papeng') === false) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('psi-papeng-admin', PSI_PAPENG_URI . 'assets/css/admin.css', array(), PSI_PAPENG_VER);
        wp_enqueue_script('psi-papeng-admin-js', PSI_PAPENG_URI . 'assets/js/admin-settings.js', array('jquery'), PSI_PAPENG_VER, true);

        wp_localize_script('psi-papeng-admin-js', 'psiPapengAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('psi_papeng_admin_nonce'),
        ));
    }

    public function ajax_save_settings() {
        check_ajax_referer('psi_papeng_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
            return;
        }

        $section = isset($_POST['section']) ? sanitize_text_field(wp_unslash($_POST['section'])) : '';
        $allowed = array('slides', 'welcome', 'leadership', 'divisions', 'social', 'contact', 'seo');

        if (!in_array($section, $allowed, true)) {
            wp_send_json_error(array('message' => 'Invalid section'));
            return;
        }

        $raw_data = isset($_POST['data']) ? wp_unslash($_POST['data']) : '';
        $data = $this->sanitize_data($section, $raw_data);

        update_option('dpw_psi_' . $section, $data);
        wp_send_json_success(array('message' => 'Pengaturan berhasil disimpan.'));
    }

    private function sanitize_data($section, $raw_data) {
        if (is_string($raw_data)) {
            $data = json_decode($raw_data, true);
        } else {
            $data = $raw_data;
        }

        if (!is_array($data)) {
            return array();
        }

        switch ($section) {
            case 'slides':
                $clean = array();
                foreach ($data as $slide) {
                    $clean[] = array(
                        'image'    => esc_url_raw($slide['image'] ?? ''),
                        'title'    => sanitize_text_field($slide['title'] ?? ''),
                        'subtitle' => sanitize_textarea_field($slide['subtitle'] ?? ''),
                        'btn_text' => sanitize_text_field($slide['btn_text'] ?? ''),
                        'btn_url'  => esc_url_raw($slide['btn_url'] ?? ''),
                    );
                }
                return $clean;

            case 'welcome':
                return array(
                    'image' => esc_url_raw($data['image'] ?? ''),
                    'title' => sanitize_text_field($data['title'] ?? ''),
                    'text'  => wp_kses_post($data['text'] ?? ''),
                    'name'  => sanitize_text_field($data['name'] ?? ''),
                    'role'  => sanitize_text_field($data['role'] ?? ''),
                );

            case 'leadership':
                $clean = array();
                foreach ($data as $leader) {
                    $clean[] = array(
                        'name'   => sanitize_text_field($leader['name'] ?? ''),
                        'role'   => sanitize_text_field($leader['role'] ?? ''),
                        'photo'  => esc_url_raw($leader['photo'] ?? ''),
                        'desc'   => sanitize_text_field($leader['desc'] ?? ''),
                        'social' => array(
                            'facebook'  => esc_url_raw($leader['social']['facebook'] ?? ''),
                            'instagram' => esc_url_raw($leader['social']['instagram'] ?? ''),
                        ),
                    );
                }
                return $clean;

            case 'divisions':
                $clean = array();
                foreach ($data as $div) {
                    $clean[] = array(
                        'title' => sanitize_text_field($div['title'] ?? ''),
                        'icon'  => sanitize_text_field($div['icon'] ?? 'bi-briefcase'),
                        'head'  => sanitize_text_field($div['head'] ?? ''),
                        'desc'  => sanitize_text_field($div['desc'] ?? ''),
                    );
                }
                return $clean;

            case 'social':
                return array(
                    'facebook'  => esc_url_raw($data['facebook'] ?? ''),
                    'instagram' => esc_url_raw($data['instagram'] ?? ''),
                    'youtube'   => esc_url_raw($data['youtube'] ?? ''),
                    'tiktok'    => esc_url_raw($data['tiktok'] ?? ''),
                );

            case 'contact':
                return array(
                    'address'  => sanitize_textarea_field($data['address'] ?? ''),
                    'email'    => sanitize_email($data['email'] ?? ''),
                    'whatsapp' => sanitize_text_field($data['whatsapp'] ?? ''),
                    'map_url'  => esc_url_raw($data['map_url'] ?? ''),
                );

            case 'seo':
                return array(
                    'enable_sitemap' => sanitize_text_field($data['enable_sitemap'] ?? 'yes'),
                    'robots_txt'     => sanitize_textarea_field($data['robots_txt'] ?? ''),
                );

            default:
                return array();
        }
    }

    private function render_wrapper($active_slug, $content_html) {
        $tabs = array(
            'slider'    => array('Slider', 'dashicons-images-alt2'),
            'welcome'   => array('Sambutan', 'dashicons-format-quote'),
            'leaders'   => array('Pimpinan', 'dashicons-groups'),
            'divisions' => array('Bidang', 'dashicons-networking'),
            'contact'   => array('Kontak', 'dashicons-share'),
            'seo'       => array('SEO', 'dashicons-search')
        );
        ?>
        <div class="wrap psi-admin-wrap">
            <div class="psi-admin-header">
                <div class="psi-admin-logo">
                    <h1>PSI Papeng <span>Premium</span></h1>
                    <p style="margin:0;color:#646970;font-size:0.8rem;">v<?php echo esc_html(PSI_PAPENG_VER); ?></p>
                </div>
                <nav class="psi-admin-tabs">
                    <?php foreach ($tabs as $slug => $tab) : ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=psi-papeng-' . $slug)); ?>" class="<?php echo $active_slug === $slug ? 'active' : ''; ?>">
                            <i class="dashicons <?php echo esc_attr($tab[1]); ?>"></i> <?php echo esc_html($tab[0]); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
            <div class="psi-admin-content">
                <div id="psi-admin-notice" style="display:none;"></div>
                <?php echo $content_html; ?>
            </div>
        </div>
        <?php
    }

    public function render_redirect_page() {
        wp_safe_redirect(admin_url('admin.php?page=psi-papeng-slider'));
        exit;
    }

    public function render_slider_page() {
        $slides = get_option('dpw_psi_sliders', array());
        ob_start();
        ?>
        <div class="psi-section-header">
            <div><h2>Kelola Slider Hero</h2><p>Tambah, edit, atau hapus slide di halaman utama.</p></div>
            <button class="button button-primary button-hero" id="addSlideBtn"><i class="dashicons dashicons-plus-alt2" style="vertical-align:middle;margin-right:4px;"></i> Tambah Slide</button>
        </div>
        <div id="slidesContainer">
            <?php if (empty($slides)) : ?>
                <div class="psi-empty-state"><i class="dashicons dashicons-images-alt2"></i><p>Belum ada slide. Klik tombol di atas untuk menambahkan.</p></div>
            <?php else : ?>
                <?php foreach ($slides as $i => $slide) : ?>
                <div class="psi-repeater-item" data-index="<?php echo esc_attr($i); ?>">
                    <div class="psi-repeater-header">
                        <span class="psi-repeater-title">Slide <?php echo esc_html($i + 1); ?></span>
                        <div class="psi-repeater-actions">
                            <button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>
                            <button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>
                        </div>
                    </div>
                    <div class="psi-repeater-body">
                        <div class="psi-field">
                            <label>Gambar Slide</label>
                            <div class="psi-image-upload">
                                <input type="hidden" class="psi-slide-image" value="<?php echo esc_attr($slide['image'] ?? ''); ?>">
                                <?php if (!empty($slide['image'])) : ?><img src="<?php echo esc_url($slide['image']); ?>" class="psi-image-preview"><?php endif; ?>
                                <button type="button" class="button psi-upload-btn">Pilih Gambar</button>
                                <button type="button" class="button psi-remove-img-btn" style="color:#a00;">Hapus</button>
                            </div>
                        </div>
                        <div class="psi-field"><label>Judul</label><input type="text" class="large-text psi-slide-title" value="<?php echo esc_attr($slide['title'] ?? ''); ?>"></div>
                        <div class="psi-field"><label>Subjudul</label><textarea class="large-text psi-slide-subtitle" rows="3"><?php echo esc_textarea($slide['subtitle'] ?? ''); ?></textarea></div>
                        <div class="psi-field-row">
                            <div class="psi-field"><label>Teks Tombol</label><input type="text" class="regular-text psi-slide-btn-text" value="<?php echo esc_attr($slide['btn_text'] ?? ''); ?>"></div>
                            <div class="psi-field"><label>URL Tombol</label><input type="url" class="regular-text psi-slide-btn-url" value="<?php echo esc_attr($slide['btn_url'] ?? ''); ?>"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="psi-save-bar"><button class="button button-primary button-hero" id="saveSlidesBtn"><i class="dashicons dashicons-saved" style="vertical-align:middle;margin-right:4px;"></i> Simpan Slider</button></div>
        <?php
        $this->render_wrapper('slider', ob_get_clean());
    }

    public function render_welcome_page() {
        $welcome = get_option('dpw_psi_welcome', array());
        ob_start();
        ?>
        <div class="psi-section-header"><div><h2>Kelola Sambutan Ketua</h2><p>Atur teks sambutan dan foto Ketua DPW.</p></div></div>
        <div class="psi-card">
            <div class="psi-field">
                <label>Foto Ketua</label>
                <div class="psi-image-upload">
                    <input type="hidden" id="welcomeImage" value="<?php echo esc_attr($welcome['image'] ?? ''); ?>">
                    <?php if (!empty($welcome['image'])) : ?><img src="<?php echo esc_url($welcome['image']); ?>" class="psi-image-preview" style="max-width:200px;"><?php endif; ?>
                    <button type="button" class="button psi-upload-btn" data-target="welcomeImage">Pilih Gambar</button>
                    <button type="button" class="button psi-remove-img-btn" data-target="welcomeImage" style="color:#a00;">Hapus</button>
                </div>
            </div>
            <div class="psi-field"><label>Judul</label><input type="text" id="welcomeTitle" class="large-text" value="<?php echo esc_attr($welcome['title'] ?? ''); ?>"></div>
            <div class="psi-field">
                <label>Isi Sambutan</label>
                <textarea id="welcomeText" class="large-text" rows="10" style="width:100%;"><?php echo esc_textarea($welcome['text'] ?? ''); ?></textarea>
                <p class="description">Mendukung tag HTML dasar seperti &lt;p&gt;, &lt;strong&gt;, &lt;br&gt;.</p>
            </div>
            <div class="psi-field-row">
                <div class="psi-field"><label>Nama Ketua</label><input type="text" id="welcomeName" class="large-text" value="<?php echo esc_attr($welcome['name'] ?? ''); ?>"></div>
                <div class="psi-field"><label>Jabatan</label><input type="text" id="welcomeRole" class="large-text" value="<?php echo esc_attr($welcome['role'] ?? ''); ?>"></div>
            </div>
        </div>
        <div class="psi-save-bar"><button class="button button-primary button-hero" id="saveWelcomeBtn"><i class="dashicons dashicons-saved" style="vertical-align:middle;margin-right:4px;"></i> Simpan Sambutan</button></div>
        <?php
        $this->render_wrapper('welcome', ob_get_clean());
    }

    public function render_leaders_page() {
        $leaders = get_option('dpw_psi_leadership', array());
        ob_start();
        ?>
        <div class="psi-section-header">
            <div><h2>Kelola Data Pimpinan</h2><p>Tambahkan data pimpinan inti DPW.</p></div>
            <button class="button button-primary button-hero" id="addLeaderBtn"><i class="dashicons dashicons-plus-alt2" style="vertical-align:middle;margin-right:4px;"></i> Tambah Pimpinan</button>
        </div>
        <div id="leadersContainer">
            <?php if (empty($leaders)) : ?>
                <div class="psi-empty-state"><i class="dashicons dashicons-groups"></i><p>Belum ada data pimpinan.</p></div>
            <?php else : ?>
                <?php foreach ($leaders as $i => $leader) : ?>
                <div class="psi-repeater-item" data-index="<?php echo esc_attr($i); ?>">
                    <div class="psi-repeater-header">
                        <span class="psi-repeater-title"><?php echo esc_html($leader['name'] ?: 'Pimpinan ' . ($i + 1)); ?></span>
                        <div class="psi-repeater-actions">
                            <button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>
                            <button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>
                        </div>
                    </div>
                    <div class="psi-repeater-body">
                        <div class="psi-field">
                            <label>Foto</label>
                            <div class="psi-image-upload">
                                <input type="hidden" class="psi-leader-photo" value="<?php echo esc_attr($leader['photo'] ?? ''); ?>">
                                <?php if (!empty($leader['photo'])) : ?><img src="<?php echo esc_url($leader['photo']); ?>" class="psi-image-preview" style="max-width:120px;"><?php endif; ?>
                                <button type="button" class="button psi-upload-btn">Pilih Gambar</button>
                                <button type="button" class="button psi-remove-img-btn" style="color:#a00;">Hapus</button>
                            </div>
                        </div>
                        <div class="psi-field-row">
                            <div class="psi-field"><label>Nama Lengkap</label><input type="text" class="large-text psi-leader-name" value="<?php echo esc_attr($leader['name'] ?? ''); ?>"></div>
                            <div class="psi-field"><label>Jabatan</label><input type="text" class="regular-text psi-leader-role" value="<?php echo esc_attr($leader['role'] ?? ''); ?>"></div>
                        </div>
                        <div class="psi-field"><label>Deskripsi Singkat</label><textarea class="large-text psi-leader-desc" rows="3"><?php echo esc_textarea($leader['desc'] ?? ''); ?></textarea></div>
                        <div class="psi-field-row">
                            <div class="psi-field"><label>Facebook URL</label><input type="url" class="large-text psi-leader-fb" value="<?php echo esc_attr($leader['social']['facebook'] ?? ''); ?>"></div>
                            <div class="psi-field"><label>Instagram URL</label><input type="url" class="large-text psi-leader-ig" value="<?php echo esc_attr($leader['social']['instagram'] ?? ''); ?>"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="psi-save-bar"><button class="button button-primary button-hero" id="saveLeadersBtn"><i class="dashicons dashicons-saved" style="vertical-align:middle;margin-right:4px;"></i> Simpan Pimpinan</button></div>
        <?php
        $this->render_wrapper('leaders', ob_get_clean());
    }

    public function render_divisions_page() {
        $divisions = get_option('dpw_psi_divisions', array());
        $icons = array('bi-bank','bi-shop','bi-cpu','bi-trophy','bi-tree','bi-heart-pulse','bi-book','bi-people','bi-briefcase','bi-gear','bi-globe','bi-shield-check');
        ob_start();
        ?>
        <div class="psi-section-header">
            <div><h2>Kelola Bidang Organisasi</h2><p>Atur 8 bidang kerja organisasi DPW.</p></div>
            <button class="button button-primary button-hero" id="addDivisionBtn"><i class="dashicons dashicons-plus-alt2" style="vertical-align:middle;margin-right:4px;"></i> Tambah Bidang</button>
        </div>
        <div id="divisionsContainer">
            <?php if (empty($divisions)) : ?>
                <div class="psi-empty-state"><i class="dashicons dashicons-networking"></i><p>Belum ada data bidang.</p></div>
            <?php else : ?>
                <?php foreach ($divisions as $i => $div) : ?>
                <div class="psi-repeater-item" data-index="<?php echo esc_attr($i); ?>">
                    <div class="psi-repeater-header">
                        <span class="psi-repeater-title"><?php echo esc_html($div['title'] ?: 'Bidang ' . ($i + 1)); ?></span>
                        <div class="psi-repeater-actions">
                            <button type="button" class="button psi-toggle-repeater"><i class="dashicons dashicons-arrow-down"></i></button>
                            <button type="button" class="button psi-remove-repeater" style="color:#a00;"><i class="dashicons dashicons-trash"></i></button>
                        </div>
                    </div>
                    <div class="psi-repeater-body">
                        <div class="psi-field-row">
                            <div class="psi-field"><label>Nama Bidang</label><input type="text" class="large-text psi-div-title" value="<?php echo esc_attr($div['title'] ?? ''); ?>"></div>
                            <div class="psi-field">
                                <label>Ikon</label>
                                <select class="psi-div-icon">
                                    <?php foreach ($icons as $icon) : ?>
                                        <option value="<?php echo esc_attr($icon); ?>" <?php selected($div['icon'] ?? '', $icon); ?>><?php echo esc_html($icon); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="psi-field"><label>Nama Ketua Bidang</label><input type="text" class="large-text psi-div-head" value="<?php echo esc_attr($div['head'] ?? ''); ?>"></div>
                        <div class="psi-field"><label>Deskripsi</label><textarea class="large-text psi-div-desc" rows="3"><?php echo esc_textarea($div['desc'] ?? ''); ?></textarea></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="psi-save-bar"><button class="button button-primary button-hero" id="saveDivisionsBtn"><i class="dashicons dashicons-saved" style="vertical-align:middle;margin-right:4px;"></i> Simpan Bidang</button></div>
        <?php
        $this->render_wrapper('divisions', ob_get_clean());
    }

    public function render_contact_page() {
        $social  = get_option('dpw_psi_social', array());
        $contact = get_option('dpw_psi_contact', array());
        ob_start();
        ?>
        <div class="psi-section-header"><div><h2>Kontak & Media Sosial</h2><p>Atur informasi kontak dan tautan media sosial.</p></div></div>
        <div class="psi-cards-grid">
            <div class="psi-card">
                <h3><i class="dashicons dashicons-share"></i> Media Sosial</h3>
                <div class="psi-field"><label>Facebook</label><input type="url" id="socialFacebook" class="large-text" value="<?php echo esc_attr($social['facebook'] ?? ''); ?>" placeholder="https://facebook.com/..."></div>
                <div class="psi-field"><label>Instagram</label><input type="url" id="socialInstagram" class="large-text" value="<?php echo esc_attr($social['instagram'] ?? ''); ?>" placeholder="https://instagram.com/..."></div>
                <div class="psi-field"><label>YouTube</label><input type="url" id="socialYoutube" class="large-text" value="<?php echo esc_attr($social['youtube'] ?? ''); ?>" placeholder="https://youtube.com/..."></div>
                <div class="psi-field"><label>TikTok</label><input type="url" id="socialTiktok" class="large-text" value="<?php echo esc_attr($social['tiktok'] ?? ''); ?>" placeholder="https://tiktok.com/..."></div>
                <button class="button button-primary" id="saveSocialBtn"><i class="dashicons dashicons-saved" style="vertical-align:middle;margin-right:4px;"></i> Simpan Sosmed</button>
            </div>
            <div class="psi-card">
                <h3><i class="dashicons dashicons-phone"></i> Informasi Kontak</h3>
                <div class="psi-field"><label>Alamat Kantor</label><textarea id="contactAddress" class="large-text" rows="3"><?php echo esc_textarea($contact['address'] ?? ''); ?></textarea></div>
                <div class="psi-field"><label>Email Resmi</label><input type="email" id="contactEmail" class="large-text" value="<?php echo esc_attr($contact['email'] ?? ''); ?>"></div>
                <div class="psi-field"><label>Nomor WhatsApp</label><input type="text" id="contactWhatsapp" class="large-text" value="<?php echo esc_attr($contact['whatsapp'] ?? ''); ?>" placeholder="+62 xxx xxxx xxxx"></div>
                <div class="psi-field"><label>Google Maps Embed URL</label><input type="url" id="contactMap" class="large-text" value="<?php echo esc_attr($contact['map_url'] ?? ''); ?>" placeholder="https://www.google.com/maps/embed?..."></div>
                <button class="button button-primary" id="saveContactBtn"><i class="dashicons dashicons-saved" style="vertical-align:middle;margin-right:4px;"></i> Simpan Kontak</button>
            </div>
        </div>
        <?php
        $this->render_wrapper('contact', ob_get_clean());
    }

    public function render_seo_page() {
        $seo = get_option('dpw_psi_seo', array());
        ob_start();
        ?>
        <div class="psi-section-header"><div><h2>Pengaturan SEO</h2><p>Konfigurasi Sitemap dan robots.txt.</p></div></div>
        <div class="psi-card">
            <div class="psi-field">
                <label><input type="checkbox" id="seoSitemap" value="yes" <?php checked($seo['enable_sitemap'] ?? 'yes', 'yes'); ?>> Aktifkan XML Sitemap</label>
                <p class="description">Sitemap akan tersedia di <code><?php echo esc_url(home_url('/sitemap.xml')); ?></code></p>
            </div>
            <div class="psi-field">
                <label>Custom robots.txt</label>
                <textarea id="seoRobots" class="large-text code" rows="10"><?php echo esc_textarea($seo['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /wp-admin/\nDisallow: /wp-includes/"); ?></textarea>
            </div>
            <button class="button button-primary button-hero" id="saveSeoBtn"><i class="dashicons dashicons-saved" style="vertical-align:middle;margin-right:4px;"></i> Simpan SEO</button>
        </div>
        <?php
        $this->render_wrapper('seo', ob_get_clean());
    }
}
