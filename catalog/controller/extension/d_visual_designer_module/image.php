<?php
/*
 *	location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleImage extends Controller
{
    private $codename = 'image';
    private $route = 'extension/d_visual_designer_module/image';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model($this->route);
    }
    public function index($setting)
    {  
        $this->load->model('tool/image');

        if (!empty($setting['image']) && is_file(DIR_IMAGE . $setting['image'])) {
            $image = $setting['image'];
        } else {
            $image = 'no_image.png';
        }

        list($width, $height) = getimagesize(DIR_IMAGE . $image);
        
        $data['thumb'] = $this->model_tool_image->resize($image, $width, $height);

        if (VERSION>='3.0.0.0') {
            $popup_width = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width');
            $popup_height = $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_height');
        } elseif (VERSION>='2.2.0.0') {
            $popup_width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
            $popup_height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
        } else {
            $popup_width = $this->config->get('config_image_popup_width');
            $popup_height = $this->config->get('config_image_popup_height');
        }
        $data['popup'] = $this->model_tool_image->resize($image, $popup_width, $popup_height);
        
        return $data;
    }

    public function styles($permission) {
        $data = array();

        $data[] = 'catalog/view/theme/default/stylesheet/d_visual_designer/blocks/image.css';

        return $data;
    }
}
