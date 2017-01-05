<?php
class ModelModuleDVisualDesigner extends Model {
    private $setting;

    private $settingJS = array();

    private $settingChild;

    private $current_row;

    private $curent_column;

    private $level= 0;

    private $parents = array();

    private $sort_order = 0;

    private $text = '';

    private $parent = '';
    
    private $token = '';

    private $parent_clear = false;

    public function parseDescription($data){
        $this->setting = array();

        $this->settingJS = array();
        
        if(!empty($data['token'])){
            $this->token = $data['token'];    
        }
        else{
            $this->token = '';
        }
        
        $content = preg_replace_callback('/' . $this->getPattern() . '/s', 'ModelModuleDVisualDesigner::do_shortcode_tag', $data['content']);

        if(empty($this->settingJS) && !empty($content)){
            $data['content'] = $this->escape($data['content']);
            $content = "[vd_row][vd_column][vd_text text='".$data['content']."'][/vd_column][/vd_row]";
            $content = preg_replace_callback('/' . $this->getPattern() . '/s', 'ModelModuleDVisualDesigner::do_shortcode_tag', $content);
        }

        $data = array(
            'field_name' => $data['field_name'],
            'content' => $content,
            'setting' => $this->settingJS,
            'id' => $data['id'],
            'token' => $data['token'],
            'description' => $data['content']
        );

        $content = $this->load->controller('module/d_visual_designer', $data);

        if(!empty($content)){
            return $content;
        }
        else{
            return $data['content'];
        }
    }

    public function getText($description){

        $blocks = $this->getTextBlocks();
        $this->text = '';
        preg_replace_callback('/' . $this->getTextPattern($blocks) . '/s', 'ModelModuleDVisualDesigner::do_shortcode_text', $description);

        $content = preg_replace('/\[.+\]/s', $this->text, $description);
        return $content;
    }

    public function getTextPattern($blocks){
        $pattern = "\\[(\\[?)(";
        $implode = array();
        foreach ($blocks as $block) {
            $implode[] = 'vd_'.$block;
        }
        $pattern .= implode('|',$implode);
        $pattern .=")(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\[(?!\\/\\2\])[^\\[]*+)*+)\[\\/\\2\])?)(\\]?)";
        return $pattern;
    }

    public function getChildPattern($type){
        $pattern = "\\[(\\[?)(vd_".$type;
        $pattern .=")(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\[(?!\\/\\2\])[^\\[]*+)*+)\[\\/\\2\])?)(\\]?)";
        return $pattern;
    }

