<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pektsekye_NotifyOdoo_Setup_Install {
	

	public static function install() {

		self::create_tables();

	  add_option('notifyodoo_url', ''); 
    add_option('notifyodoo_notify_about_new_customer', 0);
    add_option('notifyodoo_notify_about_new_order', 1);    	   
    add_option('notifyodoo_add_tracking_image', 0);	      
	  add_option('notifyodoo_allow_ip_address', '');
	  add_option('notifyodoo_last_access_ip_address', '');	    		 	
	}


	private static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();
		 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta(self::get_schema());
	}


	private static function get_schema() {
		global $wpdb;
		
		return "
CREATE TABLE {$wpdb->base_prefix}notifyodoo_notifications (
  notification_id int(11) unsigned NOT NULL auto_increment,
  type varchar(64) NOT NULL,  
  item_id int(11) unsigned NOT NULL,  
  PRIMARY KEY (notification_id),
  UNIQUE KEY uk_type_item_id (type, item_id)   
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
		";
		 
	}


	public static function wpmu_drop_tables( $tables ) {
		global $wpdb;
		$tables[] = $wpdb->base_prefix . 'notifyodoo_notifications';						
		return $tables;
	}
}
