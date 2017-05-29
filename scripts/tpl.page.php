 <?php
 include_once(__DIR__ . '/../functions/functions.php');
 $box = new \Back\pageBoxSpace\pageBox($wpdb=null, $post=null);
 if(isset($_POST['items'])){
	$box->tpl($_POST['items']);
 }
