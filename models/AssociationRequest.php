<?php

class AssociationRequest extends Model {
    protected $table = 'association_requests';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, name, description, national_id_number, logo_path, attachments, status) 
                VALUES (:user_id, :name, :description, :national_id_number, :logo_path, :attachments, 'pending')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':national_id_number' => $data['national_id_number'],
            ':logo_path' => $data['logo_path'] ?? null,
            ':attachments' => $data['attachments'] ?? null
        ]);
    }

    public function findAll() {
        $stmt = $this->db->prepare("SELECT ar.*, u.nom, u.prenom, u.email FROM {$this->table} ar 
                                   JOIN users u ON ar.user_id = u.id 
                                   ORDER BY ar.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status, $message = null) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ?, admin_message = ? WHERE id = ?");
        return $stmt->execute([$status, $message, $id]);
    }
}
