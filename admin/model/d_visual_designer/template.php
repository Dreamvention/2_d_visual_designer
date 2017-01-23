<?php
/*
*	location: admin/model
*/

class ModelDVisualDesignerTemplate extends Model {
    
    public function addTemplate($data){
        $this->db->query("INSERT INTO ".DB_PREFIX."visual_designer_template SET 
            content='".$this->db->escape($data['content'])."', 
            image='".$data['image']."',
            category='".$data['category']."',
            sort_order='".$data['sort_order']."'
        ");
        $template_id = $this->db->getLastId();
        if(!empty($data['template_description'])){
            foreach ($data['template_description'] as $language_id => $value) {
                if(!empty($value)){
                   $this->db->query("INSERT INTO ".DB_PREFIX."visual_designer_template_description SET
                    template_id='".$template_id."',
                    language_id='".$language_id."',
                    name='".$value['name']."'
                ");  
                }
            }
        }
        
        return $template_id;
    }
    
    public function editTemplate($template_id, $data){
        
        $this->db->query("UPDATE ".DB_PREFIX."visual_designer_template SET 
        content='".$this->db->escape($data['content'])."', 
        image='".$data['image']."',
        category='".$data['category']."',
        sort_order='".$data['sort_order']."'
        WHERE template_id='".$template_id."'");
        
        $this->db->query("DELETE FROM ".DB_PREFIX."visual_designer_template_description WHERE template_id='".$template_id."'");
        
        if(!empty($data['template_description'])){
            foreach ($data['template_description'] as $language_id => $value) {
                $this->db->query("INSERT INTO ".DB_PREFIX."visual_designer_template_description SET
                    template_id='".$template_id."',
                    language_id='".$language_id."',
                    name='".$value['name']."'
                ");
            }
        }
    }
    
    public function deleteTemplate($template_id){
        $this->db->query("DELETE FROM ".DB_PREFIX."visual_designer_template WHERE template_id='".$template_id."'");
        $this->db->query("DELETE FROM ".DB_PREFIX."visual_designer_template_description WHERE template_id='".$template_id."'");
    }
    
    public function getTemplates($data=array()){

        $sql = "SELECT * FROM ".DB_PREFIX."visual_designer_template  t
        LEFT JOIN ".DB_PREFIX."visual_designer_template_description td 
        ON t.template_id = td.template_id
        WHERE td.language_id='".(int)$this->config->get('config_language_id')."'";

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
        
        $template_data = array();
        
        if($query->num_rows){
            foreach ($query->rows as $row) {
                $template_data[] = array(
                    'template_id' => $row['template_id'],
                    'content' => $row['content'],
                    'sort_order' => $row['sort_order'],
                    'name' => $row['name'],
                    'image' => $row['image'],
                    'category' => $row['category']
                );
            }
        }
        
        return $template_data;
    }
    
    public function getTemplate($template_id){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."visual_designer_template t
        LEFT JOIN ".DB_PREFIX."visual_designer_template_description td 
        ON t.template_id = td.template_id
        WHERE td.language_id='".(int)$this->config->get('config_language_id')."' AND t.template_id='".$template_id."'");
        
        return $query->row;
    }
    
    public function getTemplateDescriptions($template_id){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."visual_designer_template t
            LEFT JOIN ".DB_PREFIX."visual_designer_template_description td 
            ON t.template_id = td.template_id
            WHERE t.template_id='".$template_id."'");
        $template_data = array();
        
        if($query->num_rows){
            foreach ($query->rows as $row) {
                $template_data[$row['language_id']] = array(
                    'name' => $row['name']
                );
            }
        }
        
        return $template_data;
    }
    
    public function getTotalTemplates($data = array()){
        $query = $this->db->query("SELECT count(*) as total FROM ".DB_PREFIX."visual_designer_template t
            LEFT JOIN ".DB_PREFIX."visual_designer_template_description td 
            ON t.template_id = td.template_id
            WHERE td.language_id = '".(int)$this->config->get('config_language_id')."'");
        
        return $query->row['total'];
    }
}