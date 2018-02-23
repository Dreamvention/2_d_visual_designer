<?php
/*
*	location: admin/model
*/

class ModelExtensionDVisualDesignerDesigner extends Model
{
    private $codename = 'd_visual_designer';

    private $settingJS;

    private $settingChild;

    private $parent = '';

    private $parents = array();

    private $level = 0;

    private $sort_order = 0;
    
    private $sort_orders = array();

    private $parent_clear = false;

    private $error = array();

    /**
    * Converts shortcodes to settings
    */
    public function parseContent($text)
    {
        $blocks = $this->getBlocks();

        $d_shortcode_reader_writer = new d_shortcode_reader_writer($blocks);

        $setting = $d_shortcode_reader_writer->readShortcode($text);
        
        if (!empty($text) && empty($setting)) {
            $text = "[vd_row][vd_column][vd_text text='".$d_shortcode_reader_writer->escape($text)."'][/vd_column][/vd_row]";
            $setting = $d_shortcode_reader_writer->readShortcode($text);
        }

        $that = $this;
        array_walk($setting, function (&$block, $key) use ($that) {
            $block['setting'] = $that->getSetting($block['setting'], $block['type']);
        });

        return $setting;
    }

    /**
     * Converts settings to shortcodes
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
     */
    public function getContent($route, $id, $field_name)
    {
        $query = $this->db->query("SELECT `content` FROM `".DB_PREFIX."visual_designer_content` WHERE `route` ='".$route."' AND `id` = '".(int)$id."' AND `field` = '".$field_name."'");

        return $query->row;
    }
    /**
     * Keeps shortcodes in the database
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
     * Converts shortcodes to text
     */
    public function getText($setting)
    {
        $content = '';

        foreach ($setting as $block_id => $block_setting) {
            $output = $this->load->controller('extension/d_visual_designer_module/'.$block_setting['type'].'/text', $block_setting['setting']['global']);
            if ($output) {
                $content .= $output;
            }
        }

        return $content;
    }


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

    public function getSetting($setting, $type)
    {
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
                $result[$key] = $value;
            }
        }

        $userSetting = $this->load->controller('extension/d_visual_designer_module/'.$type, $result);

        if (!$userSetting) {
            $userSetting = array();
        }
        
        $globalUserSetting = $this->prepareUserSetting($result);

        $userSetting = array_merge($globalUserSetting, $userSetting);
        
        $editSetting = $this->load->controller('extension/d_visual_designer_module/'.$type.'/setting', $result);

        if (!$editSetting) {
            $editSetting = array();
        }

        $globalEditSetting = $this->prepareEditSetting($result);

        $editSetting = array_merge($globalEditSetting, $editSetting);

        return array('global'=>$result, 'user' => $userSetting, 'edit' => $editSetting);
    }

    public function getBlocks()
    {
        $dir = DIR_APPLICATION.'controller/extension/d_visual_designer_module';
        $files = scandir($dir);
        $result = array();
        foreach ($files as $file) {
            if (strlen($file) > 1 && strpos($file, '.php')) {
                $result[] = substr($file, 0, -4);
            }
        }
        return $result;
    }

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

    public function addScript()
    {
        if (!empty($setting)) {
            if (!empty($setting['limit_access_user'])) {
                if (!empty($setting['access_user']) && in_array($this->user->getId(), $setting['access_user'])) {
                    $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5, 10));
                }
            } elseif (!empty($setting['limit_access_user_group'])) {
                if (!empty($setting['access_user_group']) && in_array($this->user->getGroupId(), $setting['access_user_group'])) {
                    $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5, 10));
                }
            } else {
                $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5, 10));
            }
        } else {
            $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5, 10));
        }
    }

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

    public function getRiotTags()
    {
        $this->load->model('extension/module/d_visual_designer');
        $result = array();
        if (count(glob(DIR_TEMPLATE."extension/d_visual_designer/compress/*")) === 0) {
            $this->{'model_extension_module_'.$this->codename}->compressRiotTag();
        }

        $files = glob(DIR_TEMPLATE."extension/d_visual_designer/compress/*.tag", GLOB_BRACE);

        foreach ($files as $file) {
            $result[] = 'view/template/extension/'.$this->codename.'/compress/'.basename($file);
        }

        if (empty($result)) {
            $files = glob(DIR_TEMPLATE."extension/d_visual_designer/{components,elements,popups,layouts,content_blocks,settings_block,layout_blocks}/*.tag", GLOB_BRACE);

            foreach ($files as $file) {
                $result[] = 'view/template/extension/'.$this->codename.'/'.basename(dirname($file)).'/'.basename($file);
            }
        }

        return $result;
    }
}
