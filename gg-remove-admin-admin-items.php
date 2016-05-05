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
    
    public function __construct() {
        $this->set_actions();
    }


    public function remove_items() {
        $items = get_theme_mod('removed-items',"");
        $items = explode("\n", $items);
        foreach($items as $item) {
            remove_menu_page( $item ); 
        }
    }  
    
    public function customizer( $wp_customize) {
        if(current_user_can('administrator')) {
            $wp_customize->add_section( 'remove-admin-items' , array(
                'title'      => "Remove admin items",
                //'priority'   => 10,
            ) );
            $wp_customize->add_setting('removed-items', array( "default" => ""));
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'removed-items',
                    array(
                        'label' => 'Items to remove',
                        'section' => 'remove-admin-items',
                        'settings' => 'removed-items',
                        'type' => 'textarea',
                        'description' => 'One per line'
                    )
                )
            );

	}
    }
    
    protected function set_actions() {
        add_action( 'customize_register', array($this,'customizer'));
        add_action( 'admin_menu', array($this, 'remove_items'));
    }
    
}
