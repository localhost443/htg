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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container bg-white">
    <div class="w-75">

        <div class="mt-5 mb-3 shadow pt-2 pe-4 ps-3 pb-3 rounded">
            <h4 class="m-2 mb-3 underline">Start A Sync Now</h4>
            <div class="mb-4">
                <form action="#" method="#">
                    <button id='startaSync' type="" value="htgsyncrequest" class="btn btn-primary p-2 px-3 m-2">Sync Now</button>
                </form>
            </div>
        </div>

        <!-- <div class="mt-5 mb-3 shadow pt-2 pe-4 ps-3 pb-3 rounded">
            <h4 class="m-2 mb-3">Change URLS</h4>
            <form action="options.php" method="post">
                <?php
                // settings_fields('htgCustomApiSettings');
                // do_settings_sections('htgCustomApiSettings');
                ?>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG API User</span>
                    <input type="text" value="<?php echo get_option('htgkey'); ?>" name="htgkey" aria-label="HTG API KEY" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG API Secret</span>
                    <input type="text" value="iRNlmTOFegkBtzYwMbF3" name="htgsecret" aria-label="HTG API SECRET" class="form-control">
                </div>
                <p class="m-2">Changing API Keys is currently Disabled</p>
                <div class="input-group m-2">
                    <button type="submit" value="hkeypass" class="btn btn-primary p-2 px-3">Save</button>
                </div>
            </form>
        </div> -->

        <div class="mt-5 mb-3 shadow pt-2 pe-4 ps-3 pb-3 rounded">
            <h4 class="m-2 mb-3">Change HTG API</h4>
            <form action="options.php" method="post">
                <?php
                settings_fields('htgCustomApiSettings');
                do_settings_sections('htgCustomApiSettings');
                ?>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG API URL</span>
                    <input type="text" value="<?php echo get_option('htgmainurl'); ?>" name="htgmainurl" aria-label="HTG API KEY" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG API User</span>
                    <input type="text" value="<?php echo get_option('htgkey'); ?>" name="htgkey" aria-label="HTG API KEY" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG API Secret</span>
                    <input type="text" value="<?php echo get_option('htgsecret'); ?>" name="htgsecret" aria-label="HTG API SECRET" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG Stock Url</span>
                    <input type="text" value="<?php echo get_option('htgstockurl'); ?>" name="htgstockurl" aria-label="HTG API KEY" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG Inventory Key</span>
                    <input type="text" value="<?php echo get_option('htgstockkey'); ?>" name="htgstockkey" aria-label="HTG API SECRET" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG Inventory Code</span>
                    <input type="text" value="<?php echo get_option('htgstockcode'); ?>" name="htgstockcode" aria-label="HTG API SECRET" class="form-control">
                </div>
                <div class="input-group m-2">
                    <button type="submit" value="hkeypass" class="btn btn-primary p-2 px-3">Save</button>
                </div>
            </form>
        </div>

        <div class="mt-5 mb-2 shadow pt-2 pe-4 ps-3 pb-3 rounded">
            <h4 class="m-2 mb-3">Change WOOCOMMERCE API</h4>
            <form action="options.php" method="post">
                <?php
                settings_fields('htgCustomWoocommerceApiSettings');
                do_settings_sections('htgCustomWoocommerceApiSettings');
                ?>
                <div class="input-group m-2">
                    <span class="input-group-text">Woocommerce API User</span>
                    <input name="hgtwoocommercekey" type="text" value="<?php echo get_option('hgtwoocommercekey') ?>" aria-label="Wocommerce User" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">Woocommerce API Secret</span>
                    <input name="hgtwoocommercepassword" type="text" value="<?php echo get_option('hgtwoocommercepassword') ?>" aria-label="Wocommerce Password" class="form-control">
                </div>
                <div class="input-group m-2">
                    <button type="submit" value="wkeypass" class="btn btn-primary p-2 px-3">Save</button>
                </div>
            </form>
        </div>
        <div class="mt-5 mb-2 shadow pt-2 pe-4 ps-3 pb-3 rounded">
            <h4 class="p-2">Default Lebel For</h4>
            <form action="options.php" method="post">
                <?php
                settings_fields('htgDefaultSettings');
                do_settings_sections('htgDefaultSettings');
                ?>
                <div>
                    <p class="mb-1">Default Label URL for Order</p>
                    <div class="input-group me-3">

                        <span class="input-group-text">Default Lebel URL</span>
                        <input name="hgtwoocommercelebelUrl" type="text" value="<?php echo get_option('hgtwoocommercelebelUrl') ?>" class="form-control">
                    </div>
                </div>

                <div>
                    <p class="mb-1 mt-3">Default Delivery Option or Orders</p>
                    <div class="input-group mt-0 pt-0 me-3" style="width: 100%;">
                        <select style="height: 100%;" class="form-select" name="hgtDefaultDeliveryOption" aria-label="Default select example">
                            <option <?php if (!get_option('hgtDefaultDeliveryOption')) echo "selected"; ?>>Please Select</option>
                            <option <?php if (get_option('hgtDefaultDeliveryOption') == 1) echo "selected"; ?> value="1">Pick UP</option>
                            <option <?php if (get_option('hgtDefaultDeliveryOption') == 2) echo "selected"; ?> value="2">Delivery</option>
                        </select>
                    </div>
                </div>
                <div>
                    <p class="mb-1 mt-3">When to Trigger HTG Plugin Order function (Default is "processing")</p>
                    <div class="input-group me-2 bd-example">
                        <select class="form-select" style="height: 100%;" name="hgtDefaultOrderStatus" aria-label="Default select example">
                            <option <?php if (!get_option('hgtDefaultOrderStatus')) echo "selected"; ?>>Please Select</option>
                            <option <?php if (get_option('hgtDefaultOrderStatus') == 1) echo "selected"; ?> value="1">Pending payment</option>
                            <option <?php if (get_option('hgtDefaultOrderStatus') == 2) echo "selected"; ?> value="2">Failed</option>
                            <option <?php if (get_option('hgtDefaultOrderStatus') == 3) echo "selected"; ?> value="3">Processing</option>
                            <option <?php if (get_option('hgtDefaultOrderStatus') == 4) echo "selected"; ?> value="4">On hold</option>
                            <option <?php if (get_option('hgtDefaultOrderStatus') == 5) echo "selected"; ?> value="5">Completed</option>
                            <option <?php if (get_option('hgtDefaultOrderStatus') == 6) echo "selected"; ?> value="6">Refunded</option>

                        </select>
                    </div>
                </div>

                <div class="input-group mt-3 mb-2">
                    <button type="submit" value="wkeypass" class="btn btn-primary p-2 px-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>