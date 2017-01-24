<?php
/*
*	location: admin/model
*/

class ModelModuleDVisualDesigner extends Model {
	
	public function createDatabase(){
		$this->db->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."visual_designer_route (
		`route_id` INT(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(256) NOT NULL,
		`token` VARCHAR(64) NOT NULL,
		`backend_route` VARCHAR(256) NOT NULL,
		`frontend_status` INT(11) NOT NULL,
		`status` INT(11) NOT NULL,
		`frontend_route` VARCHAR(256) NOT NULL,
		`backend_param` VARCHAR(256) NOT NULL,
		`frontend_param` VARCHAR(256) NOT NULL,
		`edit_url` VARCHAR(256) NOT NULL,
		PRIMARY KEY (`route_id`)
		)
		COLLATE='utf8_general_ci' ENGINE=MyISAM;");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."visual_designer_template (
			`template_id` INT(11) NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(256) NOT NULL,
			`image` VARCHAR(256) NOT NULL,
			`category` VARCHAR(64) NOT NULL,
			`content` MEDIUMTEXT NULL,
			`sort_order` INT(11) NULL DEFAULT NULL,
			PRIMARY KEY (`template_id`)
		)
		COLLATE='utf8_general_ci' ENGINE=MyISAM;");
		
		if (!file_exists(DIR_SYSTEM.'/mbooth/install/d_visual_designer.sql')) {
            exit('Could not load sql file: ' . DIR_SYSTEM.'/mbooth/install/d_visual_designer.sql');
        }

        $lines = file(DIR_SYSTEM.'/mbooth/install/d_visual_designer.sql');
        if ($lines) {
            foreach($lines as $line) {
                if ($line) {
                    if (preg_match('/;\s*$/', $line)) {				
						$sql = str_replace("INSERT INTO `oc_", "INSERT INTO `" . DB_PREFIX, $line);
						$sql = str_replace("TRUNCATE TABLE `oc_", "TRUNCATE TABLE `" . DB_PREFIX, $line);
						$this->db->query($sql);
					}
				}
			}
			if($this->config->get('config_language_id')!=1){
              $sql = "INSERT INTO ".DB_PREFIX."visual_designer_template_description
              	(`template_id`, `language_id`, `name`)
              	SELECT `template_id`, '".$this->config->get('config_language_id')."', `name`
               	FROM ".DB_PREFIX."visual_designer_template_description";
              $this->db->query($sql);
            }
		}
	}
	
	public function dropDatabase(){
		$this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."visual_designer_route");
		$this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."visual_designer_template");
		$this->db->query("DROP TABLE IF EXISTS ".DB_PREFIX."visual_designer_template_description");
	}
	
	public function ajax($link){
		return str_replace('&amp;', '&', $link);
	}
	
	public function getGroupId(){
        if(VERSION == '2.0.0.0'){
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . $this->user->getId() . "'");
            $user_group_id = (int)$user_query->row['user_group_id'];
        }else{
            $user_group_id = $this->user->getGroupId();
        }

        return $user_group_id;
    }
	
	public function getLink($route,$args,$catalog = false){
		$https = $this->request->server['HTTPS'];
		if(!empty($https)){
			if($catalog){
				$url = HTTPS_CATALOG;
			}else {
				$url = HTTPS_SERVER;
			}
		}
		else{
			if($catalog){
				$url = HTTP_CATALOG;
			}else {
				$url = HTTP_SERVER;
			}
		}
		
		$url .= 'index.php?route=' . $route;
		
		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}
		
		return $url;
	}
	
}