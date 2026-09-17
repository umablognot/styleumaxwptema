<?php
/**
 * Accessible search form (W3C valid: label + search input type).
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<form role="search" method="get" class="sumx-search-form search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="visually-hidden" for="sumx-search-field"><?php esc_html_e( 'Aranacak kelime', 'styleumax' ); ?></label>
	<div class="input-group">
		<input type="search" id="sumx-search-field" class="form-control" name="s"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			placeholder="<?php echo esc_attr__( 'Anahtar kelime yazın...', 'styleumax' ); ?>">
		<button type="submit" class="btn btn-primary" aria-label="<?php esc_attr_e( 'Ara', 'styleumax' ); ?>">
			<i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
		</button>
	</div>
</form>
