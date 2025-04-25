<?php
include 'connect/connection.php';
include 'model/role.php';

class ModelUser
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    public function addUser($name, $email, $password, $roleId)
    {
        $conn = $this->db->connect();
        $sql = "INSERT INTO user (name, email, password, role_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, password_hash($password, PASSWORD_BCRYPT), $roleId);
        return $stmt->execute();
    }
    public function updateUser($id, $name, $email, $password, $roleId)
    {
        $conn = $this->db->connect();
        $sql = "UPDATE user SET name = ?, email = ?, password = ?, role_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $name, $email, password_hash($password, PASSWORD_BCRYPT), $roleId, $id);
        return $stmt->execute();
    }

    public function deleteUser($id)
    {
        $conn = $this->db->connect();
        $sql = "DELETE FROM user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getAllUsers()
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);
        $users = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }

    public function getUserById($id)
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM user WHERE id = ?";
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


    public function getUserByRole($roleId)
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM user WHERE role_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $roleId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }
}
