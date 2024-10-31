<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$message = $this->getMessage();
$result = $this->getDebugResult();
?>
<div><h3><?php echo __('Notify Odoo Settings', 'notify-odoo'); ?></h3></div>
<?php if (isset($message['error'])): ?>
  <div id="woocommerce_errors" class="error"><p><?php echo $message['error']; ?></p></div>
  <div class="debugging-tips">   
    <b><?php echo __('Debugging tips', 'notify-odoo'); ?>:</b><br/>
    <br/>
    <?php if (!empty($result['error_text'])): ?>    
      <?php echo __('Error', 'notify-odoo'); ?>: <?php echo htmlspecialchars($result['error_text']); ?><br/>
    <?php else:?>    
      <?php if (!empty($result['response_code'])): ?>    
        <?php echo __('Server response code', 'notify-odoo'); ?>: <?php echo htmlspecialchars($result['response_code']); ?><br/>
      <?php endif;?>   
      <?php if (!empty($result['curl_error'])): ?>
        <?php echo __('Error', 'notify-odoo'); ?>: <?php echo htmlspecialchars($result['curl_error']); ?><br/>    
      <?php endif;?>
      <?php if (!empty($result['response_content'])): ?>
        <?php echo __('Output', 'notify-odoo'); ?>:<br/>
        <textarea name="output"><?php echo htmlspecialchars($result['response_content']); ?></textarea><br/>                 
      <?php endif;?>    
      <?php if (!empty($result['headers'])): ?>
        <?php echo __('Headers', 'notify-odoo'); ?>:<br/>      
        <textarea name="output"><?php echo htmlspecialchars(print_r($result['headers'], true)); ?></textarea><br/>    
      <?php endif;?>    
      <br/>    
      <?php echo __('Try to open the requested URL in your web browser', 'notify-odoo'); ?>:<br/>
      <a href="<?php echo htmlspecialchars($result['url']); ?>" target="_blank"><?php echo htmlspecialchars($result['url']); ?></a><br/>
      <br/>
      <?php echo __('It should display only one digit.', 'notify-odoo'); ?><br/> 
      <?php echo __('Digit "5" five means that the notification was received.', 'notify-odoo'); ?> (<a href="<?php echo Pektsekye_NO()->getPluginUrl(); ?>/view/adminhtml/web/no/screenshot_response.png" target="_blank"><?php echo __( 'screenshot', 'woocommerce' ); ?></a>)<br/>
      <br/>
      <?php echo __('Try to use this demo URL', 'notify-odoo'); ?>:<br/>
      http://hottons.com/demo/<br/>
      <?php echo __('It is not an Odoo server. But it can be used to check this WordPress plugin.', 'notify-odoo'); ?>  
      <br/>
    <?php endif;?>
  </div>
<?php endif;?>
<?php if (isset($message['text'])): ?>    
  <div id="message" class="updated notice notice-success is-dismissible below-h2">
  <p><?php echo $message['text']; ?></p>    
  <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __( 'Dismiss this notice.', 'woocommerce' );?></span></button></div>
<?php endif;?>
<div class="no-section">
  <hr>  
  <div><h4><?php echo __('Configuration', 'notify-odoo'); ?>:</h4></div>
  <form action="?page=notifyodoo_settings&action=updateSettings" method="post">
      <fieldset class="no-fieldset">
          <label for="notify_url"><?php echo __('Odoo website URL', 'notify-odoo'); ?>:</label><br/>
          <input style="width:500px;" type="text" name="notify_url" id="notify_url" value="<?php echo $this->getNotifyUrl();?>" autocomplete="off"/><br/>
          <span class="no-config-option-note"><?php echo __('It should start with http:// or https://', 'notify-odoo') ; ?></span><br/>
          <br/>    
           
          <input type="checkbox" name="notify_about_new_customer" id="notify_about_new_customer_field" value="1" <?php echo $this->getNotifyAboutNewCustomer() ? 'checked="checked"' : '';?>/>
          <label for="notify_about_new_customer_field"><?php echo __('Notify About New Customer', 'notify-odoo'); ?></label><br/>        
          <br/>
                           
          <input type="checkbox" name="notify_about_new_order" id="notify_about_new_order_field" value="1" <?php echo $this->getNotifyAboutNewOrder() ? 'checked="checked"' : '';?> onclick="var section = jQuery('.add-tracking-image-section'); if (this.checked){section.show()} else {section.hide();}"/>
          <label for="notify_about_new_order_field"><?php echo __('Notify About New Order', 'notify-odoo'); ?></label><br/>        
          <br/>
          
          <div class="add-tracking-image-section" <?php echo $this->getNotifyAboutNewOrder() ? '' : 'style="display:none;"';?>>         
            <input type="checkbox" name="add_tracking_image" id="add_tracking_image_field" value="1" <?php echo $this->getAddTrackingImage() ? 'checked="checked"' : '';?>/>
            <label for="add_tracking_image_field"><?php echo __('Add Tracking Image', 'notify-odoo'); ?></label><br/>        
            <span class="no-config-option-note"><?php echo __('You can use this when server side notification does not work.', 'notify-odoo'); ?></span><br/>
            <span class="no-config-option-note"><?php echo __('It will add image tag &lt;img /&gt; on the WooCommerce\'s "Thank you for your order" page.', 'notify-odoo'); ?></span><br/>
            <span class="no-config-option-note"><?php echo __('Customer will load the image from your Odoo website so that it will get notified about the new order.', 'notify-odoo'); ?></span><br/>
            <br/>
          </div>
                             
          <label for="allow_ip_address"><?php echo __('Allow to access notifications only from IP address', 'notify-odoo'); ?>:</label><br/>
          <input type="text" class="no-allow-ip-address" name="allow_ip_address" id="allow_ip_address" value="<?php echo $this->getAllowIpAddress();?>" autocomplete="off"/><br/>
          <span class="no-config-option-note"><?php echo sprintf(__('For debugging leave it empty so you can check the failed notifications by accesiing the static file:<br/><a href="%1$s" target="_blank">%1$s</a><br/>and then the notifications URL:<br/><a href="%2$s" target="_blank">%2$s</a><br/>in your web browser.', 'notify-odoo'), Pektsekye_NO()->getPluginUrl() . 'pub/static/new_notifications_flag.txt', admin_url('admin-ajax.php') . '?action=notifyodoo_get_notifications'); ?></span><br/>
          <span class="no-config-option-note"><?php echo sprintf(__('If you don\'t know the IP address of your Odoo website. Last time the notifications URL was accessed from Odoo server with IP %s', 'notify-odoo'), $this->getLastAccessIpAddress() != '' ? $this->getLastAccessIpAddress() : '('. __('there has been no request from Odoo server yet', 'notify-odoo') .')'); ?></span><br/>
                    
          <br/>                                                                                       
          <input type="submit" name="save_configuration" class="button button-primary" value="<?php echo __('Save Configuration', 'notify-odoo') ; ?>"/>
          &nbsp;&nbsp;
          <input type="submit" name="check_connection" class="button button-primary" value="<?php echo __('Check Connection', 'notify-odoo') ; ?>"/>      
      </fieldset>
  </form>       
</div>     
