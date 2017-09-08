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
        $this->load->model('extension/module/d_visual_designer');
        $this->load->model('extension/d_opencart_patch/load');
    }
    public function index($setting)
    {
        $data['setting'] = $this->model_extension_module_d_visual_designer->getSetting($setting, $this->codename);
        
        $matches = array();
        
        if (strpos($data['setting']['link'], 'youtube')) {
            preg_match('/(v=)([a-zA-Z0-9]+)/', $data['setting']['link'], $matches);
            
            if (!empty($matches[2])) {
                $youtube_id = $matches[2];
            }
            
            $data['link'] = str_replace("watch?v=", "embed/", $data['setting']['link']);
            
            $data['link'] .= "?playlist=".$youtube_id."&autoplay=1&controls=0&showinfo=0&disablekb=1&loop=1&rel=0&modestbranding";
        } elseif (strpos($data['setting']['link'], 'vimeo')) {
            $data['link'] = str_replace("vimeo.com", "player.vimeo.com/video", $data['setting']['link']);
            
            $data['link'] .= "?autoplay=1&background=1&loop=1";
        }
        
        $data['unique_id'] = uniqid();
        
        return $this->model_extension_d_opencart_patch_load->view($this->route, $data);
    }
    public function setting($setting)
    {
        $data['entry_background_video'] = $this->language->get('entry_background_video');
        $data['entry_video_link'] = $this->language->get('entry_video_link');
        $data['entry_row_stretch'] = $this->language->get('entry_row_stretch');
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        
        $data['setting'] = $this->model_extension_module_d_visual_designer->getSetting($setting, $this->codename);
        
        $data['stretchs'] = array(
            '' => $this->language->get('text_default'),
            'stretch_row' => $this->language->get('text_stretch_row'),
            'stretch_row_content' => $this->language->get('text_stretch_row_content'),
            'stretch_row_content_no_spaces' => $this->language->get('text_stretch_row_content_no_spaces')
            );
        
        return $this->model_extension_d_opencart_patch_load->view($this->route.'_setting', $data);
    }
    
    public function layout($setting)
    {
        $setting_layout = $setting['setting'];
        
        $items = $setting['items'];
        
        $size = explode('+', $setting_layout['size']);
        
        $default_column_setting = $this->model_extension_module_d_visual_designer->getSettingBlock('column');
        
        if (count($size) > count($items)) {
            $count = count($size) - count($items);
            
            for ($i=0; $i < $count; $i++) {
                $block_id = 'column_'.$this->model_extension_module_d_visual_designer->getRandomString();
                $items[$block_id] = array(
                    'setting' => $default_column_setting,
                    'sort_order' => 99,
                    'parent' => $setting['parent'],
                    'type' => 'column'
                    );
            }
        } elseif (count($size) < count($items)) {
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
