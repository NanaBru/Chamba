<?php
$input = 'cedulas.csv';
$output = 'cedulas_ordenado.csv';

$data = [];
$handle = fopen($input, 'r');

while (($linea = fgetcsv($handle)) !== false) {
    if (count($linea) >= 3) {
        $data[] = $linea;
    }
}
fclose($handle);

// Ordenar por cédula (columna 0)
usort($data, function($a, $b) {
    return (int)$a[0] - (int)$b[0];
});

// Guardar ordenado
$out = fopen($output, 'w');
foreach ($data as $linea) {
    fputcsv($out, $linea);
}
fclose($out);

echo "Archivo ordenado creado: cedulas_ordenado.csv\n";
echo "Total de registros: " . count($data);
