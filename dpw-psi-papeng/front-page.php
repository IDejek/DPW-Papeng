<?php
/**
 * Homepage Template
 * @package DPW_PSI_Papeng
 */
defined('ABSPATH') || exit;
get_header();

 $social_links = get_option('dpw_psi_social', array());
 $slides = get_option('dpw_psi_slides', array());
if (empty($slides)) {
    $slides = array(array('image' => DPW_PSI_URI . '/assets/images/hero-default.jpg', 'title' => 'DPW PSI Papua Pegunungan', 'subtitle' => 'Memperjuangkan Keadilan dan Solidaritas untuk Tanah Papua', 'btn_text' => 'Tentang Kami', 'btn_url' => home_url('/profil/'))));
}
 $welcome = get_option('dpw_psi_welcome', array());
 $w_img = !empty($welcome['image']) ? $welcome['image'] : DPW_PSI_URI . '/assets/images/ketua-default.jpg';
 $w_title = !empty($welcome['title']) ? $welcome['title'] : 'Sambutan Ketua DPW';
 $w_text = !empty($welcome['text']) ? $welcome['text'] : 'Selamat datang di website resmi Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan.';
 $w_name = !empty($welcome['name']) ? $welcome['name'] : 'Yotam Wonda, S.H., M.Si';
 $w_role = !empty($welcome['role']) ? $welcome['role'] : 'Ketua DPW PSI Papua Pegunungan';

 $leaders = get_option('dpw_psi_leadership', array());
if (empty($leaders)) {
    $leaders = array(
        array('name' => 'Yotam Wonda, S.H., M.Si', 'role' => 'Ketua', 'photo' => DPW_PSI_URI . '/assets/images/leader-ketua.jpg', 'desc' => 'Memimpin DPW PSI Papua Pegunungan.', 'social' => array()),
        array('name' => 'Yotias Kobak, S.Sos', 'role' => 'Sekretaris', 'photo' => DPW_PSI_URI . '/assets/images/leader-sekretaris.jpg', 'desc' => 'Mengelola administrasi organisasi.', 'social' => array()),
        array('name' => 'Almina Wakur, S.IP', 'role' => 'Bendahara', 'photo' => DPW_PSI_URI . '/assets/images/leader-bendahara.jpg', 'desc' => 'Mengelola keuangan organisasi.', 'social' => array())
    );
}
 $divisions = get_option('dpw_psi_divisions', array());
if (empty($divisions)) {
    $divisions = array(
        array('title' => 'Hubungan Antar Lembaga Hukum dan HAM', 'icon' => 'bi-bank', 'head' => '', 'desc' => 'Membangun relasi dengan lembaga hukum.'),
        array('title' => 'UMKM, Koperasi dan Kepariwisataan', 'icon' => 'bi-shop', 'head' => '', 'desc' => 'Mendorong ekonomi rakyat.'),
        array('title' => 'Media, Teknologi dan Informatika', 'icon' => 'bi-cpu', 'head' => '', 'desc' => 'Mengelola komunikasi publik.'),
        array('title' => 'Pemuda, Olahraga, Seni & Budaya', 'icon' => 'bi-trophy', 'head' => '', 'desc' => 'Mengembangkan potensi pemuda.'),
        array('title' => 'Buruh, Petani, Nelayan dan SDA', 'icon' => 'bi-tree', 'head' => '', 'desc' => 'Memperjuangkan hak pekerja.'),
        array('title' => 'Kesehatan dan Lingkungan Hidup', 'icon' => 'bi-heart-pulse', 'head' => '', 'desc' => 'Meningkatkan akses kesehatan.'),
        array('title' => 'Keagamaan', 'icon' => 'bi-book', 'head' => '', 'desc' => 'Membanun harmonisasi antar umat.'),
        array('title' => 'Perempuan dan Anak', 'icon' => 'bi-people', 'head' => '', 'desc' => 'Melindungi perempuan dan anak.')
    );
}
?>

