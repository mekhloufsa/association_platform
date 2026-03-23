<?php

class Siege extends Model {
    protected $table = 'sieges';

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT s.*, w.name as wilaya_name, a.name as association_name, u.nom as manager_nom, u.prenom as manager_prenom, u.email as manager_email 
                                  FROM {$this->table} s 
                                  JOIN wilayas w ON s.wilaya_id = w.id 
                                  JOIN associations a ON s.association_id = a.id
                                  LEFT JOIN users u ON s.manager_user_id = u.id
                                  WHERE s.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByAssociationId($assocId) {
        $stmt = $this->db->prepare("SELECT s.*, w.name as wilaya_name, u.nom as manager_nom, u.prenom as manager_prenom 
                                  FROM {$this->table} s 
                                  JOIN wilayas w ON s.wilaya_id = w.id 
                                  LEFT JOIN users u ON s.manager_user_id = u.id
                                  WHERE s.association_id = ?");
        $stmt->execute([$assocId]);
        return $stmt->fetchAll();
    }

    public function findByManagerId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE manager_user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function findAllPubliclyVisible($assocId) {
        $stmt = $this->db->prepare("SELECT s.*, w.name as wilaya_name, u.nom as manager_nom, u.prenom as manager_prenom 
                                   FROM {$this->table} s 
                                   JOIN wilayas w ON s.wilaya_id = w.id 
                                   JOIN users u ON s.manager_user_id = u.id
                                   WHERE s.association_id = ? AND s.manager_user_id IS NOT NULL");
        $stmt->execute([$assocId]);
        return $stmt->fetchAll();
    }

    public function findVacantByWilaya($wilayaId) {
        $stmt = $this->db->prepare("SELECT s.*, a.name as association_name 
                                   FROM {$this->table} s 
                                   JOIN associations a ON s.association_id = a.id 
                                   WHERE s.wilaya_id = ? AND s.manager_user_id IS NULL");
        $stmt->execute([$wilayaId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (association_id, wilaya_id, address, manager_user_id) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['association_id'],
            $data['wilaya_id'],
            $data['address'],
            $data['manager_user_id'] ?? null
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateManager($id, $managerId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET manager_user_id = ? WHERE id = ?");
        return $stmt->execute([$managerId, $id]);
    }

    public function findByAssocAndWilaya($assocId, $wilayaId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE association_id = ? AND wilaya_id = ?");
        $stmt->execute([$assocId, $wilayaId]);
        return $stmt->fetch();
    }
}