    public function getChildSetting($content, $type){

        $setting_block = $this->getSettingBlock($type);
        if(!empty($setting_block['child'])){
            $this->settingChild = array();
            preg_replace_callback('/' . $this->getChildPattern($setting_block['child']) . '/s', 'ModelModuleDVisualDesigner::do_child_shortcode_tag', $content);

            return $this->settingChild;
        }
        else{
            return array();
        }

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

    public function do_child_shortcode_tag($m){
        $attr = $this->shortcode_parse_atts( $m[3] );

        $this->settingChild[] = $attr;
    }

    public function parseDescriptionWithoutDesigner($description){
        $this->setting = array();

        $this->settingJS = array();

        $content = preg_replace_callback('/' . $this->getPattern() . '/s', 'ModelModuleDVisualDesigner::do_shortcode_tag', $description);

        if(!empty($content))
        {
            return array('content' => $content, 'setting' => $this->settingJS);
        }
        else{
            return array('content' => $description, 'setting' => $this->settingJS);
        }
    }

    public function getPattern(){
        $blocks = $this->getBlocks();

        $pattern = "\\[(\\[?)(vd_row|vd_column";

        foreach ($blocks as $block) {
            $pattern .= '|vd_'.$block;
        }
        $pattern .=")(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\[(?!\\/\\2\])[^\\[]*+)*+)\[\\/\\2\])?)(\\]?)";

        return $pattern;
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
    public function getTextBlocks(){
        $dir = DIR_APPLICATION.'controller/d_visual_designer_module';
        $files = scandir($dir);
        $result = array();
        foreach($files as $file){
            if(strlen($file) > 1 && strpos( $file, '.php')){
                $type = substr($file, 0, -4);

                $setting_block = $this->getSettingBlock($type);
                if(!empty($setting_block['text'])){
                    $result[] = $type;
                }
            }
        }
        return $result;
    }
    public function shortcode_parse_atts($text, $parse_name = true) {

        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|([a-zA-Z:0-9_]+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {

            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $res = $this->parseName($m[3], $m[4], $parse_name);
                    $atts = array_merge_recursive($atts, $res);
                } elseif (!empty($m[5])) {

                    $m[6] = preg_replace('/^"/', '', $m[6]);
                    $m[6] = preg_replace('/$"/', '', $m[6]);
                    $atts[strtolower($m[5])] =  stripcslashes($m[6]);
                } elseif (isset($m[7]) and strlen($m[7])) {
                    $atts[] =  stripcslashes($m[7]);
                } elseif (isset($m[8])) {
                    $atts[] =  stripcslashes($m[8]);
                }
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }

    public function parseName($name,$value){
        $pos = strpos($name, '::');
        if($pos === false){

            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            $value = $this->unescape($value);

            return array($name => $value);
        }
        else{
            $name = str_replace('::','[',$name);
            $name = str_replace(':','][',$name);
            $name .= ']';

            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            $value = $this->unescape($value);

            parse_str($name.'='.$value, $res);
            return $res;
        }
    }

    public function getRandomString(){
        return substr( md5(rand()), 0, 7);
    }

    private function do_shortcode_text($m) {

        if(!empty($m[3])){

            $type = substr($m[2],3);

            $setting_block = $this->getSettingBlock($type);

            $attr = $this->shortcode_parse_atts( $m[3] );

            if(!empty($attr[$setting_block['text']])){
                $this->text .= $attr[$setting_block['text']].' ';
            }
        }
    }

    private function do_shortcode_tag($m) {
        if ( $m[1] == '[' && $m[6] == ']' ) {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->shortcode_parse_atts( $m[3] );
        $attrd = $this->shortcode_parse_atts( $m[3], false );

        $type=str_replace('vd_','',$tag);

        if ( !empty( $m[5] ) ) {
            $current_block = $type.'_'.$this->getRandomString();

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
                'sort_order' => 0,
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
            $this->level++;
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

            $this->load->language('d_visual_designer/'.$type);

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

    public function getContent($type, $setting, $key, $level, $inner_blocks = 0, $permission = 0){

        $data = array();

        $this->load->language('d_visual_designer/'.$type);

        $setting_block = $this->getSettingBlock($type);

        $data = $setting_block;

        $data['type'] = $type;

        $data['content'] = trim($this->load->controller('d_visual_designer_module/'.$type, $setting));

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

        $status = $this->config->get('d_visual_designer_status');

        if(VERSION >= '2.2.0.0'){
            $this->user = new Cart\User($this->registry);
        }
        else{
            $this->user = new User($this->registry);
        }

        if(!empty($this->token)){
            $route_info = $this->getRoute($this->token);
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

        if(!isset($this->request->get['edit'])){
            $edit_status = false;
        }

        if(empty($route_info)){
            $edit_status = false;
        }
        elseif (!$route_info['status']) {
            $edit_status = false;
        }

        if(!empty($this->request->get['route'])){
            switch ($this->request->get['route']) {
                case 'module/d_visual_designer/getTemplate':
                    $edit_status = true;
                    break;
                case 'module/d_visual_designer/getModule':
                    $edit_status = true;
                    break;
                case 'module/d_visual_designer/getContent':
                    $edit_status = true;
                    break;
                case 'module/d_visual_designer/getChildBlock':
                    $edit_status = true;
                    break;
                case $route_info['frontend_route']:
                    $edit_status = true;
                    break;
                
                default:
                    $edit_status = false;
                    break;
            }
        }

        if($edit_status){
            $data['permission'] = true;
        }
        else{
            $data['permission'] = false;
        }

        if($permission){
            $data['permission'] = true;
        }

        $this->load->language('d_visual_designer_module/'.$type);

        $data['title'] = $this->language->get('text_title');

        $this->load->model('tool/image');

        if (is_file(DIR_IMAGE .'data/d_visual_designer/'.$type.'.svg')) {
            $data['image'] = $this->config->get('config_url').'image/data/d_visual_designer/'.$type.'.svg';
        } else {
            $data['image'] = $this->model_tool_image->resize('no_image.png', 40, 40);
        }

        if(!empty($setting['design_background_image'])){
            $image = $setting['design_background_image'];

            if(file_exists(DIR_IMAGE.$image)){
                list($width, $height) = getimagesize(DIR_IMAGE . $image);
                $data['setting']['design_background_image'] = $this->model_tool_image->resize($image, $width, $height);
            }
        }

        if(!empty($setting_block['custom_template'])){
            return $this->loadView('d_visual_designer_template/'.$setting_block['custom_template'], $data);
        }
        else{
            if($inner_blocks == 1){
                return $this->loadView('d_visual_designer_template/medium', $data);
            }
            else if($inner_blocks == 2){
                return $this->loadView('d_visual_designer_template/main', $data);
            }
            elseif ($setting_block['child_blocks'] && $inner_blocks == 0) {
                return $this->loadView('d_visual_designer_template/medium', $data);
            }
            else{
                return $this->loadView('d_visual_designer_template/children', $data);
            }
        }
    }

    public function loadView($route, $data){
        $route = rtrim($route, ".tpl");
        if(VERSION>='2.2.0.0') {
            return $this->load->view($route, $data);
        }
        else {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$route.'.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/'.$route.'.tpl', $data);
            } else {
                return $this->load->view('default/template/'.$route.'.tpl', $data);
            }
        }
    }

    public function parseDescriptionHelper($description){
        $content = preg_replace_callback('/' . $this->getPattern() . '/s', 'ModelModuleDVisualDesigner::do_shortcode_tag', $description);
        array_pop($this->parents);
        $this->level--;
        return $content;
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

    public function getContentBySetting($blocks, $block_id){

        $content = '';

        $block_info = $blocks['items'][$block_id];

        if(!empty($blocks['relateds'][$block_id])){
            $content_child = '';
            $setting_block = array();
            foreach ($blocks['relateds'][$block_id] as $parent_id => $child_id) {
                $result = $this->getContentBySetting($blocks, $child_id);
                $content_child .= $result;
                $setting_block[$child_id] = $blocks['items'][$child_id]['setting'];
            }
            if(!empty($block_info['setting'])){
                $setting = $block_info['setting'] + array('setting_child'=>$setting_block);
            }
            else{
                $setting = array('setting_child'=>$setting_block);
            }

            $content_main = $this->getContent($block_info['type'], $setting, $block_info['block_id'], ($block_info['level']), ($block_info['level'] == 0)?2:1, 1);
            $content = str_replace('{{{inner-block}}}', $content_child, $content_main);

        }
        else{

            $setting_block = $this->getSettingBlock($block_info['type']);
            $content = $this->getContent($block_info['type'], $block_info['setting'], $block_info['block_id'], $block_info['level'],$setting_block['child_blocks']?1:0, 1);
            $content = str_replace('{{{inner-block}}}', '', $content);
        }

        return $content;
    }

    public function getFullContent($block_info, $level, $settingJS = array(), $parent=false){
        $content = '';

        $setting_block = $this->getSettingBlock($block_info['type']);
        $settingChild = array();
        if($level == 0 && $setting_block['level_min'] == 2){
            $this->parent_clear = true;
            $setting_main_block = $this->getSettingBlock('row');

            $child_block =array(
                'type' => 'row',
                'parent'=> '',
                'setting' => $setting_main_block['setting'],
                'block_id' => 'row_'.$this->getRandomString()
            );
            $result_main = $this->getFullContent($child_block, ($level), $settingJS, true);

            $content_child = $result_main['content'];
            $settingJS = $result_main['setting'];

            $block_info['setting']['setting_child'] = $result_main['setting_child'];

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
                'setting' => $block_info['setting']
            );
            $this->parent = $block_info['block_id'];
            $setting_child_block = $this->getSettingBlock($setting_block['child']);

            $child_block =array(
                'type' => $setting_block['child'],
                'parent'=> $block_info['block_id'],
                'setting' => $setting_child_block['setting'],
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

    public function getRoute($token){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."visual_designer_route WHERE token = '".$token."'");
        return $query->row;
    }

    public function getTemplates($data=array()){

        $sql = "SELECT * FROM ".DB_PREFIX."visual_designer_template  t
        LEFT JOIN ".DB_PREFIX."visual_designer_template_description td
        ON t.template_id = td.template_id
        WHERE td.language_id='".(int)$this->config->get('config_language_id')."'";

        $query = $this->db->query($sql);

        $template_data = array();

        if($query->num_rows){
            foreach ($query->rows as $row) {
                $template_data[] = array(
                    'template_id' => $row['template_id'],
                    'content' => $row['content'],
                    'sort_order' => $row['sort_order'],
                    'name' => $row['name']
                );
            }
        }

        return $template_data;
    }

    public function getTemplate($template_id){
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."visual_designer_template t
        LEFT JOIN ".DB_PREFIX."visual_designer_template_description td
        ON t.template_id = td.template_id
        WHERE td.language_id='".(int)$this->config->get('config_language_id')."' AND t.template_id='".$template_id."'");

        return $query->row;
    }

    public function addTemplate($data){
        $this->db->query("INSERT INTO ".DB_PREFIX."visual_designer_template SET content='".$this->db->escape($data['content'])."', sort_order='".$data['sort_order']."'");
        $template_id = $this->db->getLastId();
        if(!empty($data['template_description'])){
            foreach ($data['template_description'] as $language_id => $value) {
                if(!empty($value)){
                   $this->db->query("INSERT INTO ".DB_PREFIX."visual_designer_template_description SET
                    template_id='".$template_id."',
                    language_id='".$language_id."',
                    name='".$value['name']."'
                ");
                }
            }
        }

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
    public function getSetting($setting, $type){
        $setting_default = $this->getSettingBlock($type);

        $result = $setting_default['setting'];

        if(!empty($setting)){
            foreach ($setting as $key => $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

}
