<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shop/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
$hashed=$user_data['password'];
$old_password=((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password=trim($old_password);
$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
$password=trim($password);
$confirm=((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm=trim($confirm);
$new_hashed=password_hash($password,PASSWORD_DEFAULT);
$user_id=$user_data['id'];
$errors=array();

?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto text-center card">
                <div id="login-form card-body">
                    <div>
                        <?php
                        if ($_POST){
                            //form validation
                            if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
                                $errors[]='You must fill out all fields.';
                            }
                            //password is more than 6 charackters
                            if(strlen($password)<6){
                                $errors[]='Password must be at least 6 characters.';
                            }
                            //if new password matches confirm
                            if($password !=$confirm){
                                $errors[]='The new password and confirm new password does not match!';
                            }
                            if(!password_verify($old_password,$hashed)){
                                $errors[]='Your old password is not correct';
                            }
                            //check for errors
                            if(!empty($errors)){
                                echo display_errors($errors);
                            }else{
                                //change password
                                $db->query("UPDATE users SET password = '$new_hashed' WHERE id='$user_id'");
                                $_SESSION['success_flash']='Your password has been updated';
                                header('Location:index.php');
                            }
                        }
                        ?>
                    </div>
                    <h2 class="text-center text-primary mt-3">Change Password</h2>
                    <hr>
                    <form action="change_password.php" method="post">
                        <div class="form-group">
                            <label for="old_password">Old Password:</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
                        </div>
                        <div class="form-group">
                            <label for="password">New Password:</label>
                            <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password:</label>
                            <input type="password" name="confirm" id="confirm_password" class="form-control" value="<?=$confirm;?>">
                        </div>
                        <div class="form-group text-left ">
                            <a href="index.php" class="btn btn-warning">Cancel</a>
                            <input type="submit" value="Login" class="btn btn-primary ">
                        </div>
                    </form>
                    <p class="text-right"><a href="/shop/index.php" alt="home" class="btn btn-link">Visit Site</a></p>
                </div>
            </div>
        </div>
    </div>

<?php
include 'includes/footer.php'; ?>