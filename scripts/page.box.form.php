<?php
require_once(__DIR__ . '/../functions/functions.php');

global $wpdb;
global $post;

$box = new \Back\pageBoxSpace\pageBox($wpdb, $post);
$data = $box->execute();

include_once(__DIR__ .  '/../tpl/page.box.form.php');