<?php

include 'connect/connection.php';

class ModelRole
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllRoles()
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM role";
        $result = $conn->query($sql);
        $roles = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $roles[] = $row;
            }
        }

        return $roles;
    }
}