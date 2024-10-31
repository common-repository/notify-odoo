<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pektsekye_NotifyOdoo_Controller_Adminhtml_No_Settings {

 
  public function predispatch(){
    
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'index';
    
    switch($action){         
      case 'updateSettings':
      
        if (isset($_POST['save_configuration'])){
        
          $this->_updateSettings();

          $message = __('Settings were saved.', 'notify-odoo');              
          Pektsekye_NO()->setMessage($message);
          
        } elseif (isset($_POST['check_connection'])){

          $this->_updateSettings();
          
          Pektsekye_NO()->setDebugEnabled(true);
           
          $observer = new Pektsekye_NotifyOdoo_Model_Observer();
       
          $observer->notify();
          
          $result = Pektsekye_NO()->getDebugResult();
          
          if (isset($result['response']) && $result['response'] == 5){
            Pektsekye_NO()->setMessage(__('Connection is good. The notification has been sent.', 'notify-odoo'));                       
          } else {
            Pektsekye_NO()->setMessage(__('Connection error. Cannot send notification.', 'notify-odoo'), 'error'); 
          }    
        }               
      break;                                                                                                                   
    }
      
  }	 
  
  protected function _updateSettings(){
    $url = isset($_POST['notify_url']) ? sanitize_text_field(stripslashes($_POST['notify_url'])) : '' ; 
    update_option('notifyodoo_url', $url);
    
    $notifyAboutCustomer = isset($_POST['notify_about_new_customer']) && $_POST['notify_about_new_customer'] == 1 ? 1 : 0;    
    update_option('notifyodoo_notify_about_new_customer', $notifyAboutCustomer); 
       
    $notifyAboutOrder = isset($_POST['notify_about_new_order']) && $_POST['notify_about_new_order'] == 1 ? 1 : 0;    
    update_option('notifyodoo_notify_about_new_order', $notifyAboutOrder);
        
    $addTrackingImage = isset($_POST['add_tracking_image']) && $_POST['add_tracking_image'] == 1 ? 1 : 0;    
    update_option('notifyodoo_add_tracking_image', $addTrackingImage);
    
    $allowIpAddress = isset($_POST['allow_ip_address']) ? sanitize_text_field($_POST['allow_ip_address']) : '';
    update_option('notifyodoo_allow_ip_address', $allowIpAddress);            
  }


}
