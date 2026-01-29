<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Inventory System' ?></title>
    <link rel="stylesheet" href="<?= url('/assets/styles.css') ?>">
    <script>
        window.APP_BASE_PATH = '<?= url('') ?>';
    </script>
</head>

<body>

    <nav>
        <a href="<?= url('/products') ?>">Products</a> |
        <a href="<?= url('/suppliers') ?>">Suppliers</a> |
        <a href="<?= url('/products/search') ?>">Search</a>
        <?php if (isset($_SESSION['user_id'])): ?>
        | <a href="<?= url('/logout') ?>">Logout (<?= $_SESSION['username'] ?>)</a>
        <?php endif; ?>
    </nav>
    <hr>