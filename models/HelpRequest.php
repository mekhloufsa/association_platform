<?php

class HelpRequest extends Model {
    protected $table = 'help_requests';

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT hr.*, a.name as association_name 
                                  FROM {$this->table} hr 
                                  LEFT JOIN associations a ON hr.association_id = a.id 
                                  WHERE hr.user_id = ? 
                                  ORDER BY hr.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, association_id, siege_id, subject, description, attachments, status) 
                VALUES (:user_id, :association_id, :siege_id, :subject, :description, :attachments, :status)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':association_id' => $data['association_id'] ?? null,
            ':siege_id' => $data['siege_id'] ?? null,
            ':subject' => $data['subject'],
            ':description' => $data['description'],
            ':attachments' => $data['attachments'] ?? null, // JSON string
            ':status' => 'pending'
        ]) ? $this->db->lastInsertId() : false;
    }
    public function findByAssociationId($associationId) {
        $stmt = $this->db->prepare("SELECT hr.*, u.nom, u.prenom, u.email 
                                  FROM {$this->table} hr 
                                  JOIN users u ON hr.user_id = u.id 
                                  WHERE hr.association_id = ? 
                                  ORDER BY hr.created_at DESC");
        $stmt->execute([$associationId]);
        return $stmt->fetchAll();
    }

    public function findBySiegeId($siegeId) {
        $stmt = $this->db->prepare("SELECT hr.*, u.nom, u.prenom, u.email 
                                  FROM {$this->table} hr 
                                  JOIN users u ON hr.user_id = u.id 
                                  WHERE hr.siege_id = ? 
                                  ORDER BY hr.created_at DESC");
        $stmt->execute([$siegeId]);
        return $stmt->fetchAll();
    }

    public function findByWilayaAndAssociation($wilayaId, $assocId) {
        $stmt = $this->db->prepare("SELECT hr.*, u.nom, u.prenom, u.email 
                                  FROM {$this->table} hr 
                                  JOIN users u ON hr.user_id = u.id 
                                  WHERE u.wilaya_id = ? AND hr.association_id = ? 
                                  ORDER BY hr.created_at DESC");
        $stmt->execute([$wilayaId, $assocId]);
        return $stmt->fetchAll();
    }

    public function findAllExtended() {
        $stmt = $this->db->prepare("SELECT hr.*, u.nom, u.prenom, a.name as association_name 
                                  FROM {$this->table} hr 
                                  JOIN users u ON hr.user_id = u.id 
                                  LEFT JOIN associations a ON hr.association_id = a.id 
                                  ORDER BY hr.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByIdWithDetails($id) {
        $stmt = $this->db->prepare("SELECT hr.*, u.nom, u.prenom, u.email, u.phone, a.name as association_name, s.address as siege_address 
                                  FROM {$this->table} hr 
                                  JOIN users u ON hr.user_id = u.id 
                                  LEFT JOIN associations a ON hr.association_id = a.id 
                                  LEFT JOIN sieges s ON hr.siege_id = s.id 
                                  WHERE hr.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status, $appointmentDetails = null, $refusalMessage = null) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ?, appointment_details = ?, refusal_message = ? WHERE id = ?");
        return $stmt->execute([$status, $appointmentDetails, $refusalMessage, $id]);
    }
}
