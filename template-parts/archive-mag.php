<?php
/**
 * Archive layout: magazine. Big lead story + 2 medium stories +
 * remaining list, then pagination.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<?php if ( have_posts() ) : ?>
        <?php
        $styleumax_count = 0;
        while ( have_posts() ) :
                the_post();
                $styleumax_count++;

                if ( 1 === $styleumax_count ) :
                        // Lead story.
                        ?>
                        <article <?php post_class( 'sumx-mag-lead mb-4' ); ?>>
                                <a class="sumx-mag-lead-img" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                        <?php styleumax_thumbnail( 'styleumax-lead' ); ?>
                                </a>
                                <div class="sumx-mag-lead-caption">
                                        <div class="d-flex align-items-center gap-2">
                                                <?php styleumax_category_chip(); ?>
                                                <?php if ( is_sticky() ) : ?>
                                                        <span class="sumx-sticky-flag"><i class="fa-solid fa-thumbtack" aria-hidden="true"></i> <?php esc_html_e( 'Manşet', 'styleumax' ); ?></span>
                                                <?php endif; ?>
                                        </div>
                                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                        <p class="sumx-mag-lead-desc mb-0 d-none d-md-block"><?php echo esc_html( get_the_excerpt() ); ?></p>
                                        <p class="sumx-card-meta mb-0">
                                                <span><i class="fa-regular fa-user" aria-hidden="true"></i><?php the_author(); ?></span>
                                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i><?php echo esc_html( get_the_date() ); ?></span>
                                        </p>
                                </div>
                        </article>

                        <div class="row g-3 g-lg-4 mb-1">
                        <?php elseif ( $styleumax_count >= 2 && $styleumax_count <= 3 ) : ?>
                                <div class="col-12 col-md-6 d-flex">
                                        <article <?php post_class( 'sumx-mag-sub' ); ?>>
                                                <div class="sumx-mag-sub-media">
                                                        <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                                                <?php styleumax_thumbnail( 'styleumax-grid' ); ?>
                                                        </a>
                                                </div>
                                                <div class="p-3">
                                                        <?php styleumax_category_chip(); ?>
                                                        <h3 class="sumx-card-title h6 mt-2 mb-0">
                                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                        </h3>
                                                        <?php styleumax_card_meta(); ?>
                                                </div>
                                        </article>
                                </div>
                        <?php if ( 3 === $styleumax_count ) : ?>
                        </div>
                        <?php endif; ?>

                        <?php else : ?>
                                <article <?php post_class( 'sumx-row-card mb-3' ); ?>>
                                        <div class="sumx-row-card-media">
                                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                                        <?php styleumax_thumbnail( 'styleumax-thumb' ); ?>
                                                </a>
                                        </div>
                                        <div class="sumx-row-card-body">
                                                <h3 class="sumx-card-title h6 mb-0">
                                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h3>
                                                <?php styleumax_card_meta(); ?>
                                        </div>
                                </article>
                        <?php endif; ?>
                <?php endwhile; ?>

                <?php
                // Close the sub grid if only 2 posts were shown.
                if ( 2 === $styleumax_count ) {
                        echo '</div>';
                }
                ?>
        <?php else : ?>
                <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>

<?php styleumax_pagination(); ?>
