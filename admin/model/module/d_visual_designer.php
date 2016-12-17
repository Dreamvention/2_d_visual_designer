<?php
/*
*	location: admin/model
*/

class ModelModuleDVisualDesigner extends Model {
	
	public function createDatabase(){
		$this->db->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."visual_designer_route (
		`route_id` INT(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(256) NOT NULL,
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
			`content` MEDIUMTEXT NULL,
			`sort_order` INT(11) NULL DEFAULT NULL,
			PRIMARY KEY (`template_id`)
		)
		COLLATE='utf8_general_ci' ENGINE=MyISAM;");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."visual_designer_template_description (
			`template_id` INT(11) NOT NULL,
			`language_id` INT(11) NOT NULL,
			`name` VARCHAR(256) NULL DEFAULT NULL,
			PRIMARY KEY (`template_id`, `language_id`)
		)
		COLLATE='utf8_general_ci' ENGINE=MyISAM;");

		$this->db->query("INSERT IGNORE INTO ".DB_PREFIX."visual_designer_route
			(`route_id`, `name`, `backend_route`, `frontend_status`, `frontend_route`, `backend_param`, `frontend_param`, `edit_url`, `status`)
		VALUES
			(1, 'Add Product', 'catalog/product/add', 0, '', '', '', '', 1),
			(2, 'Add Category', 'catalog/category/add', 0, '', '', '', '', 1),
			(3, 'Add Information','catalog/information/add', 0, '', '', '', '', 1),
			(4, 'Edit Product','catalog/product/edit', 1, 'product/product', 'product_id', 'product_id', '".$this->config->get('config_url')."index.php?route=module/d_visual_designer/saveProduct', 1),
			(5,' Edit Category', 'catalog/category/edit', 1, 'product/category', 'category_id', 'path', '".$this->config->get('config_url')."index.php?route=module/d_visual_designer/saveCategory', 1),
			(6,  'Edit Information','catalog/information/edit', 1, 'information/information', 'information_id', 'information_id', '".$this->config->get('config_url')."index.php?route=module/d_visual_designer/saveInformation', 1);
		");
				
		if(VERSION >= '2.3.0.0'){
			$this->db->query("INSERT IGNORE INTO ".DB_PREFIX."visual_designer_route
				(`route_id`, `name`, `backend_route`, `frontend_status`, `frontend_route`, `backend_param`, `frontend_param`, `edit_url`, `status`)
				VALUES (7, 'Visual Designer Module', 'extension/module/d_visual_designer_module', 1, 'common/home', 'module_id', 'module_id', '".$this->config->get('config_url')."index.php?route=module/d_visual_designer/saveVDModule', 1);");
		}
		else{
			$this->db->query("INSERT IGNORE INTO ".DB_PREFIX."visual_designer_route
				(`route_id`, `name`, `backend_route`, `frontend_status`, `frontend_route`, `backend_param`, `frontend_param`, `edit_url`, `status`)
			VALUES (7, 'Visual Designer Module', 'module/d_visual_designer_module', 0, 'common/home', 'module_id', 'module_id', '".$this->config->get('config_url')."index.php?route=module/d_visual_designer/saveVDModule', 1);");
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