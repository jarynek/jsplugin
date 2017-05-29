<?php
namespace Back\UninstallSpace;
/**
 * Class Uninstall
 * @package Back\UninstallSpace
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
        $sql = "DELETE FROM `{$this->wpdb->prefix}postmeta` WHERE `meta_key` = 'js_append' ";
        $this->wpdb->query($sql);
    }

    /**
     * Init uninstall plugin
     */
    public function Init()
    {
        $this->js_append();
    }
}