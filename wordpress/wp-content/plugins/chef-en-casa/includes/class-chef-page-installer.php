<?php
/**
 * Creates public catalog pages without duplicating existing content.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

final class Chef_Page_Installer {
	public static function install(): void {
		$page_ids = array();
		foreach ( array( 'portada' => 'Portada', 'menus' => 'Menús', 'platos' => 'Platos', 'alimentacion' => 'Tipos de alimentación' ) as $slug => $title ) {
			if ( ! get_page_by_path( $slug, OBJECT, 'page' ) ) {
				$page_ids[ $slug ] = wp_insert_post( array( 'post_title' => $title, 'post_name' => $slug, 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => '' ) );
			} else {
				$page_ids[ $slug ] = get_page_by_path( $slug, OBJECT, 'page' )->ID;
			}
		}
		if ( ! empty( $page_ids['portada'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', (int) $page_ids['portada'] );
		}
	}
}
