<?php
/**
 * Plantilla provisional de la Capa 1.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<main>
		<h1><?php esc_html_e( 'Chef en Casa', 'chef-en-casa' ); ?></h1>
		<p><?php esc_html_e( 'La base WordPress está lista. La interfaz se incorporará en una capa posterior.', 'chef-en-casa' ); ?></p>
	</main>
	<?php wp_footer(); ?>
</body>
</html>

