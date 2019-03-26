<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shop/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';


$sql = "Select * FROM products WHERE deleted = 1";
$presults = $db->query($sql);
if (isset($_GET['refresh'])){
    $id=sanitize($_GET['refresh']);
    $db->query("UPDATE products SET deleted = 0, featured = 0 WHERE id = '$id'");
    header('Location:archived.php');
}
?>
    <div class="container">
        <h2 class="text-center">Archived items</h2>
        <hr>
        <div class="row">
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                <th></th>
                <th>Product</th>
                <th>Price</th>
                <th>Category</th>
                <th>Sold</th>
                </thead>
                <tbody>
                <?php while ($product = mysqli_fetch_assoc($presults)):
                    $childID = $product['categories'];
                    $catSql = "SELECT * FROM categories WHERE id = '$childID'";
                    $result = $db->query($catSql);
                    $child = mysqli_fetch_assoc($result);
                    $parentID = $child['parent'];
                    $pSql = "SELECT * FROM categories WHERE id = '$parentID'";
                    $presult = $db->query($pSql);
                    $parent = mysqli_fetch_assoc($presult);
                    $category = $parent['category'] . '~' . $child['category'];
                    ?>
                    <tr>
                        <td>
                            <a href="archived.php?refresh=<?= $product['id']; ?>" class="btn btn-xs btn-info"><i
                                    class="fas fa-sync-alt"></i></a>
                        </td>
                        <td><?= $product['title']; ?></td>
                        <td><?= money($product['price']); ?></td>
                        <td><?= $category; ?></td>
                        <td>0</td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>

<?php
include 'includes/footer.php'; ?>
