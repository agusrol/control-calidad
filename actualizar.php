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

    $id = $_GET['id'] ?? null;
    if (!$id) {
        die("ID no proporcionado.");
    }

    function limpiarYPromediar($valores) {
        if (!is_array($valores)) {
            $valores = [$valores];
        }
        $filtrados = array_filter($valores, fn($v) => $v !== '' && is_numeric($v));
        $numeros = array_map('floatval', $filtrados);
        $promedio = count($numeros) > 0 ? array_sum($numeros) / count($numeros) : null;
        return [json_encode($numeros), $promedio];
    }

    list($peso_array, $peso_prom) = limpiarYPromediar($_POST['peso'] ?? []);
    list($dureza_array, $dureza_prom) = limpiarYPromediar($_POST['dureza'] ?? []);
    list($altura_array, $altura_prom) = limpiarYPromediar($_POST['altura'] ?? []);
    list($friabilidad_array, $friabilidad_prom) = limpiarYPromediar($_POST['friabilidad'] ?? []);
    list($peso1_array, $peso1_prom) = limpiarYPromediar($_POST['peso1'] ?? []);
    list($altura1_array, $altura1_prom) = limpiarYPromediar($_POST['altura1'] ?? []);

    $sql = "UPDATE control_calidad SET
        peso_array = :peso_array, peso = :peso,
        dureza_array = :dureza_array, dureza = :dureza,
        altura_array = :altura_array, altura = :altura,
        friabilidad_array = :friabilidad_array, friabilidad = :friabilidad,
        peso1_array = :peso1_array, peso1 = :peso1,
        altura1_array = :altura1_array, altura1 = :altura1,
        n_análisis = :n_analisis,
        n_orden = :n_orden,
        producto = :producto,
        lote = :lote,
        formato = :formato,
        control_de_balanza = :control_de_balanza,
        fecha = :fecha,
        controló = :controlo,
        pct_humedad_g_humeda = :pct_humedad_g_humeda,
        pct_humedad_estufa = :pct_humedad_estufa,
        humedad_pct = :humedad_pct,
        grueso = :grueso,
        entrefino = :entrefino,
        fino = :fino,
        densidad_aparente = :densidad_aparente,
        densidad_compactada = :densidad_compactada,
        indice_de_hausner = :indice_de_hausner,
        indice_de_carr = :indice_de_carr,
        disgregación = :disgregacion,
        disgregación1 = :disgregacion1,
        humedad = :pct_humedad,
        a_totales = :a_totales,
        enterobact = :enterobact,
        e_coli = :e_coli,
        s_aureus = :s_aureus,
        hyl = :hyl,
        grados__brix = :grados_brix,
        peso_promedio_10_caramelos = :peso_promedio_10_caramelos,
        azucar_libre___gr_ = :azucar_libre_gr
        WHERE id = :id";



    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
    
        // Arrays
        'peso_array' => $peso_array,
        'dureza_array' => $dureza_array,
        'altura_array' => $altura_array,
        'friabilidad_array' => $friabilidad_array,
        'peso1_array' => $peso1_array,
        'altura1_array' => $altura1_array,
    
        // Promedios
        'peso' => $peso_prom,
        'dureza' => $dureza_prom,
        'friabilidad' => $friabilidad_prom,
        'altura' => $altura_prom,
        'peso1' => $peso1_prom,
        'altura1' => $altura1_prom,
    
        // Otros campos
        'n_analisis' => $_POST['n_analisis'] ?? null,
        'n_orden' => $_POST['n_orden'] ?? null,
        'producto' => $_POST['producto'] ?? null,
        'lote' => $_POST['lote'] ?? null,
        'formato' => $_POST['formato'] ?? null,
        'control_de_balanza' => $_POST['control_de_balanza'] ?? null,
        'fecha' => !empty($_POST['fecha']) ? $_POST['fecha'] : null,
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
    
        'disgregacion' => $_POST['disgregacion'] ?? null,
        'disgregacion1' => $_POST['disgregacion1'] ?? null,
        'pct_humedad' => is_numeric($_POST['pct_humedad'] ?? '') ? $_POST['pct_humedad'] : null,
    
        'a_totales' => $_POST['a_totales'] ?? null,
        'enterobact' => is_numeric($_POST['enterobact'] ?? '') ? $_POST['enterobact'] : null,
        'e_coli' => is_numeric($_POST['e_coli'] ?? '') ? $_POST['e_coli'] : null,
        's_aureus' => is_numeric($_POST['s_aureus'] ?? '') ? $_POST['s_aureus'] : null,
        'hyl' => is_numeric($_POST['hyl'] ?? '') ? $_POST['hyl'] : null,
    
        'grados_brix' => is_numeric($_POST['grados_brix'] ?? '') ? $_POST['grados_brix'] : null,
        'peso_promedio_10_caramelos' => is_numeric($_POST['peso_promedio_10_caramelos'] ?? '') ? $_POST['peso_promedio_10_caramelos'] : null,
        'azucar_libre_gr' => is_numeric($_POST['azucar_libre_gr'] ?? '') ? $_POST['azucar_libre_gr'] : null
    ]);
    

    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    echo "❌ Error al actualizar los datos: " . $e->getMessage();
} catch (Exception $e) {
    echo "❌ Error inesperado: " . $e->getMessage();
}
?>
