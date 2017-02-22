<?php
/*
 *	location: admin/controller
 */

class ControllerDVisualDesignerModuleColumn extends Controller {
	private $codename = 'column';
	private $route = 'd_visual_designer_module/column';

	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->load->language($this->route);
		$this->load->model('d_visual_designer/designer');
	}
    public function index($setting){

        $data['button_add'] = $this->language->get('button_add');
		
		$data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);

		if(isset($setting['unique_id'])){
			$data['unique_id'] = $setting['unique_id'];
		}
		else{
			$data['unique_id'] = substr(md5(rand()), 0, 7);
		}
	
        return $this->load->view($this->route.'.tpl', $data);
    }
    public function setting($setting){

		$data['entry_size'] = $this->language->get('entry_size');
		$data['entry_float'] = $this->language->get('entry_float');
		$data['entry_align'] = $this->language->get('entry_align');
		$data['entry_offset'] = $this->language->get('entry_offset');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		
		$data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
		
		$data['sizes'] = array(
			'1' => '1/12',
			'2' => '2/12',
			'3' => '3/12',
			'4' => '4/12',
			'5' => '5/12',
			'6' => '6/12',
			'7' => '7/12',
			'8' => '8/12',
			'9' => '9/12',
			'10' => '10/12',
			'11' => '11/12',
			'12' => '12/12',
		);
		
		$data['floats'] = array(
			'' => $this->language->get('text_none'),
			 'left' => $this->language->get('text_left'),
			 'right' => $this->language->get('text_right')
		 );
		$data['aligns'] = array(
			 'left' => $this->language->get('text_left'),
			 'center' => $this->language->get('text_center'),
			 'right' => $this->language->get('text_right')
		 );
		
        return $this->load->view($this->route.'_setting.tpl', $data);
    }
}