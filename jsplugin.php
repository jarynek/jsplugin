<?php
/*
Plugin Name: js-plugins
Plugin URI: js-plugins
Description: Plugin management extension is under construction.
Version: 1.1
Author: Jaroslav Spurný
Author URI: js-plugins
*/

include_once(__DIR__ . '/functions/functions.php');

/**
* install
*/
$install = new JsPluginsInstall();
$install->execute();
