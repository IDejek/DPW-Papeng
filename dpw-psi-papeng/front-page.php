<?php
/**
 * Homepage Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Get slider data from theme options
 $slides = get_option( 'dpw_psi_slides', array() );
if ( empty( $slides ) ) {
    $slides = array(
        array(
            'image'   => DPW_PSI_URI . '/assets/images/hero-default.jpg',
            'title'   => 'DPW PSI Papua Pegunungan',
            'subtitle' => 'Memperjuangkan Keadilan dan Solidaritas untuk Tanah Papua',
            'btn_text' => 'Tentang Kami',
            'btn_url'  => home_url( '/profil/visi-misi/' ),
        ),
    );
}

// Get welcome/sambutan data
 $welcome = get_option( 'dpw_psi_welcome', array() );
 $welcome_image = ! empty( $welcome['image'] ) ? $welcome['image'] : DPW_PSI_URI . '/assets/images/ketua-default.jpg';
 $welcome_title = ! empty( $welcome['title'] ) ? $welcome['title'] : 'Sambutan Ketua DPW';
 $welcome_text  = ! empty( $welcome['text'] ) ? $welcome['text'] : 'Selamat datang di website resmi Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Papua Pegunungan. Kami berkomitmen untuk memperjuangkan keadilan, solidaritas, dan kesejahteraan bagi seluruh masyarakat Papua Pegunungan.';
 $welcome_name  = ! empty( $welcome['name'] ) ? $welcome['name'] : 'Yotam Wonda, S.H., M.Si';
 $welcome_role  = ! empty( $welcome['role'] ) ? $welcome['role'] : 'Ketua DPW PSI Papua Pegunungan';

// Get leadership data
 $leaders = get_option( 'dpw_psi_leadership', array() );
if ( empty( $leaders ) ) {
    $leaders = array(
        array(
            'name'    => 'Yotam Wonda, S.H., M.Si',
            'role'    => 'Ketua',
            'photo'   => DPW_PSI_URI . '/assets/images/leader-ketua.jpg',
            'desc'    => 'Memimpin DPW PSI Papua Pegunungan dengan visi membangun Papua yang berkeadilan.',
            'social'  => array(),
        ),
        array(
            'name'    => 'Yotias Kobak, S.Sos',
            'role'    => 'Sekretaris',
            'photo'   => DPW_PSI_URI . '/assets/images/leader-sekretaris.jpg',
            'desc'    => 'Mengelola administrasi dan koordinasi organisasi DPW PSI.',
            'social'  => array(),
        ),
        array(
            'name'    => 'Almina Wakur, S.IP',
            'role'    => 'Bendahara',
            'photo'   => DPW_PSI_URI . '/assets/images/leader-bendahara.jpg',
            'desc'    => 'Mengelola keuangan dan sumber daya organisasi.',
            'social'  => array(),
        ),
    );
}

// Get divisions data
 $divisions = get_option( 'dpw_psi_divisions', array() );
if ( empty( $divisions ) ) {
    $divisions = array(
        array( 'title' => 'Hubungan Antar Lembaga Hukum dan HAM', 'icon' => 'bi-bank', 'head' => '', 'desc' => 'Membangun relasi dengan lembaga hukum dan memperjuangkan hak asasi manusia.' ),
        array( 'title' => 'UMKM, Koperasi dan Kepariwisataan', 'icon' => 'bi-shop', 'head' => '', 'desc' => 'Mendorong pertumbuhan ekonomi rakyat melalui UMKM dan pariwisata.' ),
        array( 'title' => 'Media, Teknologi dan Informatika', 'icon' => 'bi-cpu', 'head' => '', 'desc' => 'Mengelola komunikasi publik dan pemanfaatan teknologi informasi.' ),
        array( 'title' => 'Pemuda, Olahraga, Seni & Budaya', 'icon' => 'bi-trophy', 'head' => '', 'desc' => 'Mengembangkan potensi pemuda dan melestarikan budaya Papua.' ),
        array( 'title' => 'Buruh, Petani, Nelayan dan SDA', 'icon' => 'bi-tree', 'head' => '', 'desc' => 'Memperjuangkan hak-hak pekerja dan pengelolaan sumber daya alam.' ),
        array( 'title' => 'Kesehatan dan Lingkungan Hidup', 'icon' => 'bi-heart-pulse', 'head' => '', 'desc' => 'Meningkatkan akses kesehatan dan kelestarian lingkungan.' ),
        array( 'title' => 'Keagamaan', 'icon' => 'bi-book', 'head' => '', 'desc' => 'Membangun harmonisasi antar umat beragama di Papua Pegunungan.' ),
        array( 'title' => 'Perempuan dan Anak', 'icon' => 'bi-people', 'head' => '', 'desc' => 'Melindungi dan memberdayakan perempuan serta anak-anak.' ),
    );
}

// Get featured news
 $featured_news = new WP_Query( array(
    'post_type'      => 'psi-news',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
) );

// Get videos
 $videos = new WP_Query( array(
    'post_type'      => 'psi-video',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
) );

// Get DPD data
 $dpd_list = new WP_Query( array(
    'post_type'      => 'psi-dpd',
    'posts_per_page' => 8,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
) );
?>

<!-- Hero Slider -->
<section class="psi-hero" id="heroSlider">
    <div class="hero-slides">
        <?php $slide_idx = 0; ?>
        <?php foreach ( $slides as $slide ) : ?>
            <div class="hero-slide <?php echo 0 === $slide_idx ? 'active' : ''; ?>" data-index="<?php echo esc_attr( $slide_idx ); ?>">
                <div class="hero-slide-bg" style="background-image: url('<?php echo esc_url( $slide['image'] ); ?>');"></div>
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-content">
                    <div class="container">
                        <div class="hero-content-inner">
                            <div class="hero-badge">
                                <i class="bi bi-star-fill"></i>
                                Partai Solidaritas Indonesia
                            </div>
                            <h1><?php echo wp_kses_post( ! empty( $slide['title'] ) ? $slide['title'] : get_bloginfo( 'name' ) ); ?></h1>
                            <p><?php echo esc_html( ! empty( $slide['subtitle'] ) ? $slide['subtitle'] : get_bloginfo( 'description' ) ); ?></p>
                            <div class="hero-buttons">
                                <?php if ( ! empty( $slide['btn_text'] ) && ! empty( $slide['btn_url'] ) ) : ?>
                                    <a href="<?php echo esc_url( $slide['
