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
        $this->load->model('extension/d_opencart_patch/load');
    }
    
    public function index($setting)
    {   

        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
        
        return $data;
    }
    
    public function setting($setting)
    {
        $data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
        
        return $data;
    }

    public function local()
    {
        $data = array();

        $data['entry_text'] = $this->language->get('entry_text');

        return $data;
    }
}
