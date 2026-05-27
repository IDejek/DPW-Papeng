<?php
/**
 * Front Page Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<!-- ════════ HERO SLIDER ════════ -->
<?php get_template_part( 'template-parts/hero-slider' ); ?>

<!-- ════════ WELCOME SECTION ════════ -->
<?php get_template_part( 'template-parts/welcome-section' ); ?>

<!-- ════════ LEADERSHIP SECTION ════════ -->
<?php get_template_part( 'template-parts/leadership-section' ); ?>

<!-- ════════ DIVISIONS SECTION ════════ -->
<?php get_template_part( 'template-parts/divisions-section' ); ?>

<!-- ════════ NEWS HIGHLIGHT ════════ -->
<?php get_template_part( 'template-parts/news-section' ); ?>

<!-- ════════ VIDEO SECTION ════════ -->
<?php get_template_part( 'template-parts/video-section' ); ?>

<!-- ════════ DPD SECTION ════════ -->
<?php get_template_part( 'template-parts/dpd-section' ); ?>

<!-- ════════ MEMBERSHIP CTA ════════ -->
<?php get_template_part( 'template-parts/membership-cta' ); ?>

<?php get_footer(); ?>
