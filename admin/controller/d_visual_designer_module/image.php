<?php
/*
 *	location: admin/controller
 */

class ControllerDVisualDesignerModuleImage extends Controller
{
    private $codename = 'image';
    private $route = 'd_visual_designer_module/image';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model('d_visual_designer/designer');
    }
    public function index($setting)
    {
        $this->load->model('tool/image');
        
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_style'] = $this->language->get('entry_style');
        
        $data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
        
        if (isset($data['setting']['image']) && is_file(DIR_IMAGE . $data['setting']['image'])) {
            $image = $data['setting']['image'];
        } else {
            $image = 'no_image.png';
        }
        
        if (!empty($data['setting']['style'])) {
            $data['style'] = $this->language->get('text_style_'.$data['setting']['style']);
        } else {
            $data['style'] = $this->language->get('text_style_default');
        }
        
        
        $data['thumb'] = $this->model_tool_image->resize($image, 32, 32);
        
        return $this->load->view($this->route.'.tpl', $data);
    }
    
    public function setting($setting)
    {
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_size'] = $this->language->get('entry_size');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_style'] = $this->language->get('entry_style');
        $data['entry_align'] = $this->language->get('entry_align');
        $data['entry_animate'] = $this->language->get('entry_animate');
        $data['entry_additional_image'] = $this->language->get('entry_additional_image');
        $data['entry_onclick'] = $this->language->get('entry_onclick');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_link_target'] = $this->language->get('entry_link_target');
        $data['entry_alt'] = $this->language->get('entry_alt');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_parallax'] = $this->language->get('entry_parallax');
        $data['entry_parallax_height'] = $this->language->get('entry_parallax_height');
        
        $data['text_new_window'] = $this->language->get('text_new_window');
        $data['text_current_window'] = $this->language->get('text_current_window');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        
        
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_image_add'] = $this->language->get('button_image_add');
        
        $data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
        
        $this->load->model('tool/image');
        
        if (isset($data['setting']['image']) && is_file(DIR_IMAGE . $data['setting']['image'])) {
            $image = $data['setting']['image'];
        } else {
            $image = 'no_image.png';
        }
        
        
        $data['thumb'] = $this->model_tool_image->resize($image, 100, 100);
        
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        
        $data['styles'] = array(
            '' => $this->language->get('text_style_default'),
            'rounded' => $this->language->get('text_style_rounded'),
            'border' => $this->language->get('text_style_border'),
            'outline' => $this->language->get('text_style_outline'),
            'shadow' => $this->language->get('text_style_shadow'),
            'shadow_border' => $this->language->get('text_style_bordered_shadow'),
            'shadow_3d' => $this->language->get('text_style_shadow_3d'),
            'circle' => $this->language->get('text_style_circle'),
            'border_circle' => $this->language->get('text_style_border_circle'),
            'outline_circle' => $this->language->get('text_style_outline_circle'),
            'shadow_circle' => $this->language->get('text_style_shadow_circle'),
            'shadow_border_circle' => $this->language->get('text_style_shadow_border_circle'),
        );
        
        $data['aligns'] = array(
            'left' => $this->language->get('text_left'),
            'center' => $this->language->get('text_center'),
            'right' => $this->language->get('text_right')
        );
        
        $data['animates'] = array(
            '' => $this->language->get('text_no'),
            'fadeInDown' => $this->language->get('text_top_to_bottom'),
            'fadeInUp' => $this->language->get('text_bottom_to_top'),
            'fadeInLeft' => $this->language->get('text_left_to_right'),
            'fadeInRight' => $this->language->get('text_right_to_left'),
            'fadeIn' =>  $this->language->get('text_apear')
        );
        
        $data['sizes'] = array(
            'original' => $this->language->get('text_original'),
            'responsive' => $this->language->get('text_responsive'),
            'small' => $this->language->get('text_small'),
            'medium' => $this->language->get('text_medium'),
            'large' => $this->language->get('text_large'),
            'custom' => $this->language->get('text_custom')
        );
        
        $data['actions'] = array(
            '' => $this->language->get('text_none'),
            'link' => $this->language->get('text_link'),
            'popup' => $this->language->get('text_popup')
        );
        
        return $this->load->view($this->route.'_setting.tpl', $data);
    }
}
