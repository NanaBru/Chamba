<?php
header('Content-Type: application/json; charset=UTF-8');

$cedula = trim($_GET['cedula'] ?? '');

if (empty($cedula) || strlen($cedula) < 7 || strlen($cedula) > 8 || !ctype_digit($cedula)) {
    echo json_encode(['success' => false, 'mensaje' => 'Cédula inválida']);
    exit;
}

$archivo_csv = __DIR__ . '/../datos/cedulas.csv';

if (!file_exists($archivo_csv)) {
    echo json_encode(['success' => false, 'mensaje' => 'Archivo no encontrado']);
    exit;
}

// Leer línea por línea sin cargar todo en memoria
$handle = fopen($archivo_csv, 'r');

// Optimización: si las cédulas están ordenadas, podemos salir antes
$cedula_int = (int)$cedula;

while (($linea = fgetcsv($handle)) !== false) {
    if (count($linea) < 3) continue;
    
    $cedula_csv = trim($linea[0]);
    
    // Salida temprana si ya pasamos el rango (solo si el CSV está ordenado)
    $cedula_csv_int = (int)$cedula_csv;
    if ($cedula_csv_int > $cedula_int) {
        break; // Ya pasamos, no está
    }
    
    // COMPARACIÓN EXACTA
    if ($cedula_csv === $cedula) {
        $nombres = explode(' ', trim($linea[1]));
        $apellidos = explode(' ', trim($linea[2]));
        
        echo json_encode([
            'success' => true,
            'nombre' => ucwords(mb_strtolower(trim($nombres[0]), 'UTF-8')),
            'apellido' => ucwords(mb_strtolower(trim($apellidos[0]), 'UTF-8'))
        ]);
        
        fclose($handle);
        exit;
    }
}

fclose($handle);
echo json_encode(['success' => false, 'mensaje' => 'Cédula no encontrada']);
