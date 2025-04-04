<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host     = "localhost";
$port     = "5432";
$dbname   = "control_calidad";
$user     = "postgres";
$password = "admin";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido");
}

$limit = $_GET['limit'] ?? 10;
$page = $_GET['page'] ?? 1;
$search = $_GET['search'] ?? '';
$id = (int) $_GET['id'];

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("DELETE FROM control_calidad WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: index.php?limit=$limit&page=$page&search=" . urlencode($search));
    exit;

} catch (PDOException $e) {
    echo "❌ Error al eliminar: " . $e->getMessage();
}
?>
