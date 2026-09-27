<?php
/**
 * Theme Customizer settings.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'customize_register',
	static function ( WP_Customize_Manager $manager ): void {
		$manager->add_section( 'chef_home', array( 'title' => __( 'Portada Chef en Casa', 'chef-en-casa' ), 'priority' => 30 ) );

		$fields = array(
			'chef_hero_eyebrow' => array( 'label' => __( 'Texto superior', 'chef-en-casa' ), 'default' => __( 'Chef personal · en la comodidad de tu hogar', 'chef-en-casa' ) ),
			'chef_hero_title'   => array( 'label' => __( 'Título principal', 'chef-en-casa' ), 'default' => __( 'Tu tiempo importa.', 'chef-en-casa' ) ),
			'chef_hero_accent'  => array( 'label' => __( 'Título destacado', 'chef-en-casa' ), 'default' => __( 'Nosotros cocinamos.', 'chef-en-casa' ) ),
			'chef_hero_text'    => array( 'label' => __( 'Descripción', 'chef-en-casa' ), 'default' => __( 'Menús preparados en tu cocina, pensados para tu forma de alimentarte y el ritmo real de tu semana.', 'chef-en-casa' ), 'type' => 'textarea' ),
			'chef_cta_label'    => array( 'label' => __( 'Texto del botón', 'chef-en-casa' ), 'default' => __( 'Descubre cómo funciona', 'chef-en-casa' ) ),
		);

		foreach ( $fields as $id => $field ) {
			$manager->add_setting( $id, array( 'default' => $field['default'], 'sanitize_callback' => 'sanitize_text_field' ) );
			$manager->add_control( $id, array( 'section' => 'chef_home', 'label' => $field['label'], 'type' => $field['type'] ?? 'text' ) );
		}
	}
);

