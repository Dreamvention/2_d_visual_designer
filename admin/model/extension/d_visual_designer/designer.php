<?php
/*
*   location: admin/model
*/

class ModelExtensionDVisualDesignerDesigner extends Model
{
    private $codename = 'd_visual_designer';

    private $error = array();

    private $styles = array();

    private $styleContent = '';

    /**
     * Converts shortcodes to settings
     * @param $text
     * @return array
     */
    public function parseContent($text)
    {
        $blocks = $this->getBlocks();

        $d_shortcode_reader_writer = new d_shortcode_reader_writer($blocks);

        $setting = $d_shortcode_reader_writer->readShortcode($text);

        if (!empty($text) && empty($setting)) {
            $text = "[vd_section_wrapper][vd_row][vd_column][vd_text text='".$d_shortcode_reader_writer->escape($text)."'][/vd_column][/vd_row][/vd_section_wrapper]";
            $setting = $d_shortcode_reader_writer->readShortcode($text);
        }
        
        $setting = $this->checkCompability($setting);

        $that = $this;
        array_walk($setting, function (&$block, $key) use ($that) {
            $block['setting'] = $that->getSetting($block['setting'], $block['type']);
        });


        return $setting;
    }

    public function checkCompability($setting)
    {
        $resSetting = $setting;
        
        $blocks = $this->getBlocksByParent('', $setting);

        foreach($blocks as $block_id => $block_info) {
            if($block_info['type'] == 'row') {
                $new_block = $this->newBlock('section_wrapper');
                $new_block['sort_order'] = $block_info['sort_order'];
                $resSetting[$new_block['id']] = $new_block;
                $resSetting[$block_id]['parent'] = $new_block['id'];
            }
        }

        return $resSetting;
    }

    public function newBlock($type)
    {
        $setting = $this->getSettingBlock($type);

        return array(
            'id' => $type.'_'.substr( md5(rand()), 0, 7),
            'parent' => '',
            'type' => $type,
            'setting' => $setting['setting']
        );
    }

    /**
     * Converts settings to shortcodes
     * @param $setting
     * @return string
     */
    public function parseSetting($setting)
    {
        $blocks = $this->getBlocks();

        $that = $this;
        array_walk($setting, function (&$block, $key) use ($that) {
            $block['setting'] = $block['setting']['global'];
        });

        if (empty($setting)) {
            return '';
        }

        $d_shortcode_reader_writer = new d_shortcode_reader_writer($blocks);

        $content = $d_shortcode_reader_writer->writeShortcode($setting);

        return $content;
    }

    /**
     * Returns the shortcodes for the specified config
     * @param $route
     * @param $id
     * @param $field_name
     * @return array
     */
    public function getContent($route, $id, $field_name)
    {
        $query = $this->db->query("SELECT `content` FROM `".DB_PREFIX."visual_designer_content` WHERE `route` ='".$route."' AND `id` = '".(int)$id."' AND `field` = '".$field_name."'");

        return $query->row;
    }

    /**
     * Keeps shortcodes in the database
     * @param $content
     * @param $route
     * @param $id
     * @param $field_name
     */
    public function saveContent($content, $route, $id, $field_name)
    {
        $query = $this->db->query("SELECT * FROM `".DB_PREFIX."visual_designer_content` WHERE `route` ='".$route."' AND `id` = '".(int)$id."' AND `field` = '".$field_name."'");

        if ($query->num_rows) {
            $this->db->query("UPDATE `".DB_PREFIX."visual_designer_content` SET `content`= '".$this->db->escape($content)."' WHERE `route` ='".$route."' AND `id` = '".(int)$id."' AND `field` = '".$field_name."'");
        } else {
            $this->db->query("INSERT INTO `".DB_PREFIX."visual_designer_content` SET `content`= '".$this->db->escape($content)."', `route` ='".$route."', `id` = '".(int)$id."', `field` = '".$field_name."'");
        }
    }

