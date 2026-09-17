<?php
/**
 * Customizer options. Every option is sanitized; ad fields are editable
 * only by users who already have edit_theme_options capability.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/**
 * Get a theme mod with the styleumax_ prefix and a default value.
 *
 * @param string $key     Option key without prefix.
 * @param mixed  $default Default value.
 * @return mixed
 * @since 1.0.0
 */
function styleumax_get_option( string $key, $default = '' ) {
        return get_theme_mod( 'styleumax_' . $key, $default );
}

/**
 * Sanitize a checkbox (bool).
 *
 * @param mixed $value Raw value.
 * @return bool
 * @since 1.0.0
 */
function styleumax_sanitize_checkbox( $value ): bool {
        return (bool) $value;
}

/**
 * Sanitize select values against a whitelist.
 *
 * @param string   $value    Raw value.
 * @param WP_Customize_Setting $setting Setting object.
 * @return string
 * @since 1.0.0
 */
function styleumax_sanitize_select( $value, $setting = null ): string {
        $value   = (string) $value;
        $choices = $setting->manager->get_control( $setting->id )->choices ?? array();

        return array_key_exists( $value, $choices ) ? $value : (string) ( $setting->default ?? '' );
}

/**
 * Sanitize hex colors, allow empty.
 *
 * @param string $value Raw value.
 * @return string
 * @since 1.0.0
 */
function styleumax_sanitize_color( $value ): string {
        $value = (string) $value;

        return '' === $value ? '' : sanitize_hex_color( $value ) ?? '';
}

/**
 * Sanitize URL fields, allow empty.
 *
 * @param string $value Raw value.
 * @return string
 * @since 1.0.0
 */
function styleumax_sanitize_url( $value ): string {
        $value = (string) $value;

        return '' === trim( $value ) ? '' : esc_url_raw( $value );
}

/**
 * Sanitize email, allow empty.
 *
 * @param string $value Raw value.
 * @return string
 * @since 1.0.0
 */
function styleumax_sanitize_email_field( $value ): string {
        $value = (string) $value;

        return '' === trim( $value ) ? '' : sanitize_email( $value );
}

/**
 * Sanitize multiline ad/embed code.
 *
 * The Customizer restricts writing to users with edit_theme_options,
 * and this callback re-checks the capability as defense in depth.
 * PHP tags are always stripped.
 *
 * @param string $value Raw value.
 * @param WP_Customize_Setting $setting Setting object.
 * @return string
 * @since 1.0.0
 */
function styleumax_sanitize_ad_code( $value, $setting = null ): string {
        if ( $setting && ! current_user_can( 'edit_theme_options' ) ) {
                return '';
        }

        $value = (string) $value;
        $value = str_replace( array( '<?php', '<?', '<%' ), '', wp_unslash( $value ) );

        return (string) wp_check_invalid_utf8( $value, true );
}

/**
 * Sanitize limited footer HTML (links, strong, em, br only).
 *
 * @param string $value Raw value.
 * @return string
 * @since 1.0.0
 */
function styleumax_sanitize_footer_text( $value ): string {
        return wp_kses(
                $value,
                array(
                        'a'      => array( 'href' => array(), 'title' => array(), 'target' => array(), 'rel' => array() ),
                        'strong' => array(),
                        'b'      => array(),
                        'em'     => array(),
                        'i'      => array(),
                        'br'     => array(),
                        'span'   => array( 'class' => array() ),
                )
        );
}

/**
 * Register Customizer panels, sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @since 1.0.0
 */
