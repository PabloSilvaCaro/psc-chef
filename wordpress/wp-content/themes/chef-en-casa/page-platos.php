<?php
/** Template Name: Explorador de platos */
defined( 'ABSPATH' ) || exit;
$dishes = class_exists( 'Chef_Dish_Service' ) ? ( new Chef_Dish_Service() )->all() : array();
$theme_uri = get_template_directory_uri();
$images = array(
	'ensalada-citrica-de-pollo' => 'ensalada-citrica-pollo.webp', 'ensalada-mediterranea-quinoa' => 'ensalada-mediterranea-quinoa.webp',
	'pollo-arroz-integral-brocoli' => 'pollo-arroz-brocoli.webp', 'bowl-ligero-de-pavo-y-quinoa' => 'bowl-pavo-quinoa.webp',
	'lentejas-verdes-con-espinaca' => 'lentejas-espinaca.webp', 'pavo-salteado-zapallo-italiano' => 'pavo-zapallo-italiano.webp',
	'ensalada-tibia-papa-brocoli' => 'ensalada-papa-brocoli.webp', 'bowl-garbanzos-lentejas' => 'bowl-garbanzos-lentejas.webp',
	'tofu-dorado-vegetales' => 'tofu-dorado-vegetales.webp',
);
$dish_image = static function ( array $dish ) use ( $images, $theme_uri ): string {
	$file = $images[ $dish['slug'] ] ?? 'hero-cocina-saludable.webp';
	return $theme_uri . '/assets/images/' . ( isset( $images[ $dish['slug'] ] ) ? 'dishes/' : '' ) . $file;
};
get_header();
?>
<main id="main-content" class="dish-explorer-page" data-dish-explorer>
	<header class="dish-banner" style="--dish-hero:url('<?php echo esc_url( $theme_uri . '/assets/images/hero-cocina-saludable.webp' ); ?>')">
		<div class="container dish-banner-content"><p class="kicker">Ingredientes reales · decisiones simples</p><h1>Encuentra tu próximo <strong>plato favorito.</strong></h1><p>Elige una categoría y descubre una preparación a la vez, con toda la información que necesitas.</p></div>
	</header>
	<section class="dish-explorer" aria-labelledby="dish-explorer-title"><div class="container">
		<div class="dish-picker">
			<div><p class="kicker">Explora a tu ritmo</p><h2 id="dish-explorer-title">¿Qué te gustaría comer hoy?</h2></div>
			<div class="dish-picker-fields">
				<label>Tipo de alimentación<select data-dish-category><option value="all">Todas las categorías</option><option value="hipocalorica">Hipocalórica</option><option value="baja-en-grasa">Baja en grasa</option><option value="vegana">Vegana</option></select></label>
				<label>Plato<select data-dish-select><?php foreach ( $dishes as $dish ) : $slugs = wp_list_pluck( $dish['food_types'], 'slug' ); ?><option value="<?php echo esc_attr( $dish['slug'] ); ?>" data-types="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>"><?php echo esc_html( $dish['name'] ); ?></option><?php endforeach; ?></select></label>
			</div>
		</div>
		<div class="dish-panels" aria-live="polite">
		<?php foreach ( $dishes as $index => $dish ) :
			$slugs = wp_list_pluck( $dish['food_types'], 'slug' ); $nutrition = $dish['nutrition']['per_serving'];
			$suggestions = array_values( array_filter( $dishes, static function ( array $candidate ) use ( $dish, $slugs ): bool {
				return $candidate['id'] !== $dish['id'] && count( array_intersect( $slugs, wp_list_pluck( $candidate['food_types'], 'slug' ) ) ) > 0;
			} ) );
			$suggestions = array_slice( $suggestions, 0, 2 );
		?>
			<article class="dish-profile" data-dish-panel="<?php echo esc_attr( $dish['slug'] ); ?>" data-types="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>" <?php echo 0 !== $index ? 'hidden' : ''; ?>>
				<div class="dish-profile-photo"><img src="<?php echo esc_url( $dish_image( $dish ) ); ?>" alt="<?php echo esc_attr( $dish['name'] ); ?>" <?php echo 0 === $index ? '' : 'loading="lazy"'; ?>><span><?php echo esc_html( $dish['preparation_type'] ); ?></span></div>
				<div class="dish-profile-content">
					<div class="dish-types"><?php foreach ( $dish['food_types'] as $type ) : ?><span><?php echo esc_html( $type['name'] ); ?></span><?php endforeach; ?></div>
					<h2><?php echo esc_html( $dish['name'] ); ?></h2><p class="dish-lead"><?php echo esc_html( $dish['description'] ); ?></p>
					<div class="dish-meta"><span><?php echo esc_html( $dish['servings'] ); ?> <?php echo 1 === $dish['servings'] ? 'porción' : 'porciones'; ?></span><span>Valores por porción</span></div>
					<div class="dish-nutrition"><div><b><?php echo esc_html( number_format_i18n( $nutrition['calories'], 0 ) ); ?></b><span>kcal</span></div><div><b><?php echo esc_html( number_format_i18n( $nutrition['proteins'], 1 ) ); ?> g</b><span>proteína</span></div><div><b><?php echo esc_html( number_format_i18n( $nutrition['carbohydrates'], 1 ) ); ?> g</b><span>carbohidratos</span></div><div><b><?php echo esc_html( number_format_i18n( $nutrition['fats'], 1 ) ); ?> g</b><span>grasas</span></div><div><b><?php echo esc_html( number_format_i18n( $nutrition['fiber'], 1 ) ); ?> g</b><span>fibra</span></div></div>
					<div class="dish-recipe-grid"><div><h3>Ingredientes</h3><ul><?php foreach ( $dish['ingredients'] as $ingredient ) : ?><li><span><?php echo esc_html( $ingredient['name'] ); ?></span><b><?php echo esc_html( number_format_i18n( $ingredient['quantity'], 0 ) . ' ' . $ingredient['unit_measure'] ); ?></b></li><?php endforeach; ?></ul></div><div><h3>Preparación</h3><p><?php echo esc_html( $dish['instructions'] ); ?></p><small><?php echo esc_html( $dish['nutrition']['notice'] ); ?></small></div></div>
				</div>
				<?php if ( $suggestions ) : ?><aside class="dish-suggestions"><div><p class="kicker">También podría gustarte</p><h3>Dos opciones relacionadas</h3></div><div class="dish-suggestion-list"><?php foreach ( $suggestions as $suggestion ) : ?><button type="button" data-dish-suggestion="<?php echo esc_attr( $suggestion['slug'] ); ?>"><img src="<?php echo esc_url( $dish_image( $suggestion ) ); ?>" alt="" loading="lazy"><span><?php echo esc_html( $suggestion['name'] ); ?><small>Ver plato →</small></span></button><?php endforeach; ?></div></aside><?php endif; ?>
			</article>
		<?php endforeach; ?>
		</div>
	</div></section>
</main>
<?php get_footer(); ?>
