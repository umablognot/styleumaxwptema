<?php
/**
 * Comments display + form.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="sumx-comments">

	<?php if ( have_comments() ) : ?>
		<h2>
			<i class="fa-regular fa-comments" aria-hidden="true"></i>
			<?php
			printf(
				/* translators: %d: comment count. */
				esc_html( _n( '%d yorum', '%d yorum', (int) get_comments_number(), 'styleumax' ) ),
				absint( (int) get_comments_number() )
			);
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="sumx-pagination mb-4" aria-label="<?php esc_attr_e( 'Yorum sayfaları', 'styleumax' ); ?>">
				<div class="nav-links">
					<?php previous_comments_link( '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i> ' . esc_html__( 'Eski yorumlar', 'styleumax' ) ); ?>
					<?php next_comments_link( esc_html__( 'Yeni yorumlar', 'styleumax' ) . ' <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>' ); ?>
				</div>
			</nav>
		<?php endif; ?>

		<ol class="sumx-comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'callback'    => 'styleumax_comment_callback',
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="sumx-pagination" aria-label="<?php esc_attr_e( 'Yorum sayfaları', 'styleumax' ); ?>">
				<div class="nav-links">
					<?php previous_comments_link( '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i> ' . esc_html__( 'Eski yorumlar', 'styleumax' ) ); ?>
					<?php next_comments_link( esc_html__( 'Yeni yorumlar', 'styleumax' ) . ' <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>' ); ?>
				</div>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() ) : ?>
			<p class="alert alert-info"><?php esc_html_e( 'Bu yazıya yorum yapma kapalıdır.', 'styleumax' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php comment_form( styleumax_comment_form_args() ); ?>
</div>
