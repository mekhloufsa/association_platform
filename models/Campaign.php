<?php

class Campaign extends Model {
    protected $table = 'campaigns';

    public function findOpen($limit = 10) {
        $stmt = $this->db->prepare("SELECT c.*, a.name as association_name,
                                    (SELECT COUNT(*) FROM volunteers v WHERE v.campaign_id = c.id AND v.status IN ('registered', 'confirmed', 'attended')) as current_volunteers 
                                  FROM {$this->table} c 
                                  JOIN associations a ON c.association_id = a.id 
                                  WHERE c.status = 'open' AND c.end_date >= CURDATE() 
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

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (association_id, title, description, start_date, end_date, location, max_volunteers, campaign_type, need_type, financial_goal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['association_id'],
            $data['title'],
            $data['description'],
            $data['start_date'],
            $data['end_date'],
            $data['location'],
            $data['max_volunteers'],
            $data['campaign_type'] ?? 'local',
            $data['need_type'] ?? 'personnel',
            $data['financial_goal'] ?? null
        ]);
    }
}
