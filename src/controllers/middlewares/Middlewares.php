<?php
class Middlewares
{
    public function checkRequiredId()
    {
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            echo "El ID es obligatorio.";
            exit;
        }
    }
    public function checkIsValidId($id)
    {
        if (!is_numeric($id)) {
            echo "El id " . $id . " no es valido.";
            exit;
        }
    }
    public function checkExistId($id)
    {
        $database = new DatabaseConnection();
        $conn = $database->getConnection();

        $controller = new EquipCardController($conn);

        $result = $controller->getEquipCardsById($id);
        if (!$result->data) {
            echo "El no existe ningun registro con id " . $id . ".";
            exit;
        }


    }
}

?>