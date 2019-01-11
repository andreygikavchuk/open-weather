<?php
/*
Plugin Name: Open Weather
Description: This plugin will enable Weather in your WordPress site.
Plugin URI: https://github.com/andreygikavchuk/open-weather
Author: Andrew
Version: 1.0
*/

define( 'OPEN_WEATHER_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );

/* Including files */
require_once("inc/scripts.php");
require_once("inc/widget.php");