<?php
/*
Plugin Name: Remove admin items
Plugin URI:  http://gresak.net/wp/plugins
Description: Remove specific items from admin list
Version:     1.0
Author:      Gregor Grešak
Author URI:  http://gresak.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

new GG_Remove_Admin_Items();

class GG_Remove_Admin_Items {
    
    protected $wp_admin_bar;
    
    public function __construct() {
        $this->set_actions();
    }


    public function remove_items() {
        $items = $this->get_items('removed-menu-items');
        foreach($items as $item) {
            remove_menu_page( $item ); 
        }

    }  
    
    public function remove_from_admin_bar() {
        $wp_admin_bar = $this->get_global('wp_admin_bar');
        $items = $this->get_items('removed-topbar-items');
        foreach ($items as $item) {
            $wp_admin_bar->remove_menu($item);
        }
    }
    
    public function customizer( $wp_customize) {
        if(current_user_can('administrator')) {
            $wp_customize->add_section( 'remove-admin-items' , array(
                'title'      => "Remove admin items",
                'priority'   => 20,
            ) );
            $wp_customize->add_setting('removed-menu-items', array( "default" => ""));
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'removed-menu-items',
                    array(
                        'label' => 'Items to remove',
                        'section' => 'remove-admin-items',
                        'settings' => 'removed-menu-items',
                        'type' => 'textarea',
                        'description' => 'One per line'
                    )
                )
            );
            $wp_customize->add_setting('removed-topbar-items', array( "default" => ""));
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'removed-topbar-items',
                    array(
                        'label' => 'Items to remove',
                        'section' => 'remove-admin-items',
                        'settings' => 'removed-topbar-items',
                        'type' => 'textarea',
                        'description' => 'One per line'
                    )
                )
            );

	}
    }
    
    protected function get_items($setting) {
        $items = get_theme_mod($setting,array());
        return explode("\n", $items);
    }

        protected function get_global($global) {
        global $$global;
        return $$global;
    }


    protected function set_actions() {
        add_action( 'customize_register', array($this,'customizer'));
        add_action( 'admin_menu', array($this, 'remove_items'));
        add_action( 'wp_before_admin_bar_render', array($this,'remove_from_admin_bar') );
    }
    
}
