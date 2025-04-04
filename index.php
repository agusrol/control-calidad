<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$port = "5432";
$dbname = "control_calidad";
$user = "postgres";
$password = "admin";

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit  = isset($_GET['limit'])  ? (int) $_GET['limit'] : 10;
$page   = isset($_GET['page'])   ? (int) $_GET['page']  : 1;
if ($page < 1) $page = 1;

$validLimits = [10, 25, 50, 100];
if (!in_array($limit, $validLimits)) {
    $limit = 10;
}

$offset = ($page - 1) * $limit;
$sql     = "SELECT * FROM control_calidad";
$sqlCount= "SELECT COUNT(*) FROM control_calidad";

if ($search !== '') {
    $sql .= ' WHERE "n_análisis" ILIKE :search';
    $sqlCount .= ' WHERE "n_análisis" ILIKE :search';
}

$sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
if ($search !== '') {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtCount = $pdo->prepare($sqlCount);
if ($search !== '') {
    $stmtCount->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$stmtCount->execute();
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

$columns = [];
if (!empty($results)) {
    $columns = array_keys($results[0]);
} else {
    $emptyStmt = $pdo->query("SELECT * FROM control_calidad LIMIT 0");
    for ($i = 0; $i < $emptyStmt->columnCount(); $i++) {
        $meta = $emptyStmt->getColumnMeta($i);
        $columns[] = $meta['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Calidad - Listado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="container my-4">

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"> Análisis de control de calidad </h2>
  <img src="mnt/data/Provefarma logo.png" alt="Logo Provefarma" style="height: 150px;">
</div>

<div class="d-flex justify-content-between mb-3">
    <div>
        <a href="formulario.php" class="btn btn-primary me-2">Nuevo Registro</a>
        <a href="exportar_excel.php" class="btn btn-success">Exportar a Excel</a>
    </div>
    <form method="get" class="d-flex" action="index.php">
        <input class="form-control me-2" type="text" name="search" placeholder="Buscar Nº de análisis" value="<?= htmlspecialchars($search ?? '') ?>">
        <select name="limit" class="form-select me-2">
            <?php foreach ($validLimits as $option): ?>
                <option value="<?= $option ?>" <?= ($limit === $option) ? 'selected' : '' ?>><?= $option ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <?php foreach ($columns as $col): ?>
                    <th>
                        <?php
                            if (str_ends_with($col, '_array')) {
                                echo htmlspecialchars(ucfirst(str_replace('_array', '', $col))) . " (valores tomados)";
                            } else {
                                echo htmlspecialchars((string) $col);
                            }
                        ?>
                    </th>
                <?php endforeach; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $index => $row): ?>
                <tr>
                    <?php foreach ($columns as $col): ?>
                        <td>
                            <?php if (str_ends_with($col, '_array')): ?>
                                <button class="btn btn-sm btn-link p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $col . $index ?>">Ver valores</button>
                                <div class="collapse mt-1" id="collapse<?= $col . $index ?>">
                                    <pre class="small bg-light p-2 border rounded"><?= htmlspecialchars($row[$col] ?? '') ?></pre>
                                </div>
                            <?php else: ?>
                                <?= htmlspecialchars((string) ($row[$col] ?? '')) ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    <td>
                        <a href="formulario.php?id=<?= urlencode($row['id']) ?>&modo=editar" class="btn btn-sm btn-warning">Editar</a>
                        <a href="eliminar.php?id=<?= urlencode($row['id']) ?>&limit=<?= $limit ?>&page=<?= $page ?>&search=<?= urlencode($search) ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= count($columns) + 1 ?>">No se encontraron resultados.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<nav>
  <ul class="pagination justify-content-center">
    <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">&laquo; Anterior</a></li>
    <?php endif; ?>

    <?php if ($page < $totalPages): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">Siguiente &raquo;</a></li>
    <?php endif; ?>
  </ul>
</nav>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
