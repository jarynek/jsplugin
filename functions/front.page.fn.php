<?php
namespace Front\FnSpace;
/**
 * Class Front
 * @package Front\FnSpace
 */
class Front{

    /**
     * @var array
     */
    private $result = array();

    /**
     * Front constructor
     */
    public function __construct($post_id)
    {
        global $wpdb;
        global $post;

        $this->wpdb = $wpdb;
        $this->post = $post;

        $this->postId = $post_id ? $post_id : $this->post->ID;
    }

    /**
     * get el id
     * @param $arg
     * @return mixed
     */
    private function getId($arg)
    {
        try{
            if(!isset($arg) || $arg == ''){
                throw new \Exception('neni arg pro id');
            }
        }catch (\Exception $err){
            print $err->getMessage();
            die;
        }

        return preg_split('/\-/', $arg, -1, PREG_SPLIT_NO_EMPTY)[1];
    }

    /**
     * set post tags
     * @param $arg
     * @return string
     */
    private function setDate($arg)
    {
        return get_the_time('Y m d h:i:s',$arg->id);
    }

    /**
     * set link
     * @param $arg
     * @return mixed
     */

    private function setLink($arg)
    {
        if($arg->type == 'category'){
            return get_category_link($arg->id);
        }else{
            return get_permalink($arg->id);
        }
    }

    /**
     * set img
     * @param $arg
     * @return string
     */
    private function setImg($arg)
    {
        if($arg->type == 'category'){
            return false;
        }else{
            if(has_post_thumbnail($arg->id)){
                return get_the_post_thumbnail_url($arg->id, 'medium');
            }
        }
    }

    /**
     * set short
     * @param $arg
     * @return mixed
     */
    private function setShort($arg)
    {
        if($arg->type == 'category'){
            return category_description($arg->id);
        }else{
            if(has_excerpt($arg->id)){
                return get_the_excerpt($arg->id);
            }else{
                return substr(strip_tags(apply_filters('the_content', get_post_field('post_content', $arg->id))), 0, 100) . '...';
            }
        }
    }

    /**
     * get attr of el (type)
     * @param $arg
     * @return mixed
     */
    private function getAttr($arg)
    {
        return preg_split('/\-/', $arg, -1, PREG_SPLIT_NO_EMPTY)[0];
    }

    /**
     * set attr link, img, short
     * @param $arg
     */
    private function setAttr($arg)
    {
        try{
            if(!isset($arg) || empty($arg)){
                throw new \Exception('pole je prazdne');
            }
        }catch (\Exception $err){
            print $err->getMessage();
            die;
        }

        if(count($arg) > 1){
            foreach ($arg as $key=>$item){
                $arg[$key]->link = $this->setLink($item);
                $arg[$key]->img = $this->setImg($item);
                $arg[$key]->excerpt = $this->setShort($item);
                $arg[$key]->date = $this->setDate($item);
            }
        }else{
            end($arg)->link = $this->setLink(end($arg));
            end($arg)->img = $this->setImg(end($arg));
            end($arg)->excerpt = $this->setShort(end($arg));
            end($arg)->date = $this->setDate(end($arg));
        }
    }

    /**
     * @param $arg
     * @return \array
     */
    private function pages($arg)
    {
        $sql = "SELECT `ID` AS `id`, `post_title` AS `title`, `post_type` AS `type` FROM `{$this->wpdb->prefix}posts` WHERE `ID` = {$this->getId($arg->id)} AND `post_type` = 'page' ";
        $this->result[] = $this->wpdb->get_results($sql)[0];
        return $this->result;
    }

    /**
     * @param $arg
     * @return array
     */
    private function posts($arg)
    {
        if(isset($arg->childs)){
            $sql = "SELECT `{$this->wpdb->prefix}term_relationships`.`object_id`
                      FROM `{$this->wpdb->prefix}term_relationships`
                     WHERE `{$this->wpdb->prefix}term_relationships`.`term_taxonomy_id` = {$this->getId($arg->id)} ";

            if(isset($arg->sort)){
                $sql .= "ORDER BY `{$this->wpdb->prefix}term_relationships`.`object_id` {$arg->sort} ";
            }
            if(isset($arg->count)){
                $sql .= "LIMIT {$arg->count} ";
            }

            $data = $this->wpdb->get_results($sql);
            foreach ($data as $key=>$val){
                $sql = "SELECT `ID` AS `id`, `post_title` AS `title`, `post_type` AS `type` FROM `{$this->wpdb->prefix}posts` WHERE `ID` = {$val->object_id} AND `post_type` = 'post' ";
                $this->result[] = $this->wpdb->get_results($sql)[0];
            }
        }else{
            $sql = "SELECT `ID` AS `id`, `post_title` AS `title`, `post_type` AS `type` FROM `{$this->wpdb->prefix}posts` WHERE `ID` = {$this->getId($arg->id)} AND `post_type` = 'post' ";
            $this->result[] = $this->wpdb->get_results($sql)[0];
        }
        return $this->result;
    }

    /**
     * @param $arg
     * @return array
     */
    private function category($arg)
    {
        $sql = "SELECT `{$this->wpdb->prefix}terms`.`term_id` AS `id`, 
                       `{$this->wpdb->prefix}terms`.`name` AS `title`,
                       `{$this->wpdb->prefix}term_taxonomy`.`taxonomy` AS `type`
                  FROM `{$this->wpdb->prefix}terms`
            INNER JOIN `{$this->wpdb->prefix}term_taxonomy` ON `{$this->wpdb->prefix}term_taxonomy`.`term_id` = `{$this->wpdb->prefix}terms`.`term_id`
                 WHERE `{$this->wpdb->prefix}terms`.`term_id` = {$this->getId($arg->id)} ";

        $this->result[] = $this->wpdb->get_results($sql)[0];
        return $this->result;
    }

    /**
     * @return array
     */
    public function pageAppendAction()
    {
        $data = get_post_meta($this->postId, 'js_append') ? json_decode(get_post_meta($this->postId, 'js_append')[0]) : '';
        try{
            if(!isset($data) || empty($data)){
                throw new \Exception('Nejsou data');
            }
            foreach ($data as $key=>$row) {
                if($this->getAttr($key) == 'pages'){
                    $this->setAttr($this->pages($row));
                }elseif ($this->getAttr($key) == 'posts'){
                    $this->setAttr($this->posts($row));
                }else if ($this->getAttr($key) == 'category'){
                    if(isset($row->childs)){
                        $this->setAttr($this->posts($row));
                    }else{
                        $this->setAttr($this->category($row));
                    }
                }
            }
            return $this->result;
        }catch (\Exception $err){
            print $err->getMessage();
            return array();
        }
    }
}