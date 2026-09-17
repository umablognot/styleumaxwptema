<?php
/**
 * The footer: follow-us bar, three widget areas, bottom meta.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<?php if ( styleumax_get_option( 'show_followbar', true ) ) : ?>
	<?php $styleumax_social = styleumax_get_social_links(); ?>
	<?php if ( array() !== $styleumax_social ) : ?>
		<section class="sumx-followus" aria-label="<?php esc_attr_e( 'Bizi takip edin', 'styleumax' ); ?>">
			<div class="sumx-container">
				<div class="sumx-follow-grid">
					<?php foreach ( $styleumax_social as $styleumax_link ) : ?>
						<a class="sumx-follow-item" href="<?php echo esc_url( $styleumax_link['url'] ); ?>" rel="nofollow noopener">
							<i class="<?php echo esc_attr( $styleumax_link['icon'] ); ?>" aria-hidden="true"></i>
							<span>
								<span class="sumx-follow-title"><?php echo esc_html( $styleumax_link['label'] ); ?></span>
								<span class="sumx-follow-desc"><?php esc_html_e( 'Takip edin', 'styleumax' ); ?></span>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
<?php endif; ?>

<footer class="sumx-footer" role="contentinfo">
	<div class="sumx-container">
		<div class="row g-4">
			<div class="col-md-4">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php else : ?>
					<section class="sumx-widget">
						<h2 class="sumx-widget-title"><i class="fa-solid fa-newspaper" aria-hidden="true"></i> <?php bloginfo( 'name' ); ?></h2>
						<p class="mb-0"><?php echo esc_html( (string) get_bloginfo( 'description' ) ); ?></p>
					</section>
				<?php endif; ?>
			</div>
			<div class="col-md-4">
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<?php dynamic_sidebar( 'footer-2' ); ?>
				<?php endif; ?>
			</div>
			<div class="col-md-4">
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<?php dynamic_sidebar( 'footer-3' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="sumx-footer-bottom mt-4">
		<div class="sumx-container">
			<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
				<div class="sumx-footer-copy">
					<?php
					$styleumax_footer_text = (string) styleumax_get_option( 'footer_text', '' );
					if ( '' !== $styleumax_footer_text ) {
						echo wp_kses_post( $styleumax_footer_text );
					} else {
						printf(
							/* translators: 1: site name, 2: year. */
							esc_html__( '© %2$s %1$s. Tüm hakları saklıdır.', 'styleumax' ),
							esc_html( (string) get_bloginfo( 'name' ) ),
							esc_html( date_i18n( 'Y' ) )
						);
					}
					?>
				</div>
				<div class="sumx-footer-links">
					<?php
					if ( has_nav_menu( 'footer' ) ) {
						wp_nav_menu(
							array(
								'theme_location'       => 'footer',
								'container'            => 'nav',
								'container_aria_label' => __( 'Alt menü', 'styleumax' ),
								'menu_class'           => 'menu list-inline mb-0 d-flex flex-wrap gap-3',
								'depth'                => 1,
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</footer>

<?php if ( styleumax_get_option( 'show_backtotop', true ) ) : ?>
	<button type="button" class="sumx-backtotop" id="sumx-backtotop"
		aria-label="<?php esc_attr_e( 'Sayfanın başına dön', 'styleumax' ); ?>">
		<i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
	</button>
<?php endif; ?>

</div><!-- .sumx-site-wrapper -->

<?php wp_footer(); ?>
</body>
</html>