    /**
     * Save config to Route
     * @param $config_name
     * @param $template_id
     * @param $route
     * @param $id
     * @param $field_name
     */
    public function saveConfig($config_name, $template_id, $route, $id, $field_name) {
        $this->load->model('extension/'.$this->codename.'template');
        $content = $this->model_extension_d_visual_designer_template->getConfigTemplate($template_id, $config_name);
        $this->saveContent($content, $route, $id, $field_name);
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

        $content = $content.$styles;

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
        $content .= '<style type="text/css">';
        $content .= $this->stylesContent;
        $content .= '</style>';
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
                    'children' => $this->preRenderLevel($block_info['id'], $setting),
                    'id' => $block_info['id']
                );

                $result .= $this->model_extension_d_opencart_patch_load->view('extension/d_visual_designer_module/'.$block_info['type'], $renderData);
                $output = $this->load->controller('extension/d_visual_designer_module/'.$block_info['type'].'/catalog_styles', false);

                $styles = $this->load->view('extension/d_visual_designer/partials/layout_style', $block_info);

                $styles = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $styles);
                $styles = preg_replace('/\s{2,}/', ' ', $styles);
                $styles = preg_replace('/\s*([:;{}])\s*/', '$1', $styles);
                $styles = preg_replace('/;}/', '}', $styles);

                $this->stylesContent .= $styles;

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
     * Prepare setting for user
     * @param $setting
     * @return array
     */
    public function prepareUserSetting($setting)
    {
        $data = array();
        $this->load->model('tool/image');
        if (!empty($setting['design_background_image'])) {
            $image = $setting['design_background_image'];

            if (file_exists(DIR_IMAGE.$image)) {
                list($width, $height) = getimagesize(DIR_IMAGE . $image);
                $data['design_background_image'] = $this->model_tool_image->resize($image, $width, $height);
            }
        }
        return $data;
    }

    /**
     * Prepare setting for edit
     * @param $setting
     * @return array
     */
    public function prepareEditSetting($setting)
    {
        $data = array();
        $this->load->model('tool/image');
        if (!empty($setting['design_background_image'])) {
            $image = $setting['design_background_image'];

            if (file_exists(DIR_IMAGE.$image)) {
                $data['design_background_thumb'] = $this->model_tool_image->resize($image, 100, 100);
            } else {
                $data['design_background_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            }
        } else {
            $data['design_background_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }
        return $data;
    }

    /**
     * Get full setting
     * @param $setting
     * @param $type
     * @return array
     */
    public function getSetting($setting, $type, $short = false)
    {
        if (!empty($this->session->data['vd_test_setting'])) {
            if(file_exists(DIR_APPLICATION.'controller/extension/'.$this->codename.'_module/'.$type.'.php')) {
                rename(DIR_APPLICATION.'controller/extension/'.$this->codename.'_module/'.$type.'.php', DIR_APPLICATION.'controller/extension/'.$this->codename.'_module/'.$type.'.php_');
            }
            
            unset($this->session->data['vd_test_setting']);
            return $setting;
        }

        $this->session->data['vd_test_setting'] = true;

        $this->config->load('d_visual_designer');

        $setting_main = $this->config->get('d_visual_designer_default_block_setting');

        $setting_default = $this->getSettingBlock($type);

        $result = $setting_main;

        if (!empty($setting_default['setting'])) {
            foreach ($setting_default['setting'] as $key => $value) {
                $result[$key] = $value;
            }
        }

        if (!empty($setting)) {
            foreach ($setting as $key => $value) {
                if (!is_array($value) && !empty($setting_default['types'][$key])) {
                    if ($setting_default['types'][$key] == 'boolean') {
                        $result[$key] = $value? true : false;
                    } elseif ($setting_default['types'][$key] == 'number') {
                        $result[$key] = (int)$value;
                    } elseif ($setting_default['types'][$key] == 'string') {
                        $result[$key] = (string)$value;
                    } else {
                        $result[$key] = $value;
                    }
                } else {
                    $result[$key] = $value;
                }
            }
        }
        if (!$short) {
            $userSetting = $this->load->controller('extension/d_visual_designer_module/'.$type, $result);

            if (!$userSetting || !is_array($userSetting)) {
                $userSetting = array();
            }

            $globalUserSetting = $this->prepareUserSetting($result);

            $userSetting = array_merge($globalUserSetting, $userSetting);
        } else {
            $userSetting = false;
        }
        $editSetting = $this->load->controller('extension/d_visual_designer_module/'.$type.'/setting', $result);

        if (!$editSetting || !is_array($editSetting)) {
            $editSetting = array();
        }

        $globalEditSetting = $this->prepareEditSetting($result);

        $editSetting = array_merge($globalEditSetting, $editSetting);

        unset($this->session->data['vd_test_setting']);

        return array('global'=>$result, 'user' => $userSetting, 'edit' => $editSetting);
    }

    /**
     * Get all blocks
     * @return array
     */
    public function getBlocks()
    {
        $dir = DIR_CONFIG.'/d_visual_designer';
        $files = scandir($dir);
        $result = array();
        foreach ($files as $file) {
            if (strlen($file) > 1 && strpos($file, '.php')) {
                $result[] = substr($file, 0, -4);
            }
        }
        return $result;
    }

    /**
     * Get config for block type
     * @param $type
     * @return array
     */
    public function getSettingBlock($type)
    {
        $results = array();

        $file = DIR_CONFIG.'d_visual_designer/'.$type.'.php';
        if (file_exists($file)) {
            $_ = array();

            require($file);

            $results = array_merge($results, $_);
        }

        return $results;
    }

    /**
     * Get Route By Backend Route
     * @param $backend_route
     * @return array
     */
    public function getRouteByBackendRoute($backend_route)
    {
        $this->load->model('setting/setting');
        $setting = $this->model_setting_setting->getSetting('d_visual_designer');
        if (isset($setting['d_visual_designer_setting']['use'])) {
            $routes = $setting['d_visual_designer_setting']['use'];
        } else {
            $routes = array();
        }
        foreach ($routes as $route) {
            $route_info = $this->getRoute($route);
            if (isset($route_info['backend_route_regex'])) {
                $pattern = $route_info['backend_route_regex'];
            } else {
                $pattern = $route_info['backend_route'];
            }
            if (preg_match('/' . str_replace(array('\*', '\?'), array('.*', '.'), preg_quote($pattern, '/')) . '/', $backend_route)) {
                $route_info['config_name'] = $route;
                return $route_info;
            }
        }
        return array();
    }

    /**
     * Get all routes
     * @return array
     */
    public function getRoutes()
    {
        $dir = DIR_CONFIG.'d_visual_designer_route/*.php';

        $files = glob($dir);

        $route_data = array();

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $route_info = $this->getRoute($name);
            $route_data[$name] = $route_info;
        }
        uasort($route_data, 'ModelExtensionDVisualDesignerDesigner::compareRoute');
        return $route_data;
    }


    /**
     *
     * @param $a
     * @param $b
     * @return int
     */
    public function compareRoute($a, $b)
    {
        if ($a['name'] > $b['name']) {
            return 1;
        } elseif ($a['name'] < $b['name']) {
            return -1;
        } else {
            return 0;
        }
    }

    /**
     * Get Route by name
     * @param $name
     * @return array
     */
    public function getRoute($name)
    {
        $results = array();

        $file = DIR_CONFIG.'d_visual_designer_route/'.$name.'.php';

        if (file_exists($file)) {
            $_ = array();

            require($file);

            $results = array_merge($results, $_);
        }
        if (!empty($results) && !isset($results['name'])) {
            $results['name'] = ucfirst(strtolower($name));
        }
        return $results;
    }

    /**
     * Check permission
     * @return bool
     */
    public function checkPermission()
    {
        $this->load->model('extension/module/d_visual_designer');
        $setting = $this->model_extension_module_d_visual_designer->getSetting($this->codename);

        if (!empty($setting['limit_access_user'])) {
            if (!empty($setting['access_user']) && in_array($this->user->getId(), $setting['access_user'])) {
                return true;
            }
        } elseif (!empty($setting['limit_access_user_group'])) {
            if (!empty($setting['access_user_group']) && in_array($this->user->getGroupId(), $setting['access_user_group'])) {
                return true;
            }
        } else {
            return true;
        }

        return false;
    }

    /**
     * Check complete version
     * @return bool
     */
    public function checkCompleteVersion()
    {
        $return = false;
        if (!file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_visual_designer_module.json')) {
            $return = true;
        }
        if (!file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_visual_designer_landing.json')) {
            $return = true;
        }

        return $return;
    }

    /**
     * Install template
     */

     public function checkInstallModule() {
        $this->load->model('extension/d_opencart_patch/extension');

        if(!$this->model_extension_d_opencart_patch_extension->isInstalled($this->codename)) {
            return false;
        }

        $this->load->model('setting/setting');

        $setting_module = $this->model_setting_setting->getSetting($this->codename);

        if(!$setting_module) {
            return false;
        }
        return true;
     }

    /**
     * Check config enabled
     * @param $config_name
     * @return bool
     * @throws Exception
     */
    public function checkConfig($config_name = false)
    {
        $this->load->model('extension/d_opencart_patch/extension');

        if(!$this->model_extension_d_opencart_patch_extension->isInstalled($this->codename)) {
            return false;
        }

        $this->load->model('setting/setting');

        $setting_module = $this->model_setting_setting->getSetting($this->codename);

        if(!empty($setting_module[$this->codename.'_setting'])){
            $setting = $setting_module[$this->codename.'_setting'];
        } else {
            $this->load->config($this->codename);
            $setting = $this->config->get($this->codename.'_setting');
        }
        
        if(!empty($setting_module[$this->codename.'_status'])){
            $status = $setting_module[$this->codename.'_status'];
        } else {
            $status = false;
        }

        if(!$status) {
            return false;
        }

        if ($config_name) {
            if (is_array($config_name)) {
                foreach ($config_name as $value) {
                    if (!in_array($value, $setting['use'])) {
                        return false;
                    }
                }
            } else {
                if (!in_array($config_name, $setting['use'])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Install config VD
     * @param $config_name
     * @throws Exception
     */
    public function installConfig($config_name = false) {
        $this->load->model('extension/d_opencart_patch/extension');
        $this->load->model('extension/d_opencart_patch/setting');
        $this->load->model('extension/d_opencart_patch/user');

        if(!$this->model_extension_d_opencart_patch_extension->isInstalled($this->codename)) {
            $this->model_extension_d_opencart_patch_extension->install('module', $this->codename);

            $this->load->model('user/user_group');

            $this->model_user_user_group->addPermission($this->model_extension_d_opencart_patch_user->getGroupId(), 'access', 'extension/module/' . $this->codename);
            $this->model_user_user_group->addPermission($this->model_extension_d_opencart_patch_user->getGroupId(), 'modify', 'extension/module/' . $this->codename);

            $this->load->controller('extension/module/'.$this->codename.'/install');
        }

        $this->load->model('setting/setting');

        $setting_module = $this->model_extension_d_opencart_patch_setting->getSetting($this->codename);

        if(!empty($setting_module[$this->codename.'_setting'])){
            $setting = $setting_module[$this->codename.'_setting'];
            if(is_array($config_name)) {
                foreach($config_name as $value) {
                    if(!in_array($value, $setting['use'])) {
                        $setting['use'][] = $value;
                        $setting_module[$this->codename.'_setting'] = $setting;
                    }
                }
            } else {
                if(!in_array($config_name, $setting['use'])) {
                    $setting['use'][] = $config_name;
                    $setting_module[$this->codename.'_setting'] = $setting;
                }
            }

            $this->load->controller('extension/'.$this->codename.'/setting/uninstallEvents');
            $this->load->controller('extension/'.$this->codename.'/setting/installEvents', $setting['use']);

            $setting_module[$this->codename.'_status'] = 1;
            $this->model_extension_d_opencart_patch_setting->editSetting($this->codename, $setting_module);
        } else {
            $this->load->config($this->codename);
            $setting = $this->config->get($this->codename.'_setting');

            if (is_array($config_name)) {
                foreach ($config_name as $value) {
                    $setting['use'][] = $value;
                }
            } else {
                $setting['use'][] = $config_name;
            }
            $this->load->controller('extension/'.$this->codename.'/setting/installEvents', $setting['use']);
            $this->model_extension_d_opencart_patch_setting->editSetting($this->codename, array(
                $this->codename.'_setting'=> $setting,
                $this->codename.'_status' => 1
            ));
        }
    }

    /**
     * Validate access
     * @param $config_name
     * @return bool
     */
    public function validateEdit($config_name)
    {
        $this->error = array();

        $status = $this->config->get($this->codename . '_status');

        if (!$status) {
            $this->error['status'] = $this->language->get('error_status');
        }

        if (!empty($setting['d_visual_designer_setting']['limit_access_user'])) {
            if (!empty($setting['d_visual_designer_setting']['access_user']) && !in_array($this->user->getId(), $setting['d_visual_designer_setting']['access_user'])) {
                $this->error['warning'] = $this->language->get('error_permission');
            } elseif ($setting['d_visual_designer_setting']['access_user']) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
        }
        if (!empty($setting['d_visual_designer_setting']['limit_access_user_group'])) {
            if (!empty($setting['d_visual_designer_setting']['access_user_group']) && !in_array($this->user->getGroupId(), $setting['d_visual_designer_setting']['access_user_group'])) {
                $this->error['warning'] = $this->language->get('error_permission');
            } elseif (empty($setting['d_visual_designer_setting']['access_user_group'])) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
        } else {
            $route_info = $this->getRoute($config_name);

            if (empty($route_info)) {
                $this->error['config'] = $this->language->get('error_config');
            }
        }
        return !$this->error;
    }

    /**
     * Get all Custom Icon Set
     * @return array
     */
    public function getIconSets()
    {
        $files = glob(DIR_APPLICATION."view/javascript/d_visual_designer/iconset/*.js", GLOB_BRACE);

        $result = array();

        foreach ($files as $file) {
            $result[] = basename($file, '.js');
        }

        return $result;
    }


    /**
     * Get all Riot Tags
     * @param $compress
     * @return array
     */
    public function getRiotTags($compress)
    {
        $result = array();

        if ($compress) {
            $this->load->model('extension/module/d_visual_designer');

            if (count(glob(DIR_TEMPLATE."extension/d_visual_designer/compress/*")) === 0) {
                $this->{'model_extension_module_'.$this->codename}->compressRiotTag();
            }

            $files = glob(DIR_TEMPLATE."extension/d_visual_designer/compress/*.tag", GLOB_BRACE);

            foreach ($files as $file) {
                $result[] = 'view/template/extension/'.$this->codename.'/compress/'.basename($file);
            }
        }

        if (!$compress || empty($result)) {
            $files = glob(DIR_TEMPLATE."extension/d_visual_designer/{components,elements,popups,layouts,content_blocks,settings_block,layout_blocks}/*.tag", GLOB_BRACE);

            foreach ($files as $file) {
                $result[] = 'view/template/extension/'.$this->codename.'/'.basename(dirname($file)).'/'.basename($file);
            }
        }

        return $result;
    }
}
