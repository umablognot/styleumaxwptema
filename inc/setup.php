<?php
/**
 * Theme setup: supports, menus, widget areas, editor settings.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/**
 * Register theme features.
 *
 * @since 1.0.0
 */
function styleumax_setup(): void {
        load_theme_textdomain( 'styleumax', STYLEUMAX_DIR . '/languages' );

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'customize-selective-refresh-widgets' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'align-wide' );
        add_theme_support( 'wp-block-styles' );

        add_theme_support(
                'html5',
                array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
        );

        add_theme_support(
                'custom-logo',
                array(
                        'height'      => 96,
                        'width'       => 400,
                        'flex-height' => true,
                        'flex-width'  => true,
                )
        );

        add_theme_support(
                'post-formats',
                array( 'video', 'gallery', 'audio', 'quote' )
        );

        // Image sizes used by the archive layouts and the homepage.
        add_image_size( 'styleumax-slider', 1280, 640, true );
        add_image_size( 'styleumax-lead', 1024, 576, true );
        add_image_size( 'styleumax-grid', 600, 375, true );
        add_image_size( 'styleumax-thumb', 320, 200, true );
        add_image_size( 'styleumax-mini', 136, 104, true );

        register_nav_menus(
                array(
                        'primary' => __( 'Ana menü', 'styleumax' ),
                        'top'     => __( 'Üst menü (masthead)', 'styleumax' ),
                        'footer'  => __( 'Alt menü', 'styleumax' ),
                )
        );

        add_editor_style( 'assets/css/styleumax-editor.css' );

        // Block editor palette mirrors the Customizer defaults.
        add_theme_support(
                'editor-color-palette',
                array(
                        array(
                                'name'  => __( 'Tema ana rengi', 'styleumax' ),
                                'slug'  => 'sumx-primary',
                                'color' => '#c8102e',
                        ),
                        array(
                                'name'  => __( 'Tema vurgu rengi', 'styleumax' ),
                                'slug'  => 'sumx-accent',
                                'color' => '#e8930c',
                        ),
                        array(
                                'name'  => __( 'Koyu metin', 'styleumax' ),
                                'slug'  => 'sumx-dark',
                                'color' => '#1c1f23',
                        ),
                        array(
                                'name'  => __( 'Açık zemin', 'styleumax' ),
                                'slug'  => 'sumx-light',
                                'color' => '#f6f7f9',
                        ),
                )
        );
}
add_action( 'after_setup_theme', 'styleumax_setup' );

/**
 * Content width global.
 *
 * @since 1.0.0
 */
function styleumax_content_width(): void {
        $GLOBALS['content_width'] = apply_filters( 'styleumax_content_width', 780 );
}
add_action( 'after_setup_theme', 'styleumax_content_width', 0 );

/**
 * Register widget areas.
 *
 * @since 1.0.0
 */
function styleumax_widgets_init(): void {
        $common = array(
                'before_widget' => '<section id="%1$s" class="sumx-widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="sumx-widget-title">',
                'after_title'   => '</h2>',
                'before_sidebar' => '',
                'after_sidebar'  => '',
        );

        register_sidebar(
                array_merge(
                        $common,
                        array(
                                'name'        => __( 'Yan sütun', 'styleumax' ),
                                'id'          => 'sidebar-1',
                                'description' => __( 'Arşiv, tek yazı ve sayfa görünümünde sağ tarafta görüntülenir.', 'styleumax' ),
                        )
                )
        );

        $footer_areas = array(
                'footer-1' => __( 'Alt bilgi 1. alan', 'styleumax' ),
                'footer-2' => __( 'Alt bilgi 2. alan', 'styleumax' ),
                'footer-3' => __( 'Alt bilgi 3. alan', 'styleumax' ),
        );

        foreach ( $footer_areas as $id => $name ) {
                register_sidebar(
                        array_merge(
                                $common,
                                array(
                                        /* translators: %d: footer widget area number. */
                                        'name'        => $name,
                                        'id'          => $id,
                                        'description' => __( 'Sayfa alt bilgisindeki üç kolonlu widget alanlarından biri.', 'styleumax' ),
                                )
                        )
                );
        }
}
add_action( 'widgets_init', 'styleumax_widgets_init' );

/**
 * Body classes.
 *
 * @param array $classes Existing classes.
 * @return array
 * @since 1.0.0
 */
function styleumax_body_classes( array $classes ): array {
        if ( is_page_template( 'templates/fullwidth.php' ) ) {
                $classes[] = 'sumx-fullwidth';
        }
        return $classes;
}
add_filter( 'body_class', 'styleumax_body_classes' );

/**
 * Excerpt tweaks: length + "read more" string.
 *
 * @since 1.0.0
 */
function styleumax_excerpt_length(): int {
        return 22;
}
add_filter( 'excerpt_length', 'styleumax_excerpt_length' );

