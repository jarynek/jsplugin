<?php
/**
 * append fn
 */
function js_append($post_id=''){
    $append = new \Front\FnSpace\Front($post_id);
    $data = $append->pageAppendAction();
    include __DIR__ . '/../tpl/item.append.php';
}