<?php
require_once '../core/init.php';

include 'includes/head.php';
include 'includes/navigation.php';
if (!is_logged_in()) {
    login_error_redirect();
}
if (!has_permission('admin')) {
    permission_error_redirect('index.php');
}
if (isset($_GET["delete"])) {
    $delete_id = sanitize($_GET['delete']);
    $db->query("DELETE FROM users WHERE id='$delete_id'");
    $_SESSION['success_flash'] = 'User has been deleted';
    header("location:users.php");
}
if (isset($_GET['add'])) {
    $name = ((isset($_POST['name'])) ? sanitize($_POST['name']) : '');
    $email = ((isset($_POST['email'])) ? sanitize($_POST['email']) : '');
    $password = ((isset($_POST['password'])) ? sanitize($_POST['password']) : '');
    $confirm = ((isset($_POST['confirm'])) ? sanitize($_POST['confirm']) : '');
    $permissions = ((isset($_POST['permissions'])) ? sanitize($_POST['permissions']) : '');
    $errors = array();
    if ($_POST) {
        $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
        $emailCount = mysqli_num_rows($emailQuery);
        if ($emailCount != 0) {
            $errors[] = "The email is already in use , choose another email";
        }

        //fields are not empty
        $required = array('name', 'email', 'password', 'confirm', 'permissions');
        foreach ($required as $f) {
            if (empty($_POST[$f])) {
                $errors[] = "You must fill out all fields";
                break;
            }
        }
        if (strlen($password) < 6) {
            $errors[] = 'Your password must be at least 6 characters';
        }

        if ($password != $confirm) {
            $errors[] = 'Your passwords dont match';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'The email you entered is not valid';
        }

        if (!empty($errors)) {
            echo display_errors($errors);
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $db->query("INSERT INTO users (full_name,email,password,permission) VALUES ('$name','$email','$hashed','$permissions')");
            $_SESSION['success_flash'] = 'The User has Added';
            header('Location:users.php');
        }

    }

    ?>
    <div class="container">
        <h2 class="text-center">Adding New User</h2>
        <hr>

        <form action="users.php?add=1" method="post">
            <div class="row ">
                <div class="form-group col-md-6">
                    <label for="name">Full Name:</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?= $name; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= $email; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" value="<?= $password; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="confirm">Confirm Password:</label>
                    <input type="password" class="form-control" name="confirm" id="confirm" value="<?= $confirm; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="permissions">Permissions:</label>
                    <select id="permissions" name="permissions" class="form-control">
                        <option value=""<?= (($permissions == '') ? ' selected' : ''); ?>></option>
                        <option value="editor"<?= (($permissions == 'editor') ? ' selected' : ''); ?>>Editor</option>
                        <option value="admin,editor"<?= (($permissions == 'admin,editor') ? ' selected' : ''); ?>>Admin
                        </option>
                    </select>
                </div>
                <div class="form-group col-md-6 text-right pt-2 mt-4">
                    <a href="users.php" class="btn btn-warning">Cancel</a>
                    <input type="submit" value="Add User" class="btn btn-success">
                </div>
            </div>
        </form>

    </div>

    <?php
} else {


    $userQuery = $db->query("SELECT * FROM users ORDER BY full_name");
    ?>
    <div class="container">
        <h2 class="text-center">Users</h2>
        <a href="users.php?add=1" class="btn btn-success float-right" id="add-product">Add New User</a>
        <hr>
        <div class="row">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <th>

                </th>
                <th>Name</th>
                <th>Email</th>
                <th>Join Date</th>
                <th>Last Login</th>
                <th>Permissions</th>
                </thead>
                <tbody>
                <?php while ($user = mysqli_fetch_assoc($userQuery)): ?>
                    <tr>
                        <td>
                            <?php if ($user['id'] != $user_data['id']):; ?>
                                <a href="users.php?delete=<?= $user['id'] ?>" class="btn btn-danger btn-xs"><i
                                            class="fas fa-trash-alt"></i></a>
                            <?php endif; ?>
                        </td>
                        <td><?= $user['full_name']; ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= pretty_date($user['join_date']) ?></td>
                        <td><?=(($user['last_login']=='0000-00-00 00:00:00')?'didnt log in yet': pretty_date($user['last_login'])); ?></td>
                        <td><?= $user['permission'] ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php }
include 'includes/footer.php' ?>