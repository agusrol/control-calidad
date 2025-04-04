<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$modo = $_GET['modo'] ?? 'crear';
$registro = [];

if ($modo === 'editar') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $conn = new PDO('pgsql:host=localhost;dbname=control_calidad', 'postgres', 'admin');
        $stmt = $conn->prepare("SELECT * FROM control_calidad WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

function generarInputsConValores($nombre, $registro) {
    $valores = [];
    if (!empty($registro[$nombre . '_array'])) {
        $decoded = json_decode($registro[$nombre . '_array'], true);
        if (is_array($decoded)) {
            foreach ($decoded as $k => $v) {
                if (is_numeric($k)) {
                    $valores[(int)$k] = $v;
                }
            }
        }
    }
    for ($i = 0; $i < 75; $i++) {
        $valor = htmlspecialchars($valores[$i] ?? '');
        echo "<label class='form-label'>" . ucfirst($nombre) . " " . ($i + 1) . ": ";
        echo "<input type='number' step='any' name='{$nombre}[]' class='form-control mb-2' value='{$valor}'></label>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $modo === 'editar' ? 'Editar Registro de Análisis' : 'Nuevo Registro de Análisis' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="container my-3">

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><?= $modo === 'editar' ? 'Editar Registro de Análisis' : 'Nuevo Registro de Análisis' ?></h2>
  <img src="mnt/data/Provefarma logo.png" alt="Logo Provefarma" style="height: 150px;">
</div>

<a href="index.php" class="btn btn-secondary mb-3">Ver análisis</a>

<form action="<?= $modo === 'editar' ? 'actualizar.php?id=' . $registro['id'] : 'insertar.php' ?>" method="POST">
  <div class="accordion" id="formAccordion">

    <div class="accordion-item">
      <h2 class="accordion-header" id="headingProducto">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#producto" aria-expanded="false">
          Datos del producto
        </button>
      </h2>
      <div id="producto" class="accordion-collapse collapse">
        <div class="accordion-body">
          <input class="form-control mb-2" placeholder="Nº Análisis" type="text" name="n_analisis" value="<?= htmlspecialchars($registro['n_análisis'] ?? '') ?>" required>
          <input class="form-control mb-2" placeholder="Nº Orden" type="text" name="n_orden" value="<?= htmlspecialchars($registro['n_orden'] ?? '') ?>" required>
          <input class="form-control mb-2" placeholder="Producto" type="text" name="producto" value="<?= htmlspecialchars($registro['producto'] ?? '') ?>" required>
          <input class="form-control mb-2" placeholder="Lote" type="text" name="lote" value="<?= htmlspecialchars($registro['lote'] ?? '') ?>" required>
          <input class="form-control mb-2" placeholder="Formato" type="text" name="formato" value="<?= htmlspecialchars($registro['formato'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#materias" aria-expanded="false">
          Pesado de materias primas
        </button>
      </h2>
      <div id="materias" class="accordion-collapse collapse">
        <div class="accordion-body">
          <input class="form-control mb-2" placeholder="Control de balanza" type="text" name="control_de_balanza" value="<?= htmlspecialchars($registro['control_de_balanza'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Fecha" type="date" name="fecha" value="<?= htmlspecialchars($registro['fecha'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Controló" type="text" name="controlo" value="<?= htmlspecialchars($registro['controlo'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mezcla" aria-expanded="false">
          Mezcla húmeda y secado
        </button>
      </h2>
      <div id="mezcla" class="accordion-collapse collapse">
        <div class="accordion-body">
          <input class="form-control mb-2" placeholder="% Humedad (G. Húmeda)" type="number" step="any" name="pct_humedad_g_humeda" value="<?= htmlspecialchars($registro['pct_humedad_g_humeda'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="% Humedad (Estufa)" type="number" step="any" name="pct_humedad_estufa" value="<?= htmlspecialchars($registro['pct_humedad_estufa'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#granulacion" aria-expanded="false">
          Granulación y mezcla seca
        </button>
      </h2>
      <div id="granulacion" class="accordion-collapse collapse">
        <div class="accordion-body">
          <input class="form-control mb-2" placeholder="Humedad %" type="number" step="any" name="humedad_pct" value="<?= htmlspecialchars($registro['humedad_pct'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Grueso" type="text" name="grueso" value="<?= htmlspecialchars($registro['grueso'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Entrefino" type="text" name="entrefino" value="<?= htmlspecialchars($registro['entrefino'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Fino" type="text" name="fino" value="<?= htmlspecialchars($registro['fino'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Densidad Aparente" type="number" step="any" name="densidad_aparente" value="<?= htmlspecialchars($registro['densidad_aparente'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Densidad Compactada" type="number" step="any" name="densidad_compactada" value="<?= htmlspecialchars($registro['densidad_compactada'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Índice de Hausner" type="number" step="any" name="indice_de_hausner" value="<?= htmlspecialchars($registro['indice_de_hausner'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Índice de Carr" type="number" step="any" name="indice_de_carr" value="<?= htmlspecialchars($registro['indice_de_carr'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#compresion" aria-expanded="false">
          Compresión
        </button>
      </h2>
      <div id="compresion" class="accordion-collapse collapse">
        <div class="accordion-body" style="max-height: 400px; overflow-y:auto;">
          <?php
          generarInputsConValores("peso", $registro);
          generarInputsConValores("dureza", $registro);
          generarInputsConValores("altura", $registro);
          generarInputsConValores("friabilidad", $registro);
          ?>
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#recubrimiento" aria-expanded="false">
          Recubrimiento
        </button>
      </h2>
      <div id="recubrimiento" class="accordion-collapse collapse">
        <div class="accordion-body" style="max-height: 400px; overflow-y:auto;">
          <?php
          generarInputsConValores("peso1", $registro);
          generarInputsConValores("altura1", $registro);
          ?>
          <input class="form-control mt-2" placeholder="Disgregación" type="text" name="disgregacion1" value="<?= htmlspecialchars($registro['disgregacion1'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#higienico" aria-expanded="false">
          Control higiénico y Gummies
        </button>
      </h2>
      <div id="higienico" class="accordion-collapse collapse">
        <div class="accordion-body">
          <input class="form-control mb-2" placeholder="A. Totales" type="text" name="a_totales" value="<?= htmlspecialchars($registro['a_totales'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Enterobacterias" type="text" name="enterobact" value="<?= htmlspecialchars($registro['enterobact'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="E. Coli" type="text" name="e_coli" value="<?= htmlspecialchars($registro['e_coli'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="S. Aureus" type="text" name="s_aureus" value="<?= htmlspecialchars($registro['s_aureus'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="H. y Levaduras (HyL)" type="text" name="hyl" value="<?= htmlspecialchars($registro['hyl'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Grados Brix" type="number" step="any" name="grados_brix" value="<?= htmlspecialchars($registro['grados_brix'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Peso Promedio (10 caramelos)" type="number" step="any" name="peso_promedio_10_caramelos" value="<?= htmlspecialchars($registro['peso_promedio_10_caramelos'] ?? '') ?>">
          <input class="form-control mb-2" placeholder="Azúcar Libre (gr)" type="number" step="any" name="azucar_libre_gr" value="<?= htmlspecialchars($registro['azucar_libre_gr'] ?? '') ?>">
        </div>
      </div>
    </div>

  </div>

  <button type="submit" class="btn btn-primary mt-3">Guardar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>