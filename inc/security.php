<?php
/**
 * Security & hardening. All switches live in the Customizer
 * ("Styleumax Ayarları > Güvenlik ve yükleme") with safe defaults.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Extended upload MIME types (mirrors the original theme's custom list,
 * filtered down to safe, commonly used document/media types).
 * ---------------------------------------------------------------------- */

/**
 * Return the extended MIME type map.
 *
 * @return array<string,string>
 * @since 1.0.0
 */
function styleumax_mime_map(): array {
        $map = array(
                'pdf'  => 'application/pdf',
                'doc'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls'  => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt'  => 'application/vnd.ms-powerpoint',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'odt'  => 'application/vnd.oasis.opendocument.text',
                'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
                'odp'  => 'application/vnd.oasis.opendocument.presentation',
                'txt'  => 'text/plain',
                'csv'  => 'text/csv',
                'rtf'  => 'application/rtf',
                'epub' => 'application/epub+zip',
                'webp' => 'image/webp',
                'avif' => 'image/avif',
                'heic' => 'image/heic',
                'ico'  => 'image/x-icon',
                'tiff' => 'image/tiff',
                'mp4'  => 'video/mp4',
                'm4v'  => 'video/x-m4v',
                'mov'  => 'video/quicktime',
                'webm' => 'video/webm',
                'mkv'  => 'video/x-matroska',
                'mp3'  => 'audio/mpeg',
                'wav'  => 'audio/wav',
                'ogg'  => 'audio/ogg',
                'zip'  => 'application/zip',
                '7z'   => 'application/x-7z-compressed',
        );

        return apply_filters( 'styleumax_mime_map', $map );
}

/**
 * Merge the map into WP's upload_mimes.
 *
 * @param array $mimes Current mime types.
 * @return array
 * @since 1.0.0
 */
function styleumax_upload_mimes( array $mimes ): array {
        // SVG is handled separately: only administrators, only when enabled.
        return array_merge( $mimes, styleumax_mime_map() );
}
add_filter( 'upload_mimes', 'styleumax_upload_mimes' );

/**
 * Allow SVG uploads for administrators only, when the option is enabled.
 *
 * @param array $mimes Current mime types.
 * @return array
 * @since 1.0.0
 */
function styleumax_allow_svg( array $mimes ): array {
        if ( styleumax_get_option( 'allow_svg', false ) && current_user_can( 'manage_options' ) ) {
                $mimes['svg'] = 'image/svg+xml';
        }

        return $mimes;
}
add_filter( 'upload_mimes', 'styleumax_allow_svg', 20 );

/**
 * Hide WP version from head & feeds (opt-in).
 *
 * @return string
 * @since 1.0.0
 */
function styleumax_hide_generator(): string {
        return styleumax_get_option( 'hide_version', false ) ? '' : '<meta name="generator" content="WordPress ' . esc_attr( get_bloginfo( 'version' ) ) . '">' . "\n";
}
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'styleumax_hide_generator', 1 );

/* -------------------------------------------------------------------------
 * Optional hardening toggles (default: off, so nothing breaks silently).
 * ---------------------------------------------------------------------- */

/**
 * Disable XML-RPC if the toggle is on.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_maybe_disable_xmlrpc(): void {
        if ( styleumax_get_option( 'disable_xmlrpc', false ) ) {
                add_filter( 'xmlrpc_enabled', '__return_false' );
        }
}
add_action( 'init', 'styleumax_maybe_disable_xmlrpc' );

/**
 * Prevent direct access to sensitive attachment pages for uploads
 * (redirects image attachment URLs to the file itself).
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_redirect_attachment_pages(): void {
        if ( ! is_attachment() || ! styleumax_get_option( 'redirect_attachments', true ) ) {
                return;
        }

        $file_url = wp_get_attachment_url( (int) get_the_ID() );
        if ( '' !== (string) $file_url ) {
                wp_safe_redirect( $file_url, 301 );
                exit;
        }
}
add_action( 'template_redirect', 'styleumax_redirect_attachment_pages' );

/**
 * Add the Security section to the Customizer (late-loaded so this file
 * can be required before customizer.php without order issues).
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 * @return void
 * @since 1.0.0
 */
function styleumax_security_customizer( WP_Customize_Manager $wp_customize ): void {
        $wp_customize->add_section(
                'styleumax_security',
                array(
                        'title'       => __( 'Güvenlik ve yükleme', 'styleumax' ),
                        'panel'       => 'styleumax_panel',
                        'description' => __( 'Tüm seçenekler varsayılan olarak kapalı/başlangıç değerindedir; güvenliği artırmak için açabilirsiniz.', 'styleumax' ),
                )
        );

        $toggles = array(
                'styleumax_allow_svg'         => __( 'SVG yüklemesine izin ver (yalnızca yöneticiler)', 'styleumax' ),
                'styleumax_disable_xmlrpc'    => __( 'XML-RPC\'yi devre dışı bırak', 'styleumax' ),
                'styleumax_hide_version'      => __( 'WordPress sürümünü gizle', 'styleumax' ),
                'styleumax_redirect_attachments' => __( 'Dosya ek sayfalarını doğrudan dosyaya yönlendir', 'styleumax' ),
        );

        foreach ( $toggles as $setting_id => $label ) {
                $wp_customize->add_setting(
                        $setting_id,
                        array(
                                'default'           => 'styleumax_redirect_attachments' === $setting_id,
                                'sanitize_callback' => 'styleumax_sanitize_checkbox',
                        )
                );
                $wp_customize->add_control(
                        $setting_id,
                        array(
                                'label'   => $label,
                                'section' => 'styleumax_security',
                                'type'    => 'checkbox',
                        )
                );
        }
}
add_action( 'customize_register', 'styleumax_security_customizer', 20 );
