<?php
/**
 * Membership CTA Template Part
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

 $cta_url  = dpw_psi_get_option( 'membership_url', 'https://psi.id/menjadi-anggota' );
 $cta_text = dpw_psi_get_option( 'membership_text', 'Daftar Anggota' );
?>

<section class="dpw-membership-cta" id="dpwMembership">
    <div class="dpw-cta-bg"></div>
    <div class="dpw-cta-overlay"></div>
    <div class="container position-relative">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8 dpw-animate-on-scroll">
                <h2 class="text-white fw-bold mb-3"><?php esc_html_e( 'Bergabunglah Bersama Kami', 'dpw-psi-papeng' ); ?></h2>
                <p class="text-white-50 mb-4 lh-lg"><?php esc_html_e( 'Jadilah bagian dari perubahan nyata untuk Papua Pegunungan. Bersama PSI, kita wujudkan keadilan dan solidaritas.', 'dpw-psi-papeng' ); ?></p>
                <a href="<?php echo esc_url( $cta_url ); ?>" target="_blank" rel="noopener" class="btn btn-danger btn-lg fw-bold px-5 py-3 dpw-cta-btn">
                    <i class="bi bi-person-plus me-2"></i><?php echo esc_html( $cta_text ); ?>
                </a>
            </div>
        </div>
    </div>
</section>
