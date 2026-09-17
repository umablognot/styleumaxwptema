<?php
/**
 * Single post template.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

get_header();

// Reading progress bar (decorative; animated by assets/js/styleumax.js).
if ( styleumax_get_option( 'show_progress', true ) ) {
        echo '<div class="sumx-progress" aria-hidden="true"><span></span></div>';
}

while ( have_posts() ) :
        the_post();
        ?>
        <div class="sumx-container sumx-site-main" id="sumx-main-content">
                <?php styleumax_breadcrumbs(); ?>

                <div class="row g-4">
                        <div class="col-lg-8">
                                <article <?php post_class(); ?>>
                                        <header class="sumx-single-header">
                                                <?php styleumax_category_chip(); ?>

                                                <h1><?php the_title(); ?></h1>

                                                <?php
                                                $styleumax_subtitle = (string) get_post_meta( (int) get_the_ID(), 'styleumax_subtitle', true );
                                                if ( '' !== $styleumax_subtitle ) :
                                                        ?>
                                                        <p class="sumx-subtitle"><?php echo esc_html( $styleumax_subtitle ); ?></p>
                                                <?php endif; ?>

                                                <?php styleumax_single_meta(); ?>
                                        </header>

                                        <?php if ( has_post_thumbnail() ) : ?>
                                                <figure class="sumx-single-thumb mb-0">
                                                        <?php the_post_thumbnail( 'styleumax-slider', array( 'class' => 'img-fluid' ) ); ?>
                                                </figure>
                                        <?php endif; ?>

                                        <div class="sumx-entry">
                                                <?php the_content(); ?>
                                                <?php
                                                wp_link_pages(
                                                        array(
                                                                'before' => '<nav class="page-links d-flex gap-2 align-items-center"><span class="fw-bold">' . esc_html__( 'Sayfalar:', 'styleumax' ) . '</span>',
                                                                'after'  => '</nav>',
                                                        )
                                                );
                                                ?>
                                        </div>

                                        <?php styleumax_the_tags(); ?>

                                        <?php styleumax_author_box(); ?>

                                        <nav class="sumx-postnav" aria-label="<?php esc_attr_e( 'Yazı dolaşımı', 'styleumax' ); ?>">
                                                <?php
                                                $styleumax_prev = get_previous_post();
                                                $styleumax_next = get_next_post();

                                                if ( $styleumax_prev instanceof WP_Post ) {
                                                        printf(
                                                                '<a href="%1$s"><span class="sumx-postnav-label"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> %2$s</span><span class="sumx-postnav-title">%3$s</span></a>',
                                                                esc_url( get_permalink( $styleumax_prev ) ),
                                                                esc_html__( 'Önceki yazı', 'styleumax' ),
                                                                esc_html( (string) get_the_title( $styleumax_prev ) )
                                                        );
                                                }
                                                if ( $styleumax_next instanceof WP_Post ) {
                                                        printf(
                                                                '<a href="%1$s" class="sumx-postnav-next"><span class="sumx-postnav-label">%2$s <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span><span class="sumx-postnav-title">%3$s</span></a>',
                                                                esc_url( get_permalink( $styleumax_next ) ),
                                                                esc_html__( 'Sonraki yazı', 'styleumax' ),
                                                                esc_html( (string) get_the_title( $styleumax_next ) )
                                                        );
                                                }
                                                ?>
                                        </nav>

                                        <?php styleumax_ad_slot( 'single_bottom' ); ?>

                                        <?php edit_post_link( __( 'Düzenle', 'styleumax' ), '<span class="sumx-edit-link">', '</span>' ); ?>

                                        <?php
                                        $styleumax_related = styleumax_get_related_posts( 3 );
                                        if ( array() !== $styleumax_related ) :
                                                ?>
                                                <section class="sumx-related" aria-label="<?php esc_attr_e( 'İlgili yazılar', 'styleumax' ); ?>">
                                                        <h2><i class="fa-solid fa-layer-group" aria-hidden="true"></i> <?php esc_html_e( 'İlgili yazılar', 'styleumax' ); ?></h2>
                                                        <div class="row g-3">
                                                                <?php
                                                                global $post;
                                                                foreach ( $styleumax_related as $post ) {
                                                                        setup_postdata( $post );
                                                                        get_template_part( 'template-parts/content', 'mini' );
                                                                }
                                                                wp_reset_postdata();
                                                                ?>
                                                        </div>
                                                </section>
                                        <?php endif; ?>
                                </article>

                                <section class="sumx-comments">
                                        <?php comments_template(); ?>
                                </section>
                        </div>

                        <?php if ( styleumax_get_option( 'show_sidebar', true ) ) : ?>
                                <aside class="col-lg-4">
                                        <?php get_sidebar(); ?>
                                </aside>
                        <?php endif; ?>
                </div>
        </div>
        <?php
endwhile;

get_footer();
