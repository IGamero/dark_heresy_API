<?php

require_once(dirname(__DIR__) . "/model/EquipCardModel.php");
require_once(dirname(dirname(__DIR__)) . "/config.php");
require_once(__DIR__ . "/middlewares/Middlewares.php");

class EquipCardController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new EquipCardModel($conn);
    }

    public function getEquipCards()
    {
        $result = $this->model->getEquipCards();
        return $result;
    }
    public function getEquipCardsById($id)
    {
        $result = $this->model->getEquipCardsById($id);
        return $result;
    }
    public function postEquipCard($name, $description, $image)
    {
        $result = $this->model->postEquipCard($name, $description, $image);
        return $result;
    }
    public function putEquipCard($id, $name, $description, $image)
    {
        $result = $this->model->putEquipCard($id, $name, $description, $image);
        return $result;
    }
    public function deleteEquipCardById($id)
    {
        $result = $this->model->deleteEquipCardById($id);
        return $result;
    }
    public function setFalseEquipCardById($id)
    {
        $result = $this->model->setFalseEquipCardById($id);
        return $result;
    }
}

$database = new DatabaseConnection();
$conn = $database->getConnection();

$controller = new EquipCardController($conn);

$middlewares = new Middlewares();

// TODO implementar TOKENS

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET["id"];
            $result = $controller->getEquipCardsById($id);

            $middlewares->checkIsValidId($id);
            $middlewares->checkExistId($id);

        } else {
            $result = $controller->getEquipCards();
        }

        if ($result->success) {
            if (isset($_GET['id'])) {
                $data = $result->data;
                if (count($data) > 0) {
                    $response = new stdClass();
                    $response->name = $data[0]["name"];
                    $response->description = $data[0]["description"];
                    $response->image = base64_encode($data[0]["image"]);
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } else {
                    echo "No se encontraron datos para el ID proporcionado.";
                }
            } else {
                $data = $result->data;
                $responseArray = [];
                foreach ($data as $card) {
                    $response = new stdClass();
                    $response->name = $card["name"];
                    $response->description = $card["description"];
                    $response->image = base64_encode($card["image"]);
                    $responseArray[] = $response;
                }
                header('Content-Type: application/json');
                echo json_encode($responseArray);
            }
        } else {
            echo "Error al obtener los datos.";
        }
        break;


    case 'POST':
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = file_get_contents($_FILES['image']['tmp_name']);

        $result = $controller->postEquipCard($name, $description, $image);

        header('Content-Type: application/json');
        $jsonResult = json_encode($result);

        echo $jsonResult;

        break;

    case 'PUT':
        $id = $_GET["id"];

        $middlewares->checkRequiredId();
        $middlewares->checkIsValidId($id);
        $middlewares->checkExistId($id);

        $put_data = json_decode(file_get_contents("php://input"), true);


        if ($put_data !== null) {
            $name = $put_data['name'];
            $description = $put_data['description'];
            $image = base64_decode($put_data['image']);

            // var_dump($name, $description, $image);

            $result = $controller->putEquipCard($id, $name, $description, $image);

            header('Content-Type: application/json');
            $jsonResult = json_encode($result);

            echo $jsonResult;

        } else {
            $response = new stdClass();
            $response->error = '500 - Internal Server ERROR';
            $response->error_msg = "Error al decodificar los datos JSON.";
            $jsonResult = json_encode($response);
            echo $jsonResult;
        }

        break;
    case 'DELETE':
        $delete_permanent = false;
        $id = $_GET["id"];

        $middlewares->checkRequiredId();
        $middlewares->checkIsValidId($id);
        $middlewares->checkExistId($id);

        if ($delete_permanent) {
            $result = $controller->deleteEquipCardById($id);
        } else {
            $result = $controller->setFalseEquipCardById($id);
        }
        $jsonResult = json_encode($result);

        echo $jsonResult;

        break;
    default:
        echo $result->msg = "error";
        break;
}

?>