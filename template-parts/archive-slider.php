<?php
/**
 * Archive layout: slider. Featured carousel (Customizer count) +
 * remaining posts as rows, then pagination.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

$styleumax_slide_count = max( 2, min( 10, (int) styleumax_get_option( 'archive_slider_count', 6 ) ) );
$styleumax_index       = 0;
?>
<div class="sumx-slider-wrapper mb-4">
	<div id="sumx-archive-slider" class="carousel slide sumx-slider" data-bs-ride="carousel" data-bs-interval="6000">
		<div class="carousel-indicators">
			<?php $styleumax_total = min( $styleumax_slide_count, (int) $GLOBALS['wp_query']->post_count ); ?>
			<?php for ( $styleumax_i = 0; $styleumax_i < $styleumax_total; $styleumax_i++ ) : ?>
				<button type="button" data-bs-target="#sumx-archive-slider" data-bs-slide-to="<?php echo esc_attr( (string) $styleumax_i ); ?>"
					<?php echo 0 === $styleumax_i ? 'class="active" aria-current="true"' : ''; ?>
					aria-label="<?php echo esc_attr(
						/* translators: %d: slide number. */
						sprintf( __( 'Slayt %d', 'styleumax' ), $styleumax_i + 1 )
					); ?>"></button>
			<?php endfor; ?>
		</div>

		<div class="carousel-inner">
			<?php while ( have_posts() && $styleumax_index < $styleumax_slide_count ) : ?>
				<?php
				the_post();
				$styleumax_index++;
				?>
				<div class="carousel-item<?php echo 1 === $styleumax_index ? ' active' : ''; ?>">
					<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
						<?php styleumax_thumbnail( 'styleumax-slider' ); ?>
					</a>
					<div class="carousel-caption">
						<div class="d-flex align-items-center gap-2">
							<?php styleumax_category_chip(); ?>
						</div>
						<h2 class="h3">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<p class="sumx-card-meta mb-0">
							<span><i class="fa-regular fa-user" aria-hidden="true"></i><?php the_author(); ?></span>
							<span><i class="fa-regular fa-clock" aria-hidden="true"></i><?php echo esc_html( get_the_date() ); ?></span>
						</p>
					</div>
				</div>
			<?php endwhile; ?>
		</div>

		<?php if ( $styleumax_total > 1 ) : ?>
			<button class="carousel-control-prev" type="button" data-bs-target="#sumx-archive-slider" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden"><?php esc_html_e( 'Önceki slayt', 'styleumax' ); ?></span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#sumx-archive-slider" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden"><?php esc_html_e( 'Sonraki slayt', 'styleumax' ); ?></span>
			</button>
		<?php endif; ?>
	</div>
</div>
