<?php
/**
 * Archive router. Layout priority:
 * 1. Category term meta (set on the category edit screen)
 * 2. Customizer default (Styleumax > Düzenler)
 * Layouts: default list, 2/3/4 column grids, magazine, slider.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="sumx-container sumx-site-main" id="sumx-main-content">
        <?php styleumax_breadcrumbs(); ?>

        <?php styleumax_ad_slot( 'archive_top' ); ?>

        <?php if ( is_category() || is_tag() || is_date() || is_author() ) : ?>
                <section class="sumx-page-lead">
                        <h1><?php the_archive_title(); ?></h1>
                        <?php
                        $styleumax_archive_desc = (string) get_the_archive_description();
                        if ( '' !== $styleumax_archive_desc ) {
                                echo '<div class="sumx-lead-desc">' . wp_kses_post( $styleumax_archive_desc ) . '</div>';
                        }
                        ?>
                </section>
        <?php endif; ?>

        <?php
        $styleumax_layout       = styleumax_get_archive_layout();
        $styleumax_with_sidebar = (bool) styleumax_get_option( 'show_sidebar', true );

        // Slider layout renders full width above the list + sidebar.
        if ( 'slider' === $styleumax_layout && ! is_paged() ) {
                get_template_part( 'template-parts/archive', 'slider' );
                get_template_part( 'template-parts/archive', 'default' );
        } else {
                $styleumax_part = 'archive-' . $styleumax_layout;
                if ( 'mag' === $styleumax_layout && is_paged() ) {
                        $styleumax_part = 'archive-default';
                }

                // 2col/3col/4col map to the shared grid part with a column count.
                if ( preg_match( '/^([234])col$/', $styleumax_layout, $styleumax_matches ) ) {
                        set_query_var( 'sumx_columns', (int) $styleumax_matches[1] );
                        $styleumax_part = 'archive-grid';
                }
                ?>
                <div class="row g-4">
                        <div class="col-lg-8<?php echo $styleumax_with_sidebar ? '' : ' mx-auto'; ?>">
                                <?php get_template_part( 'template-parts/' . $styleumax_part ); ?>
                        </div>
                        <?php if ( $styleumax_with_sidebar ) : ?>
                                <aside class="col-lg-4">
                                        <?php get_sidebar(); ?>
                                </aside>
                        <?php endif; ?>
                </div>
                <?php
        }
        ?>
</div>
<?php
get_footer();
