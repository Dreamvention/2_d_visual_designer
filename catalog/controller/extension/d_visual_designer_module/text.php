<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleText extends Controller {
    private $codename = 'text';
    private $route = 'extension/d_visual_designer_module/text';

    public function __construct($registry) {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('extension/module/d_visual_designer');
    }
    
    public function index($setting){
        
        $data['setting'] = $this->model_extension_module_d_visual_designer->getSetting($setting, $this->codename);
        
        $data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
        
        if(VERSION>='2.2.0.0') {
            return $this->load->view($this->route, $data);
        }
        else {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$this->route.'.twig')) {
                return $this->load->view($this->config->get('config_template') . '/template/'.$this->route.'.twig', $data);
            } else {
                return $this->load->view('default/template/'.$this->route.'.twig', $data);
            }
        }
    }
    
    public function setting($setting){

        $data['entry_text'] = $this->language->get('entry_text');
        
        $data['setting'] = $this->model_extension_module_d_visual_designer->getSetting($setting, $this->codename);
        
        $data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
        
        if(VERSION>='2.2.0.0') {
            return $this->load->view($this->route.'_setting', $data);
        }
        else {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$this->route.'_setting.twig')) {
                return $this->load->view($this->config->get('config_template') . '/template/'.$this->route.'_setting.twig', $data);
            } else {
                return $this->load->view('default/template/'.$this->route.'_setting.twig', $data);
            }
        }
    }
}