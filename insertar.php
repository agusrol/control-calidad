<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$host     = "localhost";
$port     = "5432";
$dbname   = "control_calidad";
$user     = "postgres";
$password = "admin";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'INSERT INTO control_calidad (
        "n_análisis", n_orden, producto, lote, formato, fecha, control_de_balanza, "controló",
        pct_humedad_g_humeda, pct_humedad_estufa, humedad_pct, grueso, entrefino, fino,
        densidad_aparente, densidad_compactada, indice_de_hausner, indice_de_carr,
        peso, dureza, friabilidad, "disgregación", altura, humedad, peso1,
        "disgregación1", altura1, a_totales, enterobact, e_coli, s_aureus, hyl,
        grados__brix, peso_promedio_10_caramelos, azucar_libre___gr_
    ) VALUES (
        :n_analisis, :n_orden, :producto, :lote, :formato, :fecha, :control_de_balanza, :controlo,
        :pct_humedad_g_humeda, :pct_humedad_estufa, :humedad_pct, :grueso, :entrefino, :fino,
        :densidad_aparente, :densidad_compactada, :indice_de_hausner, :indice_de_carr,
        :peso, :dureza, :friabilidad, :disgregacion, :altura, :humedad, :peso1,
        :disgregacion1, :altura1, :a_totales, :enterobact, :e_coli, :s_aureus, :hyl,
        :grados_brix, :peso_promedio_10_caramelos, :azucar_libre_gr
    )';

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'n_analisis' => $_POST['n_analisis'] ?? null,
        'n_orden' => $_POST['n_orden'] ?? null,
        'producto' => $_POST['producto'] ?? null,
        'lote' => $_POST['lote'] ?? null,
        'formato' => $_POST['formato'] ?? null,
        'fecha' => !empty($_POST['fecha']) ? $_POST['fecha'] : null,
        'control_de_balanza' => $_POST['control_de_balanza'] ?? null,
        'controlo' => $_POST['controlo'] ?? null,
        'pct_humedad_g_humeda' => $_POST['pct_humedad_g_humeda'] ?? null,
        'pct_humedad_estufa' => is_numeric($_POST['pct_humedad_estufa'] ?? null) ? $_POST['pct_humedad_estufa'] : null,
        'humedad_pct' => $_POST['humedad_pct'] ?? null,
        'grueso' => is_numeric($_POST['grueso'] ?? null) ? $_POST['grueso'] : null,
        'entrefino' => is_numeric($_POST['entrefino'] ?? null) ? $_POST['entrefino'] : null,
        'fino' => is_numeric($_POST['fino'] ?? null) ? $_POST['fino'] : null,
        'densidad_aparente' => is_numeric($_POST['densidad_aparente'] ?? null) ? $_POST['densidad_aparente'] : null,
        'densidad_compactada' => is_numeric($_POST['densidad_compactada'] ?? null) ? $_POST['densidad_compactada'] : null,
        'indice_de_hausner' => is_numeric($_POST['indice_de_hausner'] ?? null) ? $_POST['indice_de_hausner'] : null,
        'indice_de_carr' => is_numeric($_POST['indice_de_carr'] ?? null) ? $_POST['indice_de_carr'] : null,
        'peso' => $_POST['peso'] ?? null,
        'dureza' => $_POST['dureza'] ?? null,
        'friabilidad' => is_numeric($_POST['friabilidad'] ?? null) ? $_POST['friabilidad'] : null,
        'disgregacion' => $_POST['disgregacion'] ?? null,
        'altura' => is_numeric($_POST['altura'] ?? null) ? $_POST['altura'] : null,
        'humedad' => $_POST['humedad'] ?? null,
        'peso1' => $_POST['peso1'] ?? null,
        'disgregacion1' => $_POST['disgregacion1'] ?? null,
        'altura1' => is_numeric($_POST['altura1'] ?? null) ? $_POST['altura1'] : null,
        'a_totales' => $_POST['a_totales'] ?? null,
        'enterobact' => is_numeric($_POST['enterobact'] ?? null) ? $_POST['enterobact'] : null,
        'e_coli' => is_numeric($_POST['e_coli'] ?? null) ? $_POST['e_coli'] : null,
        's_aureus' => is_numeric($_POST['s_aureus'] ?? null) ? $_POST['s_aureus'] : null,
        'hyl' => is_numeric($_POST['hyl'] ?? null) ? $_POST['hyl'] : null,
        'grados_brix' => $_POST['grados_brix'] ?? null,
        'peso_promedio_10_caramelos' => is_numeric($_POST['peso_promedio_10_caramelos'] ?? null) ? $_POST['peso_promedio_10_caramelos'] : null,
        'azucar_libre_gr' => $_POST['azucar_libre'] ?? null
    ]);

    // Redirigir a index.php tras insertar
    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    echo "❌ Error al insertar los datos: " . $e->getMessage();
}
?>





<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de registros</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px; border: 1px solid #aaa; }
    th { background-color: #eee; }
  </style>
</head>
<body>
  <h2>Registros de Control de Calidad</h2>
  <a href="formulario.html">➕ Nuevo Registro</a>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nº Análisis</th>
        <th>Nº Orden</th>
        <th>Producto</th>
        <th>Lote</th>
        <th>Formato</th>
        <th>Fecha</th>
        <th>Control Balanza</th>
        <th>Controló</th>
        <th>% Humedad (G. Humeda)</th>
        <th>% Humedad (Estufa)</th>
        <th>Humedad %</th>
        <th>Grueso</th>
        <th>Entrefino</th>
        <th>Fino</th>
        <th>Densidad Aparente</th>
        <th>Densidad Compactada</th>
        <th>Indice Hausner</th>
        <th>Indice Carr</th>
        <th>Peso</th>
        <th>Dureza</th>
        <th>Friabilidad</th>
        <th>Disgregación</th>
        <th>Altura</th>
        <th>Humedad</th>
        <th>A. Totales</th>
        <th>Enterobact</th>
        <th>E. Coli</th>
        <th>S. Aureus</th>
        <th>HyL</th>
        <th>Grados Brix</th>
        <th>Peso Promedio (10 caramelos)</th>
        <th>Azúcar Libre (gr)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($registros as $fila): ?>
        <tr>
          <?php foreach ($fila as $valor): ?>
            <td><?= htmlspecialchars($valor ?? '') ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>