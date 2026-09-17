<?php
/**
 * Static page template.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="sumx-container sumx-site-main" id="sumx-main-content">
		<?php styleumax_breadcrumbs(); ?>

		<div class="row g-4">
			<div class="col-lg-8 mx-auto">
				<article <?php post_class(); ?>>
					<header class="sumx-single-header">
						<h1><?php the_title(); ?></h1>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="sumx-single-thumb">
							<?php the_post_thumbnail( 'styleumax-slider', array( 'class' => 'img-fluid' ) ); ?>
						</figure>
					<?php endif; ?>

					<div class="sumx-entry">
						<?php the_content(); ?>
						<?php
						wp_link_pages(
							array(
								'before' => '<nav class="page-links d-flex gap-2 align-items-center"><span class="fw-bold">' . esc_html__( 'Sayfalar:', 'styleumax' ) . '</span>',
								'after'  => '</nav>',
							)
						);
						?>
					</div>
				</article>

				<?php if ( comments_open() || get_comments_number() ) : ?>
					<section class="sumx-comments">
						<?php comments_template(); ?>
					</section>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
