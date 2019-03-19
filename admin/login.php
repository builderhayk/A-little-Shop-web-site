<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shop/core/init.php';
include 'includes/head.php';
//
//$hash='password';
//$hashedp=password_hash($hash,PASSWORD_DEFAULT);
//echo $hashedp;die;
$email=((isset($_POST['email']))?sanitize($_POST['email']):'');
$email=trim($email);
$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
$password=trim($password);
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
                        if(empty($_POST['email']) || empty($_POST['password'])){
                            $errors[]='You must provide email or password.';
                        }
                        //validat email adress
                        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                            $errors[]='Email is not valid.';
                        }
                        //password is more than 6 charackters
                        if(strlen($password)<6){
                            $errors[]='Password must be at least 6 characters.';
                        }

                        //if the email exists in the database
                        $query=$db->query("SELECT * FROM users WHERE email = '$email'");
                        $user = mysqli_fetch_assoc($query);
                        $userCount =mysqli_num_rows($query);
                        if ($userCount<1){
                            $errors[]='Email does not exist in data';
                        }
                        if(!password_verify($password,$user['password'])){
                            $errors[]='The password does not match';
                        }
                        //check for errors
                        if(!empty($errors)){
                            echo display_errors($errors);
                        }else{
                            //log user in
                            $user_id=$user['id'];
                                login($user_id);
                        }
                    }
                    ?>
                </div>
                <h2 class="text-center text-primary mt-3">Login</h2>
                <hr>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?=$email?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" value="<?=$password?>">
                    </div>
                    <div class="form-group text-left ">
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