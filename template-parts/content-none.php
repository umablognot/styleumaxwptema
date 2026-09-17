<?php
/**
 * "No posts found" message.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<section class="sumx-page-lead text-center">
	<p class="mb-3"><i class="fa-regular fa-folder-open fa-2x text-primary" aria-hidden="true"></i></p>
	<h2 class="h4"><?php esc_html_e( 'Henüz içerik yok', 'styleumax' ); ?></h2>
	<p class="sumx-lead-desc"><?php esc_html_e( 'Bu bölümde henüz yayınlanmış bir yazı bulunmuyor. Arama kutusunu kullanabilir ya da başka bir kategoriye göz atabilirsiniz.', 'styleumax' ); ?></p>

	<div class="mx-auto mt-3" style="max-width: 400px;">
		<?php get_search_form(); ?>
	</div>
</section>
