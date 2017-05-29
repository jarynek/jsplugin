<?php
/**
* Install
*/
class JsPluginsInstall
{
	public function __construct()
	{
	}

    /**
     * addExcerpt
     */
	private function addExcerpt()
    {
        add_action( 'init', 'excerpts_to_pages' );
        add_action( 'init', 'excerpts_to_post' );

        function excerpts_to_pages() {
            add_post_type_support( 'page', 'excerpt' );
        }

        function excerpts_to_post() {
            add_post_type_support( 'post', 'excerpt' );
        }
    }

    /**
    * Create page meta box, save meta box
    */
	private function pageMetaBox()
	{
	    add_action( 'add_meta_boxes', 'pageBox' );
	    add_action( 'save_post', 'js_append_save' );

	    function  pageBox(){
	      add_meta_box(
	      'pageBox',
	      'Page',
	      'pageBoxCallback',
	      'page',
	      'normal',
	      'high' );
	    }

	    function pageBoxCallback(){
	    	wp_nonce_field('js_append_add_nonce', 'js_append_nonce');
	    	include_once(__DIR__ . '/../scripts/page.box.form.php');
	    }

	    function js_append_save(){
			global $post;

			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			 return;
			}

			if( !isset( $_POST['js_append_nonce'] ) || !wp_verify_nonce( $_POST['js_append_nonce'], 'js_append_add_nonce' ) ){
			 return;
			}
	    	update_post_meta($post->ID, "js_append", $_POST['js_append']);
	    }
	}

    /**
    * admin css
    */
	private function adminCss()
	{
		function plugin_admin_css() {
		    $url = get_bloginfo('url') . '/wp-content/plugins/jsplugin/css/back.page.box.css';
		    echo '<link rel="stylesheet" href="'. $url . '" type="text/css" media="all" />';
		}
		add_action('admin_head', 'plugin_admin_css');
	}

    /**
     * front css
     */
	private function frontCss()
    {
        function plugin_front_css() {
            $url = get_bloginfo('url') . '/wp-content/plugins/jsplugin/css/front.page.css';
            echo '<link rel="stylesheet" href="'. $url . '" type="text/css" media="all" />';
        }
        add_action('wp_head', 'plugin_front_css');
    }

    /**
    * admin js
    */
	private function adminJs()
	{
		function plugin_admin_js() {
		    $url = get_bloginfo('url') . '/wp-content/plugins/jsplugin/js/back.page.box.js';
		    echo '<script type="text/javascript" src="'. $url . '"></script>';
		}
		add_action('admin_footer', 'plugin_admin_js');
	}

    /**
    * Execute
    */
	public function execute()
	{
		$this->pageMetaBox();
		$this->addExcerpt();
		$this->adminCss();
        $this->frontCss();
		$this->adminJs();
	}
}