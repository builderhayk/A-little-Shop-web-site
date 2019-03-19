<?php
require_once 'core/init.php';
include "includes/head.php";
include "includes/navigation.php";
include "includes/headerfull.php";
include "includes/leftbar.php";

$sql = "SELECT * FROM products WHERE featured = 1";
$featured = $db->query($sql);
?>

    <!--Main-content-->
    <div class="col-md-8">
        <h2 class="text-center">Feature Products</h2>
        <div class="row">
            <?php while ($product = mysqli_fetch_assoc($featured)): ?>
                <div class="col-sm-3 text-center">
                    <h4 ><?= $product['title'];?></h4>
                    <img class="img-thumb text-center " src="<?= $product['image'];?>" alt="<?= $product['title'];?>">
                    <p class="list-price text-danger">List Price <s>$<?= $product['list_price'];?></s></p>
                    <div class="price">Our Price: $<?= $product['price'];?></div>
                    <button type="button" class="btn btn-sm btn-success mt-2" onclick="detailsmodal(<?=$product['id']?>);">
                        Details
                    </button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php

include "includes/rightbar.php";
include "includes/footer.php";
?>