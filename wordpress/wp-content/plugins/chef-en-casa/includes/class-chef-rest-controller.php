<?php
/**
 * Public read-only REST API.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

final class Chef_REST_Controller {
	private string $namespace = 'chef-en-casa/v1';

	public function register_routes(): void {
		register_rest_route( $this->namespace, '/food-types', array( 'methods' => WP_REST_Server::READABLE, 'callback' => array( $this, 'food_types' ), 'permission_callback' => '__return_true' ) );
		register_rest_route(
			$this->namespace,
			'/ingredients',
			array(
				'methods' => WP_REST_Server::READABLE, 'callback' => array( $this, 'ingredients' ), 'permission_callback' => '__return_true',
				'args' => array( 'food_type' => array( 'sanitize_callback' => 'sanitize_title', 'validate_callback' => static fn( $value ): bool => is_string( $value ) ) ),
			)
		);
		register_rest_route( $this->namespace, '/dishes', array( 'methods' => WP_REST_Server::READABLE, 'callback' => array( $this, 'dishes' ), 'permission_callback' => '__return_true' ) );
		register_rest_route(
			$this->namespace,
			'/dishes/(?P<id>[\d]+)',
			array(
				'methods' => WP_REST_Server::READABLE, 'callback' => array( $this, 'dish' ), 'permission_callback' => '__return_true',
				'args' => array( 'id' => array( 'sanitize_callback' => 'absint', 'validate_callback' => static fn( $value ): bool => absint( $value ) > 0 ) ),
			)
		);
		register_rest_route( $this->namespace, '/menus', array( 'methods' => WP_REST_Server::READABLE, 'callback' => array( $this, 'menus' ), 'permission_callback' => '__return_true' ) );
		register_rest_route( $this->namespace, '/menus/(?P<id>[\d]+)', array( 'methods' => WP_REST_Server::READABLE, 'callback' => array( $this, 'menu' ), 'permission_callback' => '__return_true', 'args' => array( 'id' => array( 'sanitize_callback' => 'absint' ) ) ) );
	}

	public function menus(): WP_REST_Response {
		$data = ( new Chef_Menu_Service() )->all();
		return rest_ensure_response( array( 'data' => $data, 'meta' => array( 'count' => count( $data ) ) ) );
	}

	public function menu( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$data = ( new Chef_Menu_Service() )->find( absint( $request['id'] ) );
		return $data ? rest_ensure_response( array( 'data' => $data ) ) : new WP_Error( 'chef_menu_not_found', __( 'Menú no encontrado.', 'chef-en-casa' ), array( 'status' => 404 ) );
	}

	public function dishes(): WP_REST_Response {
		$data = ( new Chef_Dish_Service() )->all();
		return rest_ensure_response( array( 'data' => $data, 'meta' => array( 'count' => count( $data ) ) ) );
	}

	public function dish( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$data = ( new Chef_Dish_Service() )->find( absint( $request['id'] ) );
		return $data ? rest_ensure_response( array( 'data' => $data ) ) : new WP_Error( 'chef_dish_not_found', __( 'Plato no encontrado.', 'chef-en-casa' ), array( 'status' => 404 ) );
	}

	public function food_types(): WP_REST_Response {
		global $wpdb;
		$types = $wpdb->prefix . 'chef_food_types';
		$links = $wpdb->prefix . 'chef_ingredient_food_type';
		$rows  = $wpdb->get_results( "SELECT t.id, t.name, t.slug, t.description, t.active, COUNT(l.ingredient_id) AS ingredient_count FROM {$types} t LEFT JOIN {$links} l ON l.food_type_id = t.id WHERE t.active = 1 GROUP BY t.id ORDER BY t.name", ARRAY_A );
		return rest_ensure_response( array( 'data' => $rows, 'meta' => array( 'count' => count( $rows ) ) ) );
	}

	public function ingredients( WP_REST_Request $request ): WP_REST_Response {
		global $wpdb;
		$items = $wpdb->prefix . 'chef_ingredients';
		$links = $wpdb->prefix . 'chef_ingredient_food_type';
		$types = $wpdb->prefix . 'chef_food_types';
		$slug  = (string) $request->get_param( 'food_type' );

		$sql = "SELECT i.id, i.name, i.slug, i.description, i.unit_measure, i.calories_100g, i.proteins_100g, i.carbohydrates_100g, i.fats_100g, i.fiber_100g FROM {$items} i WHERE i.active = 1";
		if ( '' !== $slug ) {
			$sql = $wpdb->prepare( "SELECT DISTINCT i.id, i.name, i.slug, i.description, i.unit_measure, i.calories_100g, i.proteins_100g, i.carbohydrates_100g, i.fats_100g, i.fiber_100g FROM {$items} i INNER JOIN {$links} l ON l.ingredient_id = i.id INNER JOIN {$types} t ON t.id = l.food_type_id WHERE i.active = 1 AND t.active = 1 AND t.slug = %s", $slug );
		}
		$sql  .= ' ORDER BY i.name';
		$rows  = $wpdb->get_results( $sql, ARRAY_A );

		return rest_ensure_response(
			array(
				'data' => $rows,
				'meta' => array( 'count' => count( $rows ), 'food_type' => $slug ?: null, 'nutrition_notice' => __( 'Valores aproximados y demostrativos; no constituyen recomendación médica.', 'chef-en-casa' ) ),
			)
		);
	}
}
