<?php
include 'connect/connection.php';
include 'model/user.php';

class ModelProrangan
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addProrangan($name, $description, $userId)
    {
        $conn = $this->db->connect();
        $sql = "INSERT INTO prorangan (name, description, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $description, $userId);
        return $stmt->execute();
    }
    public function updateProrangan($id, $name, $description, $userId)
    {
        $conn = $this->db->connect();
        $sql = "UPDATE prorangan SET name = ?, description = ?, user_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $name, $description, $userId, $id);
        return $stmt->execute();
    }
    public function deleteProrangan($id)
    {
        $conn = $this->db->connect();
        $sql = "DELETE FROM prorangan WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getAllProrangan()
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM prorangan";
        $result = $conn->query($sql);
        $prorangan = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prorangan[] = $row;
            }
        }

        return $prorangan;
    }

    public function getProranganById($id)
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM prorangan WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
}
