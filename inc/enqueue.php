<?php
/**
 * Front-end asset loading. Everything ships locally - no CDN calls.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/**
 * Return a version string based on file modification time (cache busting).
 *
 * @param string $rel Relative path from theme root.
 * @return string
 * @since 1.0.0
 */
function styleumax_asset_version( string $rel ): string {
        $file = STYLEUMAX_DIR . '/' . ltrim( $rel, '/' );
        return file_exists( $file ) ? (string) filemtime( $file ) : STYLEUMAX_VERSION;
}

/**
 * Enqueue front-end styles and scripts.
 *
 * @since 1.0.0
 */
function styleumax_enqueue_assets(): void {
        wp_enqueue_style(
                'styleumax-bootstrap',
                STYLEUMAX_URI . '/assets/css/bootstrap.min.css',
                array(),
                styleumax_asset_version( 'assets/css/bootstrap.min.css' )
        );

        wp_enqueue_style(
                'styleumax-fontawesome',
                STYLEUMAX_URI . '/assets/css/fontawesome.min.css',
                array(),
                styleumax_asset_version( 'assets/css/fontawesome.min.css' )
        );

        wp_enqueue_style(
                'styleumax-fontawesome-solid',
                STYLEUMAX_URI . '/assets/css/solid.min.css',
                array( 'styleumax-fontawesome' ),
                styleumax_asset_version( 'assets/css/solid.min.css' )
        );

        wp_enqueue_style(
                'styleumax-fontawesome-regular',
                STYLEUMAX_URI . '/assets/css/regular.min.css',
                array( 'styleumax-fontawesome' ),
                styleumax_asset_version( 'assets/css/regular.min.css' )
        );

        wp_enqueue_style(
                'styleumax-fontawesome-brands',
                STYLEUMAX_URI . '/assets/css/brands.min.css',
                array( 'styleumax-fontawesome' ),
                styleumax_asset_version( 'assets/css/brands.min.css' )
        );

        wp_enqueue_style( 'styleumax-style', get_stylesheet_uri(), array( 'styleumax-bootstrap' ), styleumax_asset_version( 'style.css' ) );

        // Customizer colors / dark mode preference as inline CSS.
        wp_add_inline_style( 'styleumax-style', styleumax_inline_css() );

        wp_enqueue_script(
                'styleumax-bootstrap',
                STYLEUMAX_URI . '/assets/js/bootstrap.bundle.min.js',
                array(),
                styleumax_asset_version( 'assets/js/bootstrap.bundle.min.js' ),
                array( 'strategy' => 'defer' )
        );

        wp_enqueue_script(
                'styleumax-script',
                STYLEUMAX_URI . '/assets/js/styleumax.js',
                array( 'styleumax-bootstrap' ),
                styleumax_asset_version( 'assets/js/styleumax.js' ),
                array( 'strategy' => 'defer' )
        );

        wp_localize_script(
                'styleumax-script',
                'styleumaxSettings',
                array(
                        'themeMode'  => (string) get_theme_mod( 'styleumax_color_mode', 'light' ),
                        'isRtl'      => is_rtl(),
                )
        );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
        }
}
add_action( 'wp_enqueue_scripts', 'styleumax_enqueue_assets' );

/**
 * Build the inline CSS driven by Customizer settings.
 *
 * Values are re-sanitized on READ as defense in depth: theme mods live in
 * the database and may contain unexpected content (manual edits, imports),
 * and this string is printed inside a <style> tag.
 *
 * The primary color is also bridged into Bootstrap's own custom properties
 * (--bs-primary / --bs-primary-rgb / --bs-link-color-rgb) so that .btn-primary,
 * .text-primary, focus rings and friends follow the brand color.
 *
 * @return string
 * @since 1.0.0
 * @since 1.1.0 Read-side sanitization + Bootstrap color bridge.
 */
