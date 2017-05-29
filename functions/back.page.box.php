<?php
namespace Back\pageBoxSpace;

/**
* page meta box
*/
class pageBox
{
    /**
     * @var \stdClass $db
     */
    private $db;

    /**
     * @var \stdClass $post
     */
    private $post;

    /**
     * @var string $sql Sql
     */
    private $sql;

    /**
     * @var array $data data
     */
    private $data;

    /**
     * @var array $types
     */
    private $types;

    /**
     * pageBox constructor.
     * @param $db
     * @param $post
     */
    function __construct($db, $post)
	{
		$this->db = $db;
		$this->post = $post;
	}

	/**
	* @return \stdClass pages
	*/
	private function pages()
	{
        $this->sql = null;
        $this->sql = " SELECT `ID` AS `id`, `post_title` AS `title`
		                 FROM `{$this->db->prefix}posts`
		                WHERE `ID` != '{$this->post->ID}'
		                  AND `post_status` =  'publish'
		                  AND `post_type` = 'page' ";

		return $this->db->get_results($this->sql);
	}

	/**
	* @param \int $cat_id
	*/
	private function posts($cat_id=null)
	{
		if(isset($cat_id) && $cat_id !== ''){
			$this->sql = " SELECT `ID` AS `id`, `post_title` AS `title`
			                 FROM `{$this->db->prefix}posts`
			           INNER JOIN `{$this->db->prefix}term_relationships`
			                   ON `{$this->db->prefix}posts`.`ID` = `{$this->db->prefix}term_relationships`.`object_id`
			                WHERE `{$this->db->prefix}term_relationships`.`term_taxonomy_id` = '{$cat_id}'
			                  AND `post_status` = 'publish'
			                  AND `post_type` = 'post' ";
		}else{
			$this->sql = " SELECT `ID` AS `id`, `post_title` AS `title`
			                 FROM `{$this->db->prefix}posts`
			                WHERE `ID` != '{$this->post->ID}'
			                  AND `post_status` =  'publish'
			                  AND `post_type` = 'post' ";
		}

		return $this->db->get_results($this->sql);
	}

	/**
	* return \stdClass category
	*/
	private function category()
	{
		$this->data = [];
		$this->sql = " SELECT `{$this->db->prefix}terms`.`term_id` AS `id`,
		                      `{$this->db->prefix}terms`.`name` AS `title`
		                 FROM `{$this->db->prefix}terms`
		           INNER JOIN `{$this->db->prefix}term_taxonomy`
		                   ON `{$this->db->prefix}terms`.`term_id` = `{$this->db->prefix}term_taxonomy`.`term_id`
		                  AND `{$this->db->prefix}term_taxonomy`.`taxonomy` = 'category' ";

		if($this->db->get_results($this->sql)){
			foreach ($this->db->get_results($this->sql) as $key => $item) {
				$this->data[] = $item;
				if($this->posts($item->id)){
					$this->data[$key]->posts = count($this->posts($item->id));
				}
			}
		}

		return $this->data;
	}

	/**
	* return \stdClass of component pages, posts, category to use in background
	*/
	public function execute()
	{
		return [
		    'pages'=>$this->pages(),
		    'posts'=>$this->posts(),
		    'category'=>$this->category()
		];
	}

	/**
	* @param \array data
	* @return string $tpl
	*/
	public function tpl($data=array())
	{
		try{
			if(isset($data) && is_array($data)){
				include_once(__DIR__ . '/../tpl/item.box.form.php');
			}else{
				throw new \Exception('argument není pole ani object');
			}
		}catch(\Exception $e){
			echo $e->getMessage();
		}
		return null;
	}

	/**
	* @param \array data
	* @return \array data
	*/
	public function search($data=array())
	{
        $this->types = [
            'pages'=>'page',
            'posts'=>'post',
            'category'=>'category'
        ];
        if(isset($data) || !empty($data)){

            if($data['type'] == 'category'){
                print_r($this->types[$data['type']]);

            }else{
                $this->sql = " SELECT `ID` AS `id`, `post_title` AS `title`
			               FROM `{$this->db->prefix}posts`
			              WHERE `post_title`
			               LIKE '%{$data['val']}%'
			                AND `post_type` = '{$this->types[$data['type']]}'
			                AND `ID` != '{$data['id']}' ";
            }

            if($this->db->get_results($this->sql) ){
                foreach ($this->db->get_results($this->sql) as $key=>$row){
                    $this->data[$data['type'] . '-' . $row->id] = array(
                        'id'=>$row->id,
                        'title'=>$row->title,
                        'type'=>$data['type']
                    );
                }
                return $this->data;
            }
		}
		return null;
	}
}