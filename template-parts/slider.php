<?php
/**
 * Bootstrap carousel slider.
 * Expects args: 'query' => WP_Query, 'id' => unique DOM id.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

$styleumax_args = wp_parse_args(
	$args ?? array(),
	array(
		'query' => null,
		'id'    => 'sumx-slider',
	)
);

$styleumax_query = $styleumax_args['query'];

if ( ! $styleumax_query instanceof WP_Query || ! $styleumax_query->have_posts() ) {
	return;
}

$styleumax_slider_id = (string) $styleumax_args['id'];
$styleumax_total     = $styleumax_query->post_count;
$styleumax_index     = 0;
?>
<div id="<?php echo esc_attr( $styleumax_slider_id ); ?>" class="carousel slide sumx-slider" data-bs-ride="carousel" data-bs-interval="6000">
	<div class="carousel-indicators">
		<?php for ( $styleumax_i = 0; $styleumax_i < $styleumax_total; $styleumax_i++ ) : ?>
			<button type="button" data-bs-target="#<?php echo esc_attr( $styleumax_slider_id ); ?>" data-bs-slide-to="<?php echo esc_attr( (string) $styleumax_i ); ?>"
				<?php echo 0 === $styleumax_i ? 'class="active" aria-current="true"' : ''; ?>
				aria-label="<?php echo esc_attr(
					/* translators: %d: slide number. */
					sprintf( __( 'Slayt %d', 'styleumax' ), $styleumax_i + 1 )
				); ?>"></button>
		<?php endfor; ?>
	</div>

	<div class="carousel-inner">
		<?php while ( $styleumax_query->have_posts() ) : ?>
			<?php
			$styleumax_query->the_post();
			$styleumax_is_active = 0 === $styleumax_index ? ' active' : '';
			?>
			<div class="carousel-item<?php echo esc_attr( $styleumax_is_active ); ?>">
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php styleumax_thumbnail( 'styleumax-slider' ); ?>
				</a>
				<div class="carousel-caption">
					<div class="d-flex align-items-center gap-2">
						<?php styleumax_category_chip(); ?>
						<?php if ( is_sticky() ) : ?>
							<span class="sumx-sticky-flag"><i class="fa-solid fa-thumbtack" aria-hidden="true"></i> <?php esc_html_e( 'Manşet', 'styleumax' ); ?></span>
						<?php endif; ?>
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
			<?php
			$styleumax_index++;
			?>
		<?php endwhile; ?>
	</div>

	<?php if ( $styleumax_total > 1 ) : ?>
		<button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr( $styleumax_slider_id ); ?>" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden"><?php esc_html_e( 'Önceki slayt', 'styleumax' ); ?></span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr( $styleumax_slider_id ); ?>" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden"><?php esc_html_e( 'Sonraki slayt', 'styleumax' ); ?></span>
		</button>
	<?php endif; ?>
</div>
<?php
wp_reset_postdata();
