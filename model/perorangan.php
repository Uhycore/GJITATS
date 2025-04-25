<?php
require_once 'connect/connection.php';
require_once 'uniqcode.php';
require_once 'user.php';

class ModelPerorangan extends ModelUser
{
    private $db;
    private $uniqcode;


    public function __construct()
    {
        $this->db = new Database();
        $this->uniqcode = new Uniqcode();
    }

    public function addPerorangan($nama_lengkap, $no_hp, $alamat)
    {
        $conn = $this->db->connect();

        $conn->begin_transaction();

        $user_id = $this->uniqcode->generateUniqueId();

        $role_id = 2;

        if (!$this->addUser($user_id, $role_id)) {
            $conn->rollback();
            return false;
        }

        $nomor_dada = $this->uniqcode->getNextNomorDada();

        $sql = "INSERT INTO perorangan (user_id, nama_lengkap, no_hp, alamat, nomor_dada) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $user_id, $nama_lengkap, $no_hp, $alamat, $nomor_dada);


        if (!$stmt->execute()) {
            $conn->rollback();
            return false;
        }
        $conn->commit();
        return true;
    }


    public function updatePerorangan($nama_lengkap, $no_hp, $alamat, $user_id)
    {
        $conn = $this->db->connect();


        $sql = "UPDATE perorangan SET nama_lengkap = ?, no_hp = ?, alamat = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nama_lengkap, $no_hp, $alamat, $user_id);

        return $stmt->execute();
    }

    public function deletePeroranganByUserId($id)
    {
        $conn = $this->db->connect();

        $sql = "DELETE FROM perorangan WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $this->deleteUser($id);

        return true;
    }



    public function getAllPerorangan()
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM perorangan";
        $result = $conn->query($sql);
        $perorangan = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $perorangan[] = $row;
            }
        }

        return $perorangan;
    }

    public function getPeroranganByUserId($id)
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM perorangan WHERE user_id = ?";
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
