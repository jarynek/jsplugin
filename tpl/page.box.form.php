<?php
$match = !empty(get_post_meta($post->ID, 'js_append')) ? json_decode(get_post_meta($post->ID, 'js_append')[0], true) : [];
function box_menu($args=array(), $type=null, $match=null){
	echo '<div class="box-menu" data-parent="'.$type.'">';
    echo'<div class="section-hd"><span data-tab="1" class="js-tab active">Vše</span>';
    echo'<span data-tab="2" class="js-tab">Hledat</span></div>';
	echo '<div class="section-bd"><div data-tabs="1"><div id="load-items"><ul>';
	foreach ($args as $key => $item) {
        $checked = '';
        $posts = isset($item->posts) ? $item->posts : ''; 
        if($match){
			foreach ($match as $key => $value) {
				if($key == $type.'-'.$item->id){
					$checked = 'checked="checked"';
				}
			}
        }
		echo '<li data-type="'.$type.'"><label><input data-item="'.$type.'-'.$item->id.'" data-posts="'.$posts.'" type="checkbox" '.$checked.'/>'.$item->title.'</label>';
		if($posts && is_array($posts)){
			box_menu($posts);
		}
		echo '</li>';
	}
	echo '</ul></div></div>';
	echo '<div data-tabs="2" class="hdn"><input type="text" name="search" data-search="'.$type.'" /><div id="ajax-items">';
	echo '</div></div>';
	echo '</div></div>';
}
function item_menu($data=array(),$count){
	include_once(__DIR__ . '/../tpl/item.box.form.php');
}
?>

<input type="hidden" id="js_append" name="js_append" style="width: 100%;" value='<?php echo get_post_meta($post->ID, 'js_append') ? get_post_meta($post->ID, 'js_append')[0] : ''; ?>'/>

<div class="js-row">
    <div class="js-left">
        <?php
        if($data){
            foreach ($data as $key => $keys) {
                echo '<div class="jsbox box-'.$key.'"><div class="hd">';
                echo '<strong data-section="'.$key.'">'.$key.'</strong></div>';
                echo '<div class="bd">';
                box_menu($keys, $key, $match);
                echo '</div></div>';
            }
        }
        ?>
    </div>
    <div class="js-right">
        <div id="jsmenu">
            <?php item_menu($match,preg_split('/\,/', COUNT, -1, PREG_SPLIT_NO_EMPTY) ); ?>
        </div>
    </div>
</div>
