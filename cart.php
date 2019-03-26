<?php
require_once 'core/init.php';
include "includes/head.php";
include "includes/navigation.php";
include 'includes/headerpartial.php';


if ($cart_id != '') {
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
    $result = mysqli_fetch_assoc($cartQ);
//    var_dump($result['items']);
    $items = json_decode($result['items'], true);
//    var_dump($items);
    $i = 1;
    $sub_total = 0;
    $item_count = 0;

};
?>


    <div class="col-md-12 text-center">
        <h2 class="text-center mt-4">
            My Shopping Cart
        </h2>
        <hr>
        <?php if ($cart_id == ''): ?>
            <div class="col-md-12">
                <div class="bg-danger py-1 pt-3">
                    <p class="text-center text-light">Your shopping cart is empty </p>
                </div>
            </div>
        <?php else: ?>
            <table class="table table-bordered-table-condensed table-striped">
                <thead>
                <th>#</th>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Size</th>
                <th>Sub Total</th>
                </thead>
                <tbody>
                <?php
                foreach ($items as $item) {
                    $product_id = $item['id'];
                    $productQ = $db->query("SELECT * FROM products WHERE id='$product_id'");
                    $product = mysqli_fetch_assoc($productQ);
                    $sArray = explode(',', $product['sizes']);
                    foreach ($sArray as $sizesString) {
                        $s = explode(':', $sizesString);
                        if ($s[0] == $item['size']) {
                            $available = $s[1];
                        }
                    }

                    ?>
                    <tr>
                        <td> <?= $i; ?></td>
                        <td><?= $product['title'] ?></td>
                        <td><?= money($product['price']); ?></td>
                        <td>
                            <button class="btn btn-xs btn-danger"
                                    onclick="update_cart('removeone','<?= $product['id']; ?>','<?= $item['size'] ?>');">
                                -
                            </button>
                            <b><?= $item['quantity']; ?></b>
                            <?php if ($item['quantity'] < $available): ?>
                                <button class="btn btn-xs btn-primary"
                                        onclick="update_cart('addone','<?= $product['id']; ?>','<?= $item['size'] ?>');">
                                    +
                                </button>
                            <?php else: ?>
                                <span class="text-danger">Max </span>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['size']; ?></td>
                        <td><?= money($item['quantity'] * $product['price']); ?></td>
                    </tr>
                    <?php
                    $i++;
                    $item_count += $item['quantity'];
                    $sub_total += ($product['price'] * $item['quantity']);
                }
                $tax = TAXRATE * $sub_total;
                $tax = number_format($tax, 2);
                $grand_total = $tax + $sub_total;
                ?>
                </tbody>
            </table>
            <table class="table table-bordered table-condensed text-right">
                <h2 class="text-left mt-3">Totals</h2>
                <hr>
                <thead class="totals-table-header">
                <th>Total Items</th>
                <th>Sub Total</th>
                <th>Tax</th>
                <th>Grand Total</th>
                </thead>
                <tbody>
                <tr>
                    <td><?= $item_count; ?></td>
                    <td><?= money($sub_total) ?></td>
                    <td><?= money($tax); ?></td>
                    <td class=" text-success"><?= money($grand_total); ?></td>
                </tr>
                </tbody>
            </table>
            <!-- chekc out modal button -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#checkoutModal">
                <span> <i class="fas fa-shopping-cart"></i> </span>Check out >>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="checkoutModalLabel">Shipping Adress</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <form action="thank_you.php" method="post" id="payment-form">
                                        <div id="step1" style="display:block;">
                                            <span id="payment-errors" class="bg-danger"></span>
                                            <div class="row">
                                                <div class="from-group col-md-6">
                                                    <label for="full_name">Full Name:</label>
                                                    <input type="text" class="form-control" name="full_name"
                                                           id="full_name">
                                                </div>
                                                <div class="from-group col-md-6">
                                                    <label for="email">Email:</label>
                                                    <input type="text" class="form-control" name="email" id="email">
                                                </div>
                                                <div class="from-group col-md-6">
                                                    <label for="street">Street Adress :</label>
                                                    <input type="text" class="form-control" name="street" id="street">
                                                </div>
                                                <div class="from-group col-md-6">
                                                    <label for="street2">Street Adress 2:</label>
                                                    <input type="text" class="form-control" name="street2" id="street2">
                                                </div>
                                                <div class="from-group col-md-6">
                                                    <label for="city">City:</label>
                                                    <input type="text" class="form-control" name="city" id="city">
                                                </div>
                                                <div class="from-group col-md-6">
                                                    <label for="state">State:</label>
                                                    <input type="text" class="form-control" name="state" id="state">
                                                </div>
                                                <div class="from-group col-md-6">
                                                    <label for="zip_code">Zip Code:</label>
                                                    <input type="text" class="form-control" name="zip_code"
                                                           id="zip_code">
                                                </div>
                                                <div class="from-group col-md-6">
                                                    <label for="country">Country:</label>
                                                    <input type="text" class="form-control" name="country" id="country">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="step2" style="display: none;">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="name">Name on Card:</label>
                                                    <input type="text" id="name" class="form-control">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="number">Card Number:</label>
                                                    <input type="text" id="number" class="form-control">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="cvc" id="lbl-cvc">CVC:</label>
                                                    <input type="text" id="cvc" class="form-control">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="exp-month">Expire Month:</label>
                                                    <select class="form-control" id="exp-month">
                                                        <option value=""></option>
                                                        <?php for ($i = 1; $i < 13; $i++): ?>
                                                            <option value="<?= $i; ?>"><?= $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="exp-year">Expire Year:</label>
                                                    <select id="exp-year" class="form-control">
                                                        <option value=""></option>
                                                        <?php $yr = date('Y') ?>
                                                        <?php for ($i = 0; $i < 11; $i++): ?>
                                                            <option value="<?= $yr + $i; ?>"><?= $yr + $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="check();" id="next_button">Next >>
                            </button>
                            <button type="button" class="btn btn-primary" onclick="check_back();" id="back_button"
                                    style="display: none"><< Back
                            </button>
                            <button type="submit" class="btn btn-primary" id="checkout_button" style="display: none">
                                Check Out
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
    <script>
        function check_back() {
            $('#payment-errors').html("");
            $('#step1').css("display", "block");
            $('#step2').css("display", "none");
            $('#next_button').css("display", "inline-block");
            $('#back_button').css("display", "none");
            $('#checkout_button').css("display", "none");
            $('#checkoutModalLabel').html("Shipping Address")
        }

        var inputs = new Array('full_name', 'email', 'street', 'street2', 'city', 'state', 'zip_code', 'country');

        function check() {
            var data = {};
            for (var i = 0; i < inputs.length; i++) {
                data[inputs[i]] = $('#' + inputs[i]).val();
            }


            $.ajax({
                'url': '/shop/admin/parsers/check_address.php',
                'method': 'POST',
                data: data,
                success: function (data) {
                    if (data != 'passed') {
                        $('#payment-errors').html(data);
                    }

                    if (data == 'passed') {
                        $('#payment-errors').html("");
                        $('#step1').css("display", "none");
                        $('#step2').css("display", "block");
                        $('#next_button').css("display", "none");
                        $('#back_button').css("display", "inline-block");
                        $('#checkout_button').css("display", "inline-block");
                        $('#checkoutModalLabel').html("Enter Your Cart details")
                    }
                },
                error: function () {
                    alert("something went wrong");
                }
            });
        }
    </script>

<?php
include 'includes/footer.php';
?>