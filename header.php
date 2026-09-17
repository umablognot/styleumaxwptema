<?php
/**
 * The header: topbar, masthead, primary navigation.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="sumx-skip-link" href="#sumx-main-content"><?php esc_html_e( 'İçeriğe atla', 'styleumax' ); ?></a>

<div class="sumx-site-wrapper">

	<?php styleumax_page_tools(); ?>

	<?php
	// Output the dark/light class as early as possible to avoid a flash.
	$styleumax_color_mode = (string) styleumax_get_option( 'color_mode', 'light' );
	?>
	<script>
		(function () {
			var mode = <?php echo wp_json_encode( $styleumax_color_mode ); ?>;
			var attr = 'light';
			if ( 'dark' === mode ) { attr = 'dark'; }
			else if ( 'auto' === mode ) {
				attr = window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light';
			}
			document.documentElement.setAttribute( 'data-bs-theme', attr );
		})();
	</script>

	<!-- Topbar -->
	<div class="sumx-topbar">
		<div class="sumx-container">
			<div class="d-flex flex-wrap align-items-center justify-content-between py-1">
				<span class="sumx-topbar-date d-none d-md-inline">
					<i class="fa-regular fa-calendar" aria-hidden="true"></i>
					<?php echo esc_html( date_i18n( 'l, j F Y' ) ); ?>
				</span>

				<nav class="sumx-topbar-nav d-none d-lg-block" aria-label="<?php esc_attr_e( 'Üst menü', 'styleumax' ); ?>">
					<ul class="menu">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'top',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'fallback_cb'    => false,
								'depth'          => 1,
							)
						);
						?>
					</ul>
				</nav>

				<div class="sumx-topbar-social ms-auto">
					<?php foreach ( styleumax_get_social_links() as $styleumax_key => $styleumax_link ) : ?>
						<a href="<?php echo esc_url( $styleumax_link['url'] ); ?>"
							<?php echo ( 'rss' !== $styleumax_key && 'email' !== $styleumax_key ) ? 'rel="me nofollow noopener" target="_blank"' : ''; ?>
							aria-label="<?php echo esc_attr( $styleumax_link['label'] ); ?>"
							title="<?php echo esc_attr( $styleumax_link['label'] ); ?>">
							<i class="<?php echo esc_attr( $styleumax_link['icon'] ); ?>" aria-hidden="true"></i>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Masthead -->
	<header class="sumx-header" role="banner">
		<div class="sumx-container">
			<div class="row align-items-center g-3">
				<div class="col-lg-6">
					<div class="sumx-brand">
						<?php if ( has_custom_logo() ) : ?>
							<?php the_custom_logo(); ?>
						<?php endif; ?>
						<div>
							<p class="sumx-brand-name">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
							</p>
							<?php $styleumax_desc = (string) get_bloginfo( 'description', 'display' ); ?>
							<?php if ( '' !== $styleumax_desc ) : ?>
								<p class="sumx-brand-desc"><?php echo esc_html( $styleumax_desc ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="sumx-header-ad">
						<?php styleumax_ad_slot( 'header' ); ?>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Primary navigation -->
	<nav class="sumx-navbar navbar navbar-expand-lg py-0" aria-label="<?php esc_attr_e( 'Ana menü', 'styleumax' ); ?>">
		<div class="sumx-container">
			<button class="navbar-toggler my-2" type="button" data-bs-toggle="collapse" data-bs-target="#sumx-primary-menu" aria-controls="sumx-primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menüyü aç/kapat', 'styleumax' ); ?>">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="sumx-primary-menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'navbar-nav mx-auto mb-2 mb-lg-0',
						'fallback_cb'    => 'styleumax_nav_fallback',
						'walker'         => new Styleumax_Bootstrap_Walker(),
						'depth'          => 3,
					)
				);
				?>

				<div class="d-flex align-items-center gap-2 mb-2 mb-lg-0">
					<?php if ( styleumax_get_option( 'show_theme_toggle', true ) ) : ?>
						<button type="button" class="sumx-theme-toggle" id="sumx-theme-toggle"
							aria-label="<?php esc_attr_e( 'Koyu/açık mod değiştir', 'styleumax' ); ?>" title="<?php esc_attr_e( 'Koyu/açık mod', 'styleumax' ); ?>">
							<i class="fa-solid fa-moon" aria-hidden="true"></i>
						</button>
					<?php endif; ?>

					<button class="btn btn-outline-secondary btn-sm rounded-circle" type="button"
						data-bs-toggle="collapse" data-bs-target="#sumx-search-collapse"
						aria-expanded="false" aria-controls="sumx-search-collapse"
						aria-label="<?php esc_attr_e( 'Arama formunu aç/kapat', 'styleumax' ); ?>">
						<i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
					</button>
				</div>
			</div>
		</div>

		<div class="collapse" id="sumx-search-collapse" data-bs-parent="#sumx-primary-menu">
			<div class="sumx-container py-3">
				<?php get_search_form(); ?>
			</div>
		</div>
	</nav>
