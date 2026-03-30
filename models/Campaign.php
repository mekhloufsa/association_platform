<?php

class Campaign extends Model {
    protected $table = 'campaigns';

    public function findOpen($limit = 10) {
        $stmt = $this->db->prepare("SELECT c.*, a.name as association_name,
                                    (SELECT COUNT(*) FROM volunteers v WHERE v.campaign_id = c.id AND v.status IN ('registered', 'confirmed', 'attended')) as current_volunteers 
                                  FROM {$this->table} c 
                                  JOIN associations a ON c.association_id = a.id 
                                  WHERE c.status = 'open' AND c.end_date >= CURDATE() AND c.approval_status = 'approved'
                                  ORDER BY c.start_date ASC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT c.*, a.name as association_name,
                                    (SELECT COUNT(*) FROM volunteers v WHERE v.campaign_id = c.id AND v.status IN ('registered', 'confirmed', 'attended')) as current_volunteers
                                  FROM {$this->table} c 
                                  JOIN associations a ON c.association_id = a.id 
                                  WHERE c.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAllWithDetails() {
        $stmt = $this->db->prepare("SELECT c.*, a.name as association_name,
                                    (SELECT COUNT(*) FROM volunteers v WHERE v.campaign_id = c.id AND v.status IN ('registered', 'confirmed', 'attended')) as current_volunteers
                                  FROM {$this->table} c 
                                  JOIN associations a ON c.association_id = a.id 
                                  ORDER BY c.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByAssociationId($assocId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE association_id = ? ORDER BY created_at DESC");
        $stmt->execute([$assocId]);
        return $stmt->fetchAll();
    }

    public function findBySiegeId($siegeId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE siege_id = ? ORDER BY created_at DESC");
        $stmt->execute([$siegeId]);
        return $stmt->fetchAll();
    }

    public function findPendingByAssociation($assocId) {
        $stmt = $this->db->prepare("SELECT c.*, s.address as siege_address 
                                  FROM {$this->table} c 
                                  LEFT JOIN sieges s ON c.siege_id = s.id
                                  WHERE c.association_id = ? AND c.approval_status = 'pending' 
                                  ORDER BY c.created_at DESC");
        $stmt->execute([$assocId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (association_id, siege_id, title, description, start_date, end_date, location, max_volunteers, campaign_type, need_type, financial_goal, image_path, approval_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['association_id'],
            $data['siege_id'] ?? null,
            $data['title'],
            $data['description'],
            $data['start_date'],
            $data['end_date'],
            $data['location'],
            $data['max_volunteers'],
            $data['campaign_type'] ?? 'national',
            $data['need_type'] ?? 'personnel',
            $data['financial_goal'] ?? null,
            $data['image_path'] ?? null,
            $data['approval_status'] ?? 'approved'
        ]);
    }

    public function updateApprovalStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET approval_status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $params[] = $value;
        }
        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
