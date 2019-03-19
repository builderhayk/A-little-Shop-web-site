<?php
require_once '../core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
//get brands from db
$sql = "SELECT * FROM brand ORDER BY brand";
$results = $db->query($sql);
$errors = array();
//EDIT brand

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "SELECT * FROM brand WHERE id= '$edit_id'";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);

}
//Delete Brand
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "DELETE FROM brand WHERE id= '$delete_id'";
    $db->query($sql);
    header('location:brands.php');
}
//if add form is submittted
if (isset($_POST['add_submit'])) {
    $brand = sanitize($_POST['brand']);
    //check if brand is blank
    if ($_POST['brand'] == '') {
        $errors[] .= 'You must enter a brand!';
    }
    //check if brand exists in database
    $sql = "SELECT * FROM brand WHERE brand ='$brand'";
    if(isset($_GET['edit'])){
        $sql="SELECT 8 FROM brand WHERE brand = '$brand' AND id !='$edit_id'";
    }
    $result = $db->query($sql);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $errors[] .= $brand . 'already excists,please choose another brand';
    }
    //display errors
    if (!empty($errors)) {
        echo display_errors($errors);
    } else {
        //Add brand to database
        $sql = "INSERT INTO brand (brand) VALUE ('$brand')";
        if(isset($_GET['edit'])){
            $sql=" UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
        }
        $db->query($sql);
        header('Location:brands.php');
    }
}

?>
    <h2 class="text-center">Brands</h2>
    <hr>
    <!--Brand Form-->
    <div class="d-flex justify-content-center ">
        <form action="brands.php<?= ((isset($_GET['edit'])) ? '?edit=' . $edit_id : '') ?>" method="post"
              class="form-inline">
            <div class="form-group">
                <?php
                $brand_value = '';
                if (isset($_GET['edit'])) {
                    $brand_value = $eBrand['brand'];
                } else {
                    if (isset($_POST['brand'])) {
                        $brand_value = sanitize($_POST['brand']);
                    }
                }
                ?>
                <label for="brand" class="mr-2"><?= ((isset($_GET['edit'])) ? 'Edit' : 'Add a') ?> Brand: </label>
                <input type="text " name="brand" id="brand" class="form-control mr-2"
                       value="<?= $brand_value; ?>">
                <?php if (isset($_GET['edit'])): ?>
                    <a href="brands.php" class="btn btn-info mr-2">Cancel</a>
                <?php endif; ?>
                <input type="submit" name="add_submit" value="<?= ((isset($_GET['edit'])) ? 'Edit' : 'Add a') ?> Brand"
                       class="btn btn-success btn-md">
            </div>
        </form>
    </div>
    <hr>

    <table class="table table-bordered table-striped table-auto">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Brand</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php while ($brands = mysqli_fetch_assoc($results)): ?>
            <tr>
                <td><a href="brands.php?edit=<?= $brands['id'] ?>" class="btn btn-xs btn-info"><i
                                class="fas fa-pencil-alt"></i></a></td>
                <td><?= $brands['brand'] ?></td>
                <td><a href="brands.php?delete=<?= $brands['id'] ?>" class="btn btn-xs btn-danger"><i
                                class="far fa-trash-alt"></i></a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

<?php include 'includes/footer.php' ?>