<?php
/*
*	location: admin/model
*/

class ModelDVisualDesignerDesigner extends Model {

    private $codename = 'd_visual_designer';

    private $settingJS;

    private $settingChild;

    private $parent = '';

    private $parents = array();

    private $level = 0;

    private $sort_order = 0;
    
    private $sort_orders = array();

    private $parent_clear = false;

    public function getPattern(){
        $blocks = $this->getBlocks();

        $pattern = "\\[(\\[?)(vd_row|vd_column";

        foreach ($blocks as $block) {
            $pattern .= '|vd_'.$block;
        }
        $pattern .=")(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\[(?!\\/\\2\])[^\\[]*+)*+)\[\\/\\2\])?)(\\]?)";

        return $pattern;
    }

    public function getChildPattern($type){
        $pattern = "\\[(\\[?)(vd_".$type;
        $pattern .=")(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\[(?!\\/\\2\])[^\\[]*+)*+)\[\\/\\2\])?)(\\]?)";
        return $pattern;
    }

    public function escape($text){

        $text = str_replace("'", '``', $text);
        $text = str_replace("[", '`{`', $text);
        $text = str_replace("]", '`}`', $text);

        return $text;
    } 

    public function unescape($text){

        $text = str_replace('`{`', '[', $text);
        $text = str_replace('`}`', ']', $text);
        $text = str_replace('``', "'", $text);

        return $text;
    }

    public function shortcode_parse_atts($text) {
        $attr = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|([a-zA-Z:0-9_]+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
            $params = '';
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $attr[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $this->parseName($m[3], $m[4], $attr);
                }
            }
        } else {
            $attr = ltrim($text);
        }

