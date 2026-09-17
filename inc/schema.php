<?php
/**
 * Structured data (JSON-LD) and OpenGraph / Twitter meta.
 * Automatically steps aside when a dedicated SEO plugin is active.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/**
 * Detect well known SEO plugins.
 *
 * @return bool
 * @since 1.0.0
 */
function styleumax_seo_plugin_active(): bool {
        return (bool) apply_filters(
                'styleumax_seo_plugin_active',
                defined( 'WPSEO_VERSION' )          // Yoast SEO.
                || defined( 'RANK_MATH_VERSION' )   // Rank Math.
                || defined( 'AIOSEO_VERSION' )      // All in One SEO.
                || defined( 'SEOPRESS_VERSION' )    // SEOPress.
                || class_exists( 'The_SEO_Framework' ) // The SEO Framework.
        );
}

/**
 * Output JSON-LD structured data.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_json_ld(): void {
        if ( styleumax_seo_plugin_active() ) {
                return;
        }

        $graph = array();

        // WebSite node (front page + always available fallback).
        $graph[] = array(
                '@type'     => 'WebSite',
                '@id'       => home_url( '/#website' ),
                'url'       => home_url( '/' ),
                'name'      => get_bloginfo( 'name' ),
                'publisher' => array( '@id' => home_url( '/#organization' ) ),
                'potentialAction' => array(
                        '@type'       => 'SearchAction',
                        'target'      => array(
                                '@type' => 'EntryPoint',
                                'urlTemplate' => home_url( '/?s={search_term_string}' ),
                        ),
                        'query-input' => 'required name=search_term_string',
                ),
                'inLanguage' => get_bloginfo( 'language' ),
        );

        if ( is_singular( 'post' ) ) {
                $post_id  = (int) get_the_ID();
                $wordsite = home_url( '/' );

                $image = '';
                if ( has_post_thumbnail( $post_id ) ) {
                        $thumb = wp_get_attachment_image_src( (int) get_post_thumbnail_id( $post_id ), 'styleumax-lead' );
                        if ( is_array( $thumb ) ) {
                                $image = $thumb[0];
                        }
                }

                $article = array(
                        '@type'            => 'NewsArticle',
                        '@id'              => get_permalink( $post_id ) . '#article',
                        'isPartOf'         => array( '@id' => $wordsite . '#website' ),
                        'mainEntityOfPage' => get_permalink( $post_id ),
                        'headline'         => wp_trim_words( (string) get_the_title( $post_id ), 110, '' ),
                        'datePublished'    => get_the_date( 'c', $post_id ),
                        'dateModified'     => get_the_modified_date( 'c', $post_id ),
                        'author'           => array(
                                '@type' => 'Person',
                                'name'  => (string) get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ),
                                'url'   => get_author_posts_url( (int) get_post_field( 'post_author', $post_id ) ),
                        ),
                        'publisher'        => array( '@id' => $wordsite . '#organization' ),
                        'inLanguage'       => get_bloginfo( 'language' ),
                );

                if ( '' !== $image ) {
                        $article['image'] = esc_url_raw( $image );
                }

                $graph[] = $article;

                // Breadcrumb list for single posts.
                $breadcrumbs = array( array( 'name' => __( 'Anasayfa', 'styleumax' ), 'item' => home_url( '/' ) ) );
                $cats        = get_the_category( $post_id );
                if ( isset( $cats[0] ) && ! is_wp_error( get_term_link( (int) $cats[0]->term_id ) ) ) {
                        $breadcrumbs[] = array(
                                'name' => $cats[0]->name,
                                'item' => (string) get_term_link( (int) $cats[0]->term_id ),
                        );
                }
                $breadcrumbs[] = array( 'name' => (string) get_the_title( $post_id ), 'item' => get_permalink( $post_id ) );

                $graph[] = array(
                        '@type'           => 'BreadcrumbList',
                        '@id'             => get_permalink( $post_id ) . '#breadcrumb',
                        'itemListElement' => array_map(
                                static function ( array $crumb, int $i ): array {
                                        return array(
                                                '@type'    => 'ListItem',
                                                'position' => $i + 1,
                                                'name'     => $crumb['name'],
                                                'item'     => $crumb['item'],
                                        );
                                },
                                $breadcrumbs,
                                array_keys( $breadcrumbs )
                        ),
                );
        }

        // Organization node when a logo exists.
        $logo_id = (int) get_theme_mod( 'custom_logo' );
        $graph[] = array(
                '@type' => 'Organization',
                '@id'   => home_url( '/#organization' ),
                'name'  => get_bloginfo( 'name' ),
                'url'   => home_url( '/' ),
                'logo'  => $logo_id > 0 ? (string) wp_get_attachment_image_url( $logo_id, 'full' ) : '',
        );

        $graph = apply_filters( 'styleumax_json_ld_graph', $graph );

        echo '<script type="application/ld+json">' . wp_json_encode(
                array(
                        '@context' => 'https://schema.org',
                        '@graph'   => $graph,
                ),
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded above.
}
add_action( 'wp_head', 'styleumax_json_ld', 20 );

/**
 * OpenGraph + Twitter Card meta tags.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_social_meta(): void {
        if ( styleumax_seo_plugin_active() ) {
                return;
        }

        $title       = wp_get_document_title();
        $description = '';
        $image       = '';
        $url         = home_url( '/' );
        $type        = 'website';
        $site_name   = get_bloginfo( 'name' );

        if ( is_singular() ) {
                $post_id = (int) get_the_ID();
                $url     = get_permalink( $post_id );
                $type    = 'article';

                $excerpt = get_the_excerpt( $post_id );
                if ( '' !== $excerpt ) {
                        $description = $excerpt;
                }

                if ( has_post_thumbnail( $post_id ) ) {
                        $thumb = wp_get_attachment_image_src( (int) get_post_thumbnail_id( $post_id ), 'styleumax-lead' );
                        if ( is_array( $thumb ) ) {
                                $image = $thumb[0];
                        }
                }
        } elseif ( is_category() || is_tag() || is_tax() ) {
                $term_url    = get_term_link( get_queried_object() );
                $url         = is_string( $term_url ) ? $term_url : home_url( '/' );
                $description = term_description();
                $title       = single_term_title( '', false ) . ' - ' . $site_name;
        } elseif ( is_search() ) {
                /* translators: %s: search query. */
                $title = sprintf( __( 'Arama sonuçları: %s', 'styleumax' ), get_search_query() );
        }

        // Fallback description from the blog tagline.
        if ( '' === trim( (string) $description ) ) {
                $description = get_bloginfo( 'description' );
        }

        // Fallback image from site icon.
        if ( '' === $image ) {
                $icon_id = (int) get_option( 'site_icon' );
                if ( $icon_id > 0 ) {
                        $image = (string) wp_get_attachment_image_url( $icon_id, 'medium' );
                }
        }

        $tags = array(
                'og:locale'     => get_locale(),
                'og:type'       => $type,
                'og:title'      => $title,
                'og:site_name'  => $site_name,
                'og:url'        => $url,
                'og:description' => $description,
        );

        if ( '' !== $image ) {
                $tags['og:image'] = $image;
        }

        if ( is_singular( 'post' ) ) {
                $tags['article:published_time'] = get_the_date( 'c' );
                $tags['article:modified_time']  = get_the_modified_date( 'c' );
        }

        $tags['twitter:card']        = '' !== $image ? 'summary_large_image' : 'summary';
        $tags['twitter:title']       = $title;
        $tags['twitter:description'] = $description;

        if ( '' !== $image ) {
                $tags['twitter:image'] = $image;
        }

        // Derive the twitter:site handle from the X profile URL when present.
        if ( preg_match( '#(?:x|twitter)\.com/([A-Za-z0-9_]+)#', (string) styleumax_get_option( 'social_x', '' ), $m ) ) {
                $tags['twitter:site'] = '@' . $m[1];
        }

        $tags = apply_filters( 'styleumax_social_meta_tags', $tags );

        foreach ( $tags as $property => $value ) {
                if ( '' === (string) $value ) {
                        continue;
                }
                printf(
                        '<meta %1$s="%2$s" content="%3$s">' . "\n",
                        esc_attr( 0 === strpos( (string) $property, 'twitter:' ) ? 'name' : 'property' ),
                        esc_attr( (string) $property ),
                        esc_attr( (string) $value )
                );
        }
}
add_action( 'wp_head', 'styleumax_social_meta', 5 );
