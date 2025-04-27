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
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container bg-white">
    <div class="w-75">
        
        <div class="mt-5 mb-3 shadow pt-2 pe-4 ps-3 pb-3 rounded">
        <h4 class="m-2 mb-3 underline">Start A Sync Now</h4>
            <div class="mb-4">
                <form action="#" method="post">
                    <button type="submit" value="htgsyncrequest" class="btn btn-primary p-2 px-3 m-2">Sync Now</button>
                </form>
            </div>
        </div>

        <div class="mt-5 mb-3 shadow pt-2 pe-4 ps-3 pb-3 rounded">
            <h4 class="m-2 mb-3">Change HTG API</h4>
            <form action="#" method="post">
                <div class="input-group m-2">
                    <span class="input-group-text">HTG API User</span>
                    <input disabled type="text" value="u_lovelyperfume" name="htgkey" aria-label="HTG API KEY" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">HTG API Secret</span>
                    <input disabled type="password" value="iRNlmTOFegkBtzYwMbF3" name="htgsecret" aria-label="HTG API SECRET" class="form-control">
                </div>
                <p class="m-2">Changing API Keys is currently Disabled</p>
                <div class="input-group m-2">
                    <button type="submit" value="hkeypass" class="btn btn-primary p-2 px-3">Save</button>
                </div>
            </form>
        </div>

        <div class="mt-5 mb-2 shadow pt-2 pe-4 ps-3 pb-3 rounded">
            <h4 class="m-2 mb-3">Change WOOCOMMERCE API</h4>
            <form action="#" method="post">
                <div class="input-group m-2">
                    <span class="input-group-text">Woocommerce API User</span>
                    <input name="wkey" disabled type="text" value="*" aria-label="Wocommerce User" class="form-control">
                </div>
                <div class="input-group m-2">
                    <span class="input-group-text">Woocommerce API Secret</span>
                    <input name="wsecret" disabled type="password" value="*******************" aria-label="Wocommerce Password" class="form-control">
                </div>
                <p class="m-2">Changing API Keys is currently Disabled</p>
                <div class="input-group m-2">
                    <button type="submit" value="wkeypass" class="btn btn-primary p-2 px-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>