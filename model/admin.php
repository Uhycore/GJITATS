<?php
require_once 'connect/connection.php';
require_once 'uniqcode.php';
require_once 'user.php';

class ModelAdmin extends ModelUser
{
    private $db;
    private $uniqcode;


    public function __construct()
    {
        $this->db = new Database();
        $this->uniqcode = new Uniqcode();
    }

    public function addAdmin($nama_lengkap, $no_hp, $alamat)
    {
        $conn = $this->db->connect();

        $conn->begin_transaction();

        $user_id = $this->uniqcode->generateUniqueId();

        $role_id = 1;

        if (!$this->addUser($user_id, $role_id)) {
            $conn->rollback();
            return false;
        }

        $nomor_dada = $this->uniqcode->getNextNomorDada();

        $sql = "INSERT INTO admin (user_id, nama_lengkap, no_hp, alamat, nomor_dada) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $user_id, $nama_lengkap, $no_hp, $alamat, $nomor_dada);

        if (!$stmt->execute()) {
            $conn->rollback();
            return false;
        }
        $conn->commit();
        return true;
    }
}
