<?php
class ModelExtensionModuleDVisualDesigner extends Model {
    private $error = array();

    private $sort = 'name';

    private $order = 'ASC';

    private $styles = array();

    /**
     * Converts shortcodes to settings
     */
    public function parseContent($text){
        $blocks = $this->getBlocks();

        $d_shortcode_reader_writer = new d_shortcode_reader_writer($blocks);

        $setting = $d_shortcode_reader_writer->readShortcode($text);

        if(!empty($text) && empty($setting)) {
            $text = "[vd_row][vd_column][vd_text text='".$d_shortcode_reader_writer->escape($text)."'][/vd_column][/vd_row]";
            $setting = $d_shortcode_reader_writer->readShortcode($text);
        }
        $that = $this;
        array_walk($setting, function(&$block, $key) use($that){
            $block['setting'] = $that->getSetting($block['setting'], $block['type']);
        });

        return $setting;
    }

    /**
     * Converts settings to shortcodes
     */
    public function parseSetting($setting) {

        if(empty($setting)){
            return '';
        }

        $blocks = $this->getBlocks();

        $that = $this;
        array_walk($setting, function(&$block, $key) use($that){
            $block['setting'] = $block['setting']['global'];
        });

        $d_shortcode_reader_writer = new d_shortcode_reader_writer($blocks);

        $content = $d_shortcode_reader_writer->writeShortcode($setting);

        return $content;
    }

    /**
     * Returns the shortcodes for the specified config
     */
    public function getContent($route, $id, $field_name) {
        $query = $this->db->query("SELECT `content` FROM `".DB_PREFIX."visual_designer_content` WHERE `route` ='".$route."' AND `id` = '".(int)$id."' AND `field` = '".$field_name."'");

        return $query->row;
    }
    /**
     * Keeps shortcodes in the database
     */
    public function saveContent($content, $route, $id, $field_name) {
        $query = $this->db->query("SELECT * FROM `".DB_PREFIX."visual_designer_content` WHERE `route` ='".$route."' AND `id` = '".(int)$id."' AND `field` = '".$field_name."'");

        if($query->num_rows) {
            $this->db->query("UPDATE `".DB_PREFIX."visual_designer_content` SET `content`= '".$this->db->escape($content)."' WHERE `route` ='".$route."' AND `id` = '".(int)$id."' AND `field` = '".$field_name."'");
        } else {
            $this->db->query("INSERT INTO `".DB_PREFIX."visual_designer_content` SET `content`= '".$this->db->escape($content)."', `route` ='".$route."', `id` = '".(int)$id."', `field` = '".$field_name."'");
        }
    }

    /**
     * Converts shortcodes to text
     * @param $setting
     * @return string
     */
    public function getText($setting){
        $content = $this->preRenderLevel('', $setting, true);
        $styles = '<style type="text/css">';

        if(!empty($this->styles)) {
            foreach ($this->styles as $style) {
                $handle = fopen(DIR_APPLICATION.'../'.$style, "r");
                $contents = fread($handle, filesize(DIR_APPLICATION.'../'.$style));
                $styles .= $contents;
            }
        }
        $styles .= '</style>';

        $content = $styles.$content;

        return $content;
    }

    /**
     * Full Pre-render
     * @param $setting
     * @return string
     * @throws Exception
     */
    public function preRender($setting) {
        $content = $this->preRenderLevel('', $setting, false);

        return $content;
    }

    /**
     * Pre-render content from settings by Parent ID
     * @param $parent
     * @param $setting
     * @param bool $html
     * @return string
     * @throws Exception
     */
    public function preRenderLevel($parent, $setting, $html = false) {

        $blocks = $this->getBlocksByParent($parent, $setting);
        $this->load->model('extension/d_opencart_patch/load');
        $result = '';
        foreach ($blocks as $block_info) {

            $block_config = $this->getSettingBlock($block_info['type']);

            //Full Pre-render
            $fullPreRender = !empty($block_config['pre_render']) && !$html;
            //Save to HTML
            $saveToHtml = !empty($block_config['pre_render']) && !empty($block_config['save_html']) && $html;

            if($fullPreRender || $saveToHtml) {
                $renderData = array(
                    'setting' => $block_info['setting'],
                    'local' => $this->load->controller('extension/d_visual_designer_module/'.$block_info['type'].'/local', false),
                    'options' => $this->load->controller('extension/d_visual_designer_module/'.$block_info['type'].'/options', false),
                    'children' => $this->preRenderLevel($block_info['id'], $setting)
                );

                $result .= $this->model_extension_d_opencart_patch_load->view('extension/d_visual_designer_module/'.$block_info['type'], $renderData);

                $output = $this->load->controller('extension/d_visual_designer_module/'.$block_info['type'].'/styles', false);

                if($output) {
                    $this->styles =array_merge($this->styles, $output);
                }

            } else {
                $result .= '';
            }
        }
        return $result;
    }

