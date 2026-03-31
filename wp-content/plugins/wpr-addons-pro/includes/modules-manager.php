<?php
namespace WprAddonsPro;
use WprAddonsPro\Classes\Pro_Modules;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Manager {

	public function __construct() {
    	$modules = Pro_Modules::get_available_modules( Pro_Modules::get_registered_modules() );

    	if ( empty(Pro_Modules::get_available_modules( Pro_Modules::get_registered_modules() )) && false === get_option('wpr-element-toggle-all') ) {
    		$modules =  Pro_Modules::get_registered_modules();
    	}

		foreach ( $modules as $module ) {
			$class_name = str_replace( '-', ' ', $module );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			$class_name::instance();
		}


		// Theme Builder Modules
    	$theme_builder_modules = Pro_Modules::get_available_modules( Pro_Modules::get_theme_builder_modules() );

    	if ( empty(Pro_Modules::get_available_modules( Pro_Modules::get_theme_builder_modules() )) && false === get_option('wpr-element-toggle-all') ) {
    		$theme_builder_modules =  Pro_Modules::get_theme_builder_modules();
    	}

		foreach ( $theme_builder_modules as $module ) {
			$class_name = str_replace( '-', ' ', $module );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\Modules\\ThemeBuilder\\' . $class_name . '\Module';
			
			$class_name::instance();
		}

		// Woocommerce Builder Modules
		if ( class_exists( 'woocommerce' ) ) {
			$woocommerce_builder_modules = Pro_Modules::get_available_modules( Pro_Modules::get_woocommerce_builder_modules() );
	
			if ( empty(Pro_Modules::get_available_modules( Pro_Modules::get_woocommerce_builder_modules() )) && false === get_option('wpr-element-toggle-all') ) {
				$woocommerce_builder_modules =  Pro_Modules::get_woocommerce_builder_modules();
			}
	

			foreach ( $woocommerce_builder_modules as $module ) {
				$class_name = str_replace( '-', ' ', $module );
				$class_name = str_replace( ' ', '', ucwords( $class_name ) );
				$class_name = __NAMESPACE__ . '\\Modules\\ThemeBuilder\\Woocommerce\\' . $class_name . '\Module';
				
				$class_name::instance();
			}
		}
	}
}