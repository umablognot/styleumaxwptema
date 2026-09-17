<?php
/**
 * Standard grid card (used by 2/3/4 column archive layouts).
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'sumx-card' ); ?>>
	<div class="sumx-card-media">
		<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php styleumax_thumbnail( 'styleumax-grid' ); ?>
		</a>
		<?php styleumax_category_chip(); ?>
	</div>
	<div class="sumx-card-body">
		<h3 class="sumx-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<?php the_excerpt(); ?>
		<?php styleumax_card_meta(); ?>
	</div>
</article>
