<?php
/*
*	location: admin/model
*/

class ModelDVisualDesignerRoute extends Model {
    
    public function addRoute($data){
        $this->db->query("INSERT INTO ".DB_PREFIX."visual_designer_route SET
            name='".$this->db->escape($data['name'])."',
            token='".$data['token']."',
            backend_route='".$data['backend_route']."',
            status='".$data['status']."',
            frontend_route='".$data['frontend_route']."',
            edit_url='".$data['edit_url']."',
            frontend_param='".$data['frontend_param']."',
            backend_param='".$data['backend_param']."',
            frontend_status='".$data['frontend_status']."'"
        );
        $route_id = $this->db->getLastId();

        return $route_id;
    }
    
    public function editRoute($route_id, $data){
        $this->db->query("UPDATE ".DB_PREFIX."visual_designer_route SET 
        name='".$this->db->escape($data['name'])."',
        token='".$data['token']."',
        backend_route='".$data['backend_route']."',
        status='".$data['status']."',
        frontend_route='".$data['frontend_route']."',
        edit_url='".$data['edit_url']."',
        frontend_param='".$data['frontend_param']."',
        backend_param='".$data['backend_param']."',
        frontend_status='".$data['frontend_status']."'
        WHERE route_id='".$route_id."'");
    }
    
    public function deleteRoute($route_id){
        $this->db->query("DELETE FROM ".DB_PREFIX."visual_designer_route WHERE route_id='".$route_id."'");
    }
    
    public function getRoutes($data = array()){
        $sql = "SELECT * FROM ".DB_PREFIX."visual_designer_route r";
        
        $implode = array();
        
        if(!empty($data['filter_backend_route'])){
            $implode[] = "r.backend_route='".$data['filter_backend_route']."'";
        }
        
        if(count($implode) > 0){
          $sql .= 'WHERE'.implode(' AND ', $implode);
        }
        
        $sort_data = array(
            'name',
            'sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        
        $query = $this->db->query($sql);
        
        $route_data = array();
        
        if($query->num_rows){
            foreach ($query->rows as $row) {
                
                $route_data[] = array(
                    'route_id' => $row['route_id'],
                    'token' => $row['token'],
                    'status' => $row['status'],
                    'backend_route' => $row['backend_route'],
                    'frontend_route' => $row['frontend_route'],
                    'frontend_status' => $row['frontend_status'],
                    'frontend_param' => $row['frontend_param'],
                    'backend_param' => $row['backend_param'],
                    'edit_url' => $row['edit_url'],
                    'name' => $row['name']
                );
            }
        }
        
        return $route_data;
    }
    
    public function getRoute($route_id){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."visual_designer_route r
        WHERE r.route_id='".$route_id."'");
        
        return $query->row;
    }
    
    public function checkPermission($route){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."visual_designer_route WHERE backend_route='".$route."'");
        
        return $query->row;
    }
    
    public function getTotalRoutes(){
        $query = $this->db->query("SELECT count(*) as total FROM ".DB_PREFIX."visual_designer_route");
        
        return $query->row['total'];
    }
}