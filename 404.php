<?php
/**
 * 404 template.
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
		<div class="col-lg-8 mx-auto">
			<section class="sumx-page-lead text-center py-5">
				<p class="display-1 fw-bold text-primary mb-0" aria-hidden="true">404</p>
				<h1 class="h3"><?php esc_html_e( 'Aradığınız sayfa bulunamadı', 'styleumax' ); ?></h1>
				<p class="sumx-lead-desc mx-auto" style="max-width: 520px;">
					<?php esc_html_e( 'Sayfa taşınmış, silinmiş veya adresi yanlış yazılmış olabilir. Aşağıdaki arama kutusuyla içeriğe ulaşabilir ya da anasayfaya dönebilirsiniz.', 'styleumax' ); ?>
				</p>

				<div class="mx-auto mt-4" style="max-width: 420px;">
					<?php get_search_form(); ?>
				</div>

				<a class="btn btn-primary mt-4" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<i class="fa-solid fa-house me-2" aria-hidden="true"></i><?php esc_html_e( 'Anasayfaya dön', 'styleumax' ); ?>
				</a>
			</section>
		</div>
	</div>
</div>
<?php
get_footer();
