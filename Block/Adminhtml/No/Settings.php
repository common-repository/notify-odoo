<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pektsekye_NotifyOdoo_Block_Adminhtml_No_Settings {


  public function getNotifyUrl() 
  {   
    return get_option('notifyodoo_url', '');
  }


  public function getNotifyAboutNewCustomer() 
  {   
    return get_option('notifyodoo_notify_about_new_customer', 0);
  }


  public function getNotifyAboutNewOrder() 
  {   
    return get_option('notifyodoo_notify_about_new_order', 0);
  }
  

  public function getAddTrackingImage() 
  {   
    return get_option('notifyodoo_add_tracking_image', 0);
  }      
      
      
  public function getAllowIpAddress() 
  {   
    return get_option('notifyodoo_allow_ip_address', '');
  }
      
      
  public function getLastAccessIpAddress() 
  {   
    return get_option('notifyodoo_last_access_ip_address', '');
  } 
     
        
  public function getMessage() {
    return Pektsekye_NO()->getMessage();
  }
  
  
  public function getDebugResult() {
    return Pektsekye_NO()->getDebugResult();
  } 
 
 
  public function toHtml()
  {   
    include_once( Pektsekye_NO()->getPluginPath() . 'view/adminhtml/templates/no/settings.php');
  }
   

}