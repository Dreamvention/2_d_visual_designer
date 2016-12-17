<?php
/*
 *	location: admin/controller
 */

class ControllerDVisualDesignerModuleText extends Controller {
	private $codename = 'text';
	private $route = 'd_visual_designer_module/text';

	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->load->language($this->route);
		$this->load->model('d_visual_designer/designer');
	}
	
	public function index($setting){

		$data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
		
		$data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
		
		return $this->load->view($this->route.'.tpl', $data);
	}
	
    public function setting($setting){

        $data['entry_text'] = $this->language->get('entry_text');
		
        $data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
				
				$data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
        
        return $this->load->view($this->route.'_setting.tpl', $data);
    }
}