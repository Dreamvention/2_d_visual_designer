<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleText extends Controller
{
    private $codename = 'text';
    private $route = 'extension/d_visual_designer_module/text';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/module/d_visual_designer');
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function index($setting)
    {
        $data['setting'] = $this->model_extension_module_d_visual_designer->getSetting($setting, $this->codename);
        
        $data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
        
        return $this->model_extension_d_opencart_patch_load->view($this->route, $data);
    }
    
    public function setting($setting)
    {
        $data['entry_text'] = $this->language->get('entry_text');
        
        $data['setting'] = $this->model_extension_module_d_visual_designer->getSetting($setting, $this->codename);
        
        $data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
        
        return $this->model_extension_d_opencart_patch_load->view($this->route.'_setting', $data);
    }
}
