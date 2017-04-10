<?php
/*
*	location: admin/model
*/

class ModelDVisualDesignerTemplate extends Model {
    
    private $sort = 'name';
    
    private $order = 'ASC';
    
    public function addTemplate($data){
        $this->db->query("INSERT INTO ".DB_PREFIX."visual_designer_template SET 
            content='".$this->db->escape($data['content'])."', 
            image='".$data['image']."',
            name='".$data['name']."',
            category='".$data['category']."',
            sort_order='".$data['sort_order']."'
        ");
        $template_id = $this->db->getLastId();
        
        return $template_id;
    }
    
    public function editTemplate($template_id, $data){
        
        $this->db->query("UPDATE ".DB_PREFIX."visual_designer_template SET 
        content='".$this->db->escape($data['content'])."', 
        image='".$data['image']."',
        name='".$data['name']."',
        category='".$data['category']."',
        sort_order='".$data['sort_order']."'
        WHERE template_id='".$template_id."'");
        
    }
    
    public function deleteTemplate($template_id){
        $this->db->query("DELETE FROM ".DB_PREFIX."visual_designer_template WHERE template_id='".$template_id."'");
    }
    
    public function getTemplates($data=array()){

        $sql = "SELECT * FROM ".DB_PREFIX."visual_designer_template  t ";

        $query = $this->db->query($sql);
        
        $template_data = array();
        
        if($query->num_rows){
            foreach ($query->rows as $row) {
                $template_data[] = array(
                    'template_id' => $row['template_id'],
                    'content' => $row['content'],
                    'sort_order' => $row['sort_order'],
                    'name' => $row['name'],
                    'config' => '',
                    'image' => $row['image'],
                    'category' => $row['category']
                );
            }
        }
        
        $templates_config = $this->getConfigTemplates();
            
        $template_data = array_merge($template_data, $templates_config);
        
        $sort_data = array(
            'name',
            'sort_order'
        );
        
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $this->sort = $data['sort'];
        }
        
        
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $this->order = "DESC";
        } else {
            $this->order = "ASC";
        }
        
        uasort($template_data, 'ModelDVisualDesignerTemplate::sort');

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
        
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $template_data = array_slice($template_data, $data['start'], $data['limit']);        
        }
        
        return $template_data;
    }
    
    public function sort($a, $b){
        if($a[$this->sort] < $b[$this->sort]){
            return $this->order=='ASC'?-1:1;
        }
        else{
            return $this->order=='ASC'?1:-1;
        }
        
        if($a[$this->sort] == $b[$this->sort]){
            return 0;
        }
    }
    
    public function getConfigTemplates(){
        
        $dir = DIR_CONFIG.'d_visual_designer_template/';
        if(is_dir($dir)){
            $files = scandir($dir);
        }
        else{
            $files = array();
        }
    
        $template_data = array();
        
        foreach($files as $file){
            if(strlen($file) > 1 && strpos( $file, '.php')){
                $_ = array();
                
                $results = array();
    
                require($dir.$file);
    
                $results = array_merge($results, $_);
                
                $templates = $results['d_visual_designer_templates'];
                foreach ($templates as $template) {
                    $template_data[] = array(
                         'template_id' => $template['template_id'],
                         'content' => $template['content'],
                         'config' => substr($file, 0, -4),
                         'image' => $template['image'],
                         'category' => $template['category'],
                         'sort_order' => $template['sort_order'],
                         'name' => $template['name']
                    );
                }    
            }
        }
        return $template_data;
    }
    
    public function getTemplate($template_id){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."visual_designer_template t WHERE t.template_id='".$template_id."'");
        
        return $query->row;
    }
    
    public function getConfigTemplate($template_id, $config){
        $_ = array();
        
        $results = array();

        require(DIR_CONFIG.'d_visual_designer_template/'.$config.'.php');

        $results = array_merge($results, $_);
        
        $templates = $results['d_visual_designer_templates'];
                
        foreach ($templates as $template) {
            if($template['template_id'] == $template_id){
                return $template;
            }
        }
        return array();
    }
       
    public function getTotalTemplates(){
        $query = $this->db->query("SELECT count(*) as total FROM ".DB_PREFIX."visual_designer_template t ");

        $templates_config = $this->getConfigTemplates();
        
        return $query->row['total']+count($templates_config);
    }
}