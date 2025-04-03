<?php
$host = "localhost";
$db = "control_calidad";
$user = "postgres";
$pass = "admin";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no proporcionado.");
}

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $stmt = $pdo->prepare("SELECT * FROM control_calidad WHERE id = :id");
    $stmt->execute(['id' => $id] ?? '');
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registro) {
        die("Registro no encontrado.");
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Registro</title>
</head>
<body>
  <h2>Editar Registro de Control de Calidad</h2>
  <form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $registro['id'] ?>">
    <h3>Datos del producto</h3>
    <label>Nº Análisis: <input type="text" name="n_analisis" value="<?= htmlspecialchars($registro['n_análisis'] ?? '') ?>" required></label><br>
    <label>Nº Orden: <input type="text" name="n_orden" value="<?= htmlspecialchars($registro['n_orden'] ?? '') ?>" required></label><br>
    <label>Producto: <input type="text" name="producto" value="<?= htmlspecialchars($registro['producto'] ?? '') ?>" required></label><br>
    <label>Lote: <input type="text" name="lote" value="<?= htmlspecialchars($registro['lote'] ?? '') ?>" required></label><br>
    <label>Formato: <input type="text" name="formato" value="<?= htmlspecialchars($registro['formato'] ?? '') ?>"></label><br>

    <h3>Pesado de materias primas</h3>
    <label>Control de balanza: <input type="text" name="control_de_balanza" value="<?= htmlspecialchars($registro['control_de_balanza'] ?? '') ?>"></label><br>
    <label>Fecha: <input type="date" name="fecha" value="<?= htmlspecialchars($registro['fecha'] ?? '') ?>"></label><br>
    <label>Controló: <input type="text" name="controlo" value="<?= htmlspecialchars($registro['controló'] ?? '') ?>"></label><br>

    <hr>
    <h3>Mezcla húmeda y secado</h3>
    <label>% Humedad (G. Húmeda): <input type="number" step="any" name="pct_humedad_g_humeda" value="<?= htmlspecialchars($registro['pct_humedad_g_humeda'] ?? '') ?>"></label><br>
    <label>% Humedad (Estufa): <input type="number" step="any" name="pct_humedad_estufa" value="<?= htmlspecialchars($registro['pct_humedad_estufa'] ?? '') ?>"></label><br>

    <h3>Granulación y mezcla seca</h3>
    <label>Humedad %: <input type="number" step="any" name="humedad_pct" value="<?= htmlspecialchars($registro['humedad_pct'] ?? '') ?>"></label><br>
    <label>Grueso: <input type="text" name="grueso" value="<?= htmlspecialchars($registro['grueso'] ?? '') ?>"></label><br>
    <label>Entrefino: <input type="text" name="entrefino" value="<?= htmlspecialchars($registro['entrefino'] ?? '') ?>"></label><br>
    <label>Fino: <input type="text" name="fino" value="<?= htmlspecialchars($registro['fino'] ?? '') ?>"></label><br>
    <label>Densidad Aparente: <input type="number" step="any" name="densidad_aparente" value="<?= htmlspecialchars($registro['densidad_aparente'] ?? '') ?>"></label><br>
    <label>Densidad Compactada: <input type="number" step="any" name="densidad_compactada" value="<?= htmlspecialchars($registro['densidad_compactada'] ?? '') ?>"></label><br>
    <label>Índice de Hausner: <input type="number" step="any" name="indice_de_hausner" value="<?= htmlspecialchars($registro['indice_de_hausner'] ?? '') ?>"></label><br>
    <label>Índice de Carr: <input type="number" step="any" name="indice_de_carr" value="<?= htmlspecialchars($registro['indice_de_carr'] ?? '') ?>"></label><br>

    <h3>Compresión</h3>
    <label>Peso: <input type="number" step="any" name="peso" value="<?= htmlspecialchars($registro['peso'] ?? '') ?>"></label><br>
    <label>Dureza: <input type="number" step="any" name="dureza" value="<?= htmlspecialchars($registro['dureza'] ?? '') ?>"></label><br>
    <label>Friabilidad: <input type="number" step="any" name="friabilidad" value="<?= htmlspecialchars($registro['friabilidad'] ?? '') ?>"></label><br>
    <label>Disgregación: <input type="text" name="disgregacion" value="<?= htmlspecialchars($registro['disgregación'] ?? '') ?>"></label><br>
    <label>Altura: <input type="number" step="any" name="altura" value="<?= htmlspecialchars($registro['altura'] ?? '') ?>"></label><br>
    <label>% Humedad: <input type="number" step="any" name="pct_humedad" value="<?= htmlspecialchars($registro['humedad'] ?? '') ?>"></label><br>

    <hr>
    <h3>Recubrimiento</h3>
    <label>Peso: <input type="number" step="any" name="peso1" value="<?= htmlspecialchars($registro['peso1'] ?? '') ?>"></label><br>
    <label>Disgregación: <input type="text" name="disgregacion1" value="<?= htmlspecialchars($registro['disgregación1'] ?? '') ?>"></label><br>
    <label>Altura: <input type="number" step="any" name="altura1" value="<?= htmlspecialchars($registro['altura1'] ?? '') ?>"></label><br>

    <hr>
    <h3>Control higiénico</h3>
    <label>A. Totales: <input type="text" name="a_totales" value="<?= htmlspecialchars($registro['a_totales'] ?? '') ?>"></label><br>
    <label>Enterobacterias: <input type="text" name="enterobact" value="<?= htmlspecialchars($registro['enterobact'] ?? '') ?>"></label><br>
    <label>E. Coli: <input type="text" name="e_coli" value="<?= htmlspecialchars($registro['e_coli'] ?? '') ?>"></label><br>
    <label>S. Aureus: <input type="text" name="s_aureus" value="<?= htmlspecialchars($registro['s_aureus'] ?? '') ?>"></label><br>
    <label>H. y Levaduras (HyL): <input type="text" name="hyl" value="<?= htmlspecialchars($registro['hyl'] ?? '') ?>"></label><br>

    <h3>Gummies</h3>
    <label>Grados Brix: <input type="number" step="any" name="grados_brix" value="<?= htmlspecialchars($registro['grados__brix'] ?? '') ?>"></label><br>
    <label>Peso Promedio (10 caramelos): <input type="number" step="any" name="peso_promedio_10_caramelos" value="<?= htmlspecialchars($registro['peso_promedio_10_caramelos'] ?? '') ?>"></label><br>
    <label>Azúcar Libre (gr): <input type="number" step="any" name="azucar_libre_gr" value="<?= htmlspecialchars($registro['azucar_libre___gr_'] ?? '') ?>"></label><br>

    <button type="submit">Guardar Cambios</button>
  </form>
</body>
</html>
