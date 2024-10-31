<?php
/**
 * Plugin Name: Notify Odoo
 * Description: Notifies Odoo about a new order on WooCommerce.
 * Version: 1.0.0
 * Author: Pektsekye
 * Author URI: http://hottons.com
 * License: GPLv2     
 * Requires at least: 4.7
 * Tested up to: 6.4.2
 *
 * Text Domain: notify-odoo
 *
 * WC requires at least: 3.5
 * WC tested up to: 8.3.1
 * 
 * @package NotifyOdoo
 * @author Pektsekye
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class Pektsekye_NotifyOdoo {


  protected static $_instance = null;

  protected $_pluginUrl; 
  protected $_pluginPath;      
      
  protected $_message = array();
  protected $_debugEnabled = false;  
  protected $_debugResult = array();       


  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
      self::$_instance->initApp();
    }
    return self::$_instance;
  }


  public function __construct() {
    $this->_pluginPath = plugin_dir_path(__FILE__);
    $this->_pluginUrl  = plugins_url('/', __FILE__);
  }


  public function initApp() {
    $this->includes();
    $this->init_hooks();
    $this->init_controllers();
  }
  
  
  public function includes() {
    include_once('Setup/Install.php'); 
    include_once('Model/Observer.php');
    
    new Pektsekye_NotifyOdoo_Model_Observer();
    
    if ($this->is_request('admin')) { 
      include_once('Block/Adminhtml/No/Settings.php');            
    }    
  }
  

  private function init_hooks() {
    register_activation_hook(__FILE__, array('Pektsekye_NotifyOdoo_Setup_Install', 'install'));
    
    add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts') , 10); 
    add_action('admin_menu', array($this, 'set_admin_menu' ), 70);                             
  }    


  private function init_controllers() {
		
    if ($this->is_request('admin')){
      if (isset($_GET['page']) && $_GET['page'] == 'notifyodoo_settings') {
        include_once('Controller/Adminhtml/No/Settings.php');        
        add_action('init', array( new Pektsekye_NotifyOdoo_Controller_Adminhtml_No_Settings(), 'predispatch'));
      }      	     	  
    }
    
    if (isset($_GET['action']) && $_GET['action'] == 'notifyodoo_get_notifications'){
      include_once('Controller/Notification.php' );
      $controller = new Pektsekye_NotifyOdoo_Controller_Notification();  
      $controller->getNotifications();   			       
      add_action('wp_ajax_nopriv_notifyodoo_get_notifications', array($controller, 'getNotifications'));	                					
    }                             	  
  }


  public function enqueue_admin_scripts() {
    if (isset($_GET['page']) && $_GET['page'] == 'notifyodoo_settings'){  
		  wp_enqueue_style('notifyodoo_style', $this->_pluginUrl . 'view/adminhtml/web/no/main.css' ); 
		} 
  }
  
  
  public function set_admin_menu() {
    add_menu_page( _x( 'Notify Odoo', 'Admin menu', 'notify-odoo'), _x( 'Notify Odoo', 'Admin menu', 'notify-odoo'), 'manage_options', 'notifyodoo_settings', array( new Pektsekye_NotifyOdoo_Block_Adminhtml_No_Settings(), 'toHtml' ) );     
  }    
       
       
  private function is_request( $type ) {
    switch ( $type ) {
      case 'admin' :
        return is_admin();
      case 'ajax' :
        return defined( 'DOING_AJAX' );
      case 'cron' :
        return defined( 'DOING_CRON' );
      case 'frontend' :
        return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
    }
  }
  
  
  public function getPluginUrl() {
    return $this->_pluginUrl;
  }
  
  
  public function getPluginPath() {
    return $this->_pluginPath;
  } 
  
   
  public function setMessage($message, $type = 'text') {       
    $this->_message[$type] = $message;                                        
  }


  public function getMessage() {
    return $this->_message;
  } 

 
  public function setDebugEnabled($enabled) {       
    $this->_debugEnabled = $enabled;                                        
  }
  
  
  public function getDebugEnabled() {       
    return $this->_debugEnabled;                                        
  }    
  
  
  public function setDebugResult($result) {       
    $this->_debugResult = $result;                                        
  }
  

  public function getDebugResult() {
    return $this->_debugResult;
  } 
        
}


function Pektsekye_NO() {
	return Pektsekye_NotifyOdoo::instance();
}


Pektsekye_NO();