<!-- Hero Slider -->
<section class="psi-hero" id="heroSlider">
    <div class="hero-slides">
        <?php $si = 0; foreach ($slides as $slide) : ?>
        <div class="hero-slide <?php echo $si === 0 ? 'active' : ''; ?>">
            <div class="hero-slide-bg" style="background-image: url('<?php echo esc_url($slide['image']); ?>');"></div>
            <div class="hero-slide-overlay"></div>
            <div class="hero-slide-content"><div class="container"><div class="hero-content-inner">
                <div class="hero-badge"><i class="bi bi-star-fill"></i> Partai Solidaritas Indonesia</div>
                <h1><?php echo wp_kses_post(!empty($slide['title']) ? $slide['title'] : get_bloginfo('name')); ?></h1>
                <p><?php echo esc_html(!empty($slide['subtitle']) ? $slide['subtitle'] : get_bloginfo('description')); ?></p>
                <div class="hero-buttons">
                    <?php if (!empty($slide['btn_text']) && !empty($slide['btn_url'])) : ?>
                    <a href="<?php echo esc_url($slide['btn_url']); ?>" class="btn btn-primary btn-lg"><?php echo esc_html($slide['btn_text']); ?> <i class="bi bi-arrow-right"></i></a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(get_post_type_archive_link('psi-news')); ?>" class="btn btn-outline btn-lg">Berita Terkini</a>
                </div>
            </div></div></div>
        </div>
        <?php $si++; endforeach; ?>
    </div>
    <div class="hero-nav">
        <button class="hero-arrow" id="heroPrev" aria-label="Prev"><i class="bi bi-chevron-left"></i></button>
        <div class="hero-dots" id="heroDots">
            <?php for ($i = 0; $i < count($slides); $i++) : ?>
            <button class="hero-dot <?php echo $i === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr($i); ?>"></button>
            <?php endfor; ?>
        </div>
        <button class="hero-arrow" id="heroNext" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
    </div>
</section>

<!-- Welcome -->
<section class="psi-welcome section-padding">
    <div class="container">
        <div class="welcome-grid">
            <div class="welcome-image psi-animate-left">
                <div class="welcome-image-main"><img src="<?php echo esc_url($w_img); ?>" alt="<?php echo esc_attr($w_name); ?>" loading="lazy"></div>
                <div class="welcome-image-badge"><div class="badge-number">8</div><div class="badge-text">Kabupaten</div></div>
            </div>
            <div class="welcome-content psi-animate-right">
                <span class="welcome-label">Sambutan Ketua</span>
                <h2><?php echo esc_html($w_title); ?></h2>
                <div class="welcome-quote">"<?php echo esc_html(wp_trim_words($w_text, 20, '...')); ?>"</div>
                <div class="welcome-text"><?php echo wp_kses_post(wpautop($w_text)); ?></div>
                <div class="welcome-signature">
                    <img src="<?php echo esc_url($w_img); ?>" alt="<?php echo esc_attr($w_name); ?>">
                    <div class="welcome-signature-info"><div class="sig-name"><?php echo esc_html($w_name); ?></div><div class="sig-role"><?php echo esc_html($w_role); ?></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership -->
