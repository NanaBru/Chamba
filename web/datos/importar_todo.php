<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

$conn = new mysqli('localhost', 'root', '', 'chamba');

// Vaciar tabla primero
$conn->query("TRUNCATE TABLE cedulas_padron");
echo "<h3>Tabla vaciada. Importando con primer nombre y primer apellido...</h3>";

$archivo = __DIR__ . '/cedulas.csv';
$handle = fopen($archivo, 'r');

$contador = 0;
$valores = [];

$conn->begin_transaction();

while (($linea = fgetcsv($handle)) !== false) {
    if (count($linea) >= 3) {
        $cedula = trim($linea[0]);
        
        // Solo cédulas de 7-8 dígitos
        if (strlen($cedula) >= 7 && strlen($cedula) <= 8 && ctype_digit($cedula)) {
            // Extraer PRIMER nombre y PRIMER apellido
            $nombres_completos = trim($linea[1]);
            $apellidos_completos = trim($linea[2]);
            
            $nombres_array = explode(' ', $nombres_completos);
            $primer_nombre = ucwords(mb_strtolower(trim($nombres_array[0]), 'UTF-8'));
            
            $apellidos_array = explode(' ', $apellidos_completos);
            $primer_apellido = ucwords(mb_strtolower(trim($apellidos_array[0]), 'UTF-8'));
            
            // Escapar para SQL
            $cedula = $conn->real_escape_string($cedula);
            $primer_nombre = $conn->real_escape_string($primer_nombre);
            $primer_apellido = $conn->real_escape_string($primer_apellido);
            
            $valores[] = "('$cedula', '$primer_nombre', '$primer_apellido')";
            $contador++;
            
            // Insertar cada 5000 registros
            if (count($valores) >= 5000) {
                $sql = "INSERT IGNORE INTO cedulas_padron (cedula, nombres, apellidos) VALUES " . implode(',', $valores);
                $conn->query($sql);
                $valores = [];
                
                if ($contador % 50000 == 0) {
                    $conn->commit();
                    $conn->begin_transaction();
                    echo "✔️ " . number_format($contador) . "<br>\n";
                    flush();
                }
            }
        }
    }
}

// Insertar los últimos
if (!empty($valores)) {
    $sql = "INSERT IGNORE INTO cedulas_padron (cedula, nombres, apellidos) VALUES " . implode(',', $valores);
    $conn->query($sql);
}

$conn->commit();
fclose($handle);

echo "<h2>✅ Completado!</h2>";
echo "Total: " . number_format($contador) . "<br>";

// Buscar tu cédula
$result = $conn->query("SELECT * FROM cedulas_padron WHERE cedula = '54512629'");
if ($result->num_rows > 0) {
    $tu = $result->fetch_assoc();
    echo "<br>🎉 <strong>Tu cédula:</strong><br>";
    echo "Nombre: " . $tu['nombres'] . "<br>";
    echo "Apellido: " . $tu['apellidos'] . "<br>";
} else {
    echo "<br>⚠️ Tu cédula 54512629 no está en el CSV";
}

$conn->close();
