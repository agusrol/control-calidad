<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host     = "localhost";
$port     = "5432";
$dbname   = "control_calidad";
$user     = "postgres";
$password = "admin";

function promedio_array($arr) {
    $arr = array_filter($arr, fn($v) => is_numeric($v));
    return count($arr) ? array_sum($arr) / count($arr) : null;
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $peso = promedio_array($_POST['peso'] ?? []);
    $dureza = promedio_array($_POST['dureza'] ?? []);
    $altura = promedio_array($_POST['altura'] ?? []);
    $friabilidad = promedio_array($_POST['friabilidad'] ?? []);
    $peso1 = promedio_array($_POST['peso1'] ?? []);
    $altura1 = promedio_array($_POST['altura1'] ?? []);

    $sql = 'INSERT INTO control_calidad (
        "n_análisis", n_orden, producto, lote, formato, fecha, control_de_balanza, "controló",
        pct_humedad_g_humeda, pct_humedad_estufa, humedad_pct, grueso, entrefino, fino,
        densidad_aparente, densidad_compactada, indice_de_hausner, indice_de_carr,
        peso, dureza, friabilidad, "disgregación", altura, humedad, peso1,
        "disgregación1", altura1, a_totales, enterobact, e_coli, s_aureus, hyl,
        grados__brix, peso_promedio_10_caramelos, azucar_libre___gr_,
        peso_array, dureza_array, altura_array, friabilidad_array, peso1_array, altura1_array
    ) VALUES (
        :n_analisis, :n_orden, :producto, :lote, :formato, :fecha, :control_de_balanza, :controlo,
        :pct_humedad_g_humeda, :pct_humedad_estufa, :humedad_pct, :grueso, :entrefino, :fino,
        :densidad_aparente, :densidad_compactada, :indice_de_hausner, :indice_de_carr,
        :peso, :dureza, :friabilidad, :disgregacion, :altura, :humedad, :peso1,
        :disgregacion1, :altura1, :a_totales, :enterobact, :e_coli, :s_aureus, :hyl,
        :grados_brix, :peso_promedio_10_caramelos, :azucar_libre_gr,
        :peso_array, :dureza_array, :altura_array, :friabilidad_array, :peso1_array, :altura1_array
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
        'pct_humedad_g_humeda' => is_numeric($_POST['pct_humedad_g_humeda'] ?? '') ? $_POST['pct_humedad_g_humeda'] : null,
        'pct_humedad_estufa' => is_numeric($_POST['pct_humedad_estufa'] ?? '') ? $_POST['pct_humedad_estufa'] : null,
        'humedad_pct' => is_numeric($_POST['humedad_pct'] ?? '') ? $_POST['humedad_pct'] : null,
        'grueso' => is_numeric($_POST['grueso'] ?? '') ? $_POST['grueso'] : null,
        'entrefino' => is_numeric($_POST['entrefino'] ?? '') ? $_POST['entrefino'] : null,
        'fino' => is_numeric($_POST['fino'] ?? '') ? $_POST['fino'] : null,
        'densidad_aparente' => is_numeric($_POST['densidad_aparente'] ?? '') ? $_POST['densidad_aparente'] : null,
        'densidad_compactada' => is_numeric($_POST['densidad_compactada'] ?? '') ? $_POST['densidad_compactada'] : null,
        'indice_de_hausner' => is_numeric($_POST['indice_de_hausner'] ?? '') ? $_POST['indice_de_hausner'] : null,
        'indice_de_carr' => is_numeric($_POST['indice_de_carr'] ?? '') ? $_POST['indice_de_carr'] : null,
        'peso' => $peso,
        'dureza' => $dureza,
        'friabilidad' => $friabilidad,
        'disgregacion' => $_POST['disgregacion'] ?? null,
        'altura' => $altura,
        'humedad' => is_numeric($_POST['pct_humedad'] ?? '') ? $_POST['pct_humedad'] : null,
        'peso1' => $peso1,
        'disgregacion1' => $_POST['disgregacion1'] ?? null,
        'altura1' => $altura1,
        'a_totales' => $_POST['a_totales'] ?? null,
        'enterobact' => is_numeric($_POST['enterobact'] ?? '') ? $_POST['enterobact'] : null,
        'e_coli' => is_numeric($_POST['e_coli'] ?? '') ? $_POST['e_coli'] : null,
        's_aureus' => is_numeric($_POST['s_aureus'] ?? '') ? $_POST['s_aureus'] : null,
        'hyl' => is_numeric($_POST['hyl'] ?? '') ? $_POST['hyl'] : null,
        'grados_brix' => is_numeric($_POST['grados_brix'] ?? '') ? $_POST['grados_brix'] : null,
        'peso_promedio_10_caramelos' => is_numeric($_POST['peso_promedio_10_caramelos'] ?? '') ? $_POST['peso_promedio_10_caramelos'] : null,
        'azucar_libre_gr' => is_numeric($_POST['azucar_libre_gr'] ?? '') ? $_POST['azucar_libre_gr'] : null,
        'peso_array' => json_encode($_POST['peso'] ?? []),
        'dureza_array' => json_encode($_POST['dureza'] ?? []),
        'altura_array' => json_encode($_POST['altura'] ?? []),
        'friabilidad_array' => json_encode($_POST['friabilidad'] ?? []),
        'peso1_array' => json_encode($_POST['peso1'] ?? []),
        'altura1_array' => json_encode($_POST['altura1'] ?? [])
    ]);

    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    echo "❌ Error al insertar los datos: " . $e->getMessage();
}
?>