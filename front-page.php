<?php
/**
 * Front page: featured slider (sticky posts first) + magazine lead +
 * latest posts grid + optional sidebar.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="sumx-container sumx-site-main" id="sumx-main-content">

        <?php if ( styleumax_get_option( 'show_hero', true ) ) : ?>
                <section class="sumx-hero" aria-labelledby="sumx-hero-title">
                        <div class="sumx-hero-inner">
                                <p class="sumx-hero-kicker">
                                        <i class="fa-solid fa-bolt" aria-hidden="true"></i>
                                        <?php echo esc_html( '' !== (string) get_bloginfo( 'description', 'display' ) ? get_bloginfo( 'description', 'display' ) : date_i18n( 'j F Y' ) ); ?>
                                </p>
                                <h1 id="sumx-hero-title" class="sumx-hero-title">
                                        <?php echo esc_html( is_home() ? (string) get_bloginfo( 'name' ) : (string) get_the_title( (int) get_queried_object_id() ) ); ?>
                                </h1>
                                <p class="sumx-hero-text">
                                        <?php
                                        /* translators: %d: reading-time style kicker for the hero CTA. */
                                        esc_html_e( 'Günün öne çıkan haberleri, manşetler ve en güncel içerikler aşağıda.', 'styleumax' );
                                        ?>
                                </p>
                                <div class="sumx-hero-actions">
                                        <a class="btn btn-primary sumx-btn-lg" href="#sumx-latest">
                                                <i class="fa-solid fa-arrow-down me-2" aria-hidden="true"></i><?php esc_html_e( 'Son yazılara göz at', 'styleumax' ); ?>
                                        </a>
                                        <?php $styleumax_hero_contact = (string) styleumax_get_option( 'contact_url', '' ); ?>
                                        <?php if ( '' !== $styleumax_hero_contact ) : ?>
                                                <a class="btn sumx-btn-ghost sumx-btn-lg" href="<?php echo esc_url( $styleumax_hero_contact ); ?>">
                                                        <i class="fa-regular fa-envelope me-2" aria-hidden="true"></i><?php esc_html_e( 'Bize ulaşın', 'styleumax' ); ?>
                                                </a>
                                        <?php endif; ?>
                                </div>
                        </div>
                </section>
        <?php endif; ?>

        <?php
        // Featured slider: sticky posts, falling back to the newest posts.
        $styleumax_featured = new WP_Query(
                array(
                        'post__in'            => get_option( 'sticky_posts' ),
                        'ignore_sticky_posts' => false,
                        'posts_per_page'      => 5,
                        'no_found_rows'       => true,
                        'post_status'         => 'publish',
                )
        );

        if ( ! $styleumax_featured->have_posts() ) {
                $styleumax_featured = new WP_Query(
                        array(
                                'posts_per_page' => 5,
                                'no_found_rows'  => true,
                                'post_status'    => 'publish',
                        )
                );
        }
        ?>

        <?php if ( $styleumax_featured->have_posts() ) : ?>
                <div class="row g-4 mb-4">
                        <div class="col-lg-8">
                                <?php
                                get_template_part(
                                        'template-parts/slider',
                                        null,
                                        array(
                                                'query' => $styleumax_featured,
                                                'id'    => 'sumx-front-slider',
                                        )
                                );
                                wp_reset_postdata();
                                ?>
                        </div>
                        <div class="col-lg-4">
                                <?php
                                // Latest headlines in the side column (skip slider posts).
                                $styleumax_slider_ids = wp_list_pluck( $styleumax_featured->posts, 'ID' );
                                $styleumax_headlines  = new WP_Query(
                                        array(
                                                'posts_per_page'      => 3,
                                                'no_found_rows'       => true,
                                                'ignore_sticky_posts' => true,
                                                'post_status'         => 'publish',
                                                'post__not_in'        => $styleumax_slider_ids,
                                        )
                                );

                                if ( $styleumax_headlines->have_posts() ) :
                                        while ( $styleumax_headlines->have_posts() ) :
                                                $styleumax_headlines->the_post();
                                                ?>
                                                <article class="sumx-row-card mb-3">
                                                        <a class="sumx-row-card-media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                                                <?php styleumax_thumbnail( 'styleumax-thumb', 'sumx-fill' ); ?>
                                                        </a>
                                                        <div class="sumx-row-card-body">
                                                                <?php styleumax_category_chip(); ?>
                                                                <h3 class="sumx-card-title h6 mb-0">
                                                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                                </h3>
                                                        </div>
                                                </article>
                                                <?php
                                        endwhile;
                                        wp_reset_postdata();
                                endif;
                                ?>
                        </div>
                </div>
        <?php endif; ?>

        <?php styleumax_ad_slot( 'archive_top' ); ?>

        <div class="row g-4" id="sumx-latest">
                <div class="col-lg-8">
                        <h2 class="sumx-section-title">
                                <i class="fa-solid fa-fire" aria-hidden="true"></i>
                                <?php esc_html_e( 'Son yazılar', 'styleumax' ); ?>
                        </h2>

                        <?php
                        /*
                         * "Son yazılar" ızgarası.
                         * - Ana sayfa en son yazıları listeliyorsa (varsayılan kurulum)
                         *   ana döngü üzerinde paylaşılan 2 kolon ızgara parçası çalışır;
                         *   kolon sayısı parçaya args ile verilir.
                         * - Ön sayfa statik bir sayfaysa ana döngü sayfanın kendisini
                         *   tuttuğundan son yazılar özel bir sorguyla listelenir.
                         * Sürüm notu: 1.0.1 ve öncesinde buradaki çağrı
                         * get_template_part( 'template-parts/archive', '2col' ) idi; bu çağrı
                         * var olmayan archive-2col.php dosyasına çözüldüğü için ızgara
                         * sessizce boş render oluyordu.
                         */
                        if ( is_home() ) {
                                get_template_part(
                                        'template-parts/archive-grid',
                                        null,
                                        array( 'columns' => 2 )
                                );
                        } else {
                                $styleumax_latest = new WP_Query(
                                        array(
                                                'post_type'           => 'post',
                                                'posts_per_page'      => 6,
                                                'ignore_sticky_posts' => true,
                                                'post_status'         => 'publish',
                                                'no_found_rows'       => true,
                                        )
                                );

                                if ( $styleumax_latest->have_posts() ) {
                                        ?>
                                        <div class="row g-3 g-lg-4">
                                                <?php
                                                while ( $styleumax_latest->have_posts() ) :
                                                        $styleumax_latest->the_post();
                                                        ?>
                                                        <div class="col-12 col-md-6 d-flex">
                                                                <?php get_template_part( 'template-parts/content' ); ?>
                                                        </div>
                                                        <?php
                                                endwhile;
                                                ?>
                                        </div>
                                        <?php
                                        wp_reset_postdata();
                                } else {
                                        get_template_part( 'template-parts/content', 'none' );
                                }
                        }
                        ?>
                </div>

                <?php if ( styleumax_get_option( 'show_sidebar', true ) ) : ?>
                        <aside class="col-lg-4">
                                <?php get_sidebar(); ?>
                        </aside>
                <?php endif; ?>
        </div>
</div>
<?php
get_footer();