    /**
     * Get list blocks by Parent ID
     * @param $parent
     * @param $setting
     * @return array
     */
    public function getBlocksByParent($parent, $setting) {
        return array_filter($setting, function($value) use ($parent) {
            return $value['parent'] == $parent;
        });
    }

    /**
     * Get list of all blocks
     * @return array
     */
    public function getBlocks(){
        $dir = DIR_APPLICATION.'controller/extension/d_visual_designer_module';
        $files = scandir($dir);
        $result = array();
        foreach($files as $file){
            if(strlen($file) > 1 && strpos( $file, '.php')){
                $result[] = substr($file, 0, -4);
            }
        }
        return $result;
    }
    
    public function validateEdit($config_name, $edit = true){

        $this->error = array();

        $this->load->model('setting/setting');

        $setting = $this->model_setting_setting->getSetting('d_visual_designer');

        if(VERSION >= '2.2.0.0'){
            $this->user = new Cart\User($this->registry);
        }
        else{
            $this->user = new User($this->registry);
        }

        if (!$this->user->isLogged()) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        else{
            if(!empty($setting['d_visual_designer_setting']['limit_access_user'])){
                if(!empty($setting['d_visual_designer_setting']['access_user']) && !in_array($this->user->getId(), $setting['d_visual_designer_setting']['access_user'])){
                    $this->error['warning'] = $this->language->get('error_permission');
                }
                elseif(empty($setting['d_visual_designer_setting']['access_user'])){
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
        }

        $routes = array(
            'extension/d_visual_designer/designer/getTemplate',
            'extension/d_visual_designer/designer/getModule',
            'extension/d_visual_designer/designer/getContent',
            'extension/d_visual_designer/designer/getChildBlock'
            );

        if(isset($this->request->get['route'])){
            $route = $this->request->get['route'];
        }
        else{
            $route = '';
        }
        
        if(!in_array($route, $routes)){
            if($edit&&!isset($this->request->get['edit'])){
                $this->error['warning'] = $this->language->get('error_permission');
            }
            $route_info = $this->getRoute($config_name);
            if(empty($route_info)){
                $this->error['route'] = $this->language->get('error_route');
            }

            if(!empty($route_info)&&$route_info['frontend_route'] != $route){
                $this->error['route'] = $this->language->get('error_frontend_route');
            }
        }

        if(!$setting['d_visual_designer_status']){
            $this->error['status'] = $this->language->get('error_status');
        }
        return !$this->error;
    }

    public function getSettingBlock($type){

        $results = array();

        $file = DIR_SYSTEM.'/config/d_visual_designer/'.$type.'.php';
        if (file_exists($file)) {
            $_ = array();

            require($file);

            $results = array_merge($results, $_);
        }

        return $results;
    }

    public function getRouteByBackendRoute($backend_route){
        $routes = $this->getRoutes();
        foreach ($routes as $config => $route) {
            if($route['backend_route'] == $backend_route){
                $route['config_name'] = $config;
                return $route;
            }
        }
        return array();
    }

    public function getRoutes(){
        $dir = DIR_CONFIG.'d_visual_designer_route/*.php';

        $files = glob($dir);

        $route_data = array();

        foreach($files as $file){

            $name = basename($file, '.php');
            $route_info = $this->getRoute($name);
            $route_data[$name] = $route_info;

        }

        return $route_data;
    }

    public function getRoute($name){

        $results = array();

        $file = DIR_CONFIG.'d_visual_designer_route/'.$name.'.php';

        if (file_exists($file)) {
            $_ = array();

            require($file);

            $results = array_merge($results, $_);
        }

        return $results;
    }

    public function getConfigTemplates(){

        $dir = DIR_CONFIG.'d_visual_designer_template/';
        if(is_dir($dir)){
            $files = scandir($dir);
        }
        else{
            $files = array();
        }
        $template_data = array();

        foreach($files as $file){
            if(strlen($file) > 1 && strpos( $file, '.php')){
                $_ = array();

                $results = array();

                require($dir.$file);

                $results = array_merge($results, $_);

                $templates = $results['d_visual_designer_templates'];
                foreach ($templates as $template) {
                    $template_data[] = array(
                       'template_id' => $template['template_id'],
                       'content' => $template['content'],
                       'config' => substr($file, 0, -4),
                       'image' => $template['image'],
                       'category' => $template['category'],
                       'sort_order' => $template['sort_order'],
                       'name' => $template['name']
                       );
                }    
            }
        }
        return $template_data;
    }

    public function getTemplates(){
        $sql = "SELECT * FROM ".DB_PREFIX."visual_designer_template  t ";

        $query = $this->db->query($sql);

        $template_data = array();

        if($query->num_rows){
            foreach ($query->rows as $row) {
                $template_data[] = array(
                    'template_id' => $row['template_id'],
                    'content' => $row['content'],
                    'sort_order' => $row['sort_order'],
                    'name' => $row['name'],
                    'config' => '',
                    'image' => $row['image'],
                    'category' => $row['category']
                    );
            }
        }

        $templates_config = $this->getConfigTemplates();

        $template_data = array_merge($template_data, $templates_config);

        $sort_data = array(
          'name',
          'sort_order'
          );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $this->sort = $data['sort'];
        }


        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $this->order = "DESC";
        } else {
            $this->order = "ASC";
        }

        uasort($template_data, 'ModelExtensionModuleDVisualDesigner::sort');

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $template_data = array_slice($template_data, $data['start'], $data['limit']);
        }

