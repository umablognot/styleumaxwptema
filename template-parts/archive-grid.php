<?php
/**
 * Archive layout: N-column post grid.
 * Expects args: 'columns' => 2|3|4.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

$styleumax_columns = get_query_var( 'sumx_columns', (int) ( $args['columns'] ?? 2 ) );
$styleumax_columns = in_array( (int) $styleumax_columns, array( 2, 3, 4 ), true ) ? (int) $styleumax_columns : 2;

$styleumax_classes = array(
        2 => 'col-12 col-md-6',
        3 => 'col-12 col-md-6 col-xl-4',
        4 => 'col-6 col-md-4 col-xl-3',
);
?>
<div class="row g-3 g-lg-4">
        <?php if ( have_posts() ) : ?>
                <?php
                while ( have_posts() ) :
                        the_post();
                        ?>
                        <div class="<?php echo esc_attr( $styleumax_classes[ $styleumax_columns ] ); ?> d-flex">
                                <?php get_template_part( 'template-parts/content' ); ?>
                        </div>
                        <?php
                endwhile;
                ?>
        <?php else : ?>
                <div class="col-12">
                        <?php get_template_part( 'template-parts/content', 'none' ); ?>
                </div>
        <?php endif; ?>
</div>

<?php styleumax_pagination(); ?>
