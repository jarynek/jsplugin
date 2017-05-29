<?php if(isset($data) && $data !==''):?>
    <ul>
        <?php foreach ($data as $key => $item): ?>
            <?php
            $match = $item['type'] .'-'. $item['id'];
            $checked = '';
            if(isset($_POST['compare'])){
                foreach (array_keys($_POST['compare']) as $keyC){
                    if($keyC == $match){
                        $checked = 'checked="checked"';
                    }
                }
            }
            ?>
            <li data-type="pages" class="active">
                <label><input data-item="<?php echo $key; ?>" data-posts="" type="checkbox" <?php echo $checked; ?>><?php echo $item['title']; ?></label>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    nejsou data
<?php endif; ?>