<?php
/**
 * PHP compatibility notice.
 *
 * Loaded ONLY when the server runs PHP below 8.0. This file intentionally
 * uses plain, old-school syntax so it can parse on any PHP version
 * WordPress supports. Its job is to replace a fatal "critical error"
 * screen with a clear, actionable message.
 *
 * @package Styleumax
 * @since   1.0.1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin dashboard notice explaining the PHP requirement.
 *
 * @return void
 */
function styleumax_compat_admin_notice() {
	$server_php = PHP_VERSION;
	$screen     = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$is_themes  = ( $screen && ! empty( $screen->id ) && false !== strpos( (string) $screen->id, 'themes' ) );
	?>
	<div class="notice notice-error is-dismissible" style="padding:12px">
		<p style="margin-top:0">
			<strong><?php esc_html_e( 'Styleumax: PHP sürümünüz çok eski.', 'styleumax' ); ?></strong>
			<?php
			printf(
				/* translators: 1: server PHP version, 2: required PHP version. */
				esc_html__( 'Sunucunuzda PHP %1$s çalışıyor; Styleumax teması PHP %2$s veya üstünü gerektiriyor. Barındırma panelinizden (cPanel / Plesk / MultiPHP) PHP sürümünü 8.0 veya üzerine yükseltin.', 'styleumax' ),
				esc_html( $server_php ),
				'8.0'
			);
			?>
		</p>
		<?php if ( $is_themes ) : ?>
			<p style="margin-bottom:12px"><?php esc_html_e( 'PHP yükseltene kadar tema sitenizde normal içerik yerine bilgilendirme ekranı gösterir; hiçbir veri kaybı olmaz.', 'styleumax' ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}
add_action( 'admin_notices', 'styleumax_compat_admin_notice' );

/**
 * Front-end guard: instead of a fatal error, show a clear 503 screen.
 * Theme templates call styleumax_* helpers, which are not loaded on
 * old PHP, so template execution must stop here.
 *
 * @return void
 */
function styleumax_compat_frontend_guard() {
	if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return;
	}

	wp_die(
		'<h1>' . esc_html__( 'Sunucu PHP sürümü çok eski', 'styleumax' ) . '</h1>' .
		'<p>' .
		sprintf(
			/* translators: 1: server PHP version, 2: required PHP version, 3: theme name. */
			esc_html__( 'Sunucunuzda PHP %1$s çalışıyor, ancak %3$s teması için PHP %2$s veya üstü gerekiyor. Barındırma sağlayıcınızın kontrol panelinden PHP sürümünü yükselttikten sonra sayfayı yenileyin.', 'styleumax' ),
			esc_html( PHP_VERSION ),
			'8.0',
			'Styleumax'
		) .
		'</p>',
		'PHP Upgrade Required',
		array( 'response' => 503, 'back_link' => false )
	);
}
add_action( 'template_redirect', 'styleumax_compat_frontend_guard', 0 );

/**
 * Block the Customizer preview on old PHP as well.
 *
 * @return void
 */
function styleumax_compat_customize_guard() {
	wp_die(
		esc_html__( 'Styleumax temanın Customizer ayarları PHP 8.0 veya üstünü gerektirir. Lütfen önce PHP sürümünü yükseltin.', 'styleumax' ),
		'PHP Upgrade Required',
		array( 'response' => 503, 'back_link' => true )
	);
}
add_action( 'load-customize.php', 'styleumax_compat_customize_guard' );
