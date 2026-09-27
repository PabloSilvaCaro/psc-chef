<?php
/**
 * Menu queries.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

final class Chef_Menu_Service {
	public function all(): array {
		global $wpdb;
		$menus = $wpdb->prefix . 'chef_menus';
		$types = $wpdb->prefix . 'chef_food_types';
		$rows  = $wpdb->get_results( "SELECT m.*,t.name AS food_type_name,t.slug AS food_type_slug FROM {$menus} m INNER JOIN {$types} t ON t.id=m.food_type_id WHERE m.active=1 ORDER BY m.name", ARRAY_A );
		return array_map( array( $this, 'hydrate' ), $rows );
	}

	public function find( int $id ): ?array {
		global $wpdb;
		$menus = $wpdb->prefix . 'chef_menus';
		$types = $wpdb->prefix . 'chef_food_types';
		$row   = $wpdb->get_row( $wpdb->prepare( "SELECT m.*,t.name AS food_type_name,t.slug AS food_type_slug FROM {$menus} m INNER JOIN {$types} t ON t.id=m.food_type_id WHERE m.id=%d AND m.active=1", $id ), ARRAY_A );
		return $row ? $this->hydrate( $row ) : null;
	}

	private function hydrate( array $menu ): array {
		global $wpdb;
		$links   = $wpdb->prefix . 'chef_menu_dish';
		$dishes  = $wpdb->prefix . 'chef_dishes';
		$service = new Chef_Dish_Service();
		$rows    = $wpdb->get_results( $wpdb->prepare( "SELECT md.dish_id,md.course_type,md.sort_order FROM {$links} md INNER JOIN {$dishes} d ON d.id=md.dish_id WHERE md.menu_id=%d AND d.active=1 ORDER BY md.sort_order", $menu['id'] ), ARRAY_A );
		$menu['id'] = (int) $menu['id'];
		$menu['active'] = (bool) $menu['active'];
		$menu['food_type'] = array( 'id' => (int) $menu['food_type_id'], 'name' => $menu['food_type_name'], 'slug' => $menu['food_type_slug'] );
		$menu['dishes'] = array();
		foreach ( $rows as $row ) {
			$dish = $service->find( (int) $row['dish_id'] );
			if ( $dish ) { $menu['dishes'][] = array( 'course_type' => $row['course_type'], 'sort_order' => (int) $row['sort_order'], 'dish' => $dish ); }
		}
		unset( $menu['food_type_id'], $menu['food_type_name'], $menu['food_type_slug'], $menu['created_at'], $menu['updated_at'] );
		return $menu;
	}
}

