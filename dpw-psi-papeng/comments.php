<?php
/**
 * Comments Template
 * @package DPW_PSIPapeng
 */
defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) return;

if ( have_comments() ) :
?>
<div class="dpw-comments" id="comments">
    <h4 class="fw-bold mb-4">
        <i class="bi bi-chat-dots text-danger me-2"></i>
        <?php printf( esc_html( _nx( '%1$s Komentar', '%1$s Komentar', get_comments_number(), 'comments title', 'dpw-psi-papeng' ) ), number_format_i18n( get_comments_number() ) ); ?>
    </h4>
    <ol class="comment-list list-unstyled">
        <?php
        wp_list_comments( [
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 50,
            'callback'    => 'dpw_psi_comment_callback',
        ] );
        ?>
    </ol>
    <?php
    the_comments_navigation( [
        'prev_text' => '<i class="bi bi-chevron-left me-1"></i>' . __( 'Komentar Lama', 'dpw-psi-papeng' ),
        'next_text' => __( 'Komentar Baru', 'dpw-psi-papeng' ) . '<i class="bi bi-chevron-right ms-1"></i>',
    ] );
    ?>
</div>
<?php endif; ?>

<?php
if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
?>
<p class="no-comments text-muted"><?php esc_html_e( 'Komentar ditutup.', 'dpw-psi-papeng' ); ?></p>
<?php endif; ?>

<?php
comment_form( [
    'class_form'         => 'dpw-comment-form',
    'title_reply'        => '<span class="fw-bold"><i class="bi bi-pencil-square text-danger me-2"></i>' . __( 'Tinggalkan Komentar', 'dpw-psi-papeng' ) . '</span>',
    'title_reply_before' => '<h4 id="reply-title" class="mb-4">',
    'title_reply_after'  => '</h4>',
    'class_submit'       => 'btn btn-danger fw-bold px-4',
    'comment_field'      => '<div class="mb-3"><label for="comment" class="form-label fw-bold small">' . __( 'Komentar', 'dpw-psi-papeng' ) . ' *</label><textarea id="comment" name="comment" class="form-control" rows="5" required></textarea></div>',
    'fields'             => [
        'author' => '<div class="mb-3"><label for="author" class="form-label fw-bold small">' . __( 'Nama', 'dpw-psi-papeng' ) . ' *</label><input type="text" id="author" name="author" class="form-control" required></div>',
        'email'  => '<div class="mb-3"><label for="email" class="form-label fw-bold small">' . __( 'Email', 'dpw-psi-papeng' ) . ' *</label><input type="email" id="email" name="email" class="form-control" required></div>',
        'url'    => '<div class="mb-3"><label for="url" class="form-label fw-bold small">' . __( 'Website', 'dpw-psi-papeng' ) . '</label><input type="url" id="url" name="url" class="form-control"></div>',
    ],
] );
?>

<?php
function dpw_psi_comment_callback( $comment, $args, $depth ): void {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'dpw-comment mb-4 p-3 bg-light rounded-3', $comment ); ?>>
        <div class="d-flex gap-3">
            <div class="flex-shrink-0">
                <?php echo get_avatar( $comment, $args['avatar_size'], '', '', [ 'class' => 'rounded-circle' ] ); ?>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="small"><?php echo esc_html( get_comment_author( $comment ) ); ?></strong>
                    <time class="text-muted small" datetime="<?php comment_time( 'c' ); ?>">
                        <?php printf( esc_html__( '%1$s pada %2$s', 'dpw-psi-papeng' ), get_comment_date( '', $comment ), get_comment_time() ); ?>
                    </time>
                </div>
                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="small text-warning mb-1"><em><?php esc_html_e( 'Komentar menunggu moderasi.', 'dpw-psi-papeng' ); ?></em></p>
                <?php endif; ?>
                <div class="comment-content small lh-lg">
                    <?php comment_text(); ?>
                </div>
                <div class="comment-actions mt-2">
                    <?php
                    comment_reply_link( array_merge( $args, [
                        'add_below' => 'comment',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'before'    => '<span class="reply me-3">',
                        'after'     => '</span>',
                    ] ) );
                    edit_comment_link( __( 'Edit', 'dpw-psi-papeng' ), '<span class="edit-link">', '</span>' );
                    ?>
                </div>
            </div>
        </div>
    </<?php echo $tag; ?>>
    <?php
}
