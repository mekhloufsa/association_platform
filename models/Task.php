<?php

class Task extends Model {
    protected $table = 'tasks';

    public function findByAssociationId($assocId) {
        $stmt = $this->db->prepare("SELECT t.*, u.nom as assigned_nom, u.prenom as assigned_prenom 
                                  FROM {$this->table} t 
                                  LEFT JOIN users u ON t.assigned_to = u.id 
                                  WHERE t.association_id = ? 
                                  ORDER BY t.due_date ASC, t.priority DESC");
        $stmt->execute([$assocId]);
        return $stmt->fetchAll();
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT t.*, a.name as association_name 
                                  FROM {$this->table} t 
                                  JOIN associations a ON t.association_id = a.id 
                                  WHERE t.assigned_to = ? 
                                  ORDER BY t.due_date ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (association_id, title, description, assigned_to, status, priority, due_date) 
                VALUES (:association_id, :title, :description, :assigned_to, :status, :priority, :due_date)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':association_id' => $data['association_id'],
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':assigned_to' => $data['assigned_to'] ?? null,
            ':status' => $data['status'] ?? 'pending',
            ':priority' => $data['priority'] ?? 'medium',
            ':due_date' => $data['due_date'] ?? null
        ]) ? $this->db->lastInsertId() : false;
    }

    public function updateStatus($taskId, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $taskId]);
    }
}
