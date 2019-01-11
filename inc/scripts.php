<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*** Plugin Scripts and CSS ***/
if (!function_exists('open_weather_scripts_and_style')) {
	function open_weather_scripts_and_style(){
		// CSS Files
		wp_enqueue_style('open-weather-style', OPEN_WEATHER_URL . 'assets/css/style.css');

	}
	add_action('wp_enqueue_scripts', 'open_weather_scripts_and_style');
}


