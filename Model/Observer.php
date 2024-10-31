<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_NotifyOdoo_Model_Observer {  

  protected $_notification;
  
        
  public function __construct(){ 
    include_once(Pektsekye_NO()->getPluginPath() . 'Model/Notification.php');		
		$this->_notification = new Pektsekye_NotifyOdoo_Model_Notification();
		
    add_action('woocommerce_created_customer', array($this, 'send_new_customer_notification'),  10, 1);		  
    add_action('woocommerce_new_order', array($this, 'send_new_order_notification'),  10, 1);       
    add_action('woocommerce_thankyou', array($this, 'add_tracking_image_on_order_complete_page'), 99, 1);     	          		
  }	


	public function send_new_customer_notification($customerId){
		if (get_option('notifyodoo_notify_about_new_customer', 0) == 1){		
	    $this->notify('new_customer', $customerId);	
	  }	    	
	}
	
		
	public function send_new_order_notification($orderId){
		if (get_option('notifyodoo_notify_about_new_order', 0) == 1){	
	    $this->notify('new_order', $orderId);		
	  }
	}		


	public function notify($type = "default", $itemId = null){
	    
    $url = $this->getNotifyUrl();  
	  
	  $debug = Pektsekye_NO()->getDebugEnabled();

	  $result = array(
	    'url' => $url,
	    'response'	=> '-1',
	    'response_code' => '',
	    'response_content' => '',	
	    'headers' => '',	        
	    'curl_error' => '',
	    'error_text' => ''	    	      
	  );
	  
	  if (!empty($url) && (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0)){    
	          
      $synchronous = $debug ? true : false;
      $timeout = $debug ? 15 : 5;
      $response = wp_remote_get($url, array('blocking' => $synchronous, 'timeout'=> $timeout, 'sslverify' => false));    
      
      if ($debug) {   
        if (is_wp_error($response)) {
          $result['curl_error'] = $response->get_error_message();
        } else {
          $response_code = wp_remote_retrieve_response_code($response);
          $content = wp_remote_retrieve_body($response);
          if ($response_code == 200){
           $result['response'] = $content;
          } else {
            $result['response_code'] = $response_code;
            $result['response_content'] = $content;
            $result['headers'] = (array) wp_remote_retrieve_headers($response);       
          }             
        }
      }      
        
	  } else {
	    $result['error_text'] = __('The URL should start with http:// or https://', 'notify-odoo');
	  }
   
    Pektsekye_NO()->setDebugResult($result);
   

    $this->_notification->addNotification($type, $itemId);     
    
	}
	
	
	public function getNotifyUrl(){	
	  $url = get_option('notifyodoo_url', '');  
	  $url = preg_replace('/[^a-z0-9\:\/_\-\.]+/', '', $url);
	  return rtrim($url, '/') . '/enotif_woo/notify/'; //http://localhost:8069/enotify/notify	
	}
	
	
	public function add_tracking_image_on_order_complete_page($orderId){	
	  if (get_option('notifyodoo_notify_about_new_order', 0) == 1 && get_option('notifyodoo_add_tracking_image', 0) == 1){
	    echo '<img src="' . $this->getNotifyUrl() . '?img=' . time() . '" height="1" width="1" style="border-style:none;" alt=""/>';		
	  }
	}
		
}
