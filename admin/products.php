<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shop/core/init.php';
if (!is_logged_in()) {
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

//delete product

if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
    header('Location:products.php');
}

$dbpath = '';
if (isset($_GET['add']) || isset($_GET['edit'])) {
    $brandQuery = $db->query("SELECT * FROM brand");
    $parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
    $title = ((isset($_POST['title']) && $_POST['title'] != '') ? sanitize($_POST['title']) : '');
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand'])) ? sanitize($_POST['brand']) : '');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent'])) ? sanitize($_POST['parent']) : '');
    $category = ((isset($_POST['child'])) && !empty($_POST['child']) ? sanitize($_POST['child']) : '');
    $price = ((isset($_POST['price']) && !empty($_POST['price'])) ? sanitize($_POST['price']) : '');
    $list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price'])) ? sanitize($_POST['list_price']) : '');
    $description = ((isset($_POST['description']) && !empty($_POST['description'])) ? sanitize($_POST['description']) : '');
    $sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes'])) ? sanitize($_POST['sizes']) : '');
    $save_image = '';
    if (isset($_GET['edit'])) {
        $edit_id = (int)$_GET['edit'];
        $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
        $product = mysqli_fetch_assoc($productResults);
        if (isset($_GET['delete_image'])) {
            $image_url = $_SERVER['DOCUMENT_ROOT'] . $product['image'];
//            echo $image_url;die;
            unlink($image_url);
            $db->query("UPDATE products SET image = '' WHERE id='$edit_id'");
            header('location:products.php?edit=' . $edit_id);
        }
        $category = ((isset($_POST['child']) && $_POST['child'] != '') ? sanitize($_POST['child']) : $product['categories']);
        $title = ((isset($_POST['title']) && $_POST['title'] != '') ? sanitize($_POST['title']) : $product['title']);
        $brand = ((isset($_POST['brand']) && $_POST['brand'] != '') ? sanitize($_POST['brand']) : $product['brand']);
        $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
        $parentResult = mysqli_fetch_assoc($parentQ);
        $parent = ((isset($_POST['parent']) && $_POST['parent'] != '') ? sanitize($_POST['parent']) : $parentResult['parent']);
        $price = ((isset($_POST['price']) && $_POST['price'] != '') ? sanitize($_POST['price']) : $product['price']);
        $price = ((isset($_POST['price']) && $_POST['price'] != '') ? sanitize($_POST['price']) : $product['price']);
        $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '') ? sanitize($_POST['list_price']) : $product['list_price']);
        $description = ((isset($_POST['description']) && $_POST['description'] != '') ? sanitize($_POST['description']) : $product['description']);
        $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '') ? sanitize($_POST['sizes']) : $product['sizes']);
        $save_image = (($product['image'] != '') ? $product['image'] : '');
        $dbpath = $save_image;
    }
    $sizesArray = array();
    if (!empty($sizes)) {
        $sizeString = sanitize($sizes);
//            $sizeString = rtrim($sizeString, ',');
        $sizesArray = explode(',', $sizeString);
        $sArray = array();
        $qArray = array();
        foreach ($sizesArray as $ss) {
            $s = explode(':', $ss);
            $sArray[] = $s[0];
            $qArray[] = $s[1];
        }
    } else {
        $sizesArray = array();
    }
    if ($_POST) {
        $errors = array();
        $required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
        foreach ($required as $field) {
            if ($_POST[$field] == '') {
                $errors[] = 'All fields with and Astrisks are required.';
                break;
            }
        }
        if (!empty($_FILES)) {
            if ($_FILES['photo']['size'] != 0) {
                $photo = $_FILES['photo'];
                $name = $photo['name'];
                $nameArray = explode('.', $name);
                $fileName = $nameArray[0];
                $fileExt = $nameArray[1];
                $mime = explode('/', $photo['type']);
                $mimeType = $mime[0];
                $mimeExt = $mime[1];
                $tmpLoc = $photo['tmp_name'];
                $fileSize = $photo['size'];
                $allowed = array('png', 'jpg', 'jpeg', 'gif');
                $uploadName = md5(microtime()) . '.' . $fileExt;
                $uploadPath = BASEURL . 'images/products/' . $uploadName;
                $dbpath = '/shop/images/products/' . $uploadName;
                if ($mimeType != 'image') {
                    $errors[] = 'The file must be an image.';
                }
                if (!in_array($fileExt, $allowed)) {
                    $errors[] = 'The photo extension must be a png,jpg,jpeg or gif.';
                }
                if ($fileSize > 1000000) {
                    $errors[] = 'The file should be under 10mbs.';
                }
                if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
                    $errors[] = 'File extension does not match the file.';
                }
            } else {
                $errors[] = 'Please choose an image';
            }
        }
        if (!empty($errors)) {
            echo display_errors($errors);
        } else {
            //upload file and insert to database
            if (!empty($_FILES)) {
                move_uploaded_file($tmpLoc, $uploadPath);
            }
            $sizesrt = rtrim($sizes, ',');

            $insertSql = "INSERT INTO products (title,price,list_price,brand,categories,sizes,image,description)
                                        VALUES ('$title','$price','$list_price','$brand','$category','$sizesrt','$dbpath','$description')";
            if (isset($_GET['edit'])) {
                $insertSql = "UPDATE products SET title = '$title',price='$price',list_price='$list_price',
                              brand='$brand',categories ='$category',sizes='$sizesrt',image='$dbpath',description ='$description' WHERE id ='$edit_id'";
            }
            $db->query($insertSql);
            header('location:products.php');
        }
    }
    ?>
    <h2 class="text-center"><?= ((isset($_GET['edit'])) ? 'Edit' : 'Add a new') ?> Product</h2>
    <hr>
    <div class="container-fluid">
        <form action="products.php?<?= ((isset($_GET['edit'])) ? 'edit=' . $edit_id : 'add=1') ?>" method="POST"
              enctype="multipart/form-data" class="row">
            <div class="form-group col-md-3">
                <label for="title">Title*:</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="<?= $title; ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="brand">Brand*:</label>
                <select name="brand" id="brand" class="form-control">
                    <option value=""<?= (($brand == '') ? 'selected' : ''); ?>></option>
                    <?php while ($b = mysqli_fetch_assoc($brandQuery)): ?>
                        <option value="<?= $b['id'] ?>"<?= (($brand == $b['id']) ? 'selected' : ''); ?>><?= $b['brand'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="parent">Parent Category*:</label>
                <select name="parent" id="parent" class="form-control">
                    <option value=""<?= (($parent == '') ? 'selected' : '') ?>></option>
                    <?php while ($p = mysqli_fetch_assoc($parentQuery)): ?>
                        <option value="<?= $p['id'] ?>"<?= ($parent == $p['id'] ? 'selected' : '') ?>><?= $p['category']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="child">Child Category*:</label>
                <select name="child" class="form-control" id="child"></select>
            </div>
            <div class="form-group col-md-3">
                <label for="price">Price*:</label>
                <input class="form-control" type="text" name="price" id="price"
                       value="<?= $price; ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="list_price">List Price:</label>
                <input class="form-control" type="text" name="list_price" id="list_price"
                       value="<?= $list_price; ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="">Quantity & Sizes*:</label>
                <button class="btn btn-outline-success form-control"
                        onclick="$('#sizesModal').modal('toggle');return false;">Quantity & Sizes
                </button>
            </div>
            <div class="form-group col-md-3">
                <label for="sizes">Sizes & Qty Prev</label>
                <input type="text" name="sizes" id="sizes"
                       value="<?= $sizes; ?>" class="form-control" readonly>
            </div>
            <div class="form-group col-md-6">
                <?php if ($save_image != ''): ?>
                    <div class="saved-image">
                        <img src="<?= $save_image ?>" alt="saved_image">
                        <a href="products.php?delete_image=1&edit=<?= $edit_id; ?>" class="text-danger">Delete Image</a>
                    </div>
                <?php else: ?>
                    <label for="photo">Product Photo*:</label>
                    <input type="file" class="form-control-file" name="photo" id="photo">
                <?php endif; ?>
            </div>
            <div class="form-group col-md-6">
                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="6"
                          class="form-control"><?= $description; ?></textarea>
            </div>
            <div class="form-group pull-right w-100 p-3 text-right">
                <a href="products.php" class="btn btn-warning">Cancel</a>
                <input type="submit" value="<?= ((isset($_GET['edit'])) ? 'Edit' : 'Add') ?>Product"
                       class="btn btn-success ">
            </div>
            <div class="clearfix"></div>
        </form>
        <!--        //modal-->
        <div class="modal fade " id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="sizesModalLabel">Sizes & Quantity</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <div class="form-group col-xl-4">
                                        <label for="size<?= $i; ?>">Size:</label>
                                        <input class="form-control" type="text" name="size<?= $i; ?>"
                                               id="size<?= $i; ?>"
                                               value="<?= ((!empty($sArray[$i - 1])) ? $sArray[$i - 1] : ''); ?>">
                                    </div>
                                    <div class="form-group col-xl-2">
                                        <label for="qty<?= $i; ?>">Quantity:</label>
                                        <input class="form-control" type="number" name="qty<?= $i; ?>"
                                               id="qty<?= $i; ?>"
                                               value="<?= ((!empty($qArray[$i - 1])) ? $qArray[$i - 1] : ''); ?>"
                                               min="0">
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                                onclick="updateSizes();$('#sizesModal').modal('toggle');return false;">Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--        endmodal-->
    </div>
<?php } else {

    $sql = "Select * FROM products WHERE deleted = 0";
    $presults = $db->query($sql);
    if (isset($_GET['featured'])) {
        $id = (int)$_GET['id'];
        $featured = (int)$_GET['featured'];
        $featuredSql = "UPDATE products SET featured = '$featured' WHERE id= '$id'";
        $db->query($featuredSql);
        header('Location:products.php');
    }
    ?>
    <h2 class="text-center">Products</h2>
    <div class="container-fluid">
        <a href="products.php?add=1" class="btn btn-success float-right" id="add-product">Add Product</a>
        <div class="clearfix"></div>
        <hr>
        <table class="table table-bordered table-condensed table-striped">
            <thead>
            <th></th>
            <th>Product</th>
            <th>Price</th>
            <th>Category</th>
            <th>Featured</th>
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
                        <a href="products.php?edit=<?= $product['id']; ?>" class="btn btn-xs btn-info"><i
                                    class="fas fa-pencil-alt"></i></a>
                        <a href="products.php?delete=<?= $product['id']; ?>" class="btn btn-xs btn-danger"><i
                                    class="fas fa-trash-alt"></i></a>
                    </td>
                    <td><?= $product['title']; ?></td>
                    <td><?= money($product['price']); ?></td>
                    <td><?= $category; ?></td>
                    <td>
                        <a href="products.php?featured=<?= (($product['featured'] == 0) ? '1' : '0'); ?>&id=<?= $product['id']; ?>"
                           class="btn btn-xs btn-light">
                            <i class="fas fa-<?= (($product['featured'] == 1) ? 'minus' : 'plus'); ?>"></i>
                        </a>&nbsp <?= (($product['featured'] == 1) ? 'Featured Product' : '') ?></td>
                    <td>0</td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php }
include 'includes/footer.php'; ?>
<script>
    $('document').ready(function () {
        get_child_options('<?=$category?>');
    })
</script>