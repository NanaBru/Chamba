<?php
$input = 'cedulas.csv';
$output = 'cedulas_limpio.csv';

$in = fopen($input, 'r');
$out = fopen($output, 'w');

while (($linea = fgetcsv($in)) !== false) {
    if (count($linea) >= 3) {
        $cedula = trim($linea[0]);
        // Solo conservar cédulas de 7 u 8 dígitos
        if (strlen($cedula) >= 7 && strlen($cedula) == 8 && ctype_digit($cedula)) {
            fputcsv($out, $linea);
        }
    }
}

fclose($in);
fclose($out);
echo "Archivo limpio creado: cedulas_limpio.csv";
