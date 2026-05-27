<?php
/**
 * Search Form Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;
?>

<form role="search" method="get" class="dpw-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="input-group">
        <input type="search" class="form-control" placeholder="<?php esc_attr_e( 'Cari...', 'dpw-psi-papeng' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" aria-label="<?php esc_attr_e( 'Pencarian', 'dpw-psi-papeng' ); ?>" required>
        <button type="submit" class="btn btn-danger" aria-label="<?php esc_attr_e( 'Cari', 'dpw-psi-papeng' ); ?>">
            <i class="bi bi-search"></i>
        </button>
    </div>
</form>
