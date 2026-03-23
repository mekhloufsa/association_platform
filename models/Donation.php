<?php

class Donation extends Model {
    protected $table = 'donations';

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT d.*, a.name as association_name 
                                  FROM {$this->table} d 
                                  LEFT JOIN associations a ON d.association_id = a.id 
                                  WHERE d.user_id = ? 
                                  ORDER BY d.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findAllWithDetails() {
        $stmt = $this->db->prepare("SELECT d.*, u.nom, u.prenom, a.name as association_name, s.address as siege_address 
                                   FROM {$this->table} d 
                                   JOIN users u ON d.user_id = u.id 
                                   LEFT JOIN associations a ON d.association_id = a.id 
                                   LEFT JOIN sieges s ON d.siege_id = s.id 
                                   ORDER BY d.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByAssociationId($assocId) {
        $stmt = $this->db->prepare("SELECT d.*, u.nom, u.prenom 
                                   FROM {$this->table} d 
                                   JOIN users u ON d.user_id = u.id 
                                   WHERE d.association_id = ? 
                                   ORDER BY d.created_at DESC");
        $stmt->execute([$assocId]);
        return $stmt->fetchAll();
    }
    public function findBySiegeId($siegeId) {
        $stmt = $this->db->prepare("SELECT d.*, u.nom, u.prenom 
                                   FROM {$this->table} d 
                                   JOIN users u ON d.user_id = u.id 
                                   WHERE d.siege_id = ? 
                                   ORDER BY d.created_at DESC");
        $stmt->execute([$siegeId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, association_id, siege_id, amount, type, message, status) 
                VALUES (:user_id, :association_id, :siege_id, :amount, :type, :message, :status)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':association_id' => $data['association_id'] ?? null,
            ':siege_id' => $data['siege_id'] ?? null,
            ':amount' => $data['amount'],
            ':type' => $data['type'] ?? 'onetime',
            ':message' => $data['message'] ?? null,
            ':status' => 'completed' // In a real app, this would depend on payment success
        ]) ? $this->db->lastInsertId() : false;
    }

    public function getTotalDonated($userId) {
        $stmt = $this->db->prepare("SELECT SUM(amount) as total FROM {$this->table} WHERE user_id = ? AND status = 'completed'");
        $stmt->execute([$userId]);
        $res = $stmt->fetch();
        return $res['total'] ?? 0;
    }
}
