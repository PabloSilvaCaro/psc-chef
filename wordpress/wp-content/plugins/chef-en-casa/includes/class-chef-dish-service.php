<?php
/**
 * Dish queries and calculated nutrition.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

final class Chef_Dish_Service {
	public function all(): array {
		global $wpdb;
		$table = $wpdb->prefix . 'chef_dishes';
		$rows  = $wpdb->get_results( "SELECT * FROM {$table} WHERE active = 1 ORDER BY name", ARRAY_A );
		return array_map( array( $this, 'hydrate' ), $rows );
	}

	public function find( int $id ): ?array {
		global $wpdb;
		$table = $wpdb->prefix . 'chef_dishes';
		$row   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d AND active = 1", $id ), ARRAY_A );
		return $row ? $this->hydrate( $row ) : null;
	}

	private function hydrate( array $dish ): array {
		$dish['id']       = (int) $dish['id'];
		$dish['servings'] = max( 1, (int) $dish['servings'] );
		$dish['active']   = (bool) $dish['active'];
		$dish['ingredients'] = $this->ingredients( $dish['id'] );
		$dish['food_types']   = $this->food_types( $dish['id'] );
		$dish['nutrition']    = $this->nutrition( $dish['ingredients'], $dish['servings'] );
		unset( $dish['created_at'], $dish['updated_at'] );
		return $dish;
	}

	private function ingredients( int $dish_id ): array {
		global $wpdb;
		$links = $wpdb->prefix . 'chef_dish_ingredient';
		$items = $wpdb->prefix . 'chef_ingredients';
		return $wpdb->get_results(
			$wpdb->prepare( "SELECT i.id, i.name, i.slug, di.quantity, di.unit_measure, i.calories_100g, i.proteins_100g, i.carbohydrates_100g, i.fats_100g, i.fiber_100g FROM {$links} di INNER JOIN {$items} i ON i.id = di.ingredient_id WHERE di.dish_id = %d ORDER BY i.name", $dish_id ),
			ARRAY_A
		);
	}

	private function food_types( int $dish_id ): array {
		global $wpdb;
		$links = $wpdb->prefix . 'chef_dish_food_type';
		$types = $wpdb->prefix . 'chef_food_types';
		return $wpdb->get_results( $wpdb->prepare( "SELECT t.id, t.name, t.slug FROM {$links} dt INNER JOIN {$types} t ON t.id = dt.food_type_id WHERE dt.dish_id = %d ORDER BY t.name", $dish_id ), ARRAY_A );
	}

	private function nutrition( array $ingredients, int $servings ): array {
		$keys   = array( 'calories', 'proteins', 'carbohydrates', 'fats', 'fiber' );
		$totals = array_fill_keys( $keys, 0.0 );
		foreach ( $ingredients as $ingredient ) {
			$factor = (float) $ingredient['quantity'] / 100;
			foreach ( $keys as $key ) {
				$totals[ $key ] += $factor * (float) $ingredient[ $key . '_100g' ];
			}
		}
		$total       = array_map( static fn( float $value ): float => round( $value, 1 ), $totals );
		$per_serving = array_map( static fn( float $value ): float => round( $value / $servings, 1 ), $totals );
		return array( 'total' => $total, 'per_serving' => $per_serving, 'approximate' => true, 'notice' => __( 'Cálculo aproximado de demostración; no constituye recomendación médica.', 'chef-en-casa' ) );
	}
}

