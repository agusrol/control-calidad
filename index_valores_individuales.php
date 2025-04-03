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
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$validLimits = [10, 25, 50, 100];
if (!in_array($limit, $validLimits)) $limit = 25;

$offset = ($page - 1) * $limit;
$sql = "SELECT id, n_orden, producto, lote, formato, 
               peso_array, dureza_array, altura_array, friabilidad_array, 
               peso1_array, altura1_array 
        FROM control_calidad";
$sqlCount = "SELECT COUNT(*) FROM control_calidad";

if ($search !== '') {
    $sql .= ' WHERE "n_análisis" ILIKE :search';
    $sqlCount .= ' WHERE "n_análisis" ILIKE :search';
}

$sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
if ($search !== '') $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtCount = $pdo->prepare($sqlCount);
if ($search !== '') $stmtCount->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmtCount->execute();
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Valores Individuales</title>
    <style>
        table {{ border-collapse: collapse; width: 100%; font-size: 12px; }}
        th, td {{ padding: 6px; border: 1px solid #aaa; text-align: left; vertical-align: top; }}
        th {{ background-color: #eee; }}
        pre {{ margin: 0; font-size: 11px; white-space: pre-wrap; word-wrap: break-word; }}
    </style>
</head>
<body>

<h2>Valores Individuales</h2>
<form method="get">
    <label for="search">Buscar N° análisis:</label>
    <input type="text" name="search" id="search" value="<?= htmlspecialchars($search) ?>">
    <label for="limit">Mostrar:</label>
    <select name="limit" id="limit">
        <?php foreach ($validLimits as $opt): ?>
            <option value="<?= $opt ?>" <?= $limit === $opt ? 'selected' : '' ?>><?= $opt ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Buscar</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nº Orden</th>
            <th>Producto</th>
            <th>Lote</th>
            <th>Formato</th>
            <th>Peso (array)</th>
            <th>Dureza (array)</th>
            <th>Altura (array)</th>
            <th>Friabilidad (array)</th>
            <th>Peso1 (array)</th>
            <th>Altura1 (array)</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($results): ?>
            <?php foreach ($results as $fila): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['n_orden']) ?></td>
                    <td><?= htmlspecialchars($fila['producto']) ?></td>
                    <td><?= htmlspecialchars($fila['lote']) ?></td>
                    <td><?= htmlspecialchars($fila['formato']) ?></td>
                    <td><pre><?= json_encode(json_decode($fila['peso_array']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre></td>
                    <td><pre><?= json_encode(json_decode($fila['dureza_array']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre></td>
                    <td><pre><?= json_encode(json_decode($fila['altura_array']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre></td>
                    <td><pre><?= json_encode(json_decode($fila['friabilidad_array']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre></td>
                    <td><pre><?= json_encode(json_decode($fila['peso1_array']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre></td>
                    <td><pre><?= json_encode(json_decode($fila['altura1_array']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="11">No se encontraron resultados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($totalPages > 1): ?>
    <div>
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">« Anterior</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">Siguiente »</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

</body>
</html>