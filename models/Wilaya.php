<?php

class Wilaya extends Model {
    protected $table = 'wilayas';
    public function findAll() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
