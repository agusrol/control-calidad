<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Calidad - Listado</title>
</head>
<body>

<?php
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
    $sqlCount.= ' WHERE "n_análisis" ILIKE :search';
}

$sql .= " ORDER BY id ASC";
$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
if ($search !== '') {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}

//Hago el select
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtCount = $pdo->prepare($sqlCount);
if ($search !== '') {
    $stmtCount->bindValue(':search', "%$search%", PDO::PARAM_STR);
}

//Hago el count (para juntar sólo una cantidad de los datos)
$stmtCount->execute();
$totalRecords = $stmtCount->fetchColumn();
$totalPages   = ceil($totalRecords / $limit);

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

<h1>Sistema de Control de Calidad - Listado de Análisis</h1>

<p>
    <a href="formulario.php">Crear nuevo registro</a> | 
    <a href="exportar_excel.php">Exportar a Excel</a>
</p>


<?php if ($totalPages > 1): ?>
<div>
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">&#171; Anterior</a>
    <?php endif; ?>
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">Siguiente &#187;</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<form method="get" action="index.php">
    <label for="search">Buscar N° de análisis:</label>
    <input type="text" id="search" name="search" value="<?= htmlspecialchars($search ?? '') ?>">
    
    <label for="limit">Mostrar:</label>
    <select id="limit" name="limit">
        <?php foreach ($validLimits as $option): ?>
            <option value="<?= $option ?>" <?= ($limit === $option) ? 'selected' : '' ?>>
                <?= $option ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit">Buscar</button>
</form>

<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <?php foreach ($columns as $colName): ?>
                <th><?= htmlspecialchars($colName ?? '') ?></th>
            <?php endforeach; ?>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $row): ?>
            <tr>
                <?php foreach ($columns as $colName): ?>
                <td><?= htmlspecialchars($row[$colName] ?? '') ?></td>
                <?php endforeach; ?>
                <td>
                    <a href="formulario.php?id=<?= urlencode($row['id']) ?>&modo=editar">✏️ Editar</a> |
                    <a href="eliminar.php?id=<?= urlencode($row['id'] ?? '') ?>&limit=<?= $limit ?>&page=<?= $page ?>&search=<?= urlencode($search) ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">🗑️ Eliminar</a>
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

<?php if ($totalPages > 1): ?>
<div>
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">&#171; Anterior</a>
    <?php endif; ?>
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">Siguiente &#187;</a>
    <?php endif; ?>
</div>
<?php endif; ?>
<p>
    <a href="formulario.php">Crear nuevo registro</a> | 
    <a href="exportar_excel.php">Exportar a Excel</a>
</p>
</body>
</html>