        return $template_data;
    }

    public function sort($a, $b){
        if($a[$this->sort] < $b[$this->sort]){
            return $this->order=='ASC'?-1:1;
        }
        else{
            return $this->order=='ASC'?1:-1;
        }

        if($a[$this->sort] == $b[$this->sort]){
            return 0;
        }
    }

    public function getTemplate($template_id){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."visual_designer_template t WHERE t.template_id='".$template_id."'");

        return $query->row;
    }
    public function getConfigTemplate($template_id, $config){
        $_ = array();

        $results = array();

        require(DIR_CONFIG.'d_visual_designer_template/'.$config.'.php');

        $results = array_merge($results, $_);

        $templates = $results['d_visual_designer_templates'];

        foreach ($templates as $template) {
            if($template['template_id'] == $template_id){
                return $template;
            }
        }
        return array();
    }

    public function addTemplate($data){
        $this->db->query("INSERT INTO ".DB_PREFIX."visual_designer_template SET 
            content='".$this->db->escape($data['content'])."', 
            image='".$data['image']."', 
            category='".$data['category']."', 
            name='".$data['name']."', 
            sort_order='".$data['sort_order']."
            '");

        $template_id = $this->db->getLastId();

        return $template_id;
    }

    public function editProduct($product_id, $data){
        if(!empty($data['product_description'])){
            foreach ($data['product_description'] as $language_id => $value) {
                $implode = array();

                if(isset($value['name'])){
                    $implode[] = "name='".$this->db->escape($value['name'])."'";
                }

                if(isset($value['description'])){
                    $implode[] = "description='".$this->db->escape($value['description'])."'";
                }

                if(count($implode) > 0){
                    $this->db->query("UPDATE " . DB_PREFIX . "product_description SET ".implode(',', $implode)."
                        WHERE product_id = '".$product_id."' AND language_id='".$language_id."'");
                }
            }
        }
    }
    public function editCaregory($category_id, $data){
        if(!empty($data['category_description'])){
            foreach ($data['category_description'] as $language_id => $value) {
                $implode = array();

                if(isset($value['name'])){
                    $implode[] = "name='".$this->db->escape($value['name'])."'";
                }

                if(isset($value['description'])){
                    $implode[] = "description='".$this->db->escape($value['description'])."'";
                }

                if(count($implode) > 0){
                    $this->db->query("UPDATE " . DB_PREFIX . "category_description SET ".implode(',', $implode)."
                        WHERE category_id = '".$category_id."' AND language_id='".$language_id."'");
                }
            }
        }
    }

    public function editInformation($information_id, $data){
        if(!empty($data['information_description'])){
            foreach ($data['information_description'] as $language_id => $value) {
                $implode = array();

                if(isset($value['name'])){
                    $implode[] = "name='".$this->db->escape($value['name'])."'";
                }

                if(isset($value['description'])){
                    $implode[] = "description='".$this->db->escape($value['description'])."'";
                }

                if(count($implode) > 0){
                    $this->db->query("UPDATE " . DB_PREFIX . "information_description SET ".implode(',', $implode)."
                        WHERE information_id = '".$information_id."' AND language_id='".$language_id."'");
                }
            }
        }
    }

    public function prepareUserSetting($setting) {
        $data = array();
        $this->load->model('tool/image');
        if(!empty($setting['design_background_image'])){
            $image = $setting['design_background_image'];

            if(file_exists(DIR_IMAGE.$image)){
                list($width, $height) = getimagesize(DIR_IMAGE . $image);
                $data['design_background_image'] = $this->model_tool_image->resize($image, $width, $height);
            }
        }
        return $data;
    }

    public function prepareEditSetting($setting) {
        $data = array();
        $this->load->model('tool/image');
        if(!empty($setting['design_background_image'])){
            $image = $setting['design_background_image'];

            if(file_exists(DIR_IMAGE.$image)){
                $data['design_background_thumb'] = $this->model_tool_image->resize($image, 100, 100);
            } else {
                $data['design_background_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            }
        } else {
            $data['design_background_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }
        return $data;
    }

    public function getSetting($setting, $type){
        $this->config->load('d_visual_designer');

        $setting_main = $this->config->get('d_visual_designer_default_block_setting');

        $setting_default = $this->getSettingBlock($type);

        $result = $setting_main;

        if(!empty($setting_default['setting'])){
            foreach ($setting_default['setting'] as $key => $value) {
                $result[$key] = $value;
            }
        }

        if(!empty($setting)){
            foreach ($setting as $key => $value) {
                if(!is_array($value) && !empty($setting_default['types'][$key])){
                    if($setting_default['types'][$key] == 'boolean'){
                        $result[$key] = $value? true : false;
                    } else if($setting_default['types'][$key] == 'number'){
                        $result[$key] = (int)$value;
                    } else if($setting_default['types'][$key] == 'string'){
                        $result[$key] = (string)$value;
                    } else {
                        $result[$key] = $value;
                    }
                } else {
                    $result[$key] = $value;
                }
            }
        }

        $userSetting = $this->load->controller('extension/d_visual_designer_module/'.$type, $result);

        if(!$userSetting){
            $userSetting = array();
        }
        
        $globalUserSetting = $this->prepareUserSetting($result);

        $userSetting = array_merge($globalUserSetting, $userSetting);
        
        $editSetting = $this->load->controller('extension/d_visual_designer_module/'.$type.'/setting', $result);

        if(!$editSetting){
            $editSetting = array();
        }

        $globalEditSetting = $this->prepareEditSetting($result);

        $editSetting = array_merge($globalEditSetting, $editSetting);

        return array('global'=>$result, 'user' => $userSetting, 'edit' => $editSetting);
    }

    public function checkCompleteVersion(){
        $return = false;
        if(!file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_visual_designer_module.json')){
            $return = true; 
        }
        if(!file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_visual_designer_landing.json')){
            $return = true; 
        }

        return $return;
    }

    public function getIconSets(){
        $files = glob(DIR_APPLICATION."view/javascript/d_visual_designer/iconset/*.js", GLOB_BRACE);

        $result = array();

        foreach ($files as $file) {
            $result[] = basename($file, '.js');
        }

        return $result;
    }

    public function getRiotTags($compress = true){
        $result = array();

        if (in_array($this->config->get('config_theme'), array('theme_default', 'default'))) {
            $this->theme = $this->config->get('theme_default_directory');
        } else {
            $this->theme = $this->config->get('config_theme');
        }

        if(!$this->theme){
            $this->theme = $this->config->get('config_template');
        }

        if($compress) {
            if (!is_dir(DIR_TEMPLATE."default/template/extension/d_visual_designer/compress")) {
                $this->compressRiotTag();
            }

            $files = glob(DIR_TEMPLATE."default/template/extension/d_visual_designer/compress/*.tag", GLOB_BRACE);

            foreach ($files as $file) {
                $result[] = 'catalog/view/theme/default/template/extension/d_visual_designer/compress/'.basename($file);
            }
        }
        
        if (!$compress || empty($result)) {
            $files = glob(DIR_TEMPLATE."default/template/extension/d_visual_designer/{components,elements,popups,layouts,content_blocks,settings_block,layout_blocks}/*.tag", GLOB_BRACE);
            foreach ($files as $file) {
                if (file_exists(DIR_TEMPLATE . $this->theme . '/template/extension/d_visual_designer/'.basename(dirname($file)).'/'.basename($file))) {
                    $result[] = 'catalog/view/theme/' . $this->theme . '/template/extension/d_visual_designer/'.basename(dirname($file)).'/'.basename($file);
                } else {
                    $result[] = 'catalog/view/theme/default/template/extension/d_visual_designer/'.basename(dirname($file)).'/'.basename($file);
                }
            }
        }

        return $result;
    }

    public function compressRiotTag()
    {
        $this->compressRiotTagByFolder(DIR_TEMPLATE."default/template/extension/d_visual_designer/");
    }

    protected function compressRiotTagByFolder($folder) {
        if(is_dir($folder."compress")){
            array_map('unlink', glob($folder."compress/*"));
        } else {
            mkdir($folder."compress");
        }

        $files = glob($folder . 'components/*.tag', GLOB_BRACE);

        foreach($files as $file){
            file_put_contents($folder."compress/component.tag", file_get_contents($file).PHP_EOL, FILE_APPEND);
        }
        $files = glob($folder . 'elements/*.tag', GLOB_BRACE);

        foreach($files as $file){
            if (file_exists(DIR_TEMPLATE . $this->theme . '/template/extension/d_visual_designer/elements/'.basename($file))) {
                $file = DIR_TEMPLATE . $this->theme . '/template/extension/d_visual_designer/elements/'.basename($file);
            }
            file_put_contents($folder."compress/elements.tag", file_get_contents($file).PHP_EOL, FILE_APPEND);
        }

        $files = glob($folder . 'popups/*.tag', GLOB_BRACE);
        foreach($files as $file){
            file_put_contents($folder."compress/popups.tag", file_get_contents($file).PHP_EOL, FILE_APPEND);
        }

        $files = glob($folder . 'layouts/*.tag', GLOB_BRACE);
        foreach($files as $file){
            file_put_contents($folder."compress/layouts.tag", file_get_contents($file).PHP_EOL, FILE_APPEND);
        }
        $files = glob($folder . 'content_blocks/*.tag', GLOB_BRACE);
        foreach($files as $file){
            if (file_exists(DIR_TEMPLATE . $this->theme . '/template/extension/d_visual_designer/content_blocks/'.basename($file))) {
                $file = DIR_TEMPLATE . $this->theme . '/template/extension/d_visual_designer/content_blocks/'.basename($file);
            }
            file_put_contents($folder."compress/content_blocks.tag", file_get_contents($file).PHP_EOL, FILE_APPEND);
        }
        $files = glob($folder . 'settings_block/*.tag', GLOB_BRACE);
        foreach($files as $file){
            file_put_contents($folder."compress/settings_block.tag", file_get_contents($file).PHP_EOL, FILE_APPEND);
        }
        $files = glob($folder . 'layout_blocks/*.tag', GLOB_BRACE);
        foreach($files as $file){
            file_put_contents($folder."compress/layout_blocks.tag", file_get_contents($file).PHP_EOL, FILE_APPEND);
        }
    }
}