function styleumax_inline_css(): string {
        $primary = styleumax_sanitize_color( (string) get_theme_mod( 'styleumax_primary_color', '#c8102e' ) );
        $accent  = styleumax_sanitize_color( (string) get_theme_mod( 'styleumax_accent_color', '#e8930c' ) );
        $footer_bg = styleumax_sanitize_color( (string) get_theme_mod( 'styleumax_footer_bg', '#14181d' ) );
        $topbar_bg = styleumax_sanitize_color( (string) get_theme_mod( 'styleumax_topbar_bg', '#14181d' ) );

        $primary      = '' !== $primary ? $primary : '#c8102e';
        $accent       = '' !== $accent ? $accent : '#e8930c';
        $footer_bg    = '' !== $footer_bg ? $footer_bg : '#14181d';
        $topbar_bg    = '' !== $topbar_bg ? $topbar_bg : '#14181d';
        $primary_dark = styleumax_darken_hex( $primary, 0.28 );
        $primary_rgb  = styleumax_hex_to_rgb( $primary );

        $css  = ':root{';
        $css .= '--sumx-primary:' . $primary . ';';
        $css .= '--sumx-primary-dark:' . $primary_dark . ';';
        $css .= '--sumx-accent:' . $accent . ';';
        $css .= '--sumx-footer-bg:' . $footer_bg . ';';
        $css .= '--sumx-topbar-bg:' . $topbar_bg . ';';
        $css .= '--bs-primary:' . $primary . ';';
        $css .= '--bs-primary-rgb:' . $primary_rgb . ';';
        $css .= '--bs-link-color:' . $primary . ';';
        $css .= '--bs-link-color-rgb:' . $primary_rgb . ';';
        $css .= '}';

        // Follow us / topbar hover uses primary even on dark surfaces.
        $css .= '[data-bs-theme="dark"] .sumx-topbar{--sumx-topbar-bg:#0a0c0f;}';

        return $css;
}

/**
 * Convert a #rrggbb hex color into an "r,g,b" triplet for --bs-*-rgb vars.
 *
 * @param string $hex Hex color (#rgb or #rrggbb).
 * @return string Comma separated RGB channels, e.g. "200,16,46".
 * @since 1.1.0
 */
function styleumax_hex_to_rgb( string $hex ): string {
        $hex = ltrim( $hex, '#' );

        if ( 3 === strlen( $hex ) ) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
                return '200,16,46';
        }

        return (string) hexdec( substr( $hex, 0, 2 ) )
                . ',' . (string) hexdec( substr( $hex, 2, 2 ) )
                . ',' . (string) hexdec( substr( $hex, 4, 2 ) );
}

/**
 * Darken a hex color by a percentage.
 *
 * @param string $hex   Hex color (#rgb or #rrggbb).
 * @param float  $ratio 0-1 darkening ratio.
 * @return string
 * @since 1.0.0
 */
function styleumax_darken_hex( string $hex, float $ratio = 0.2 ): string {
        $hex = ltrim( $hex, '#' );

        if ( 3 === strlen( $hex ) ) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
                return '#1c1f23';
        }

        $out = '#';
        for ( $i = 0; $i < 3; $i++ ) {
                $channel = hexdec( substr( $hex, $i * 2, 2 ) );
                $channel = (int) max( 0, min( 255, round( $channel * ( 1 - $ratio ) ) ) );
                $out    .= str_pad( dechex( $channel ), 2, '0', STR_PAD_LEFT );
        }

        return $out;
}

/**
 * Preconnect hints for embeds. Fonts and assets are local, so only
 * common external embeds are preconnected.
 *
 * @param array  $urls          URLs to print.
 * @param string $relation_type Relation type.
 * @return array
 * @since 1.0.0
 */
function styleumax_resource_hints( array $urls, string $relation_type ): array {
        if ( 'preconnect' === $relation_type ) {
                $urls[] = array(
                        'href'        => 'https://www.youtube.com',
                        'crossorigin' => 'anonymous',
                );
        }
        return $urls;
}
add_filter( 'wp_resource_hints', 'styleumax_resource_hints', 10, 2 );
