<?php
/*
 *	location: admin/controller
 */

class ControllerDVisualDesignerModuleRow extends Controller {
	private $codename = 'row';
	private $route = 'd_visual_designer_module/row';

	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->load->language($this->route);
		$this->load->model('d_visual_designer/designer');
	}
    public function index($setting){

        $data['button_add'] = $this->language->get('button_add');
		
		$data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
		
        return $this->load->view($this->route.'.tpl', $data);
    }
    public function setting($setting){
		$data['entry_background_video'] = $this->language->get('entry_background_video');
		$data['entry_video_link'] = $this->language->get('entry_video_link');
		$data['entry_row_stretch'] = $this->language->get('entry_row_stretch');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		
		$data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
		
		$data['stretchs'] = array(
			'' => $this->language->get('text_default'),
			'stretch_row' => $this->language->get('text_stretch_row'),
			'stretch_row_content' => $this->language->get('text_stretch_row_content'),
			'stretch_row_content_no_spaces' => $this->language->get('text_stretch_row_content_no_spaces')
		);
		
        return $this->load->view($this->route.'_setting.tpl', $data);
    }
	
	public function layout($setting){
		$setting_layout = $setting['setting'];
		
		$items = $setting['items'];
		
		$size = explode('+', $setting_layout['size']);
		
		$default_column_setting = $this->model_d_visual_designer_designer->getSettingBlock('column');
		
		if(count($size) > count($items)){
			$count = count($size) - count($items);
			
			for ($i=0; $i < $count; $i++) { 
				$block_id = 'column_'.$this->model_d_visual_designer_designer->getRandomString();
				$items[$block_id] = array(
					'setting' => $default_column_setting,
					'sort_order' => 99,
					'parent' => $setting['parent'],
					'type' => 'column'
				);
			}
		}
		elseif (count($size) < count($items)) {
			$count = count($size) - count($items);
			$items = array_slice($items, 0, $count);
		}
		
		$index = 0;
		
		foreach ($items as $key => $item) {
			
			$items[$key]['setting']['size'] = $size[$index++]; 
		}
		
		return $items;
	}

}