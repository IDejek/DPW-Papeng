<?php
/**
 * Sidebar Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>
<aside class="article-sidebar">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
