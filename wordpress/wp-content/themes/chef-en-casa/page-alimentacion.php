<?php
/** Template Name: Guía de alimentación */
defined( 'ABSPATH' ) || exit;

$menus     = class_exists( 'Chef_Menu_Service' ) ? ( new Chef_Menu_Service() )->all() : array();
$dishes    = class_exists( 'Chef_Dish_Service' ) ? ( new Chef_Dish_Service() )->all() : array();
$theme_uri = get_template_directory_uri();
$types     = array(
	'hipocalorica' => array(
		'name' => 'Hipocalórica', 'eyebrow' => 'Ligera, variada y consciente',
		'description' => 'Un enfoque que busca moderar la energía total del menú sin reducir la experiencia a “comer menos”. La variedad, la saciedad y una planificación realista son fundamentales.',
		'useful' => 'Puede ser útil cuando se busca organizar porciones y aumentar la presencia de alimentos de baja densidad energética.',
		'priorities' => array( 'Verduras variadas y alimentos ricos en fibra', 'Proteínas que ayuden a sostener la saciedad', 'Porciones planificadas y preparaciones simples' ),
		'consider' => 'Hipocalórica no significa insuficiente. Las necesidades energéticas cambian según edad, actividad y situación personal.',
		'image' => 'ensalada-mediterranea-quinoa.webp', 'number' => '01',
	),
	'baja-en-grasa' => array(
		'name' => 'Baja en grasa', 'eyebrow' => 'Cocciones simples, sabor auténtico',
		'description' => 'Prioriza ingredientes naturalmente magros y técnicas que requieren poca grasa añadida. El objetivo es moderar, no eliminar por completo un nutriente esencial.',
		'useful' => 'Puede acompañar preferencias personales o planes que requieran controlar la cantidad de grasa total de las preparaciones.',
		'priorities' => array( 'Carnes magras, legumbres y lácteos según preferencia', 'Horno, vapor, plancha o salteados breves', 'Aliños medidos y sabor aportado por hierbas y especias' ),
		'consider' => 'La calidad también importa: algunas grasas insaturadas forman parte de una alimentación equilibrada.',
		'image' => 'pollo-arroz-brocoli.webp', 'number' => '02',
	),
	'vegana' => array(
		'name' => 'Vegana', 'eyebrow' => 'Diversidad que nace de las plantas',
		'description' => 'Excluye ingredientes de origen animal y construye sus platos con vegetales, frutas, cereales, legumbres, semillas y otros alimentos vegetales.',
		'useful' => 'Responde a decisiones éticas, ambientales o personales y puede ofrecer una cocina especialmente diversa y colorida.',
		'priorities' => array( 'Combinar distintas fuentes de proteína vegetal', 'Incluir legumbres, cereales, verduras, frutas y semillas', 'Planificar nutrientes relevantes, incluida la vitamina B12' ),
		'consider' => 'Una alimentación vegana necesita planificación. La suplementación de B12 debe revisarse con un profesional competente.',
		'image' => 'bowl-garbanzos-lentejas.webp', 'number' => '03',
	),
);
get_header();
?>
<main id="main-content" class="food-guide-page">
	<header class="food-guide-hero" style="--food-guide-hero:url('<?php echo esc_url( $theme_uri . '/assets/images/hero-guia-alimentacion.webp' ); ?>')">
		<div class="container food-guide-hero-content"><p class="kicker">Comprender para elegir mejor</p><h1>Alimentación con <strong>propósito.</strong></h1><p>Conoce las características de cada enfoque y descubre alternativas que pueden adaptarse a tus preferencias y objetivos.</p></div>
	</header>

	<section class="food-guide-intro"><div class="container">
		<div class="food-guide-intro-heading"><div><p class="kicker">Una guía, no una regla</p><h2>No existe una única forma de alimentarse bien.</h2></div><p>Los nombres ayudan a ordenar las opciones, pero no reemplazan el contexto personal. Una elección sostenible combina variedad, disfrute y necesidades individuales.</p></div>
		<div class="guide-principles">
			<article><span>01</span><h3>Variedad</h3><p>Alternar ingredientes, colores y preparaciones amplía la experiencia y el aporte de nutrientes.</p></article>
			<article><span>02</span><h3>Equilibrio</h3><p>Importa el conjunto del menú y la frecuencia, no la perfección de una comida aislada.</p></article>
			<article><span>03</span><h3>Personalización</h3><p>Preferencias, cultura, actividad y salud hacen que cada decisión sea diferente.</p></article>
		</div>
	</div></section>

	<nav class="food-guide-jump" aria-label="Tipos de alimentación"><div class="container"><span>Ir a:</span><?php foreach ( $types as $slug => $type ) : ?><a href="#<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $type['name'] ); ?></a><?php endforeach; ?></div></nav>

	<section class="food-type-guides"><div class="container">
	<?php foreach ( $types as $slug => $type ) :
		$menu_count = count( array_filter( $menus, static fn( $menu ) => $menu['food_type']['slug'] === $slug ) );
		$dish_count = count( array_filter( $dishes, static fn( $dish ) => in_array( $slug, wp_list_pluck( $dish['food_types'], 'slug' ), true ) ) );
	?>
		<article id="<?php echo esc_attr( $slug ); ?>" class="food-type-guide food-type-guide-<?php echo esc_attr( $slug ); ?>">
			<div class="food-type-image"><img src="<?php echo esc_url( $theme_uri . '/assets/images/dishes/' . $type['image'] ); ?>" alt="Ejemplo de alimentación <?php echo esc_attr( strtolower( $type['name'] ) ); ?>" loading="lazy"><span><?php echo esc_html( $type['number'] ); ?></span></div>
			<div class="food-type-content">
				<p class="kicker"><?php echo esc_html( $type['eyebrow'] ); ?></p><h2><?php echo esc_html( $type['name'] ); ?></h2><p class="food-type-description"><?php echo esc_html( $type['description'] ); ?></p>
				<div class="food-type-useful"><b>¿Cuándo puede resultar útil?</b><p><?php echo esc_html( $type['useful'] ); ?></p></div>
				<h3>Claves para construir un buen plato</h3><ul class="food-type-keys"><?php foreach ( $type['priorities'] as $priority ) : ?><li><?php echo esc_html( $priority ); ?></li><?php endforeach; ?></ul>
				<div class="food-type-note"><b>Ten en cuenta</b><p><?php echo esc_html( $type['consider'] ); ?></p></div>
				<div class="food-type-footer"><div class="type-stats"><span><b><?php echo esc_html( $menu_count ); ?></b> menús</span><span><b><?php echo esc_html( $dish_count ); ?></b> platos</span></div><div class="type-actions"><a class="button" href="<?php echo esc_url( home_url( '/menus/?tipo=' . $slug ) ); ?>">Explorar menús</a><a class="text-link" href="<?php echo esc_url( home_url( '/platos/?tipo=' . $slug ) ); ?>">Ver platos →</a></div></div>
			</div>
		</article>
	<?php endforeach; ?>
	</div></section>

	<section class="food-guide-comparison"><div class="container"><div class="comparison-heading"><p class="kicker">Vista rápida</p><h2>¿Qué distingue a cada enfoque?</h2></div>
		<div class="comparison-table-wrap"><table><thead><tr><th>Enfoque</th><th>Énfasis principal</th><th>Fuentes habituales</th><th>Técnicas frecuentes</th></tr></thead><tbody>
			<tr><th>Hipocalórica</th><td>Densidad energética moderada</td><td>Verduras, proteínas magras, legumbres</td><td>Horno, vapor y plancha</td></tr>
			<tr><th>Baja en grasa</th><td>Menor grasa total añadida</td><td>Cortes magros, cereales y vegetales</td><td>Vapor, horno y salteado breve</td></tr>
			<tr><th>Vegana</th><td>Ingredientes 100% vegetales</td><td>Legumbres, cereales, tofu y semillas</td><td>Horno, salteado y cocción de legumbres</td></tr>
		</tbody></table></div>
		<p class="food-guide-disclaimer">Información general y educativa. Si tienes una condición de salud, alergias o necesidades nutricionales específicas, consulta a un profesional de la salud.</p>
	</div></section>
</main>
<?php get_footer(); ?>
