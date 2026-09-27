<?php
/** Template Name: Selector de menús */
defined( 'ABSPATH' ) || exit;
$menus = class_exists( 'Chef_Menu_Service' ) ? ( new Chef_Menu_Service() )->all() : array();
$theme_uri = get_template_directory_uri();
$images = array(
	'ensalada-citrica-de-pollo' => 'ensalada-citrica-pollo.webp', 'ensalada-mediterranea-quinoa' => 'ensalada-mediterranea-quinoa.webp',
	'pollo-arroz-integral-brocoli' => 'pollo-arroz-brocoli.webp', 'bowl-ligero-de-pavo-y-quinoa' => 'bowl-pavo-quinoa.webp',
	'lentejas-verdes-con-espinaca' => 'lentejas-espinaca.webp', 'pavo-salteado-zapallo-italiano' => 'pavo-zapallo-italiano.webp',
	'ensalada-tibia-papa-brocoli' => 'ensalada-papa-brocoli.webp', 'bowl-garbanzos-lentejas' => 'bowl-garbanzos-lentejas.webp',
	'tofu-dorado-vegetales' => 'tofu-dorado-vegetales.webp',
);
$dish_image = static function ( array $dish ) use ( $images, $theme_uri ): string {
	return $theme_uri . '/assets/images/dishes/' . ( $images[ $dish['slug'] ] ?? 'ensalada-mediterranea-quinoa.webp' );
};
get_header();
?>
<main id="main-content" class="menu-explorer-page" data-menu-explorer>
	<header class="menu-banner" style="--menu-hero:url('<?php echo esc_url( $theme_uri . '/assets/images/hero-cocina-saludable.webp' ); ?>')">
		<div class="container menu-banner-content"><p class="kicker">Una elección, una comida completa</p><h1>Menús pensados para <strong>compartir y disfrutar.</strong></h1><p>Elige el estilo y la cantidad de personas. Nosotros reunimos preparaciones compatibles en una propuesta clara y equilibrada.</p></div>
	</header>
	<section class="menu-explorer"><div class="container">
		<div class="menu-picker">
			<div><p class="kicker">Arma tu experiencia</p><h2>¿Qué menú necesitas hoy?</h2><p>Comienza por tus preferencias y revisa una propuesta completa a la vez.</p></div>
			<div class="menu-picker-fields">
				<label>Tipo de alimentación<select data-menu-category><option value="all">Todas las categorías</option><option value="hipocalorica">Hipocalórica</option><option value="baja-en-grasa">Baja en grasa</option><option value="vegana">Vegana</option></select></label>
				<label>Modalidad<select data-menu-mode><option value="daily">Menú diario</option><option disabled>Plan semanal · Próximamente</option></select></label>
				<label>Personas<select data-menu-people><option value="1">1 persona</option><option value="2" selected>2 personas</option><option value="4">4 personas</option><option value="6">6 personas</option></select></label>
				<label>Propuesta<select data-menu-select><?php foreach ( $menus as $menu ) : ?><option value="<?php echo esc_attr( $menu['slug'] ); ?>" data-type="<?php echo esc_attr( $menu['food_type']['slug'] ); ?>"><?php echo esc_html( $menu['name'] ); ?></option><?php endforeach; ?></select></label>
			</div>
		</div>
		<div class="menu-plan-panels" aria-live="polite">
		<?php foreach ( $menus as $index => $menu ) :
			$totals = array( 'calories' => 0, 'proteins' => 0, 'carbohydrates' => 0, 'fats' => 0, 'fiber' => 0 );
			foreach ( $menu['dishes'] as $entry ) { foreach ( $totals as $key => $value ) { $totals[ $key ] += (float) $entry['dish']['nutrition']['per_serving'][ $key ]; } }
			$cover = $menu['dishes'][0]['dish'] ?? array();
		?>
			<article class="menu-plan menu-plan-<?php echo esc_attr( $menu['food_type']['slug'] ); ?>" data-menu-panel="<?php echo esc_attr( $menu['slug'] ); ?>" data-type="<?php echo esc_attr( $menu['food_type']['slug'] ); ?>" <?php echo 0 !== $index ? 'hidden' : ''; ?>>
				<div class="menu-plan-cover" style="--menu-cover:url('<?php echo esc_url( $dish_image( $cover ) ); ?>')"><div><span><?php echo esc_html( $menu['food_type']['name'] ); ?></span><h2><?php echo esc_html( $menu['name'] ); ?></h2><p><?php echo esc_html( $menu['description'] ); ?></p></div></div>
				<div class="menu-plan-summary">
					<div class="menu-plan-heading"><div><p class="kicker">Tu propuesta completa</p><h3><?php echo esc_html( count( $menu['dishes'] ) ); ?> preparaciones complementarias</h3></div><span class="menu-for">Para <b data-menu-people-label>2 personas</b></span></div>
					<div class="menu-course-list">
					<?php foreach ( $menu['dishes'] as $entry ) : $dish = $entry['dish']; ?>
						<div class="menu-course"><img src="<?php echo esc_url( $dish_image( $dish ) ); ?>" alt="<?php echo esc_attr( $dish['name'] ); ?>" loading="lazy"><div><small><?php echo esc_html( $entry['course_type'] ); ?></small><h4><?php echo esc_html( $dish['name'] ); ?></h4><p><?php echo esc_html( $dish['description'] ); ?></p><a href="<?php echo esc_url( home_url( '/platos/?plato=' . $dish['slug'] ) ); ?>">Ver detalle del plato →</a></div><span><?php echo esc_html( number_format_i18n( $dish['nutrition']['per_serving']['calories'], 0 ) ); ?> kcal<small>por persona</small></span></div>
					<?php endforeach; ?>
					</div>
					<div class="menu-total">
						<div><p class="kicker">Resumen del menú</p><h3>Total para <span data-menu-summary-people>2 personas</span></h3><small>La suma considera todas las preparaciones incluidas.</small></div>
						<div class="menu-total-metrics">
							<div><b data-menu-nutrient data-base="<?php echo esc_attr( $totals['calories'] ); ?>" data-decimals="0"><?php echo esc_html( number_format_i18n( $totals['calories'] * 2, 0 ) ); ?></b><span>kcal</span></div>
							<div><b data-menu-nutrient data-base="<?php echo esc_attr( $totals['proteins'] ); ?>" data-decimals="1"><?php echo esc_html( number_format_i18n( $totals['proteins'] * 2, 1 ) ); ?></b><span>g proteína</span></div>
							<div><b data-menu-nutrient data-base="<?php echo esc_attr( $totals['carbohydrates'] ); ?>" data-decimals="1"><?php echo esc_html( number_format_i18n( $totals['carbohydrates'] * 2, 1 ) ); ?></b><span>g carbos</span></div>
							<div><b data-menu-nutrient data-base="<?php echo esc_attr( $totals['fats'] ); ?>" data-decimals="1"><?php echo esc_html( number_format_i18n( $totals['fats'] * 2, 1 ) ); ?></b><span>g grasas</span></div>
						</div>
					</div>
					<div class="menu-plan-note"><span>ℹ</span><p>Los valores son aproximados y se actualizan según la cantidad de personas. No constituyen una recomendación médica.</p></div>
				</div>
			</article>
		<?php endforeach; ?>
		</div>
	</div></section>
	<section class="menu-how"><div class="container"><div><p class="kicker">La diferencia está en el conjunto</p><h2>Un menú organiza la experiencia completa.</h2></div><div class="menu-how-steps"><article><span>01</span><h3>Elige</h3><p>Define alimentación y cantidad de personas.</p></article><article><span>02</span><h3>Revisa</h3><p>Conoce cada preparación y el total nutricional.</p></article><article><span>03</span><h3>Disfruta</h3><p>Recibe una propuesta coherente, no platos aislados.</p></article></div></div></section>
</main>
<?php get_footer(); ?>
