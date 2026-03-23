<?php

class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (nom, prenom, email, password_hash, role, phone, wilaya_id, status) 
                VALUES (:nom, :prenom, :email, :password_hash, :role, :phone, :wilaya_id, :status)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'] ?? 'default123', PASSWORD_BCRYPT),
            ':role' => $data['role'] ?? 'user',
            ':phone' => $data['phone'] ?? null,
            ':wilaya_id' => $data['wilaya_id'] ?? null,
            ':status' => 'active'
        ]) ? $this->db->lastInsertId() : false;
    }

    public function findAll($search = '', $role = '', $status = '') {
        $sql = "SELECT u.*, w.name as wilaya_name 
                FROM {$this->table} u 
                LEFT JOIN wilayas w ON u.wilaya_id = w.id WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (u.nom LIKE ? OR u.prenom LIKE ? OR u.email LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if ($role) {
            $sql .= " AND u.role = ?";
            $params[] = $role;
        }

        if ($status) {
            $sql .= " AND u.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY u.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT u.*, w.name as wilaya_name FROM {$this->table} u LEFT JOIN wilayas w ON u.wilaya_id = w.id WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateRole($id, $role) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
