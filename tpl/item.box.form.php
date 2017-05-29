<ul class="item-menu">
	<?php if(isset($data) && $data !==''):?>
		<?php foreach ($data as $key => $item): ?>
			<li class="item-box" data-type="<?php echo $item['type']; ?>" data-menu="<?= $item['id']; ?>" data-parent>
			<label>
				<input data-item="<?php echo $item['id']; ?>" type="checkbox" checked="checked" />
            </label>
                <?php echo '<strong>' . $item['title'].'</strong>' . ' (' . $item['type'] . ')'; ?>
				<?php if(isset($item['posts']) && $item['posts'] !== ''): ?>
					<span data-posts><?php if(isset($item['childs'])){echo 'only posts';}?></span>
					<span data-count><?php if(isset($item['count'])){echo $item['count'];}?></span>
                    <span data-sort><?php if(isset($item['sort'])){echo $item['sort'];}?></span>
				<?php endif; ?>
                <?php if(isset($item['posts']) && $item['posts'] !== ''): ?><span data-toggle class="opener"></span><?php endif; ?>

			<?php if(isset($item['posts']) && $item['posts'] !== ''): ?>
				<span class="section<?php if(!isset($item['childs'])):?> disabled<?php endif;?> bd hdn">
					<label class="display-block margin-bt-4">
						<input type="checkbox" name="childs" value="1"<?php if(isset($item['childs'])):?> checked="checked"	<?php endif; ?>  />
						(post - <?php echo $item['posts']; ?>)
					</label>
					<label class="childs display-block margin-bt-4">
						<select name="count">
						  <option value="">----</option>
						  <?php foreach(preg_split('/\,/', COUNT, -1, PREG_SPLIT_NO_EMPTY) as $val){
						  	$selected = '';
						  	if(isset($item['count']) && $item['count'] == $val){
						  		$selected = 'selected="selected"';
						  	}
						  	echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
						  	}
						  	?>
						</select>
						<span>&nbsp;the number of elements on the page</span>
					</label>
					<label class="childs display-block">
						<select name="sort">
						  <option value="">----</option>
                            <?php foreach(preg_split('/\,/', SORT, -1, PREG_SPLIT_NO_EMPTY) as $val){
                                $selected = '';
                                if(isset($item['sort']) && $item['sort'] == $val){
                                    $selected = 'selected="selected"';
                                }
                                echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                            }
                            ?>
						</select>
						<span>&nbspsort</span>
					</label>
				</span>
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>