<?php
class ControllerModuleDVisualDesigner extends Controller {

    private $codename = 'd_visual_designer';

    private $route = 'module/d_visual_designer';
    
    private $theme = 'default';

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language($this->route);
        $this->load->model($this->route);
        $this->theme = $this->config->get('config_template');
        if(empty($this->theme)&& VERSION=='2.2.0.0'){
            $this->theme = $this->config->get('theme_default_directory');
        }
        
        if(VERSION >= '2.3.0.0'){
            $this->route = 'extension/'.$this->route;
        }
    }

    public function index($setting) {

        $this->load->model($this->route);

        $status = $this->config->get($this->codename.'_status');
        if(VERSION >= '2.2.0.0'){
            $this->user = new Cart\User($this->registry);
        }
        else{
            $this->user = new User($this->registry);
        }
        
        if(!empty($setting['token'])){
            $route_info = $this->model_module_d_visual_designer->getRoute($setting['token']);
        }
        else{
            $route_info = array();
        }

        $edit_status = true;

        if(!$status){
            $edit_status = false;
        }

        if(!$this->user->isLogged()){
            $edit_status = false;
        }
        
        if(empty($route_info)){
            $edit_status = false;
        }
        elseif (!$route_info['status']) {
            $edit_status = false;
        }
        
        if(!empty($this->request->get['route']) && $this->request->get['route'] != $route_info['frontend_route']){
            $edit_status = false;
        }
                
        //sharrre
        $this->document->addScript('catalog/view/javascript/d_visual_designer/library/sharrre/jquery.sharrre.min.js');
        $this->document->addStyle('catalog/view/javascript/d_visual_designer/library/sharrre/style.css');
        //magnific-popup
        $this->document->addScript('catalog/view/javascript/d_visual_designer/library/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('catalog/view/javascript/d_visual_designer/library/magnific/magnific-popup.css');
        //chart
        $this->document->addScript('catalog/view/javascript/d_visual_designer/library/chart/Chart.min.js');
        $this->document->addScript('catalog/view/javascript/d_visual_designer/library/pie-chart.js');
        $this->document->addScript('https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.0/circle-progress.js');
        //Carousel
        $this->document->addScript('catalog/view/javascript/d_visual_designer/library/owl-carousel/owl.carousel.min.js');
        $this->document->addStyle('catalog/view/javascript/d_visual_designer/library/owl-carousel/owl.carousel.css');
        
        $this->document->addStyle('catalog/view/javascript/d_visual_designer/library/owl-carousel/owl.transitions.css');
                
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/d_visual_designer/animate.css')) {
            $this->document->addStyle('catalog/view/theme/' . $this->theme . '/stylesheet/d_visual_designer/animate.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_visual_designer/animate.css');
        }
        
        if($edit_status&&isset($this->request->get['edit'])){

            if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_visual_designer/d_visual_designer.css')) {
                $this->document->addStyle('catalog/view/theme/' . $this->theme . '/stylesheet/d_visual_designer/d_visual_designer.css');
            } else {
                $this->document->addStyle('catalog/view/theme/default/stylesheet/d_visual_designer/d_visual_designer.css');
            }

            if (file_exists(DIR_TEMPLATE . $this->theme . '/javascript/d_visual_designer.js')) {
                $this->document->addScript('catalog/view/theme/' . $this->theme . '/javascript/d_visual_designer.js');
            } else {
                $this->document->addScript('catalog/view/theme/default/javascript/d_visual_designer.js');
            }

            //bootstrap-switch
            $this->document->addScript('catalog/view/javascript/d_visual_designer/library/bootstrap-switch/bootstrap-switch.js');
            $this->document->addStyle('catalog/view/javascript/d_visual_designer/library/bootstrap-switch/bootstrap-switch.min.css');

            //bootstrap-colorpicker
            $this->document->addScript('catalog/view/javascript/d_visual_designer/library/bootstrap-colorpicker/bootstrap-colorpicker.min.js');
            $this->document->addStyle('catalog/view/javascript/d_visual_designer/library/bootstrap-colorpicker/bootstrap-colorpicker.min.css');

            //fontawesome
            $this->document->addScript('catalog/view/javascript/d_visual_designer/library/fontawesome-iconpicker/fontawesome-iconpicker.min.js');
            $this->document->addStyle('catalog/view/javascript/d_visual_designer/library/fontawesome-iconpicker/fontawesome-iconpicker.min.css');

            //summernote
            $this->document->addScript('catalog/view/javascript/d_visual_designer/library/summernote/summernote.min.js');
            $this->document->addStyle('catalog/view/javascript/d_visual_designer/library/summernote/summernote.css');

            $this->document->addScript('catalog/view/javascript/d_visual_designer/library/jquery-ui.js');
            $this->document->addScript('catalog/view/javascript/d_visual_designer/library/handlebars-v4.0.5.js');
            $this->document->addScript('catalog/view/javascript/d_visual_designer/library/jquery.serializejson.js');

            //button
            $data['button_add'] = $this->language->get('button_add');
            $data['button_close'] = $this->language->get('button_close');
            $data['button_save'] = $this->language->get('button_save');
            $data['button_saved'] = $this->language->get('button_saved');
            //text
            $data['text_add_block'] = $this->language->get('text_add_block');
            $data['text_edit_block'] = $this->language->get('text_edit_block');
            $data['text_add_template'] = $this->language->get('text_add_template');
            $data['text_classic_mode'] = $this->language->get('text_classic_mode');
            $data['text_backend_editor'] = $this->language->get('text_backend_editor');
            $data['text_frontend_editor'] = $this->language->get('text_frontend_editor');
            $data['text_welcome_header'] = $this->language->get('text_welcome_header');
            $data['text_add_block'] = $this->language->get('text_add_block');
            $data['text_add_text_block'] = $this->language->get('text_add_text_block');
            $data['text_add_template'] = $this->language->get('text_add_template');
            $data['text_search'] = $this->language->get('text_search');
            $data['text_layout'] = $this->language->get('text_layout');
            $data['entry_size'] = $this->language->get('entry_size');
            $data['text_set_custom'] = $this->language->get('text_set_custom');

            $data['text_left'] = $this->language->get('text_left');
            $data['text_right'] = $this->language->get('text_right');
            $data['text_top'] = $this->language->get('text_top');
            $data['text_bottom'] = $this->language->get('text_bottom');

            $data['entry_border_color'] = $this->language->get('entry_border_color');
            $data['entry_border_style'] = $this->language->get('entry_border_style');
            $data['entry_border_radius'] = $this->language->get('entry_border_radius');
            $data['entry_background'] = $this->language->get('entry_background');
            $data['entry_image'] = $this->language->get('entry_image');
            $data['entry_additional_css_class'] = $this->language->get('entry_additional_css_class');
            $data['entry_additional_css_before'] = $this->language->get('entry_additional_css_before');
            $data['entry_additional_css_content'] = $this->language->get('entry_additional_css_content');
            $data['entry_additional_css_after'] = $this->language->get('entry_additional_css_after');
            $data['entry_margin'] = $this->language->get('entry_margin');
            $data['entry_padding'] = $this->language->get('entry_padding');
            $data['entry_border'] = $this->language->get('entry_border');
            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_image_style'] = $this->language->get('entry_image_style');

            $data['tab_general'] = $this->language->get('tab_general');
            $data['tab_design'] = $this->language->get('tab_design');
            $data['tab_css'] = $this->language->get('tab_css');
            $data['tab_save_block'] = $this->language->get('tab_save_block');
            $data['tab_templates'] = $this->language->get('tab_templates');
            $data['tab_all_blocks'] = $this->language->get('tab_all_blocks');
            $data['tab_content_blocks'] = $this->language->get('tab_content_blocks');
            $data['tab_social_blocks'] = $this->language->get('tab_social_blocks');
            $data['tab_structure_blocks'] = $this->language->get('tab_structure_blocks');
            //error
            $data['error_size'] = $this->language->get('error_size');
            $data['designer_id'] = $this->{'model_module_'.$this->codename}->getRandomString();

            $data['description'] = $setting['description'];

            $data['frontend_route'] = '';

            $data['edit_url'] = $route_info['edit_url'];
            $data['field_name'] = $setting['field_name'];

            $data['id'] = $setting['id'];

            $this->load->model('localisation/language');

            $data['languages'] = $this->model_localisation_language->getLanguages();
            foreach ($data['languages'] as $key =>  $language){
                if(VERSION >= '2.2.0.0'){
                    $data['languages'][$key]['flag'] = 'catalog/language/'.$language['code'].'/'.$language['code'].'.png';
                }else{
                    $data['languages'][$key]['flag'] = 'catalog/view/image/flags/'.$language['image'];
                }
            }

            $setting_module = $this->config->get($this->codename.'_setting');
            if(!empty($setting_module)){
                $data['save_change'] = $setting_module['save_change'];
            }
            else{
                $data['save_change'] = 0;
            }
            
            $data['content'] = $setting['content'];

            $data['settings'] = $setting['setting'];

            $data['base'] = $this->request->server['HTTPS'] ? HTTPS_SERVER.'catalog/view/theme/default/' : HTTP_SERVER.'catalog/view/theme/default/';

            $data['filemanager_url'] = $this->config->get('config_url').'index.php?route=common/filemanager&token='.$this->session->data['token'].'';

            $this->load->model('tool/image');

            $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

            $data['styles'] = array(
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
                'repeat' => $this->language->get('text_repeat')
            );

            if(VERSION>='2.2.0.0') {
                return $this->load->view('d_visual_designer/designer', $data);
            }
            else {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_visual_designer/designer.tpl')) {
                    return $this->load->view($this->config->get('config_template') . '/template/d_visual_designer/designer.tpl', $data);
                } else {
                    return $this->load->view('default/template/d_visual_designer/designer.tpl', $data);
                }
            }
        }
        elseif($edit_status&&!empty($setting['id'])){

            if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_visual_designer/frontend.css')) {
                $this->document->addStyle('catalog/view/theme/' . $this->theme . '/stylesheet/d_visual_designer/frontend.css');
            } else {
                $this->document->addStyle('catalog/view/theme/default/stylesheet/d_visual_designer/frontend.css');
            }
                    
            if($this->request->server['HTTPS']){
                $frontend_url = htmlentities(urlencode(HTTPS_SERVER.'index.php?route='.
                $route_info['frontend_route'].'&'.$route_info['frontend_param'].'='.$setting['id']));
            }
            else{
                $frontend_url = htmlentities(urlencode(HTTP_SERVER.'index.php?route='.
                $route_info['frontend_route'].'&'.$route_info['frontend_param'].'='.$setting['id']));
            }
            $edit_url = $this->config->get('config_url').'admin/index.php?route=d_visual_designer/designer/frontend&token='.$this->session->data['token'].'&url='.$frontend_url.'&route_id='.$route_info['route_id'].'&id='.$setting['id'];
           
            $setting['content'] = '<div class="btn-group-xs btn-edit" ><a class="btn btn-default " href="'.$edit_url.'" target="_blank"><i class="fa fa-pencil"></i> '.$this->language->get('text_edit').'</a><br/><br/></div>'.$setting['content'];
            return $setting['content'];
        }
        else{
            if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_visual_designer/frontend.css')) {
                $this->document->addStyle('catalog/view/theme/' . $this->theme . '/stylesheet/d_visual_designer/frontend.css');
            } else {
                $this->document->addStyle('catalog/view/theme/default/stylesheet/d_visual_designer/frontend.css');
            }
            return $setting['content'];
        }
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


            $json['content'] = $this->load->controller($this->codename.'_module/'.$type.'/setting', $this->request->post);
            $json['success'] = 'success';
        }
        else {
            $json['error'] = 'error';
        }
        $this->response->setOutput(json_encode($json));
    }

    public function getBlocks(){

        if(isset($this->request->post['level'])){
            $level = $this->request->post['level'];
        }

        $json = array();

        if(isset($level)){

            $this->load->model('tool/image');

            $results = $this->{'model_module_'.$this->codename}->getBlocks();
            $json['success'] = 'success';

            $json['blocks'] = array();
            $json['socials'] = array();
            $json['contents'] = array();
            $json['structures'] = array();

            foreach ($results as $block) {

                $this->load->language($this->codename.'_module/'.$block);

                $setting = $this->{'model_module_'.$this->codename}->getSettingBlock($block);

                if (is_file(DIR_IMAGE .'data/d_visual_designer/'.$block.'.svg')) {
                    $image = $this->config->get('config_url').'image/data/d_visual_designer/'.$block.'.svg';
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                }
                if($setting['display']){
                    if(($level >= $setting['level_min']) && ($level <= $setting['level_max']) || ($level == '0' && $setting['level_min'] == '2')){
                        $json['blocks'][] = array(
                            'sort_order' => $setting['sort_order'],
                            'title' => $this->language->get('text_title'),
                            'type'	=> $block,
                            'description' => $this->language->get('text_description'),
                            'image' => $image
                        );
                    }
                }

                usort($json['blocks'], 'ControllerModuleDVisualDesigner::sort_block');

            }
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

            $json['items'] = $this->load->controller('d_visual_designer_module/'.$type.'/layout',$block_data);
            $json['success'] = 'success';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
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

            $setting_block = $this->{'model_module_'.$this->codename}->getSettingBlock($type);

            $setting_child_block = $this->{'model_module_'.$this->codename}->getSettingBlock($setting_block['child']);

            $key = $setting_block['child'].'_'.$this->{'model_module_'.$this->codename}->getRandomString();

            $content = $this->{'model_module_'.$this->codename}->getContent($setting_block['child'], $setting_child_block, $key, $level, 1, 1);

            $setting = array($key => array('type'=> $setting_block['child'], 'parent' => $parent, 'setting' => $setting_child_block['setting'], 'child' => true));

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

        if(isset($this->request->post['setting'])){
            $setting = $this->request->post['setting'];
        }
        else{
            $setting = array();
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
            $block_id =$type.'_'. $this->{'model_module_'.$this->codename}->getRandomString();
        }

        $json = array();

        if(isset($type)&isset($parent)&isset($level)){
            if(empty($setting)){
                $setting_block = $this->{'model_module_'.$this->codename}->getSettingBlock($type);
                $setting = $setting_block['setting'];
            }
            $block_info = array(
                'type' => $type,
                'parent' => $parent,
                'setting' => $setting,
                'block_id' => $block_id
            );
            $result = $this->{'model_module_'.$this->codename}->getFullContent($block_info, $level);
            $json['content'] = $result['content'];
            $json['target'] = $block_id;
            $json['setting'] = json_encode($result['setting']);
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
            $result = $this->{'model_module_'.$this->codename}->getContentBySetting($blocks, $block_id);
            $json['content'] = $result;
            $json['success'] = 'success';
        }
        else {
            $json['error'] = 'error';
        }
        $this->response->setOutput(json_encode($json));
    }

    public function getTemplates(){
        $json = array();

        $templates = $this->model_module_d_visual_designer->getTemplates();

        $json['templates'] = array();

        foreach ($templates as $template) {
            $json['templates'][] = array(
                'template_id' => $template['template_id'],
                'name' => html_entity_decode($template['name'], ENT_QUOTES, "UTF-8")
            );
        }

        $json['success'] = 'success';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTemplate(){
        $json = array();

        if(!empty($this->request->post['template_id'])){
            $template_id = $this->request->post['template_id'];
        }
        if(isset($template_id)){

            $template_info = $this->model_module_d_visual_designer->getTemplate($template_id);

            if(!empty($template_info)){
                $this->load->model('module/d_visual_designer');

                $result = $this->model_module_d_visual_designer->parseDescriptionWithoutDesigner($template_info['content']);
                $json['content'] = $result['content'];
                $json['setting'] = $result['setting'];
                $json['text'] = $template_info['content'];
                $json['success'] = 'success';
            }
            else{
                $json['errorr'] = 'error';
            }
        }
        else{
            $json['errorr'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveProduct(){
        $json = array();

        if(!empty($this->request->post['product_description'])){
            $product_description = $this->request->post['product_description'];
        }

        if(!empty($this->request->get['id'])){
            $product_id = $this->request->get['id'];
        }

        if(isset($product_description)&&isset($product_id)){

            $this->{'model_module_'.$this->codename}->editProduct($product_id, array('product_description' => $product_description));

            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function saveCategory(){
        $json = array();

        if(!empty($this->request->post['category_description'])){
            $category_description = $this->request->post['category_description'];
        }

        if(!empty($this->request->get['id'])){
            $category_id = $this->request->get['id'];
        }

        if(isset($category_description)&&isset($category_id)){

            $this->{'model_module_'.$this->codename}->editCaregory($category_id, array('category_description' => $category_description));

            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function saveInformation(){
        $json = array();

        if(!empty($this->request->post['information_description'])){
            $information_description = $this->request->post['information_description'];
        }

        if(!empty($this->request->get['id'])){
            $information_id = $this->request->get['id'];
        }

        if(isset($information_description)&&isset($information_id)){

            $this->{'model_module_'.$this->codename}->editInformation($information_id, array('information_description' => $information_description));

            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function saveTemplate(){
        $this->load->model('setting/setting');

        $json = array();

        if(isset($this->request->post['content'])){
            $content = $this->request->post['content'];
        }

        if(isset($this->request->post['template_description'])){
            $template_description = $this->request->post['template_description'];
        }

        if(isset($template_description) && isset($content)){
            $this->{'model_module_'.$this->codename}->addTemplate($template_description+array('content' => $content,'sort_order'=>0));
            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
