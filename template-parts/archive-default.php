<?php
/**
 * Default archive layout: horizontal rows + pagination.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<div class="d-flex flex-column gap-3">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'sumx-row-card' ); ?>>
				<div class="sumx-row-card-media">
					<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
						<?php styleumax_thumbnail( 'styleumax-grid' ); ?>
					</a>
					<?php styleumax_category_chip(); ?>
				</div>
				<div class="sumx-row-card-body">
					<h2 class="sumx-card-title h5 mb-0">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<?php the_excerpt(); ?>
					<?php styleumax_card_meta(); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</div>

<?php styleumax_pagination(); ?>
