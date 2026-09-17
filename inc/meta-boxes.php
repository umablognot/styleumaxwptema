<?php
/**
 * Post meta boxes: "subtitle" field shown under single titles.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/**
 * Register the subtitle meta box.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_add_subtitle_meta_box(): void {
	add_meta_box(
		'styleumax_subtitle',
		__( 'Alt başlık', 'styleumax' ),
		'styleumax_subtitle_meta_box_html',
		'post',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'styleumax_add_subtitle_meta_box' );

/**
 * Meta box markup.
 *
 * @param WP_Post $post Current post.
 * @return void
 * @since 1.0.0
 */
function styleumax_subtitle_meta_box_html( WP_Post $post ): void {
	wp_nonce_field( 'styleumax_save_subtitle', 'styleumax_subtitle_nonce' );

	$value = (string) get_post_meta( (int) $post->ID, 'styleumax_subtitle', true );
	?>
	<p>
		<label for="styleumax_subtitle_field" class="screen-reader-text"><?php esc_html_e( 'Alt başlık', 'styleumax' ); ?></label>
		<textarea id="styleumax_subtitle_field" name="styleumax_subtitle" rows="2" class="large-text" placeholder="<?php echo esc_attr__( 'Tek satırda yazının özeti veya açıklaması (isteğe bağlı)', 'styleumax' ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
	</p>
	<p class="description">
		<?php esc_html_e( 'Bu alan yazı sayfasında başlığın hemen altında vurgulanarak gösterilir.', 'styleumax' ); ?>
	</p>
	<?php
}

/**
 * Save the subtitle value.
 *
 * @param int $post_id Post ID.
 * @return void
 * @since 1.0.0
 */
function styleumax_save_subtitle( int $post_id ): void {
	if ( ! isset( $_POST['styleumax_subtitle_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( (string) $_POST['styleumax_subtitle_nonce'] ) ), 'styleumax_save_subtitle' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['styleumax_subtitle'] ) ) {
		delete_post_meta( $post_id, 'styleumax_subtitle' );
		return;
	}

	$subtitle = sanitize_textarea_field( wp_unslash( (string) $_POST['styleumax_subtitle'] ) );

	if ( '' === trim( $subtitle ) ) {
		delete_post_meta( $post_id, 'styleumax_subtitle' );
		return;
	}

	update_post_meta( $post_id, 'styleumax_subtitle', $subtitle );
}
add_action( 'save_post', 'styleumax_save_subtitle' );
