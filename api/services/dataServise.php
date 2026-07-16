<?php
// dataService.php
require_once __DIR__ . '/../models/dataModel.php';

class DataService {
    private $model;

    public function __construct($dbConnection) {
        // Inicializamos el modelo pasando la conexión
        $this->model = new DataModel($dbConnection);
    }

    public function procesarDatosUsuarios() {
        $usuarios = $this->model->obtenerUsuariosConResenas();
        
        if ($usuarios === false) {
            return ["error" => "No se pudieron obtener los datos."];
        }

        // Si necesitas castear el total_reseñas a entero (PHP lo trae como string a veces)
        foreach ($usuarios as &$usuario) {
            $usuario['total_reseñas'] = (int)$usuario['total_reseñas'];
        }

        return $usuarios;
    }
}