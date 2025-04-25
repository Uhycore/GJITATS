<?php
require_once 'connect/connection.php';
require_once 'user.php';

require_once 'uniqcode.php';

class ModelPerorangan
{
    private $db;
    private $uniqcode;

    private $userModel;

    public function __construct()
    {
        $this->db = new Database();
        $this->uniqcode = new Uniqcode();

        $this->userModel = new ModelUser();
    }

    public function addPerorangan($nama_lengkap, $no_hp, $alamat)
    {
        $conn = $this->db->connect();

        // Start a transaction
        $conn->begin_transaction();

        // Generate unique user_id
        $user_id = $this->uniqcode->generateUniqueId();

        // Assign role_id for user (assumed to be 2 for perorangan)
        $role_id = 2;

        // Add user first
        if (!$this->userModel->addUser($user_id, $role_id)) {
            $conn->rollback(); // Rollback if user insert fails
            return false;
        }

        // Generate nomor dada
        $nomor_dada = $this->uniqcode->getNextNomorDada();

        // Insert into perorangan table
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


    public function updatePerorangan($id, $name, $description, $userId)
    {
        $conn = $this->db->connect();
        $sql = "UPDATE perorangan SET name = ?, description = ?, user_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $name, $description, $userId, $id);
        return $stmt->execute();
    }

    public function deletePeroranganByUserId($id)
    {
        $conn = $this->db->connect();

        // Hapus data perorangan terlebih dahulu
        $sql = "DELETE FROM perorangan WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Kemudian baru hapus data user
        $this->userModel->deleteUser($id);

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
