<?php
$index = 1;
$devide = 3;
$list = false;
if(isset($data) && !empty($data)){
    if($list){
        echo '<ul>';
        foreach ($data as $key=>$item){
            echo '<li><a href="'.$item->link.'">'.$item->title.'</a></li>';
        }
        echo '</ul>';
    }else{
        echo '<div class="js-row">';
        foreach ($data as $key=>$item){
            $rand = rand(1, $devide);
            $is_img = $item->img ? 'is_img' : '';
            echo'<div class="js-column size-'.$devide.' rand-'.$rand.'"><div class="item '.$is_img.'">';
            if($item->img){
                echo'<div class="img"><img src="'.$item->img.'"></div>';
            }
            echo '<div class="hd"><time class="entry-date published updated" datetime="'.$item->date.'">'.$item->date.'</time>';
            echo '<h2><a href="'.$item->link.'">'.$item->title.'</a></h2>';
            echo '</div><div class="bd"><div class="content"><p>'.$item->excerpt.'</p></div>';
            echo '<a href="'.$item->link.'" class="link-more"><span class="hide-for-small">více</span></a></div></div></div>';
            if(is_int($index / $devide) && $index !== count($data)){
                echo '</div><div class="js-row">';
            }
            if($index == count($data)){
                echo '</div>';
            }
            $index ++;
        }
    }
}