<?php
require_once('../../../../wp-load.php');
require_once(__DIR__ . '/../functions/functions.php');

global $wpdb;
global $post;

$box = new \Back\pageBoxSpace\pageBox($wpdb, $post);
$data = $box->search($_POST['data']);

include_once(__DIR__ . '/../tpl/item.menu.box.php');

