<?php
// Conexión a PostgreSQL
$host = "localhost";
$db = "control_calidad";
$user = "postgres";
$pass = "admin";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $query = $pdo->query("SELECT 
        id, 
        \"n_análisis\", 
        n_orden, 
        producto, 
        lote, 
        formato, 
        control_de_balanza, 
        fecha, 
        \"controló\",
        pct_humedad_g_humeda, 
        pct_humedad_estufa, 
        humedad_pct, 
        grueso, 
        entrefino, 
        fino,
        densidad_aparente, 
        densidad_compactada, 
        indice_de_hausner, 
        indice_de_carr,
        peso, 
        dureza, 
        friabilidad, 
        \"disgregación\", 
        altura, 
        humedad,
        peso1, 
        \"disgregación1\", 
        altura1, 
        a_totales, 
        enterobact, 
        e_coli, 
        s_aureus, 
        hyl,
        grados__brix, 
        peso_promedio_10_caramelos, 
        azucar_libre___gr_ AS azucar_libre
    FROM control_calidad ORDER BY id DESC");

    $registros = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de registros</title>
  <style>
    table { border-collapse: collapse; width: 100%; font-size: 12px; }
    th, td { padding: 6px; border: 1px solid #aaa; text-align: center; }
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
        <th>Control de balanza</th>
        <th>Fecha</th>
        <th>Controló</th>
        <th>% Humedad (G. Humeda)</th>
        <th>% Humedad (Estufa)</th>
        <th>Humedad %</th>
        <th>Grueso</th>
        <th>Entrefino</th>
        <th>Fino</th>
        <th>Densidad Aparente</th>
        <th>Densidad Compactada</th>
        <th>Indice de Hausner</th>
        <th>Indice de Carr</th>
        <th>Peso</th>
        <th>Dureza</th>
        <th>Friabilidad</th>
        <th>Disgregación</th>
        <th>Altura</th>
        <th>Humedad</th>
        <th>Peso 1</th>
        <th>Disgregación 1</th>
        <th>Altura 1</th>
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
          <td><?= htmlspecialchars($fila['id'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['n_análisis'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['n_orden'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['producto'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['lote'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['formato'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['control_de_balanza'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['fecha'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['controló'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['pct_humedad_g_humeda'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['pct_humedad_estufa'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['humedad_pct'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['grueso'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['entrefino'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['fino'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['densidad_aparente'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['densidad_compactada'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['indice_de_hausner'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['indice_de_carr'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['peso'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['dureza'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['friabilidad'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['disgregación'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['altura'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['humedad'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['peso1'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['disgregación1'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['altura1'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['a_totales'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['enterobact'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['e_coli'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['s_aureus'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['hyl'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['grados__brix'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['peso_promedio_10_caramelos'] ?? '') ?></td>
          <td><?= htmlspecialchars($fila['azucar_libre'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

<a href="formulario.html">➕ Nuevo Registro</a>
<a href="exportar_excel.php" style="margin-left: 20px;">📥 Exportar a Excel</a>

</body>
</html>
	
