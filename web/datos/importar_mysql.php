<?php
ini_set('max_execution_time', 600);
ini_set('memory_limit', '2048M');

$conn = new mysqli('localhost', 'root', '', 'chambabd');

if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

$archivo = __DIR__ . '/cedulas.csv';
$handle = fopen($archivo, 'r');

$stmt = $conn->prepare("INSERT IGNORE INTO cedulas_padron (cedula, nombres, apellidos) VALUES (?, ?, ?)");

$contador = 0;

echo "<h3>Importando solo MAYORES DE 18 AÑOS (10M - 63M)...</h3>";

while (($linea = fgetcsv($handle)) !== false) {
    if (count($linea) >= 3) {
        $cedula = trim($linea[0]);
        $cedula_int = (int)$cedula;
        
        // Filtro: Solo mayores de 18 (aproximadamente hasta cédula 63 millones - nacidos antes de 2007)
        if ($cedula_int >= 10000000 && $cedula_int <= 63000000) {
            $stmt->bind_param("sss", $cedula, trim($linea[1]), trim($linea[2]));
            $stmt->execute();
            
            $contador++;
            if ($contador % 10000 == 0) {
                echo "✔️ Importados: $contador<br>\n";
                flush();
            }
        }
    }
}

fclose($handle);
$stmt->close();
$conn->close();

echo "<h2>✅ Importación completada!</h2>";
echo "Total importados: $contador<br>";

$conn2 = new mysqli('localhost', 'root', '', 'chambabd');
$result = $conn2->query("SELECT COUNT(*) as total FROM cedulas_padron");
$row = $result->fetch_assoc();
echo "<br><strong>✅ Registros en BD: " . $row['total'] . "</strong><br>";

// Verificar tu cédula
$result2 = $conn2->query("SELECT * FROM cedulas_padron WHERE cedula = '54512629' LIMIT 1");
if ($result2->num_rows > 0) {
    $tu = $result2->fetch_assoc();
    echo "<br>🎉 <strong>Tu cédula (54512629) encontrada:</strong><br>";
    echo "Nombres: " . $tu['nombres'] . "<br>";
    echo "Apellidos: " . $tu['apellidos'] . "<br>";
} else {
    echo "<br>⚠️ Tu cédula 54512629 NO está en este rango";
}

$conn2->close();
