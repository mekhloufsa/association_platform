<?php

class SiegeRequest extends Model {
    protected $table = 'siege_requests';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, siege_id, national_id_number, description, contact_info, attachments, status) 
                VALUES (:user_id, :siege_id, :national_id_number, :description, :contact_info, :attachments, 'pending')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':siege_id' => $data['siege_id'],
            ':national_id_number' => $data['national_id_number'],
            ':description' => $data['description'],
            ':contact_info' => $data['contact_info'],
            ':attachments' => $data['attachments'] ?? null
        ]);
    }

    public function findByAssociationId($assocId) {
        $stmt = $this->db->prepare("SELECT sr.*, u.nom, u.prenom, u.email, s.address, w.name as wilaya_name 
                                   FROM {$this->table} sr 
                                   JOIN users u ON sr.user_id = u.id 
                                   JOIN sieges s ON sr.siege_id = s.id 
                                   JOIN wilayas w ON s.wilaya_id = w.id
                                   WHERE s.association_id = ? 
                                   ORDER BY sr.created_at DESC");
        $stmt->execute([$assocId]);
        return $stmt->fetchAll();
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT sr.*, s.address, a.name as association_name 
                                   FROM {$this->table} sr 
                                   JOIN sieges s ON sr.siege_id = s.id 
                                   JOIN associations a ON s.association_id = a.id 
                                   WHERE sr.user_id = ? 
                                   ORDER BY sr.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status, $message = null) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ?, president_message = ? WHERE id = ?");
        return $stmt->execute([$status, $message, $id]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT sr.*, s.association_id FROM {$this->table} sr JOIN sieges s ON sr.siege_id = s.id WHERE sr.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
