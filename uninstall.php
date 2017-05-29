<?php
namespace Back\UninstalSpace;
/**
 * Class Uninstall
 * @package Back\UninstalSpace
 */
class Uninstall
{
    /**
     * Uninstall constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    /**
     * remove js_append from postmeta table
     */
    private function js_append()
    {
        $this->wpdb->query( "DELETE FROM `{$this->db->prefix}post_meta` WHERE `meta_key` = 'js_append' " );
    }

    /**
     * Init uninstall plugin
     */
    public function Init()
    {
        $this->js_append();
    }
}

new Uninstall();