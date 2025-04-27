<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.localhost443.com
 * @since      1.0.0
 *
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/admin/partials
 * 
 * 
 */
if (isset($_GET['page']) && $_GET['page']  == 'hgt-tracker') {
  if(isset($_GET['HTGWooCommerceOrderID'])){
    require_once("tracker/HTGWooCommerceOrderID.php");
    HTGWooCommerceOrderID($_GET['HTGWooCommerceOrderID']);
  } else if(isset($_GET['HTGtrackOrderId'])){
    require_once("tracker/HTGtrackOrderId.php");
  }
  else {
    require_once("tracker/tracking_form.php");
  }
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

