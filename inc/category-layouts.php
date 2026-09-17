<?php
/**
 * Per-category archive layout selection stored as term meta.
 * Replaces the old "comma separated category IDs" option style of
 * the original theme with a modern per-term field.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/**
 * Available archive layouts shared by the Customizer control,
 * the term meta field and the archive router.
 *
 * @return array<string,string>
 * @since 1.0.0
 */
function styleumax_layout_choices(): array {
        return array(
                'default' => __( 'Liste (yatay kartlar)', 'styleumax' ),
                '2col'    => __( '2 kolon ızgara', 'styleumax' ),
                '3col'    => __( '3 kolon ızgara', 'styleumax' ),
                '4col'    => __( '4 kolon ızgara', 'styleumax' ),
                'mag'     => __( 'Dergi (büyük manşet + kolonlar)', 'styleumax' ),
                'slider'  => __( 'Slayt (carousel + liste)', 'styleumax' ),
        );
}

/**
 * Resolve the layout for the current archive request.
 * Priority: category term meta > global Customizer default.
 *
 * @return string One of styleumax_layout_choices() keys.
 * @since 1.0.0
 */
function styleumax_get_archive_layout(): string {
        $choices = array_keys( styleumax_layout_choices() );

        if ( is_category() ) {
                $term_layout = get_term_meta( get_queried_object_id(), 'styleumax_layout', true );
                if ( is_string( $term_layout ) && in_array( $term_layout, $choices, true ) ) {
                        return $term_layout;
                }
        }

        $global = styleumax_get_option( 'archive_layout', 'default' );

        return in_array( $global, $choices, true ) ? $global : 'default';
}

/**
 * Add the layout selector on the "Add new category" screen.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_category_add_fields(): void {
        ?>
        <div class="form-field">
                <label for="styleumax_layout"><?php esc_html_e( 'Arşiv düzeni', 'styleumax' ); ?></label>
                <?php styleumax_layout_select( '' ); ?>
                <p><?php esc_html_e( 'Bu kategorideki yazı listesi hangi düzende görünsün? Boş bırakılırsa genel ayar kullanılır.', 'styleumax' ); ?></p>
        </div>
        <?php
}
add_action( 'category_add_form_fields', 'styleumax_category_add_fields' );

/**
 * Add the layout selector on the "Edit category" screen.
 *
 * @param WP_Term $term Current term.
 * @return void
 * @since 1.0.0
 */
function styleumax_category_edit_fields( WP_Term $term ): void {
        $value = get_term_meta( $term->term_id, 'styleumax_layout', true );
        $value = is_string( $value ) ? $value : '';
        ?>
        <tr class="form-field">
                <th scope="row"><label for="styleumax_layout"><?php esc_html_e( 'Arşiv düzeni', 'styleumax' ); ?></label></th>
                <td>
                        <?php styleumax_layout_select( $value ); ?>
                        <p class="description"><?php esc_html_e( 'Bu kategorideki yazı listesi hangi düzende görünsün?', 'styleumax' ); ?></p>
                </td>
        </tr>
        <?php
}
add_action( 'category_edit_form_fields', 'styleumax_category_edit_fields' );

/**
 * Print the layout <select>.
 *
 * @param string $selected Current value.
 * @return void
 * @since 1.0.0
 */
function styleumax_layout_select( string $selected ): void {
        $selected = in_array( $selected, array_keys( styleumax_layout_choices() ), true ) ? $selected : '';
        ?>
        <select name="styleumax_layout" id="styleumax_layout">
                <option value="" <?php selected( $selected, '' ); ?>><?php esc_html_e( 'Global ayar', 'styleumax' ); ?></option>
                <?php foreach ( styleumax_layout_choices() as $key => $label ) : ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $selected, $key ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
        </select>
        <?php
}

/**
 * Save the term meta on create / edit.
 *
 * @param int $term_id Term ID.
 * @return void
 * @since 1.0.0
 */
function styleumax_save_category_layout( int $term_id ): void {
        if ( ! current_user_can( 'manage_categories' ) ) {
                return;
        }

        $nonce = (string) ( $_POST['styleumax_layout_nonce'] ?? '' );
        $raw   = isset( $_POST['styleumax_layout'] ) ? sanitize_key( wp_unslash( (string) $_POST['styleumax_layout'] ) ) : '';

        // New terms use the literal "new" action; edited terms use their ID.
        $doing_edit  = doing_action( 'edit_category' );
        $nonce_action = $doing_edit ? 'styleumax_save_layout_' . $term_id : 'styleumax_save_layout_new';

        // Boş veya dolu girdi fark etmeksizin aynı nonce kanıtı gerekir;
        // doğrulama yoksa kayıt/değişiklik yapılmaz.
        if ( ! wp_verify_nonce( $nonce, $nonce_action ) ) {
                return;
        }

        if ( '' === $raw ) {
                delete_term_meta( $term_id, 'styleumax_layout' );
                return;
        }

        if ( array_key_exists( $raw, styleumax_layout_choices() ) ) {
                update_term_meta( $term_id, 'styleumax_layout', $raw );
        }
}
add_action( 'edit_category', 'styleumax_save_category_layout' );
add_action( 'create_category', 'styleumax_save_category_layout' );

/**
 * Nonce for the term layout fields. Rendered in both forms via JS-free
 * hidden inputs; created here so both screens share one name.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_category_layout_nonce(): void {
        $term_id = isset( $_GET['tag_ID'] ) ? absint( wp_unslash( $_GET['tag_ID'] ) ) : 0;
        wp_nonce_field( 'styleumax_save_layout_' . ( $term_id > 0 ? $term_id : 'new' ), 'styleumax_layout_nonce' );
}
add_action( 'category_edit_form', 'styleumax_category_layout_nonce', 1 );
add_action( 'category_add_form', 'styleumax_category_layout_nonce', 1 );
