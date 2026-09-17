<?php
/**
 * Compact card used for related posts.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'sumx-card col-12 col-md-4 d-flex' ); ?>>
        <div class="sumx-card-media">
                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                        <?php styleumax_thumbnail( 'styleumax-grid' ); ?>
                </a>
                <?php styleumax_category_chip(); ?>
        </div>
        <div class="sumx-card-body">
                <h3 class="sumx-card-title h6">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <?php styleumax_card_meta(); ?>
        </div>
</article>
