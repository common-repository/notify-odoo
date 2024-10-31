<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}


class Pektsekye_NotifyOdoo_Controller_Notification {


  protected $_notification;
        
        
	public function __construct() {
    include_once(Pektsekye_NO()->getPluginPath() . 'Model/Notification.php');		
		$this->_notification = new Pektsekye_NotifyOdoo_Model_Notification();	
	}

 
  public function getNotifications(){

    $allowIp = get_option('notifyodoo_allow_ip_address', '');
    $ip = $_SERVER['REMOTE_ADDR'];
    
    if (!empty($allowIp) && trim($allowIp) != $ip){
      echo '[]';
      exit;
    }


    $notifications = array();
    
    $rows = (array) $this->_notification->getNotifications();
    foreach($rows as $k => $r){
      $notifications[$r['type']][] = (int) $r['item_id'];
    }

    if ($_SERVER['HTTP_USER_AGENT'] == 'odoo_enotif_request'){ //the request is from Odoo. Delete all notifications to not send the same notifications again
      $this->_notification->deleteNotifications();     
      update_option('notifyodoo_last_access_ip_address', $ip);
    }
      
    echo json_encode($notifications);
    exit;     
  }	


}
