<?php
echo "Estoy en: " . __DIR__ . "<br>";
$ruta_modelo = __DIR__ . '/../models/obtener_reseñaModels.php';
echo "Buscando modelo en: " . $ruta_modelo . "<br>";

if (file_exists($ruta_modelo)) {
    echo "¡Archivo encontrado!";
} else {
    echo "Archivo NO encontrado. Verifica que la carpeta 'models' esté al mismo nivel que 'services'.";
}
?>