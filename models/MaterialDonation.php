<?php

class MaterialDonation extends Model {
    protected $table = 'material_donations';

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT md.*, a.name as association_name, s.address as siege_address 
                                  FROM {$this->table} md 
                                  JOIN associations a ON md.association_id = a.id 
                                  LEFT JOIN sieges s ON md.siege_id = s.id 
                                  WHERE md.user_id = ? 
                                  ORDER BY md.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findByAssociationId($assocId) {
        $stmt = $this->db->prepare("SELECT md.*, u.nom, u.prenom, s.address as siege_address 
                                  FROM {$this->table} md 
                                  JOIN users u ON md.user_id = u.id 
                                  LEFT JOIN sieges s ON md.siege_id = s.id 
                                  WHERE md.association_id = ? 
                                  ORDER BY md.created_at DESC");
        $stmt->execute([$assocId]);
        return $stmt->fetchAll();
    }

    public function findAllWithDetails() {
        $stmt = $this->db->prepare("SELECT md.*, u.nom, u.prenom, a.name as association_name, s.address as siege_address 
                                  FROM {$this->table} md 
                                  JOIN users u ON md.user_id = u.id 
                                  LEFT JOIN associations a ON md.association_id = a.id 
                                  LEFT JOIN sieges s ON md.siege_id = s.id 
                                  ORDER BY md.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySiegeId($siegeId) {
        $stmt = $this->db->prepare("SELECT md.*, u.nom, u.prenom 
                                  FROM {$this->table} md 
                                  JOIN users u ON md.user_id = u.id 
                                  WHERE md.siege_id = ? 
                                  ORDER BY md.created_at DESC");
        $stmt->execute([$siegeId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, association_id, siege_id, category, description, quantity, status) 
                VALUES (:user_id, :association_id, :siege_id, :category, :description, :quantity, :status)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':association_id' => $data['association_id'],
            ':siege_id' => $data['siege_id'] ?? null,
            ':category' => $data['category'],
            ':description' => $data['description'],
            ':quantity' => $data['quantity'] ?? null,
            ':status' => 'pending'
        ]) ? $this->db->lastInsertId() : false;
    }

    public function updateStatus($id, $status, $pickupDate = null, $managerMessage = null) {
        $sql = "UPDATE {$this->table} SET status = ?";
        $params = [$status];
        
        if ($pickupDate) {
            $sql .= ", pickup_date = ?";
            $params[] = $pickupDate;
        }

        if ($managerMessage !== null) {
            $sql .= ", manager_message = ?";
            $params[] = $managerMessage;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