function styleumax_excerpt_more(): string {
        return '&hellip;';
}
add_filter( 'excerpt_more', 'styleumax_excerpt_more' );

/**
 * Fallback menu for primary navigation when no menu is assigned.
 *
 * IMPORTANT: wp_nav_menu() invokes this callback as
 * call_user_func( $args->fallback_cb, (array) $args ) — the arguments
 * ARRAY is always passed as the first parameter (see WP core
 * wp-includes/nav-menu-template.php). A bool-typed first parameter here
 * causes a fatal TypeError ("critical error") on every page whenever no
 * menu is assigned to the location, so the signature must accept the
 * array. The optional $echo parameter is honoured when the callback is
 * invoked manually by custom code.
 *
 * @param array $args Nav menu arguments passed by wp_nav_menu().
 * @param bool  $echo Echo fallback used when invoked manually.
 * @return string Fallback menu HTML ('' when echoed).
 * @since 1.0.0
 * @since 1.1.0 Signature fixed: accepts the (array) $args argument that
 *              wp_nav_menu() always passes; previously bool-typed and
 *              fatal on menu-less installs.
 */
function styleumax_nav_fallback( array $args = array(), bool $echo = true ): string {
        if ( array_key_exists( 'echo', $args ) ) {
                $echo = (bool) $args['echo'];
        }

        $html  = '<ul class="navbar-nav mx-auto mb-2 mb-lg-0">';
        $html .= '<li class="menu-item nav-item"><a class="nav-link' . ( is_front_page() && is_home() ? ' active text-primary fw-bold' : '' ) . '" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Anasayfa', 'styleumax' ) . '</a></li>';
        $html .= '</ul>';

        if ( $echo ) {
                echo wp_kses_post( $html );

                return '';
        }

        return $html;
}

/**
 * Walker: adds Bootstrap 5 dropdown markup to wp_nav_menu.
 *
 * @package Styleumax
 * @since   1.0.0
 */
class Styleumax_Bootstrap_Walker extends Walker_Nav_Menu {

        /**
         * Start level: opens <ul> for dropdowns.
         *
         * @param string   $output Used to append content.
         * @param int      $depth  Depth of the item.
         * @param stdClass $args   Nav args.
         * @return void
         */
        public function start_lvl( &$output, $depth = 0, $args = null ): void {
                $output .= '<ul class="dropdown-menu">';
        }

        /**
         * Start element: adds dropdown classes/toggles.
         *
         * @param string   $output            Used to append content.
         * @param WP_Post  $data_object       Menu item object.
         * @param int      $depth             Depth.
         * @param stdClass $args              Nav args.
         * @param int      $current_object_id Item id.
         * @return void
         */
        public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {
                $menu_item = $data_object;
                $classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
                $has_child = in_array( 'menu-item-has-children', $classes, true );
                $current   = in_array( 'current-menu-item', $classes, true )
                        || in_array( 'current-menu-parent', $classes, true )
                        || in_array( 'current-menu-ancestor', $classes, true );

                $li_classes = array( 'menu-item' );
                if ( $depth > 0 ) {
                        $li_classes[] = 'dropdown-item-item';
                } else {
                        $li_classes[] = 'nav-item';
                }
                if ( $has_child ) {
                        $li_classes[] = 'dropdown';
                }
                if ( $current ) {
                        $li_classes[] = 'current-menu-item';
                }

                $indent = str_repeat( "\t", $depth );
                $output .= $indent . '<li class="' . esc_attr( implode( ' ', array_filter( $li_classes ) ) ) . '">';

                $atts = array(
                        'title'  => $menu_item->attr_title ?: '',
                        'target' => $menu_item->target ?: '',
                        'rel'    => $menu_item->xfn ?: '',
                        'href'   => $menu_item->url ?: '',
                );

                $link_classes = array();
                if ( $depth > 0 ) {
                        $link_classes[] = 'dropdown-item';
                } else {
                        $link_classes[] = 'nav-link';
                }
                if ( $has_child ) {
                        $link_classes[]              = 'dropdown-toggle';
                        $atts['data-bs-toggle']      = 'dropdown';
                        $atts['aria-expanded']       = 'false';
                        $atts['role']                = 'button';
                }
                if ( $current ) {
                        $link_classes[] = 'active';
                }

                $atts['class'] = implode( ' ', $link_classes );

                $attributes = '';
                foreach ( $atts as $attr => $value ) {
                        if ( '' !== (string) $value ) {
                                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                                $attributes .= ' ' . $attr . '="' . $value . '"';
                        }
                }

                $title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );
                $title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

                $output .= '<a' . $attributes . '>' . $title;
                if ( $has_child ) {
                        $output .= ' <span class="dropdown-toggle-icon" aria-hidden="true">&nbsp;<i class="fa-solid fa-chevron-down fa-2xs"></i></span>';
                }
                $output .= '</a>';
        }
}
