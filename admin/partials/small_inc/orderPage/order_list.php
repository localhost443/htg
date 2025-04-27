<div class="shadow p-2 me-2 mt-2 mb-2 rounded">
    <table class="table bg-white rounded table-hover">
        <thead>
            <tr>
                <th scope="col">Order</th>
                <th scope="col">remote</th>
                <th scope="col">Order Date</th>
                <th scope="col">Confirm Date</th>
                <th scope="col">Pickup Date</th>
                <th scope="col">Status</th>
                <th scope="col">P/D</th>
                <th scope="col">action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET['page_number'])) {
                $pageno = $_GET['page_number'];
            } else {
                $pageno = 1;
            }
            $results_per_page = 50;
            global $wpdb;
            $all_result =  "SELECT * FROM " . $wpdb->prefix . "htgorderlist";
            $all_result =  $wpdb->get_results($all_result);
            $all_result = count($all_result);
            $wpdb->flush();
            $page_first_result = ($pageno - 1) * $results_per_page;
            $number_of_page = ceil($all_result / $results_per_page);


            $sql = "SELECT * FROM " . $wpdb->prefix . "htgorderlist ORDER BY local_order DESC LIMIT {$page_first_result}, {$results_per_page}";
            // $wpdb->show_errors();
            $htgProducts = $wpdb->get_results($sql);
            $htgProducts = json_decode(json_encode($htgProducts), true);
            // var_dump($htgProducts);
            foreach ($htgProducts as $value) {
            ?>
                <tr <?php if ($value['remote_order']) echo 'style="background-color: rgba(77, 175, 124, .3);"' ?>>
                    <td> <a href="<?php echo get_admin_url() . "post.php?post=" . $value['local_order'] . "&action=edit" ?>">#<?php echo $value['local_order'] . " "  . $value['name'] ?></a> </td>
                    <td><?php echo $value['remote_order'] ?? 'Not Assigend' ?></td>
                    <td><?php echo $value['order_creation_time'] ?></td>
                    <td><?php echo $value['order_update_time'] ?? "Unconfirmed" ?></td>
                    <td><?php echo $value['pickup_delivery_date'] ?? "Unconfirmed" ?></td>
                    <td><?php echo $value['remote_order_status'] ?? "Unconfirmed" ?></td>
                    <td><?php echo $value['PD'] ?? "Unconfirmed" ?></td>
                    <td><a href="<?php echo get_admin_url() . "admin.php?page=hgt-orders&orderid=" . $value['local_order'] ?>" class="btn btn-outline-primary shadow-sm" aria-current="page">Actions</a></td>
                </tr>
            <?php
            }
            $wpdb->flush();
            ?>

        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            if (!($pageno <= 1)) {
            ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo get_admin_url() ?>/admin.php?page=hgt-orders&page_number=<?php echo $pageno - 1?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php
            }
            ?>

            <?php
            for ($page = 1; $page <= $number_of_page; $page++) {
                $color = "";
                if($page == $pageno) {
                    $color = "bg-warning";
                }
                echo "<li class='page-item'><a class='page-link {$color}' href='" . get_admin_url() . "/admin.php?page=hgt-orders&page_number={$page}'>{$page}</a></li>";
            }
            ?>
            <?php
            //if (!($pageno >= $number_of_page)) {
            ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo get_admin_url() ?>/admin.php?page=hgt-orders&page_number=<?php echo $pageno + 1?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <?php
            //}
            ?>
            
        </ul>
    </nav>

</div>