function styleumax_customize_register( WP_Customize_Manager $wp_customize ): void {

        /* ------------------------- PANEL ------------------------- */
        $wp_customize->add_panel(
                'styleumax_panel',
                array(
                        'title'    => __( 'Styleumax Ayarları', 'styleumax' ),
                        'priority' => 10,
                )
        );

        /* ===================== GENEL ===================== */
        $wp_customize->add_section(
                'styleumax_general',
                array(
                        'title' => __( 'Genel', 'styleumax' ),
                        'panel' => 'styleumax_panel',
                )
        );

        $wp_customize->add_setting(
                'styleumax_color_mode',
                array(
                        'default'           => 'light',
                        'sanitize_callback' => 'styleumax_sanitize_select',
                )
        );
        $wp_customize->add_control(
                'styleumax_color_mode',
                array(
                        'label'   => __( 'Renk modu', 'styleumax' ),
                        'section' => 'styleumax_general',
                        'type'    => 'select',
                        'choices' => array(
                                'light' => __( 'Açık (varsayılan)', 'styleumax' ),
                                'dark'  => __( 'Koyu', 'styleumax' ),
                                'auto'  => __( 'Ziyaretçi tercihine göre', 'styleumax' ),
                        ),
                )
        );

        $wp_customize->add_setting(
                'styleumax_show_theme_toggle',
                array(
                        'default'           => true,
                        'sanitize_callback' => 'styleumax_sanitize_checkbox',
                )
        );
        $wp_customize->add_control(
                'styleumax_show_theme_toggle',
                array(
                        'label'   => __( 'Koyu/açık mod düğmesini göster', 'styleumax' ),
                        'section' => 'styleumax_general',
                        'type'    => 'checkbox',
                )
        );

        $wp_customize->add_setting(
                'styleumax_show_pagetools',
                array(
                        'default'           => true,
                        'sanitize_callback' => 'styleumax_sanitize_checkbox',
                )
        );
        $wp_customize->add_control(
                'styleumax_show_pagetools',
                array(
                        'label'       => __( 'Yüzen kısayol çubuğunu göster', 'styleumax' ),
                        'description' => __( 'Sayfanın solunda önceki/sonraki yazı, anasayfa ve arama kısayolları.', 'styleumax' ),
                        'section'     => 'styleumax_general',
                        'type'        => 'checkbox',
                )
        );

        $wp_customize->add_setting(
                'styleumax_show_hero',
                array(
                        'default'           => true,
                        'sanitize_callback' => 'styleumax_sanitize_checkbox',
                )
        );
        $wp_customize->add_control(
                'styleumax_show_hero',
                array(
                        'label'       => __( 'Anasayfada hero (tanıtım) alanını göster', 'styleumax' ),
                        'description' => __( 'Site adı ve sloganını vurgulayan geniş karşılama paneli.', 'styleumax' ),
                        'section'     => 'styleumax_general',
                        'type'        => 'checkbox',
                )
        );

        $wp_customize->add_setting(
                'styleumax_show_progress',
                array(
                        'default'           => true,
                        'sanitize_callback' => 'styleumax_sanitize_checkbox',
                )
        );
        $wp_customize->add_control(
                'styleumax_show_progress',
                array(
                        'label'       => __( 'Tek yazıda okuma ilerleme çubuğunu göster', 'styleumax' ),
                        'description' => __( 'Sayfa kaydırıldıkça üstte dolan ince marka rengi çubuğu.', 'styleumax' ),
                        'section'     => 'styleumax_general',
                        'type'        => 'checkbox',
                )
        );

        $wp_customize->add_setting(
                'styleumax_contact_url',
                array(
                        'default'           => '',
                        'sanitize_callback' => 'styleumax_sanitize_url',
                )
        );
        $wp_customize->add_control(
                'styleumax_contact_url',
                array(
                        'label'   => __( 'İletişim sayfası adresi', 'styleumax' ),
                        'section' => 'styleumax_general',
                        'type'    => 'url',
                )
        );

        /* ===================== RENKLER ===================== */
        $wp_customize->add_section(
                'styleumax_colors',
                array(
                        'title' => __( 'Renkler', 'styleumax' ),
                        'panel' => 'styleumax_panel',
                )
        );

        $colors = array(
                'styleumax_primary_color'      => array( __( 'Ana renk (bağlantılar, düğmeler, çipler)', 'styleumax' ), '#c8102e' ),
                'styleumax_accent_color'       => array( __( 'Vurgu rengi (ikonlar, etiketler)', 'styleumax' ), '#e8930c' ),
                'styleumax_topbar_bg'          => array( __( 'Üst şerit zemini', 'styleumax' ), '#14181d' ),
                'styleumax_footer_bg'          => array( __( 'Alt bilgi zemini', 'styleumax' ), '#14181d' ),
        );

        foreach ( $colors as $setting_id => $data ) {
                $wp_customize->add_setting(
                        $setting_id,
                        array(
                                'default'           => $data[1],
                                'sanitize_callback' => 'styleumax_sanitize_color',
                        )
                );
                $wp_customize->add_control(
                        new WP_Customize_Color_Control(
                                $wp_customize,
                                $setting_id,
                                array(
                                        'label'   => $data[0],
                                        'section' => 'styleumax_colors',
                                )
                        )
                );
        }

        /* ===================== DÜZEN ===================== */
        $wp_customize->add_section(
                'styleumax_layout',
                array(
                        'title'       => __( 'Düzenler', 'styleumax' ),
                        'panel'       => 'styleumax_panel',
                        'description' => __( 'Varsayılan arşiv düzeni buradan seçilir; her kategori için ayrı düzen, Kategoriler ekranındaki alandan belirlenebilir.', 'styleumax' ),
                )
        );

        $wp_customize->add_setting(
                'styleumax_archive_layout',
                array(
                        'default'           => 'default',
                        'sanitize_callback' => 'styleumax_sanitize_select',
                )
        );
        $wp_customize->add_control(
                'styleumax_archive_layout',
                array(
                        'label'   => __( 'Varsayılan arşiv düzeni', 'styleumax' ),
                        'section' => 'styleumax_layout',
                        'type'    => 'select',
                        'choices' => styleumax_layout_choices(),
                )
        );

        $wp_customize->add_setting(
                'styleumax_archive_slider_count',
                array(
                        'default'           => 6,
                        'sanitize_callback' => 'absint',
                )
        );
        $wp_customize->add_control(
                'styleumax_archive_slider_count',
                array(
                        'label'       => __( 'Slayt düzeninde kaç yazı gösterilsin', 'styleumax' ),
                        'description' => __( 'Sadece slider düzeninde geçerlidir. Kalan yazılar liste olarak devam eder.', 'styleumax' ),
                        'section'     => 'styleumax_layout',
                        'type'        => 'number',
                        'input_attrs' => array( 'min' => 2, 'max' => 10 ),
                )
        );

        $wp_customize->add_setting(
                'styleumax_show_sidebar',
                array(
                        'default'           => true,
                        'sanitize_callback' => 'styleumax_sanitize_checkbox',
                )
        );
        $wp_customize->add_control(
                'styleumax_show_sidebar',
                array(
                        'label'   => __( 'Arşivlerde yan sütunu göster', 'styleumax' ),
                        'section' => 'styleumax_layout',
                        'type'    => 'checkbox',
                )
        );

        /* ===================== SOSYAL MEDYA ===================== */
        $wp_customize->add_section(
                'styleumax_social',
                array(
                        'title' => __( 'Sosyal medya', 'styleumax' ),
                        'panel' => 'styleumax_panel',
                )
        );

        $wp_customize->add_setting(
                'styleumax_show_followbar',
                array(
                        'default'           => true,
                        'sanitize_callback' => 'styleumax_sanitize_checkbox',
                )
        );
        $wp_customize->add_control(
                'styleumax_show_followbar',
                array(
                        'label'       => __( 'Alt bilgi üstünde "Takip Edin" şeridini göster', 'styleumax' ),
                        'section'     => 'styleumax_social',
                        'type'        => 'checkbox',
                )
        );

        $socials = array(
                'facebook'  => array( __( 'Facebook', 'styleumax' ), 'fa-brands fa-facebook-f' ),
                'x'         => array( __( 'X (Twitter)', 'styleumax' ), 'fa-brands fa-x-twitter' ),
                'instagram' => array( __( 'Instagram', 'styleumax' ), 'fa-brands fa-instagram' ),
                'youtube'   => array( __( 'YouTube', 'styleumax' ), 'fa-brands fa-youtube' ),
                'linkedin'  => array( __( 'LinkedIn', 'styleumax' ), 'fa-brands fa-linkedin-in' ),
                'pinterest' => array( __( 'Pinterest', 'styleumax' ), 'fa-brands fa-pinterest-p' ),
                'tiktok'    => array( __( 'TikTok', 'styleumax' ), 'fa-brands fa-tiktok' ),
                'telegram'  => array( __( 'Telegram', 'styleumax' ), 'fa-brands fa-telegram' ),
                'whatsapp'  => array( __( 'WhatsApp', 'styleumax' ), 'fa-brands fa-whatsapp' ),
                'bluesky'   => array( __( 'Bluesky', 'styleumax' ), 'fa-brands fa-bluesky' ),
                'mastodon'  => array( __( 'Mastodon', 'styleumax' ), 'fa-brands fa-mastodon' ),
                'rss'       => array( __( 'Özel RSS adresi', 'styleumax' ), 'fa-solid fa-rss' ),
                'email'     => array( __( 'E-posta adresi', 'styleumax' ), 'fa-solid fa-envelope' ),
        );

        foreach ( $socials as $key => $data ) {
                $wp_customize->add_setting(
                        'styleumax_social_' . $key,
                        array(
                                'default'           => '',
                                'sanitize_callback' => 'email' === $key ? 'styleumax_sanitize_email_field' : 'styleumax_sanitize_url',
                        )
                );
                $wp_customize->add_control(
                        'styleumax_social_' . $key,
                        array(
                                'label'   => $data[0],
                                'section' => 'styleumax_social',
                                'type'    => 'email' === $key ? 'email' : 'url',
                        )
                );
        }

        /* ===================== REKLAMLAR ===================== */
        $wp_customize->add_section(
                'styleumax_ads',
                array(
                        'title'       => __( 'Reklam alanları', 'styleumax' ),
                        'panel'       => 'styleumax_panel',
                        'description' => __( 'Reklam kodlarını buraya yapıştırabilir veya tema klasöründeki template-parts/ads/*.php dosyalarını doğrudan düzenleyebilirsiniz. Alan boşsa ve dosya boşsa hiçbir şey yazdırılmaz.', 'styleumax' ),
                )
        );

        $ad_slots = array(
                'header'        => __( 'Üst bilgi reklamı (logonun yanı)', 'styleumax' ),
                'sidebar_top'   => __( 'Yan sütun üstü', 'styleumax' ),
                'sidebar_bot'   => __( 'Yan sütun altı', 'styleumax' ),
                'archive_top'   => __( 'Arşiv sayfası üstü', 'styleumax' ),
                'single_bottom' => __( 'Tek yazı altı', 'styleumax' ),
        );

        foreach ( $ad_slots as $key => $label ) {
                $wp_customize->add_setting(
                        'styleumax_ad_' . $key,
                        array(
                                'default'           => '',
                                'sanitize_callback' => 'styleumax_sanitize_ad_code',
                                'transport'         => 'refresh',
                        )
                );
                $wp_customize->add_control(
                        'styleumax_ad_' . $key,
                        array(
                                'label'   => $label,
                                'section' => 'styleumax_ads',
                                'type'    => 'textarea',
                        )
                );
        }

        /* ===================== ALT BİLGİ ===================== */
        $wp_customize->add_section(
                'styleumax_footer',
                array(
                        'title' => __( 'Alt bilgi', 'styleumax' ),
                        'panel' => 'styleumax_panel',
                )
        );

        $wp_customize->add_setting(
                'styleumax_footer_text',
                array(
                        'default'           => '',
                        'sanitize_callback' => 'styleumax_sanitize_footer_text',
                )
        );
        $wp_customize->add_control(
                'styleumax_footer_text',
                array(
                        'label'       => __( 'Alt bilgi telif metni', 'styleumax' ),
                        'description' => __( 'Boş bırakılırsa site adı ve yıl otomatik yazdırılır.', 'styleumax' ),
                        'section'     => 'styleumax_footer',
                        'type'        => 'textarea',
                )
        );

        $wp_customize->add_setting(
                'styleumax_show_backtotop',
                array(
                        'default'           => true,
                        'sanitize_callback' => 'styleumax_sanitize_checkbox',
                )
        );
        $wp_customize->add_control(
                'styleumax_show_backtotop',
                array(
                        'label'   => __( '"Yukarı dön" düğmesini göster', 'styleumax' ),
                        'section' => 'styleumax_footer',
                        'type'    => 'checkbox',
                )
        );
}
add_action( 'customize_register', 'styleumax_customize_register' );
