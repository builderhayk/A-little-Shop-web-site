<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a href="index.php" class="navbar-brand">Hayk's Boutique Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="brands.php">Brands</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                <?php if (has_permission('admin')): ?>
                    <li class="nav-item"><a class="nav-link" href="archived.php">Archived</a></li>
                    <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                <?php endif; ?>

                <li class="nav-item  dropdown">
                    <a href="#" class=" nav-link dropdown-toggle" data-toggle="dropdown">Hello <?= $user_data['first']; ?> !
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" >
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
