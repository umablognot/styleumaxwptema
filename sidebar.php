<?php
/**
 * Sidebar area (wrapped in the sticky container).
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<div class="sumx-sidebar">
        <?php styleumax_ad_slot( 'sidebar_top' ); ?>

        <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
                <?php dynamic_sidebar( 'sidebar-1' ); ?>
        <?php else : ?>
                <section class="sumx-widget">
                        <h2 class="sumx-widget-title"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> <?php esc_html_e( 'Arama', 'styleumax' ); ?></h2>
                        <?php get_search_form(); ?>
                </section>
                <section class="sumx-widget">
                        <h2 class="sumx-widget-title"><i class="fa-regular fa-clock" aria-hidden="true"></i> <?php esc_html_e( 'Son yazılar', 'styleumax' ); ?></h2>
                        <ul>
                                <?php
                                $styleumax_recent = wp_get_recent_posts(
                                        array(
                                                'numberposts' => 5,
                                                'post_status' => 'publish',
                                        )
                                );
                                foreach ( $styleumax_recent as $styleumax_post ) {
                                        printf(
                                                '<li><a href="%1$s">%2$s</a><span class="post-date">%3$s</span></li>',
                                                esc_url( (string) get_permalink( $styleumax_post['ID'] ) ),
                                                esc_html( $styleumax_post['post_title'] ),
                                                esc_html( date_i18n( get_option( 'date_format' ), strtotime( (string) $styleumax_post['post_date'] ) ) )
                                        );
                                }
                                ?>
                        </ul>
                </section>
        <?php endif; ?>

        <?php styleumax_ad_slot( 'sidebar_bot' ); ?>
</div>
