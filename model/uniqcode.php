<?php

require_once 'connect/connection.php';

class Uniqcode
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    public function generateUniqueId()
    {
        $timestamp = time(); 
        return $timestamp;
    }

    public function getNextNomorDada()
    {
        $conn = $this->db->connect();
        $sql = "SELECT nomor_dada FROM perorangan ORDER BY nomor_dada DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastNomorDada = $row['nomor_dada'];
            $nextNomorDada = (int)$lastNomorDada + 1;
        } else {
            $nextNomorDada = 1001;
        }

        return $nextNomorDada;
    }
}
