<table class="table">
    <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Name</th>
            <th scope="col">HTG</th>
            <th scope="col">QTY</th>
            <th scope="col">Cost</th>
            <th scope="col">Tax</th>
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // var_dump($product_list);
        foreach ($product_list as $product) {
        ?>
            <tr <?php if ($product['ishtg'] == 0) {
                                        echo "class='table-warning'";
                                    } else {
                                        echo "class='table-dark'";
                                    } ?>>
                <th scope="row"><a href="<?php echo $product['url'] ?>"><?php echo $product['local_id'] ?></a></th>
                <td><?php echo $product['name'] ?></td>
                <td><?php if ($product['ishtg'] == 0) {
                        echo 'No';
                    } else echo "Yes"  ?></td>
                <td><?php echo $product['qty'] ?></td>
                <td><?php echo number_format((float) $product['totalPrice'] / $product['qty'], 2, '.', '') ?></td>
                <td><?php echo $product['tax'] ?></td>
                <td><?php echo number_format((float) $product['totalPrice'], 2, '.', '') ?></td>
            </tr>


        <?php
        }
        ?>

    </tbody>
</table>
<p>NOTE : Products that did't come from HTG will be ommited, And Showing here for history</p>