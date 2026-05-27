<?php
/**
 * Divisions Section Template Part
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $divisions = get_posts( [
    'post_type'      => 'division',
    'posts_per_page' => 8,
    'orderby'        => 'title',
    'order'          => 'ASC',
] );
?>

<section class="dpw-section dpw-divisions-section bg-light" id="dpwDivisions">
    <div class="container">
        <div class="text-center mb-5 dpw-animate-on-scroll">
            <span class="dpw-section-badge"><?php esc_html_e( 'Organisasi', 'dpw-psi-papeng' ); ?></span>
            <h2 class="dpw-section-title"><?php esc_html_e( 'Bidang Organisasi', 'dpw-psi-papeng' ); ?></h2>
            <p class="dpw-section-desc mx-auto"><?php esc_html_e( 'Struktur bidang kerja yang komprehensif untuk melayani seluruh aspek kehidupan masyarakat.', 'dpw-psi-papeng' ); ?></p>
        </div>

        <?php if ( ! empty( $divisions ) ) : ?>
        <div class="row g-4">
            <?php foreach ( $divisions as $i => $div ) :
                $head_name  = dpw_psi_get_meta( $div->ID, 'head_name', '' );
                $head_photo = dpw_psi_get_meta( $div->ID, 'head_photo', '' );
                $icon_class = dpw_psi_get_division_icon( $i );
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 dpw-animate-on-scroll" data-delay="<?php echo ($i % 4) * 100; ?>">
                <div class="dpw-division-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="dpw-division-icon mb-3">
                        <i class="bi <?php echo esc_attr( $icon_class ); ?>"></i>
                    </div>
                    <h6 class="fw-bold mb-2"><?php echo esc_html( get_the_title( $div ) ); ?></h6>
                    <?php if ( $head_name ) : ?>
                    <p class="text-muted small mb-0">
                        <strong><?php esc_html_e( 'Ketua:', 'dpw-psi-papeng' ); ?></strong> <?php echo esc_html( $head_name ); ?>
                    </p>
                    <?php endif; ?>
                    <?php if ( has_excerpt( $div ) ) : ?>
                    <p class="text-muted small mt-2 mb-0"><?php echo esc_html( get_the_excerpt( $div ) ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-diagram-3 fs-1 d-block mb-3"></i>
            <p><?php esc_html_e( 'Data bidang organisasi belum tersedia.', 'dpw-psi-papeng' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
function dpw_psi_get_division_icon( $index ): string {
    $icons = [
        'bi-balance-scale', 'bi-shop', 'bi-laptop',
        'bi-trophy', 'bi-truck', 'bi-heart-pulse',
        'bi-cross', 'bi-gender-ambiguous',
    ];
    return $icons[ $index % count( $icons ) ] ?? 'bi-briefcase';
}
