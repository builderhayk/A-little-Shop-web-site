<?php
require_once '../core/init.php';
$id = $_POST['id'];
$id = (int)$id;
$sql = "Select * FROM products WHERE id= '$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);
$brand_id = $product['brand'];
$sql = "SELECT brand FROM brand WHERE id ='$'";
$brand_query = $db->query($sql);
$brand = mysqli_fetch_assoc($brand_query);
$sizestring = $product['sizes'];
$size_array = explode(',', $sizestring);
?>


<?php ob_start(); ?>
    <div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mx-auto"><?= $product['title']; ?></h4>
                    <button class="close ml-0" type="button" onclick="closeModal();" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <span id="modal_errors" class="col-sm-12 text-center"></span>
                            <div class="col-sm-6">
                                <div class="center-block">
                                    <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>"
                                         class="details img-responsive">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h4>Details</h4>
                                <p><?= nl2br($product['description']); ?></p>
                                <hr>
                                <p>Price: $<?= $product['price']; ?></p>
                                <p>Brand: <?= $brand['brand']; ?></p>
                                <form action="add_cart.php" method="post" id="add_product_form">
                                    <input type="hidden" name="product_id" id="product_id" value="<?= $id ;?>">
                                    <input type="hidden" name="available" id="available" value="">
                                    <div class="form-group">
                                        <div class="col-xs-3">
                                            <label for="quantity">Quantity:</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity"
                                                   min="0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="size">Size:</label>
                                        <select name="size" id="size" class="form-control">
                                            <option value="" selected></option>
                                            <?php foreach ($size_array as $string) {
                                                $string_array = explode(':', $string);
                                                $size = $string_array[0];
                                                $available = $string_array[1];
                                                echo '<option value="' . $size . '" data-available="' . $available . '">' . $size . '(' . $available . ' Available)</option>';
                                            } ?>
                                        </select>
                                        <div class="col-xs-3"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn bt-default" onclick="closeModal();">Close</button>
                    <button class="btn btn-warning" onclick="add_to_cart();return false;" type="submit"><i
                                class="fas fa-shopping-cart"></i> Add To Card
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#size').change(function () {
            var available = $('#size option:selected').data("available");
            $('#available').val(available);
        });


        function closeModal() {
            $('#details-modal').modal('hide');
            setTimeout(function () {
                $('#details-modal').remove();
                $('')
            }, 500)
        };
    </script>
<?php echo ob_get_clean(); ?>