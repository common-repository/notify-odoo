<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Pektsekye_NotifyOdoo_Model_Notification
{

    protected $_wpdb;
    protected $_mainTable;         

        
    public function __construct() {
			global $wpdb;
			
			$this->_wpdb = $wpdb;   
      $this->_mainTable = "{$wpdb->base_prefix}notifyodoo_notifications";               
    }    



    public function getNotifications()     
    {
      $select = "SELECT type, item_id FROM {$this->_mainTable}";      
      return (array) $this->_wpdb->get_results($select, ARRAY_A);             
    }


    public function addNotification($type, $itemId)     
    { 
      $type = esc_sql($type);
      $itemId = (int) $itemId;
      $this->_wpdb->query("INSERT IGNORE INTO {$this->_mainTable} SET type='{$type}', item_id={$itemId}");
      
      $this->setHasNotificationsFlag(true);             
    }


    public function setHasNotificationsFlag($hasNotifications = false)     
    { 
    
	    $debug = Pektsekye_NO()->getDebugEnabled();
    
      $file = $this->getStaticFile();
      if (file_exists($file)){
           
        $value = $hasNotifications ? 1 : '';      
        $oldValue = @file_get_contents($file);        
                
        if ($value == $oldValue && !$debug){
          return;
        }
                
        $fo = @fopen($file, "w");
        if ($fo){
          fwrite($fo, $value);
          fclose($fo);
        } elseif ($debug){
          $result = array('error_text' => sprintf(__('The static file "%s" is not writeable.', 'notify-odoo'), $file));
          Pektsekye_NO()->setDebugResult($result);        
        }
      } elseif ($debug) {
        $result = array('error_text' => sprintf(__('The static file "%s" does not exist.', 'notify-odoo'), $file));
        Pektsekye_NO()->setDebugResult($result);
      }            
    }        
    
    
    public function getStaticFile()     
    { 
      return Pektsekye_NO()->getPluginPath() . 'pub/static/new_notifications_flag.txt';             
    }    
      

    public function deleteNotifications()
    {      
      $this->_wpdb->query("TRUNCATE TABLE {$this->_mainTable}");
      
      $this->setHasNotificationsFlag(false);       
    }	
       
}