        return $attr;
        
    }

    public function parseName($name, $value, &$attr){
        $pos = strpos($name, '::');
        if($pos === false){

            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            $value = $this->unescape($value);
            $attr[$name] = $value;
        }
        else{
            $name = str_replace('::',',',$name);
            $name = str_replace(':',',',$name);
            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            $value = $this->unescape($value);

            $exploded = explode(',', $name);
            $temp = &$attr;
            foreach($exploded as $key) {
                $temp = &$temp[$key];
            }
            $temp = $value;
        }
    }

    public function getRandomString(){
        return substr( md5(rand()), 0, 7);
    }

    public function parseDescription($description){
        $this->level=0;

        $description = html_entity_decode($description);

        $this->settingJS = array();

        $content = preg_replace_callback('/' . $this->getPattern() . '/s', 'ModelDVisualDesignerDesigner::do_shortcode_tag', $description);

        if(empty($this->settingJS) && !empty($content)){
            $description = $this->escape($description);
            $content = "[vd_row][vd_column][vd_text text='".$description."'][/vd_column][/vd_row]";
            $content = preg_replace_callback('/' . $this->getPattern() . '/s', 'ModelDVisualDesignerDesigner::do_shortcode_tag', $content);
        }

        $results = array();

        $results['content'] = $content;
        $results['setting'] = $this->settingJS;

        return $results;
    }

    public function parseDescriptionHelper($description){
        $this->sort_order = 0;
        $this->level++;
        $this->sort_orders[$this->level] = -1;
        $content = preg_replace_callback('/' . $this->getPattern() . '/s', 'ModelDVisualDesignerDesigner::do_shortcode_tag', $description);
        array_pop($this->parents);
        $this->level--;
        return $content;
    }

    public function getChildSetting($content, $type){

        $setting_block = $this->getSettingBlock($type);
        if(!empty($setting_block['child'])){
            $this->settingChild = array();
            preg_replace_callback('/' . $this->getChildPattern($setting_block['child']) . '/s', 'ModelDVisualDesignerDesigner::do_child_shortcode_tag', $content);

            return $this->settingChild;
        }
        else{
            return array();
        }

    }

    public function do_child_shortcode_tag($m){

        if ( $m[1] == '[' && $m[6] == ']' ) {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];

        $type=str_replace('vd_','',$tag);

        $attr = $this->shortcode_parse_atts( $m[3] );

        $block_id = $type.'_'.$this->getRandomString();

        $attrd = array(
            'setting' => $attr,
            'type' => $type,
            'content' => $m[5],
            'block_id' => $block_id
            );

        $this->settingChild[] = $attr;
    }

    private function do_shortcode_tag($m) {

        if ( $m[1] == '[' && $m[6] == ']' ) {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];

        $type=str_replace('vd_','',$tag);

        $attr = $this->getSetting($this->shortcode_parse_atts( $m[3]), $type);

        if ( !empty( $m[5] ) ) {
            $current_block = $type.'_'.$this->getRandomString();

            if(!isset($this->sort_orders[$this->level])){
                $this->sort_orders[$this->level] = 0;
            }
            else{
                $this->sort_orders[$this->level]++;
            }

            $attr_tmp = $attr;
            $attr_tmp['setting_child'] = $this->getChildSetting($m[5], $type);

            $level_main = $this->level;

            if($type == 'row'){
                $this->parent = '';
            }

            if(!empty($this->parents))
            {
                $parent_id = current(array_slice($this->parents, -1));
            }
            else{
                $parent_id = '';
            }


            $this->settingJS[$current_block] = array(
                'setting' => $attr,
                'parent' => $parent_id,
                'sort_order' => $this->sort_orders[$this->level],
                'type' => $type
                );

            if($parent_id != ''){
                $setting_parent = $this->getSettingBlock($this->settingJS[$parent_id]['type']);

                if(!empty($setting_parent['child'])){
                    $this->settingJS[$current_block]['child'] = true;
                }
            }

            $this->parent = $current_block;

            array_push($this->parents,$current_block);

            $content_child = $this->parseDescriptionHelper($m[5]);

            $child_settings = $this->getSettingFromArray($current_block);

            $attr_tmp = $attr;

            $attr_tmp['setting_child'] = $child_settings;

            if($type == 'row'){
                $content_main = $this->getContent($type, $attr_tmp, $current_block, $level_main, 2);
            }
            else{
                $content_main = $this->getContent($type, $attr_tmp, $current_block, $level_main, 1);
            }

            $content = str_replace('{{{inner-block}}}', $content_child, $content_main);
            return $content;

        } else {
            $current_block = $type.'_'.$this->getRandomString();

            $content = $this->getContent($type, $attr, $current_block, $this->level);

            $content = str_replace('{{{inner-block}}}', '', $content);

            $this->load->language('d_visual_designer_module/'.$type);

            $this->settingJS[$current_block] = array(
                'setting' => $attr,
                'sort_order' => $this->sort_order++,
                'parent' => $this->parent,
                'type' => $type
                );

            if($this->parent != ''){
                $setting_parent = $this->getSettingBlock($this->settingJS[$this->parent]['type']);

                if(!empty($setting_parent['child'])){
                    $this->settingJS[$current_block]['child'] = true;
                }
            }

            return $content;
        }
    }

    public function getSettingFromArray($parent_id){
        $settings = array();

        foreach ($this->settingJS as $block_id => $setting) {
            if($setting['parent'] == $parent_id){
                $settings[$block_id] = $setting['setting'];
            }
        }
        return $settings;
    }

    public function getContent($type, $setting, $key, $level, $inner_blocks = 0){

        $this->load->language('d_visual_designer_module/'.$type);

        $setting_block = $this->getSettingBlock($type);

        $data = $setting_block;

        $data['type'] = $type;

        $data['content'] = $this->load->controller('d_visual_designer_module/'.$type, $setting);

        $data['setting'] = $this->getSetting($setting, $type);

        if(!empty($key)){
            $data['key'] = $key;
        }else{
            $data['key'] = $type.'_'.$this->getRandomString();
        }

        $data['title'] = $this->language->get('text_title');

        if(!empty($setting['size']) && is_numeric($setting['size'])){
            $data['size'] = $setting['size'];
        }
        else{
            $data['size'] = '12';
        }

        if($level%2){
            $data['level'] = 1;
        }
        else{
            $data['level'] = 0;
        }

        if(!empty($setting_block['child'])){
            $this->load->language('d_visual_designer_module/'.$setting_block['child']);
            $data['help_add_child'] = sprintf($this->language->get('help_add_child'),$this->language->get('text_title'));
        }

        $this->load->language('d_visual_designer_module/'.$type);
        $data['help_add_block'] = sprintf($this->language->get('help_add_block'),$this->language->get('text_title'));
        $data['help_edit'] = sprintf($this->language->get('help_edit'),$this->language->get('text_title'));
        $data['help_copy'] =sprintf($this->language->get('help_copy'),$this->language->get('text_title'));
        $data['help_collapse'] = sprintf($this->language->get('help_collapse'),$this->language->get('text_title'));
        $data['help_remove'] = sprintf($this->language->get('help_remove'),$this->language->get('text_title'));
        $data['title'] = $this->language->get('text_title');

        $this->load->model('tool/image');



        if(!empty($setting['design_background_image'])){
            $image = $setting['design_background_image'];

            if(file_exists(DIR_IMAGE.$image)){
                list($width, $height) = getimagesize(DIR_IMAGE . $image);
                $data['setting']['design_background_image'] = $this->model_tool_image->resize($image, $width, $height);
            }
        }

        if(!empty($setting['image'])){
            $image = $setting['image'];

            if (is_file(DIR_IMAGE.$image)) {
                $data['image'] = $this->model_tool_image->resize($image, 32, 32);;
            } else {
                $data['image'] = $this->model_tool_image->resize('no_image.png', 32, 32);
            }
        }
        else{
            $image = '../image/data/d_visual_designer/'.$type.'.svg';

            if (is_file(DIR_IMAGE.$image)) {
                $data['image'] = $image;
            } else {
                $data['image'] = $this->model_tool_image->resize('no_image.png', 32, 32);
            }
        }



        if(!empty($setting_block['custom_layout'])){
            return $this->load->view('d_visual_designer_layout/'.$setting_block['custom_layout'].'.tpl',$data);
        }
        else{
            if($inner_blocks == 1){
                return $this->load->view('d_visual_designer_layout/medium.tpl',$data);
            }
            else if($inner_blocks == 2){
                return $this->load->view('d_visual_designer_layout/main.tpl',$data);
            }
            elseif ($setting_block['child_blocks'] && $inner_blocks == 0) {
                return $this->load->view('d_visual_designer_layout/medium.tpl',$data);
            }
            else{
                return $this->load->view('d_visual_designer_layout/children.tpl',$data);
            }
        }
    }

    public function getContentBySetting($blocks, $block_id){

        $block_info = $blocks['items'][$block_id];

        if(!empty($blocks['relateds'][$block_id])){
            $content_child = '';
            $setting_block = array();
            foreach ($blocks['relateds'][$block_id] as $parent_id => $child_id) {
                $result = $this->getContentBySetting($blocks, $child_id);
                $content_child .= $result;
                if(!empty($blocks['items'][$child_id]['setting'])){
                    $setting_block[$child_id] = $blocks['items'][$child_id]['setting'];
                }
                else{
                    $setting_block[$child_id] = array();
                }
            }

            if(!empty($block_info['setting']))
            {
                $setting = $block_info['setting'] + array('setting_child'=>$setting_block);
            }
            else{
                $setting = array('setting_child'=>$setting_block);
            }

            $content_main = $this->getContent($block_info['type'], $setting, $block_info['block_id'], ($block_info['level']), ($block_info['level'] == 0)?2:1);
            $content = str_replace('{{{inner-block}}}', $content_child, $content_main);

        }
        else{
            $setting_block = $this->getSettingBlock($block_info['type']);

            if(!empty($block_info['setting'])){
                $block_setting = $block_info['setting'];
            }
            else{
                $block_setting = array();
            }

            $content = $this->getContent($block_info['type'], $block_setting, $block_info['block_id'], $block_info['level'],
                $setting_block['child_blocks']?1:0);
            $content = str_replace('{{{inner-block}}}', '', $content);
        }

        return $content;
    }

    public function getFullContent($block_info, $level, $settingJS = array()){

        $setting_block = $this->getSettingBlock($block_info['type']);

        $settingChild = array();
        if($level == 0 && $setting_block['level_min'] == 2){
            $this->parent_clear = true;
            $setting_main_block = $this->getSettingBlock('row');

            $child_block =array(
                'type' => 'row',
                'parent'=> '',
                'sort_order' => 0,
                'setting' => $this->getSetting(array(), 'row'),
                'block_id' => 'row_'.$this->getRandomString()
                );
            $result_main = $this->getFullContent($child_block, ($level), $settingJS);

            $settingJS = $result_main['setting'];

            $block_info['parent'] = $this->parent;
            $this->parent_clear = false;
            $result_child = $this->getFullContent($block_info, 2, $settingJS);

            $settingJS = $settingJS+$result_child['setting'];
            $content = str_replace('{{{inner-block}}}', $result_child['content'], $result_main['content']);
        }
        else if(!empty($setting_block['child'])){
            $settingJS[$block_info['block_id']] = array(
                'type' => $block_info['type'],
                'parent' => $block_info['parent'],
                'sort_order' => 0,
                'setting' => $block_info['setting']
                );
            $this->parent = $block_info['block_id'];
            $setting_child_block = $this->getSettingBlock($setting_block['child']);

            $child_block =array(
                'type' => $setting_block['child'],
                'parent'=> $block_info['block_id'],
                'sort_order' => 0,
                'setting' => $this->getSetting(array(), $setting_block['child']),
                'block_id' => $setting_block['child'].'_'.$this->getRandomString()
                );
            $this->parent = $block_info['block_id'];

            $result = $this->getFullContent($child_block, ($level+1), $settingJS);

            $content_child = $result['content'];
            $settingJS = $settingJS+$result['setting'];
            $block_info['setting']['setting_child'] = $result['setting_child'];

            $content_main = $this->getContent($block_info['type'], $block_info['setting'], $block_info['block_id'], $level, ($level == 0)?2:1, 1);
            $content = str_replace('{{{inner-block}}}', $content_child, $content_main);
        }
        else{
            $settingJS[$block_info['block_id']] = array(
                'type' => $block_info['type'],
                'parent' => $block_info['parent'],
                'sort_order' => 0,
                'setting' => $block_info['setting']
                );
            $settingChild = $block_info['setting'];
            if(!empty($settingJS[$block_info['parent']])){
                $setting_parent = $this->getSettingBlock($settingJS[$block_info['parent']]['type']);

                if(!empty($setting_parent['child'])){
                    $settingJS[$block_info['block_id']]['child'] = true;
                }
            }

            $content = $this->getContent($block_info['type'], $block_info['setting'], $block_info['block_id'], $level, $setting_block['child_blocks']?1:0, 1);
            if(!$this->parent_clear){
                $content = str_replace('{{{inner-block}}}', '', $content);
            }
            $this->parent = $block_info['block_id'];

        }

        return array('content' => $content,'setting' => $settingJS, 'setting_child' => array( $block_info['block_id'] => $settingChild), 'block_id' => $block_info['block_id']);
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
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function getBlocks(){
        $dir = DIR_APPLICATION.'controller/d_visual_designer_module';
        $files = scandir($dir);
        $result = array();
        foreach($files as $file){
            if(strlen($file) > 1 && strpos( $file, '.php')){
                $result[] = substr($file, 0, -4);
            }
        }
        return $result;
    }

    public function getSettingBlock($type){

        $results = array();

        $file = DIR_CONFIG.'d_visual_designer/'.$type.'.php';
        if (file_exists($file)) {
            $_ = array();

            require($file);

            $results = array_merge($results, $_);
        }

        return $results;
    }

    public function getRouteByBackendRoute($backend_route){
        $this->load->model('setting/setting');
        $setting = $this->model_setting_setting->getSetting('d_visual_designer');
        if(isset($setting['d_visual_designer_setting']['use'])){
            $routes = $setting['d_visual_designer_setting']['use'];
        }
        else{
            $routes = array();
        }
        foreach ($routes as $route) {
            $route_info = $this->getRoute($route);
            if(isset($route_info['backend_route_regex'])){
                $pattern = $route_info['backend_route_regex'];
            }
            else{
                $pattern = $route_info['backend_route'];
            }
            if (preg_match('/^' . str_replace(array('\*', '\?'), array('.*', '.'), preg_quote($pattern, '/')) . '/', $backend_route)) {
                $route_info['config_name'] = $route;
                return $route_info;
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
        uasort($route_data, 'ModelDVisualDesignerDesigner::compareRoute');
        return $route_data;
    }


    public function compareRoute($a, $b){
        if($a['name'] > $b['name']){
            return 1;
        }
        elseif($a['name'] < $b['name']){
            return -1;
        }
        else{
            return 0;
        }
    }

    public function getRoute($name){

        $results = array();

        $file = DIR_CONFIG.'d_visual_designer_route/'.$name.'.php';

        if (file_exists($file)) {
            $_ = array();

            require($file);

            $results = array_merge($results, $_);
        }
        if(!empty($results) && !isset($results['name'])){
            $results['name'] = ucfirst(strtolower($name));
        }
        return $results;
    }

    public function checkPermission(){
        $this->load->model('d_shopunity/setting');
        $setting = $this->model_d_shopunity_setting->getSetting($this->codename);

        if(!empty($setting['limit_access_user'])){
            if(!empty($setting['access_user']) && in_array($this->user->getId(), $setting['access_user'])){
               return true;
            }
        }
        elseif(!empty($setting['limit_access_user_group'])){
            if(!empty($setting['access_user_group']) && in_array($this->user->getGroupId(), $setting['access_user_group'])){
                return true;
            }
        }
        else{
            return true;
        }

        return false;
    }

    public function addScript(){
        if(!empty($setting)){
            if(!empty($setting['limit_access_user'])){
                if(!empty($setting['access_user']) && in_array($this->user->getId(), $setting['access_user'])){
                    $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5,10));
                }
            }
            elseif(!empty($setting['limit_access_user_group'])){
                if(!empty($setting['access_user_group']) && in_array($this->user->getGroupId(), $setting['access_user_group'])){
                    $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5,10));
                }
            }
            else{
                $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5,10));
            }
        }
        else{
            $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5,10));
        }
    }

    public function checkCompleteVersion(){
        $return = false;
        if(!file_exists(DIR_SYSTEM.'mbooth/extension/d_visual_designer_module.json')){
            $return = true; 
        }
        if(!file_exists(DIR_SYSTEM.'mbooth/extension/d_visual_designer_landing.json')){
            $return = true; 
        }

        return $return;
    }

}