<section class="psi-leadership section-padding section-gray">
    <div class="container">
        <div class="section-header psi-animate">
            <span class="section-label">Pimpinan</span>
            <h2>Pengurus Inti DPW PSI</h2>
        </div>
        <div class="leadership-grid">
            <?php foreach ($leaders as $leader) : ?>
            <div class="leader-card psi-animate">
                <div class="leader-card-image">
                    <img src="<?php echo esc_url(!empty($leader['photo']) ? $leader['photo'] : DPW_PSI_URI . '/assets/images/placeholder-user.png'); ?>" alt="<?php echo esc_attr($leader['name']); ?>" loading="lazy">
                    <div class="leader-card-overlay"></div>
                </div>
                <div class="leader-card-body">
                    <h3><?php echo esc_html($leader['name']); ?></h3>
                    <div class="leader-role"><?php echo esc_html($leader['role']); ?></div>
                    <p class="leader-desc"><?php echo esc_html($leader['desc']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Divisions -->
<section class="psi-divisions section-padding">
    <div class="container">
        <div class="section-header psi-animate">
            <span class="section-label">Program Kerja</span>
            <h2>Bidang-Bidang Organisasi</h2>
        </div>
        <div class="divisions-grid">
            <?php foreach ($divisions as $div) : ?>
            <div class="division-card psi-animate">
                <div class="division-card-icon"><i class="bi <?php echo esc_attr(!empty($div['icon']) ? $div['icon'] : 'bi-briefcase'); ?>"></i></div>
                <h4><?php echo esc_html($div['title']); ?></h4>
                <p><?php echo esc_html($div['desc']); ?></p>
                <?php if (!empty($div['head'])) : ?><div class="division-head"><div class="division-head-info"><strong><?php echo esc_html($div['head']); ?></strong><span>Ketua Bidang</span></div></div><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- News -->
<?php $featured_news = new WP_Query(array('post_type' => 'psi-news', 'posts_per_page' => 4, 'post_status' => 'publish')); ?>
<?php if ($featured_news->have_posts()) : ?>
<section class="psi-news section-padding section-gray">
    <div class="container">
        <div class="section-header psi-animate"><span class="section-label">Terbaru</span><h2>Berita & Informasi</h2></div>
        <div class="news-grid">
            <?php $is_first = true; while ($featured_news->have_posts()) : $featured_news->the_post();
            $cats = get_the_terms(get_the_ID(), 'news-category');
            $cat_name = $cats ? $cats[0]->name : 'Umum';
            ?>
            <?php if ($is_first) : $is_first = false; ?>
            <div class="news-featured psi-animate-left">
                <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'dpw-hero') ?: DPW_PSI_URI . '/assets/images/placeholder-news.jpg'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                <div class="news-featured-overlay"></div>
                <div class="news-featured-content">
                    <span class="news-category"><?php echo esc_html($cat_name); ?></span>
                    <h3><a href="<?php the_permalink(); ?>" style="color:#fff;"><?php the_title(); ?></a></h3>
                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 25)); ?></p>
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="news-list psi-animate-right">
            <?php else : ?>
                <div class="news-item">
                    <div class="news-item-image"><img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'dpw-news-thumb') ?: DPW_PSI_URI . '/assets/images/placeholder-news.jpg'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy"></div>
                    <div class="news-item-content"><div class="news-category"><?php echo esc_html($cat_name); ?></div><h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4><div class="news-meta"><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></div></div>
                </div>
            <?php endif; endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- DPD -->
<?php $dpd_list = new WP_Query(array('post_type' => 'psi-dpd', 'posts_per_page' => 8, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC')); ?>
<?php if ($dpd_list->have_posts()) : ?>
<section class="psi-dpd section-padding section-dark">
    <div class="container">
        <div class="section-header psi-animate"><span class="section-label">Struktur</span><h2>DPD PSI Se-Papua Pegunungan</h2></div>
        <div class="dpd-grid">
            <?php while ($dpd_list->have_posts()) : $dpd_list->the_post();
            $ketua = get_post_meta(get_the_ID(), '_psi_dpd_ketua', true);
            $photo = get_post_meta(get_the_ID(), '_psi_dpd_photo', true);
            ?>
            <div class="dpd-card psi-animate">
                <div class="dpd-card-photo"><img src="<?php echo esc_url($photo ?: DPW_PSI_URI . '/assets/images/placeholder-user.png'); ?>" alt="<?php echo esc_attr($ketua ?: get_the_title()); ?>" loading="lazy"></div>
                <div class="dpd-card-body">
                    <h4><?php echo esc_html($ketua ?: '-'); ?></h4>
                    <div class="dpd-kabupaten"><?php the_title(); ?></div>
                    <a href="<?php the_permalink(); ?>" class="dpd-card-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="psi-cta">
    <div class="container">
        <div class="cta-content psi-animate">
            <h2>Bergabunglah Bersama Kami</h2>
            <p>Jadilah bagian dari perubahan untuk Papua Pegunungan yang lebih adil dan sejahtera.</p>
            <div class="cta-buttons">
                <a href="https://psi.id/menjadi-anggota" class="btn btn-primary btn-lg" target="_blank" rel="noopener"><i class="bi bi-person-plus-fill"></i> Daftar Anggota PSI</a>
                <a href="<?php echo esc_url(home_url('/kontak/')); ?>" class="btn btn-outline btn-lg"><i class="bi bi-chat-dots"></i> Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
