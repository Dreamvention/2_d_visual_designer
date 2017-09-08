<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleColumn extends Controller
{
    private $codename = 'column';
    private $route = 'extension/d_visual_designer_module/column';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/module/d_visual_designer');
        $this->load->model('extension/d_opencart_patch/load');
    }
    public function index($setting)
    {
        $data['button_add'] = $this->language->get('button_add');
        
        $data['setting'] = $this->model_extension_module_d_visual_designer->getSetting($setting, $this->codename);
        
        if (isset($setting['unique_id'])) {
            $data['unique_id'] = $setting['unique_id'];
        } else {
            $data['unique_id'] = substr(md5(rand()), 0, 7);
        }
        
        return $this->model_extension_d_opencart_patch_load->view($this->route, $data);
    }
    public function setting($setting)
    {
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_float'] = $this->language->get('entry_float');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_offset'] = $this->language->get('entry_offset');
        $data['entry_adaptive_design'] = $this->language->get('entry_adaptive_design');

        $data['column_offset'] = $this->language->get('column_offset');
        $data['column_size'] = $this->language->get('column_size');
        $data['column_device'] = $this->language->get('column_device');

        $data['text_tablet'] = $this->language->get('text_tablet');
        $data['text_phone'] = $this->language->get('text_phone');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_none'] = $this->language->get('text_none');
        
        $data['setting'] = $this->model_extension_module_d_visual_designer->getSetting($setting, $this->codename);

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

        return $this->model_extension_d_opencart_patch_load->view($this->route.'_setting', $data);
    }
}
