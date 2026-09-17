<?php
/**
 * Custom widgets: featured posts with thumbnails.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/**
 * Featured posts widget.
 *
 * @package Styleumax
 * @since   1.0.0
 */
class Styleumax_Featured_Widget extends WP_Widget {

        /**
         * Constructor.
         */
        public function __construct() {
                parent::__construct(
                        'styleumax_featured',
                        __( 'Styleumax: Öne çıkan yazılar', 'styleumax' ),
                        array(
                                'description' => __( 'Seçtiğiniz kategoriden başlıklı ve görselli yazı listesi gösterir.', 'styleumax' ),
                                'classname'   => 'sumx-widget',
                        )
                );
        }

        /**
         * Front-end output.
         *
         * NOTE: WP_Widget::widget() accepts untyped ($args, $instance).
         * Typed parameters here would trigger a fatal "must be compatible
         * with WP_Widget::widget()" error on every request, because
         * widgets_init runs before any template renders.
         *
         * @param array $args     Widget args.
         * @param array $instance Saved values.
         * @return void
         */
        public function widget( $args, $instance ): void {
                $title    = (string) ( $instance['title'] ?? __( 'Öne çıkanlar', 'styleumax' ) );
                $count    = max( 1, min( 10, (int) ( $instance['count'] ?? 4 ) ) );
                $category = max( 0, (int) ( $instance['category'] ?? 0 ) );

                $query_args = array(
                        'posts_per_page'      => $count,
                        'ignore_sticky_posts' => true,
                        'no_found_rows'       => true,
                        'post_status'         => 'publish',
                );

                if ( $category > 0 ) {
                        $query_args['cat'] = $category;
                }

                $posts_query = new WP_Query( $query_args );
                if ( ! $posts_query->have_posts() ) {
                        return;
                }

                echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- widget wrapper.
                echo $args['before_title'] . '<i class="fa-solid fa-fire" aria-hidden="true"></i> ' . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- title escaped above.
                ?>

                <?php while ( $posts_query->have_posts() ) : ?>
                        <?php $posts_query->the_post(); ?>
                        <div class="sumx-mini-item">
                                <?php if ( has_post_thumbnail() ) : ?>
                                        <a class="sumx-mini-thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                                <?php the_post_thumbnail( 'styleumax-mini', array( 'loading' => 'lazy' ) ); ?>
                                        </a>
                                <?php endif; ?>
                                <div>
                                        <a class="sumx-mini-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        <span class="sumx-mini-date d-block"><?php echo esc_html( get_the_date() ); ?></span>
                                </div>
                        </div>
                <?php endwhile; ?>

                <?php
                echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- widget wrapper.
                wp_reset_postdata();
        }

        /**
         * Admin form.
         *
         * @param array $instance Saved values.
         * @return void
         */
        public function form( $instance ): void {
                $instance = (array) $instance;
                $title    = (string) ( $instance['title'] ?? __( 'Öne çıkanlar', 'styleumax' ) );
                $count    = (int) ( $instance['count'] ?? 4 );
                $category = (int) ( $instance['category'] ?? 0 );
                ?>
                <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Başlık:', 'styleumax' ); ?></label>
                        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                </p>
                <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Kategori:', 'styleumax' ); ?></label>
                        <?php
                        wp_dropdown_categories(
                                array(
                                        'show_option_none' => __( 'Tüm kategoriler', 'styleumax' ),
                                        'option_none_value' => '0',
                                        'name'             => $this->get_field_name( 'category' ),
                                        'id'               => $this->get_field_id( 'category' ),
                                        'selected'         => $category,
                                        'class'            => 'widefat',
                                )
                        );
                        ?>
                </p>
                <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Yazı sayısı:', 'styleumax' ); ?></label>
                        <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( (string) $count ); ?>">
                </p>
                <?php
        }

        /**
         * Save values.
         *
         * @param array $new_instance New values.
         * @param array $old_instance Old values.
         * @return array
         */
        public function update( $new_instance, $old_instance ): array {
                $new_instance = (array) $new_instance;
                $old_instance = (array) $old_instance;
                $instance            = array();
                $instance['title']   = sanitize_text_field( $new_instance['title'] ?? '' );
                $instance['count']   = max( 1, min( 10, absint( $new_instance['count'] ?? 4 ) ) );
                $instance['category'] = absint( $new_instance['category'] ?? 0 );

                return $instance;
        }
}

/**
 * Register the widget.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_register_widgets(): void {
        register_widget( Styleumax_Featured_Widget::class );
}
add_action( 'widgets_init', 'styleumax_register_widgets' );
