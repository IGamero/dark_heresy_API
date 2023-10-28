<?php
class EquipCardModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getEquipCards()
    {
        $sql = "SELECT * FROM `equipCards`";
        $statement = $this->conn->prepare($sql);
        $response = new stdClass();

        if ($statement->execute()) {
            $result = $statement->get_result();
            $cards = array();

            while ($row = $result->fetch_assoc()) {
                $cards[] = $row;
            }
            $response->status = "success";
            $response->message = "Datos recibidos correctamente";
            $response->success = true;
            $response->data = $cards;
        } else {
            $response->status = "error";
            $response->message = "No se pudieron obtener los datos";
            $response->success = false;
            $response->data = new stdClass();
        }
        return $response;
    }

    public function getEquipCardsById($id)
    {
        $sql = "SELECT * FROM equipCards WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $id);

        $response = new stdClass();

        if ($statement->execute()) {
            $result = $statement->get_result();
            $cards = array();

            while ($row = $result->fetch_assoc()) {
                $cards[] = $row;
            }

            $response->status = "success";
            $response->message = "Datos recibidos correctamente";
            $response->success = true;
            $response->data = $cards;
        } else {
            $response->status = "error";
            $response->message = "No se pudieron obtener los datos";
            $response->success = false;
            $response->data = new stdClass();
        }
        return $response;
    }

    public function postEquipCard($name, $description, $image)
    {
        $sql = "INSERT INTO equipCards (name, description, image, isActive) VALUES(?, ?, ?, true)";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("sss", $name, $description, $image);

        $response = new stdClass();

        if ($statement->execute()) {
            $response->status = "success";
            $response->message = "Datos subidos correctamente";
            $response->success = true;
        } else {
            $response->status = "error";
            $response->message = "Ha ocurrido un error :(";
            $response->success = false;
        }

        return $response;

    }
    public function putEquipCard($id, $name, $description, $image)
    {
        $sql = "UPDATE equipCards SET name = ?, description = ?, image = ? WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("sssi", $name, $description, $image, $id);

        $response = new stdClass();

        if ($statement->execute()) {
            $response->status = "success";
            $response->message = "Datos actualizados correctamente";
            $response->success = true;
        } else {
            $response->status = "error";
            $response->message = "Ha ocurrido un error :(";
            $response->success = false;
        }

        return $response;
    }
    public function deleteEquipCardById($id)
    {
        $sql = "DELETE FROM equipCards WHERE equipCards . id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $id);

        $response = new stdClass();

        if ($statement->execute()) {
            $response->status = "success";
            $response->message = "El registro con id " . $id . " ha sido eliminado permanentemente";
            $response->success = true;
        } else {
            $response->status = "error";
            $response->message = "Ha ocurrido un error :(";
            $response->success = false;

        }

        return $response;
    }

    public function setFalseEquipCardById($id)
    {
        $sql = "UPDATE equipCards SET isActive = false WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $id);

        $response = new stdClass();

        if ($statement->execute()) {
            $response->status = "success";
            $response->message = "El registro con id " . $id . " ha sido desactivado";
            $response->success = true;
        } else {
            $response->status = "error";
            $response->message = "Ha ocurrido un error :(";
            $response->success = false;

        }

        return $response;
    }
}
?>