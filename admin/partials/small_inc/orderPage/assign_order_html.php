<h2 class="m-2">Assign Order</h2>
<div class="container">
    <form action="#" method="post">
        <!-- I am adding this if requires later , You should never ever edit this fields, 
        Because this field should be unique ,  -->
        <!-- <div class="input-group mb-3" style="display: none;">
            <span class="input-group-text" id="basic-addon1">Reference</span>
            <input type="text" disabled name="order_referance" value="<?php //echo $htgProducts[0]['local_order'] ?>" class="form-control">
        </div> -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">PickUP / Delivery</span>
            <select required name="pickdev" style="height: 110%;" class="form-select input-group form-select-lg">
                <option value="1">Pickup</option>
                <option selected value="2">Delivery</option>
            </select>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">PickUP / Delivery Date</span>
            <input id="nowHtgDateTime" value="<?php echo date('Y-m-d\TH:i:s') ?>" type="datetime-local" name="dateTime" id="datetime-local">
        </div>
        <script>
            // window.addEventListener('load', () => {
            //     const nowHtgDateTime = new Date();
            //     now.setMinutes(now.getMinutes() - nowHtgDateTime.getTimezoneOffset());
            //     document.getElementById('nowHtgDateTime').value = nowHtgDateTime.toISOString().slice(0, -1);
            // });
        </script>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Email</span>
            <input type="text" name="order_email" value="<?php echo $order->get_billing_email() ?>" class="form-control">
        </div>
        <div class="input-group mb-3">
            <p> Shipping Address </p>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">Name</span>
                <input type="text" value="<?php echo $order->get_shipping_first_name() . " " . $order->get_shipping_last_name() ?>" name="shipping_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">Shipping Company</span>
                <input type="text" value="<?php echo $order->get_shipping_method() ?>" name="shipping_company" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">street</span>
                <input name="shippingstreet" value="<?php echo $order->get_shipping_address_1() ?>" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">zip</span>
                <input type="text" name="shippingzip" value="<?php echo $order->get_shipping_postcode() ?>" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">city</span>
                <input type="text" name="shippingCity" value="<?php echo $order->get_shipping_city() ?>" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">country</span>
                <input type="text" value="<?php echo get_post_meta( $_GET['orderid'], '_shipping_country', true )?>" name="shippingCountry" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">phonenumber</span>
                <input type="text" required value="<?php echo $order->get_billing_phone() ?>" name="shippingPhonenumber" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>

            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">Lebel</span>
                <input type="text" id='UploadForHTG' value="<?php echo get_option('hgtwoocommercelebelUrl') ?>" name="htglebel" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
            <button class="btn btn-primary" name="confirm_order" value="confirm_order" type="submit">Confirm Order</button>
        </div>


    </form>
</div>