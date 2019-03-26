<?php
$sql = "SELECT * FROM categories Where parent = 0";
$pquery = $db->query($sql);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a href="index.php" class="navbar-brand">Hayk's Boutique</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php while ($parent = mysqli_fetch_assoc($pquery)): ?>
                    <?php
                    $parent_id = $parent['id'];
                    $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
                    $cquery = $db->query($sql2);
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $parent['category']; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php while ($child = mysqli_fetch_assoc($cquery)): ?>
                                <a class="dropdown-item"
                                   href="category.php?cat=<?= $child['id']; ?>"><?php echo $child['category']; ?></a>
                            <?php endwhile; ?>
                        </div>
                    </li>
                <?php endwhile; ?>
                <li class="nav-item"><a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart"></i>My Cart</a></li>
            </ul>
        </div>
    </div>
</nav>
