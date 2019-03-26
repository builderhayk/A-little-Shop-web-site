<?php
require_once 'core/init.php';
include "includes/head.php";
include "includes/navigation.php";
include "includes/headerpartial.php";
include "includes/leftbar.php";


if(isset($_GET['cat'])){
    $cat_id=sanitize($_GET['cat']);
}else{
    $cat_id='';
}



$sql = "SELECT * FROM products WHERE categories = '$cat_id'";
$productQ = $db->query($sql);
$category = get_category($cat_id);
?>

    <!--Main-content-->
    <div class="col-md-8">
        <h2 class="text-center"><?=$category['parent']. ' ' .$category['child']; ?></h2>
        <div class="row">
            <?php while ($product = mysqli_fetch_assoc($productQ)): ?>
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