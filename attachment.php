<?php
/**
 * Attachment template: shows the media with caption/description.
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
						<ul class="sumx-single-meta">
							<li><i class="fa-regular fa-calendar" aria-hidden="true"></i><?php echo esc_html( get_the_date() ); ?></li>
							<li><i class="fa-solid fa-arrow-up-from-bracket" aria-hidden="true"></i>
								<a href="<?php echo esc_url( (string) wp_get_attachment_url( (int) get_the_ID() ) ); ?>" download rel="nofollow">
									<?php esc_html_e( 'Dosyayı indir', 'styleumax' ); ?>
								</a>
							</li>
						</ul>
					</header>

					<div class="sumx-entry text-center">
						<?php the_content(); ?>
					</div>

					<?php if ( '' !== (string) get_the_excerpt() ) : ?>
						<p class="small text-body-secondary mt-3"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</article>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
