<?php
/**
 * Search results template.
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

	<section class="sumx-page-lead">
		<h1>
			<i class="fa-solid fa-magnifying-glass me-2 text-primary" aria-hidden="true"></i>
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Arama sonuçları: %s', 'styleumax' ), '<span class="text-primary">' . esc_html( get_search_query() ) . '</span>' );
			?>
		</h1>
		<p class="sumx-lead-desc">
			<?php
			printf(
				/* translators: %d: number of results. */
				esc_html( _n( '%d sonuç bulundu.', '%d sonuç bulundu.', (int) $GLOBALS['wp_query']->found_posts, 'styleumax' ) ),
				absint( $GLOBALS['wp_query']->found_posts )
			);
			?>
		</p>
	</section>

	<div class="row g-4">
		<div class="col-lg-8<?php echo styleumax_get_option( 'show_sidebar', true ) ? '' : ' mx-auto'; ?>">
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
