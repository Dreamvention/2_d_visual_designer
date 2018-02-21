<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleRow extends Controller
{
    private $codename = 'row';
    private $route = 'extension/d_visual_designer_module/row';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
    }

    public function options($permission){
        $data = array();
        if ($permission) {
            $data['stretchs'] = array(
                '' => $this->language->get('text_default'),
                'stretch_row' => $this->language->get('text_stretch_row'),
                'stretch_row_content' => $this->language->get('text_stretch_row_content'),
                'stretch_row_content_no_spaces' => $this->language->get('text_stretch_row_content_no_spaces')
            );
            $data['layout_sizes'] = array('12', '6+6', '4+4+4', '3+3+3+3', '8+4', '4+8', '3+9', '9+3', '6+3+3', '3+3+6', '3+6+3');
        }
        return $data;
    }

    public function local($permission)
    {
        $data = array();
        if($permission){
            $data['entry_background_video'] = $this->language->get('entry_background_video');
            $data['entry_video_link'] = $this->language->get('entry_video_link');
            $data['entry_row_stretch'] = $this->language->get('entry_row_stretch');
            
            $data['text_enabled'] = $this->language->get('text_enabled');
            $data['text_yes'] = $this->language->get('text_yes');
            $data['text_no'] = $this->language->get('text_no');
            $data['text_set_custom'] = $this->language->get('text_set_custom');
        }
        return $data;
    }
}
