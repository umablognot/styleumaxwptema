<?php
/**
 * Styleumax theme bootstrap.
 *
 * Loads every module the theme needs. All output-side logic lives in
 * /inc/*.php, all template logic stays inside the template files.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

define( 'STYLEUMAX_VERSION', '1.1.0' );
define( 'STYLEUMAX_DIR', get_template_directory() );
define( 'STYLEUMAX_URI', get_template_directory_uri() );

/**
 * Hard PHP requirement guard.
 *
 * Everything the theme needs above this line parses on PHP 7.1+, so an
 * outdated server now gets a clear, actionable message (inc/compat-notice.php)
 * instead of a fatal "critical error" screen caused by PHP 8 syntax
 * (union/mixed types, modern string helpers) in the modules below.
 *
 * @since 1.0.1
 */
if ( PHP_VERSION_ID < 80000 ) {
        require_once STYLEUMAX_DIR . '/inc/compat-notice.php';

        return;
}

/**
 * Required theme modules.
 *
 * @since 1.0.0
 */
function styleumax_requires(): void {
        require_once STYLEUMAX_DIR . '/inc/setup.php';
        require_once STYLEUMAX_DIR . '/inc/enqueue.php';
        require_once STYLEUMAX_DIR . '/inc/customizer.php';
        require_once STYLEUMAX_DIR . '/inc/category-layouts.php';
        require_once STYLEUMAX_DIR . '/inc/template-tags.php';
        require_once STYLEUMAX_DIR . '/inc/schema.php';
        require_once STYLEUMAX_DIR . '/inc/security.php';
        require_once STYLEUMAX_DIR . '/inc/widgets.php';
        require_once STYLEUMAX_DIR . '/inc/meta-boxes.php';
}
styleumax_requires();
