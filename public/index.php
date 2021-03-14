<?php

use App\NumberHelper;
use App\QueryBuilder;
use App\TableHelper;
use App\URLHelper;
use App\Table;

define('PER_PAGE', 20);

require '../vendor/autoload.php';
$pdo = new PDO("sqlite:../products.db", null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$query = (new QueryBuilder($pdo))->from('products');

// Search by city
if (!empty($_GET['q'])) {
    $query
        ->where('city LIKE :city')
        ->setParam('city', '%' . $_GET['q'] . '%');
}

$table = (new Table($query, $_GET))
    ->sortable('id', 'city', 'price')
    ->format('price', function($value) {
        return NumberHelper::price($value);
    })
    ->columns([
        'id' => 'ID',
        'name' => 'Nom',
        'city' => 'Ville',
        'price' => 'Prix'
    ]);

// dd($pages);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biens immobilier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body class="p-4">
    <h1>Les biens immobiliers</h1>
    <form action="" method="GET" class="mb-4">
        <div class="form-group">
            <input type="text" name="q" class="form-control" placeholder="Rechercher par ville" value="<?= htmlentities($_GET['q'] ?? null) ?>">
        </div>
        <button class="btn btn-primary">Rechercher</button>
    </form>

    <?php $table->render() ?>
</body>
</html>