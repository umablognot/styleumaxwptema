<?php
/**
 * Main fallback template: blog index and everything not matched above.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="sumx-container sumx-site-main" id="sumx-main-content">
	<?php styleumax_breadcrumbs(); ?>

	<div class="row g-4">
		<div class="col-lg-8<?php echo styleumax_get_option( 'show_sidebar', true ) ? '' : ' mx-auto'; ?>">
			<h1 class="sumx-section-title">
				<i class="fa-regular fa-clock" aria-hidden="true"></i>
				<?php echo esc_html( is_home() ? get_bloginfo( 'name' ) . ' ' . __( 'Günlüğü', 'styleumax' ) : get_the_archive_title() ); ?>
			</h1>

			<?php get_template_part( 'template-parts/archive', 'default' ); ?>
		</div>

		<?php if ( styleumax_get_option( 'show_sidebar', true ) ) : ?>
			<aside class="col-lg-4">
				<?php get_sidebar(); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
