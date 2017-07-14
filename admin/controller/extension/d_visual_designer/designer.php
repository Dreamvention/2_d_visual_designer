<?php
class ControllerExtensionDVisualDesignerDesigner extends Controller {
    public $codename = 'd_visual_designer';
    public $route = 'extension/d_visual_designer/designer';
    public $extension = '';

    private $d_shopunity = '';

    private $scripts = array();
    private $styles = array();

    private $error = array();

    private $store_url = '';
    
    private $catalog_url = '';

    private $store_id = '';
    
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language($this->route);
        $this->load->language('extension/module/d_visual_designer');
        $this->load->model($this->route);
        $this->load->model('extension/module/d_visual_designer');

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/d_visual_designer.json'), true);

        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;

        if($this->request->server['HTTPS']){
            $this->store_url = HTTPS_SERVER;
            $this->catalog_url = HTTPS_CATALOG;
        }
        else{
            $this->store_url = HTTP_SERVER;
            $this->catalog_url = HTTP_CATALOG;
        }

    }

    public function index(){

        $json = array();

        if (isset($this->request->post['description'])) {
            $description = $this->request->post['description'];
        }

        if(isset($this->request->post['url'])){
            $url = $this->request->post['url'];
        }

        if($this->validate()){
            $this->styles[] = 'view/stylesheet/d_visual_designer/d_visual_designer.css?'.rand();
            
            $this->styles[] = 'view/javascript/d_visual_designer/vd-libraries.min.css';
            $this->scripts[] = 'view/javascript/d_visual_designer/vd-libraries.min.js';

            //button
            $data['button_add'] = $this->language->get('button_add');
            $data['button_close'] = $this->language->get('button_close');
            $data['button_save'] = $this->language->get('button_save');
            $data['button_saved'] = $this->language->get('button_saved');
            //text
            $data['text_add_block'] = $this->language->get('text_add_block');
            $data['text_edit_block'] = $this->language->get('text_edit_block');
            $data['text_add_template'] = $this->language->get('text_add_template');
            $data['text_codeview'] = $this->language->get('text_codeview');
            $data['text_classic_mode'] = $this->language->get('text_classic_mode');
            $data['text_backend_editor'] = $this->language->get('text_backend_editor');
            $data['text_frontend_editor'] = $this->language->get('text_frontend_editor');
            $data['text_designer'] = $this->language->get('text_designer');
            $data['text_welcome_header'] = $this->language->get('text_welcome_header');
            $data['text_add_block'] = $this->language->get('text_add_block');
            $data['text_add_text_block'] = $this->language->get('text_add_text_block');
            $data['text_add_template'] = $this->language->get('text_add_template');
            $data['text_save_template'] = $this->language->get('text_save_template');
            $data['text_search'] = $this->language->get('text_search');
            $data['text_layout'] = $this->language->get('text_layout');
            $data['text_set_custom'] = $this->language->get('text_set_custom');

            $data['entry_border_color'] = $this->language->get('entry_border_color');
            $data['entry_border_style'] = $this->language->get('entry_border_style');
            $data['entry_border_radius'] = $this->language->get('entry_border_radius');
            $data['entry_animate'] = $this->language->get('entry_animate');
            $data['entry_show_on'] = $this->language->get('entry_show_on');
            $data['entry_background'] = $this->language->get('entry_background');
            $data['entry_additional_css_class'] = $this->language->get('entry_additional_css_class');
            $data['entry_additional_css_before'] = $this->language->get('entry_additional_css_before');
            $data['entry_additional_css_content'] = $this->language->get('entry_additional_css_content');
            $data['entry_additional_css_after'] = $this->language->get('entry_additional_css_after');
            $data['entry_margin'] = $this->language->get('entry_margin');
            $data['entry_padding'] = $this->language->get('entry_padding');
            $data['entry_border'] = $this->language->get('entry_border');
            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_category'] = $this->language->get('entry_category');
            $data['entry_image'] = $this->language->get('entry_image');
            $data['entry_image_style'] = $this->language->get('entry_image_style');
            $data['entry_image_position'] = $this->language->get('entry_image_position');
            $data['entry_size'] = $this->language->get('entry_size');
            $data['entry_image_template'] = $this->language->get('entry_image_template');
            $data['entry_sort_order'] = $this->language->get('entry_sort_order');

            $data['tab_general'] = $this->language->get('tab_general');
            $data['tab_design'] = $this->language->get('tab_design');
            $data['tab_css'] = $this->language->get('tab_css');
            $data['tab_save_block'] = $this->language->get('tab_save_block');
            $data['tab_templates'] = $this->language->get('tab_templates');
            $data['tab_all_blocks'] = $this->language->get('tab_all_blocks');
            $data['tab_content_blocks'] = $this->language->get('tab_content_blocks');
            $data['tab_social_blocks'] = $this->language->get('tab_social_blocks');
            $data['tab_structure_blocks'] = $this->language->get('tab_structure_blocks');

            $data['text_top'] = $this->language->get('text_top');
            $data['text_right'] = $this->language->get('text_right');
            $data['text_bottom'] = $this->language->get('text_bottom');
            $data['text_left'] = $this->language->get('text_left');

            $data['text_phone'] = $this->language->get('text_phone');
            $data['text_tablet'] = $this->language->get('text_tablet');
            $data['text_desktop'] = $this->language->get('text_desktop');

            $data['text_horizontal'] = $this->language->get('text_horizontal');
            $data['text_vertical'] = $this->language->get('text_vertical');

            $data['text_complete_version'] = $this->language->get('text_complete_version');
            $data['text_complete_version_template'] = $this->language->get('text_complete_version_template');

            $data['error_name'] = $this->language->get('error_name');

            //error
            $data['error_size'] = $this->language->get('error_size');

            $data['designer_id'] = $this->{'model_extension_'.$this->codename.'_designer'}->getRandomString();

            $blocks = $this->{'model_extension_'.$this->codename.'_designer'}->parseDescription($description);

            $url_info = parse_url(str_replace('&amp;', '&', $url));

            $url_params = array();

            parse_str($url_info['query'], $url_params);

            $route_info = $this->{'model_extension_'.$this->codename.'_designer'}->getRouteByBackendRoute($url_params['route']);

            if($route_info['frontend_status']){
                if(!empty($route_info['backend_param']) && !empty($url_params[$route_info['backend_param']])){
                    $params = '&'.$route_info['frontend_param'].'='.$url_params[$route_info['backend_param']];
                    $frontend_param = '&id='.$url_params[$route_info['backend_param']];
                    $frontend_url = htmlentities(urlencode($this->catalog_url.'index.php?route='.$route_info['frontend_route'].$params));
                    $data['frontend_route'] = $this->url->link('extension/d_visual_designer/designer/frontend','token='.$this->session->data['token'].'&url='.$frontend_url.'&route_config='.$route_info['config_name'].$frontend_param);
                }
                if(!isset($route_info['backend_param'])){
                    $frontend_url = htmlentities(urlencode($this->catalog_url.'index.php?route='.$route_info['frontend_route']));
                    $data['frontend_route'] = $this->url->link('extension/d_visual_designer/designer/frontend','token='.$this->session->data['token'].'&url='.$frontend_url.'&route_config='.$route_info['config_name'].$frontend_param);
                }
            }

            $this->load->model('localisation/language');

            $data['languages'] = $this->model_localisation_language->getLanguages();
            foreach ($data['languages'] as $key =>  $language){
                if(VERSION >= '2.2.0.0'){
                    $data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
                }
                else{
                    $data['languages'][$key]['flag'] = 'view/image/flags/'.$language['image'];
                }
            }

            $data['content'] = $blocks['content'];
            
            $json['rows'] = json_encode($blocks['setting']);

            $data['base'] = $this->store_url;

            $data['border_styles'] = array(
                ''       => $this->language->get('text_default'),
                'dotted' => $this->language->get('text_dotted'),
                'dashed' => $this->language->get('text_dashed'),
                'solid'  => $this->language->get('text_solid'),
                'double' => $this->language->get('text_double'),
                'groove' => $this->language->get('text_groove'),
                'ridge'  => $this->language->get('text_ridge'),
                'inset'  => $this->language->get('text_inset'),
                'outset' => $this->language->get('text_outset')
                );

            $data['image_styles'] = array(
                'cover' => $this->language->get('text_cover'),
                'contain' => $this->language->get('text_contain'),
                'no-repeat'  => $this->language->get('text_no_repeat'),
                'repeat' => $this->language->get('text_repeat'),
                'parallax' => $this->language->get('text_parallax')
                );

            $data['image_horizontal_positions'] = array(
                'left' => $this->language->get('text_position_left'),
                'center' => $this->language->get('text_position_center'),
                'right' => $this->language->get('text_position_right')
                );

            $data['image_vertical_positions'] = array(
                'top' => $this->language->get('text_position_top'),
                'center' => $this->language->get('text_position_center'),
                'bottom' => $this->language->get('text_position_bottom')
                );

            $data['animates'] = array(
                '' => $this->language->get('text_none'),
                'fadeInDown' => $this->language->get('text_fade_in_down'),
                'fadeInUp' => $this->language->get('text_fade_in_up'),
                'fadeInLeft' => $this->language->get('text_fade_in_left'),
                'fadeInRight' => $this->language->get('text_fade_in_right'),
                'fadeIn' =>  $this->language->get('text_fade_in'),
                'bounceIn' => $this->language->get('text_bounce_in'),
                'rubberBand' => $this->language->get('text_rubber_band'),
                'rollIn' => $this->language->get('text_roll_in'),
                'lightSpeedIn' => $this->language->get('text_light_speed_in'),
                'flipInX' => $this->language->get('text_flip_in_x'),
                'flipInY' => $this->language->get('text_flip_in_y'),
                'jello' => $this->language->get('text_jello'),
                'mk-floating-tossing' => $this->language->get('text_mk_floating_tossing'),
                'mk-floating-pulse' => $this->language->get('text_mk_floating_pulse'),
                'mk-floating-vertical' => $this->language->get('text_mk_floating_vertical'),
                'mk-floating-horizontal' => $this->language->get('text_mk_floating_horizontal'),
                'bounce_jump' => $this->language->get('text_bounce_jump')
                );

            $this->load->model('tool/image');

            $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

            $data['scripts'] = $this->scripts;
            $data['styles'] = $this->styles;

            $data['components'] = $this->{'model_extension_module_'.$this->codename}->getComponents($data);

            $data['filemanager_url'] = str_replace('&amp;', '&', $this->url->link('extension/'.$this->codename.'/filemanager', 'token='.$this->session->data['token'], 'SSL'));

            $json['content'] = $this->load->view($this->route.'.tpl', $data);

            $json['success'] = 'success';
        }
        else{
            $json['error'] = $this->language->get('error_status');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function frontend(){
        if(!empty($this->request->get['url'])){
            $url = html_entity_decode($this->request->get['url']);
        }

        if(!empty($this->request->get['route_config'])){
            $route_config = html_entity_decode($this->request->get['route_config']);
        }

        if(!empty($this->request->get['id'])){
            $id = html_entity_decode($this->request->get['id']);
        }

        if(!empty($url)&& !empty($route_config)){

            $data['button_add_block'] = $this->language->get('button_add_block');
            $data['button_add_template'] = $this->language->get('button_add_template');
            $data['button_save_template'] = $this->language->get('button_save_template');
            $data['button_mobile'] = $this->language->get('button_mobile');
            $data['button_tablet'] = $this->language->get('button_tablet');
            $data['button_desktop'] = $this->language->get('button_desktop');
            $data['button_reload'] = $this->language->get('button_reload');

            $data['button_backend_editor'] = $this->language->get('button_backend_editor');
            $data['button_publish'] = $this->language->get('button_publish');
            $data['button_cancel'] = $this->language->get('button_cancel');

            $data['text_save_template'] = $this->language->get('text_save_template');
            $data['text_success_update'] = $this->language->get('text_success_update');
            $data['error_permission'] = $this->language->get('error_permission');
            $data['text_success_template_save'] = $this->language->get('text_success_template_save');
            $data['text_success_clone_block'] = $this->language->get('text_success_clone_block');
            $data['text_success_remove_block'] = $this->language->get('text_success_remove_block');

            $data['url'] = $url;

            $route_info = $this->{'model_extension_'.$this->codename.'_designer'}->getRoute($route_config);

            if(!empty($route_info['backend_param'])&!empty($id)){
                $param = $route_info['backend_param'].'='.$id;
            }
            else{
                $param = '';
            }

            $data['backend_url'] = $this->url->link($route_info['backend_route'], $param.'&token='.$this->session->data['token']);
            $data['direction'] = $this->language->get('direction');
            $data['lang'] = $this->language->get('code');
            $data['base'] = $this->store_url;
            $data['text_frontend_title'] = $this->language->get('text_frontend_title');
            $this->response->setOutput($this->load->view('extension/'.$this->codename.'/frontend_editor.tpl',$data));
        }
    }

    public function updateDesigner(){
        $json = array();
        
        if(isset($this->request->post['description'])){
            $description = $this->request->post['description'];
        }

        if(isset($description)){
            $blocks = $this->{'model_extension_'.$this->codename.'_designer'}->parseDescription($description);
            $json['content'] = $blocks['content'];
            $json['rows'] = json_encode($blocks['setting']);
            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getSettingModule(){
        if(isset($this->request->post['type'])){
            $type = $this->request->post['type'];
        }

        $json = array();

        if(isset($type)){

            $this->load->model('tool/image');

            if(!empty($this->request->post['design_background_image'])){
                $image = $this->request->post['design_background_image'];
                if(file_exists(DIR_IMAGE.$image)){
                    $thumb = $this->model_tool_image->resize($image, 100, 100);
                }
                else{
                    $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
                }
            }
            else{
                $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            $json['design_background_thumb'] = $thumb;

            $json['content'] = $this->load->controller('extension/'.$this->codename.'_module/'.$type.'/setting', $this->request->post);
            $json['success'] = 'success';
        }
        else {
            $json['error'] = 'error';
        }

        $this->response->setOutput(json_encode($json));
    }

    public function editLayout(){

        $json = array();

        if(isset($this->request->post['setting_layout'])){
            $setting_layout = $this->request->post['setting_layout'];
        }
        else{
            $json['error'] = 'error';
        }

        if(isset($this->request->post['items'])){
            $items = $this->request->post['items'];
        }
        else{
            $json['error'] = 'error';
        }

        if(isset($this->request->post['type'])){
            $type = $this->request->post['type'];
        }
        else{
            $json['error'] = 'error';
        }

        if(isset($this->request->post['parent'])){
            $parent = $this->request->post['parent'];
        }
        else{
            $json['error'] = 'error';
        }

        if(empty($json['error'])){

            $block_data = array(
                'setting' => $setting_layout,
                'items' => $items,
                'parent' => $parent
                );

            $json['items'] = $this->load->controller('extension/d_visual_designer_module/'.$type.'/layout',$block_data);
            $json['success'] = 'success';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getBlocks(){

        if(isset($this->request->post['level'])){
            $level = $this->request->post['level'];
        }

        $json = array();

        if(isset($level)){

            $this->load->model('tool/image');

            $results = $this->{'model_extension_'.$this->codename.'_designer'}->getBlocks();
            $json['success'] = 'success';
            $json['blocks'] = array();
            $json['categories'] = array();

            foreach ($results as $block) {

                $this->load->language('extension/'.$this->codename.'_module/'.$block);

                $setting = $this->{'model_extension_'.$this->codename.'_designer'}->getSettingBlock($block);

                if (is_file(DIR_IMAGE .'catalog/d_visual_designer/'.$block.'.svg')) {
                    $image = '../image/catalog/d_visual_designer/'.$block.'.svg';
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', 32, 32);
                }
                if($setting['display']){
                    if(($level >= $setting['level_min']) && ($level <= $setting['level_max']) || ($level == '0' && $setting['level_min'] == '2')){
                        if(!empty($setting['category'])&&!in_array(ucfirst($setting['category']), $json['categories'])){
                            $json['categories'][] = ucfirst($setting['category']);
                        }
                        $json['blocks'][] = array(
                            'sort_order' => $setting['sort_order'],
                            'title' => $this->language->get('text_title'),
                            'category' => ucfirst($setting['category']),
                            'type'	=> $block,
                            'description' => $this->language->get('text_description'),
                            'image' => $image
                            );
                    }
                }
            }

            usort($json['blocks'], 'ControllerExtensionDVisualDesignerDesigner::sort_block');

            $json['notify'] = $this->{'model_extension_'.$this->codename.'_designer'}->checkCompleteVersion();

        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function sort_block($a, $b){
        if ($a['sort_order'] == $b['sort_order']) {
            return 0;
        }
        return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
    }

    public function getChildBlock(){
        if(isset($this->request->post['type'])){
            $type = $this->request->post['type'];
        }
        if(isset($this->request->post['parent'])){
            $parent = $this->request->post['parent'];
        }
        if(isset($this->request->post['level'])){
            $level = $this->request->post['level'];
        }

        $json = array();

        if(isset($type)&&isset($parent)&&isset($level)){

            $setting_block = $this->{'model_extension_'.$this->codename.'_designer'}->getSettingBlock($type);

            $setting_child_block = $this->{'model_extension_'.$this->codename.'_designer'}->getSettingBlock($setting_block['child']);

            $key = $setting_block['child'].'_'.$this->{'model_extension_'.$this->codename.'_designer'}->getRandomString();

            $content = $this->{'model_extension_'.$this->codename.'_designer'}->getContent($setting_block['child'],$setting_child_block['setting'], $key,1);

            $setting = $this->{'model_extension_'.$this->codename.'_designer'}->getSetting(array(), $setting_block['child']);

            $setting = array($key => array('type'=> $setting_block['child'], 'child' => true, 'parent' => $parent, 'setting' => $setting));

            $content = str_replace('{{{inner-block}}}','',$content);
            $json['content'] = $content;
            $json['type'] = $setting_block['child'];
            $json['setting'] = json_encode($setting);
            $json['success'] = 'success';
        }
        else {
            $json['error'] = 'error';
        }
        $this->response->setOutput(json_encode($json));
    }

    public function getModule(){
        if(isset($this->request->post['type'])){
            $type = $this->request->post['type'];
        }

        if(isset($this->request->post['parent'])){
            $parent = $this->request->post['parent'];
        }

        if(isset($this->request->post['level'])){
            $level = $this->request->post['level'];
        }

        if(isset($this->request->post['blocks'])){
            $blocks = $this->request->post['blocks'];
        }
        else{
            $blocks = array();
        }

        if(isset($this->request->post['block_id'])){
            $block_id = $this->request->post['block_id'];
        }
        else{
            $block_id =$type.'_'. $this->{'model_extension_'.$this->codename.'_designer'}->getRandomString();
        }

        $json = array();

        if(isset($type)&isset($parent)&isset($level)){

            $setting = $this->{'model_extension_'.$this->codename.'_designer'}->getSetting(array(), $type);

            $block_info = array(
                'type' => $type,
                'parent' => $parent,
                'setting' => $setting,
                'block_id' => $block_id,
                'sort_order' => 0
                );

            $result = $this->{'model_extension_'.$this->codename.'_designer'}->getFullContent($block_info, $level);

            $json['content'] = $result['content'];
            $json['setting'] = json_encode($result['setting']);
            $json['target']  = $block_id;
            $json['success'] = 'success';
        }
        else {
            $json['error'] = 'error';
        }
        $this->response->setOutput(json_encode($json));
    }

    public function getContent(){

        if(isset($this->request->post['blocks'])){
            $blocks = $this->request->post['blocks'];
        }
        else{
            $blocks = array();
        }

        if(isset($this->request->post['main_block_id'])){
            $block_id = $this->request->post['main_block_id'];
        }
        else{
            $block_id = array();
        }

        $json = array();

        if(!empty($blocks)){

            $result = $this->{'model_extension_'.$this->codename.'_designer'}->getContentBySetting($blocks, $block_id);
            $json['content'] = $result;
            $json['success'] = 'success';
        }
        else {
            $json['error'] = 'error';
        }
        $this->response->setOutput(json_encode($json));
    }

    public function validate(){

        $this->error = array();

        $status = $this->config->get($this->codename . '_status');

        if(!$status) {
            $this->error['status'] = $this->language->get('error_status');
        }

        if(!isset($this->request->post['description'])){
            $this->error['description'] = $this->language->get('error_description');
        }

        if(empty($this->request->post['url'])) {
            $this->error['url'] = $this->language->get('error_url');
        }

        if(!empty($setting['d_visual_designer_setting']['limit_access_user'])){
            if(!empty($setting['d_visual_designer_setting']['access_user']) && !in_array($this->user->getId(), $setting['d_visual_designer_setting']['access_user'])){
                $this->error['warning'] = $this->language->get('error_permission');
            }
            elseif($setting['d_visual_designer_setting']['access_user']){
                $this->error['warning'] = $this->language->get('error_permission');
            }
        }
        if(!empty($setting['d_visual_designer_setting']['limit_access_user_group'])){
            if(!empty($setting['d_visual_designer_setting']['access_user_group']) && !in_array($this->user->getGroupId(), $setting['d_visual_designer_setting']['access_user_group'])){
                $this->error['warning'] = $this->language->get('error_permission');
            }
            elseif(empty($setting['d_visual_designer_setting']['access_user_group'])){
                $this->error['warning'] = $this->language->get('error_permission');
            }
        }

        else {
            $url_info = parse_url(str_replace('&amp;', '&', $this->request->post['url']));
            $url_params = array();

            parse_str($url_info['query'], $url_params);

            $route_info = $this->{'model_extension_'.$this->codename.'_designer'}->getRouteByBackendRoute($url_params['route']);

            if(empty($route_info)) {
                $this->error['config'] = $this->language->get('error_config');
            }
        }
        return !$this->error;
    }
}
