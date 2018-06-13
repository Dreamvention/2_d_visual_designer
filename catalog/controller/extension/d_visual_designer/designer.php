<?php

class ControllerExtensionDVisualDesignerDesigner extends Controller
{
    private $codename = 'd_visual_designer';

    private $route = 'extension/d_visual_designer/designer';

    private $theme = 'default';

    private $store_url = '';
    private $scripts = array();
    private $error = array();
    private $styles = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
        $this->load->model('extension/module/' . $this->codename);

        $this->theme = $this->config->get('config_template');

        if ($this->request->server['HTTPS']) {
            $this->store_url = HTTPS_SERVER;
        } else {
            $this->store_url = HTTP_SERVER;
        }
    }

    public function index($setting)
    {
        $this->load->model('extension/d_opencart_patch/load');

        $url_token = '';
        if (isset($this->session->data['user_token']) || isset($this->session->data['token'])) {
            if (VERSION >= '3.0.0.0') {
                $url_token = 'user_token=' . $this->session->data['user_token'];
            } else {
                $url_token = 'token=' . $this->session->data['token'];
            }
        }

        $this->load->model('setting/setting');
        $setting_module = $this->model_setting_setting->getSetting($this->codename);

        if (!empty($setting_module[$this->codename . '_setting'])) {
            $setting_module = $setting_module[$this->codename . '_setting'];
        } else {
            $setting_module = $this->config->get($this->codename . '_setting');
        }

        $data['riot_tags'] = $this->{'model_extension_module_' . $this->codename}->getRiotTags($setting_module['compress_files']);

        $result = $this->{'model_extension_module_' . $this->codename}->getContent($setting['config'], $setting['id'], $setting['field_name']);

        if (!empty($result)) {
            $content = $result['content'];
        } else {
            $content = $setting['content'];
        }
        
        $this->scripts[] = 'catalog/view/javascript/d_riot/riotcompiler.min.js';
        $this->scripts[] = 'catalog/view/javascript/d_visual_designer/dist/vd-basic-libraries.min.js';
        $this->styles[]  = 'catalog/view/javascript/d_visual_designer/dist/vd-basic-libraries.min.css';

        $data['designer_id'] = substr(md5(rand()), 0, 7);

        $blocks_setting = $this->{'model_extension_module_' . $this->codename}->parseContent($content);

        $data['state']['blocks'] = array($data['designer_id'] => $blocks_setting);

        $data['state']['config'] = array();

        $data['state']['config']['blocks']     = $this->prepareBlocksConfig();
        $data['state']['config']['route']      = array($data['designer_id'] => $setting['config']);
        $data['state']['config']['id']         = array($data['designer_id'] => $setting['id']);
        $data['state']['config']['field_name'] = array($data['designer_id'] => $setting['field_name']);

        $this->addScript('javascript/d_visual_designer/main.js');
        $this->addScript('javascript/d_visual_designer/core/block.js');
        $this->addScript('javascript/d_visual_designer/core/component.js');
        $this->addScript('javascript/d_visual_designer/model/style.js');

        if ($this->{'model_extension_module_' . $this->codename}->validateEdit($setting['config'])) {
            $this->addStyle('stylesheet/d_visual_designer/d_visual_designer.css');
            $this->addScript('javascript/d_visual_designer/model/block.js');
            $this->addScript('javascript/d_visual_designer/model/content.js');
            $this->addScript('javascript/d_visual_designer/model/template.js');
            $this->addScript('javascript/d_visual_designer/model/history.js');

            $this->scripts[] = 'catalog/view/javascript/d_visual_designer/dist/vd-secondary-libraries.min.js';
            $this->styles[]  = 'catalog/view/javascript/d_visual_designer/dist/vd-secondary-libraries.min.css';
            $this->load->model('setting/setting');
            $setting_module = $this->model_setting_setting->getSetting($this->codename);

            if (!empty($setting_module[$this->codename . '_setting'])) {
                $setting_module = $setting_module[$this->codename . '_setting'];
            } else {
                $setting_module = $this->config->get($this->codename . '_setting');
            }

            $data['state']['config']['save_change'] = $setting_module['save_change'];

            $data['state']['history'] = array($data['designer_id'] => array());

            $data['state']['config']['permission'] = array($data['designer_id'] => true);
            $data['state']['config']['notify']     = $this->{'model_extension_module_' . $this->codename}->checkCompleteVersion();

            $data['state']['templates'] = $this->prepareTemplate();

            $data['local']   = $this->prepareLocal(true);
            $data['options'] = $this->prepareOptions(true);
            $this->prepareScripts(true);
            $this->prepareStyles(true);

            $data['state']['config']['filemanager_url'] = $this->store_url . 'index.php?route=extension/' . $this->codename . '/filemanager&' . $url_token;

            $data['base'] = $this->store_url . 'catalog/view/theme/default/';

            if ($setting['header']) {
                $output = $this->parseHeader($setting['header']);

                if($output) {
                    $setting['header'] = $output;
                }
            } else {
                $this->parseHeader();
            }

            return $this->model_extension_d_opencart_patch_load->view($this->route, $data);
        } elseif ($this->{'model_extension_module_' . $this->codename}->validateEdit($setting['config'], false) && !empty($setting['id'])) {
            $this->addStyle('stylesheet/d_visual_designer/frontend.css');

            $data['edit_url'] = $this->store_url . 'admin/index.php?route=extension/d_visual_designer/designer/frontend&' . $url_token . '&config=' . $setting['config'] . '&id=' . $setting['id'];

            $data['text_edit'] = $this->language->get('text_edit');

            $data['local']   = $this->prepareLocal(false);
            $data['options'] = $this->prepareOptions(false);
            $this->prepareScripts(false);
            $this->prepareStyles(false);

            $data['content'] = $this->{'model_extension_module_' . $this->codename}->preRender($blocks_setting);

            $data['state']['config']['permission'] = array($data['designer_id'] => false);


            if ($setting['header']) {
                $output = $this->parseHeader($setting['header']);

                if($output) {
                    $setting['header'] = $output;
                }
            } else {
                $this->parseHeader();
            }

            return $this->model_extension_d_opencart_patch_load->view('extension/' . $this->codename . '/frontend', $data);
        } else {
            $this->addStyle('stylesheet/d_visual_designer/frontend.css');

            $data['local']   = $this->prepareLocal(false);
            $data['options'] = $this->prepareOptions(false);
            $this->prepareScripts(false);
            $this->prepareStyles(false);

            $data['content'] = $this->{'model_extension_module_' . $this->codename}->preRender($blocks_setting);

            $data['state']['config']['permission'] = array($data['designer_id'] => false);

            if ($setting['header']) {
                $output = $this->parseHeader($setting['header']);

                if($output) {
                    $setting['header'] = $output;
                }
            } else {
                $this->parseHeader();
            }

            return $this->model_extension_d_opencart_patch_load->view('extension/' . $this->codename . '/frontend', $data);
        }
    }

    public function frontend() {
        $this->load->model('extension/d_opencart_patch/load');

        if(isset($this->request->get['id'])){
            $id = $this->request->get['id'];
        }

        if(isset($this->request->get['config'])){
            $config = $this->request->get['config'];
        }

        
        if(isset($this->request->get['field_name'])){
            $field_name = $this->request->get['field_name'];
        }

        if(isset($id) && isset($config) && isset($field_name)) {
            $designer_data = array(
                'config' => $config,
                'content' => '',
                'header' => false,
                'field_name' => $field_name,
                'id' => $id
                );

            $data['content'] = $this->index($designer_data);

            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('extension/'.$this->codename.'/content', $data));
        } else {
            $data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('extension/'.$this->codename.'/designer/frontend')
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $this->response->setOutput($this->model_extension_d_opencart_patch_load->view('error/not_found', $data));
        }
    }

    protected function parseHeader($header = false)
    {
        if ($header) {
            $html_dom = new d_simple_html_dom();
            $html_dom->load($header, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

            foreach ($this->scripts as $script) {
                if (!$html_dom->find('head', 0)->find('script[src="' . $script . '"]')) {
                    $html_dom->find('head', 0)->innertext .= '<script src="' . $script . '" type="text/javascript"></script>';
                }
            }

            foreach ($this->styles as $style) {
                if (!$html_dom->find('head', 0)->find('link[href="' . $style . '"]')) {
                    $html_dom->find('head', 0)->innertext .= '<link href="' . $style . '" rel="stylesheet" type="text/css"></script>';
                }
            }
            return (string)$html_dom;
        } else {
            foreach ($this->scripts as $script) {
                $this->document->addScript($script);
            }

            foreach ($this->styles as $style) {
                $this->document->addStyle($style);
            }
        }

        return false;
    }

    protected function prepareScripts($permission = false)
    {
        $blocks = $this->{'model_extension_module_' . $this->codename}->getBlocks();

        foreach ($blocks as $block) {
            $output = $this->load->controller('extension/d_visual_designer_module/' . $block . '/scripts', $permission);
            if ($output) {
                $this->scripts = array_merge($this->scripts, $output);
            }
        }
    }

    protected function prepareStyles($permission = false)
    {
        $blocks = $this->{'model_extension_module_' . $this->codename}->getBlocks();

        foreach ($blocks as $block) {
            $output = $this->load->controller('extension/d_visual_designer_module/' . $block . '/styles', $permission);
            if ($output) {
                $this->styles = array_merge($this->styles, $output);
            }
        }
    }

    protected function prepareLocal($permission = false)
    {
        $local                                = array();
        $local['designer']['button_cart']     = $this->language->get('button_cart');
        $local['designer']['button_wishlist'] = $this->language->get('button_wishlist');
        $local['designer']['button_compare']  = $this->language->get('button_compare');

        $local['designer']['text_tax'] = $this->language->get('text_tax');

        if ($permission) {
            $local['designer']['button_add']        = $this->language->get('button_add');
            $local['designer']['button_close']      = $this->language->get('button_close');
            $local['designer']['button_save']       = $this->language->get('button_save');
            $local['designer']['button_saved']      = $this->language->get('button_saved');
            $local['designer']['button_cancel']     = $this->language->get('button_cancel');
            $local['designer']['button_undo']       = $this->language->get('button_undo');
            $local['designer']['button_return']     = $this->language->get('button_return');
            $local['designer']['button_short_save'] = $this->language->get('button_short_save');

            $local['designer']['text_add_block']       = $this->language->get('text_add_block');
            $local['designer']['text_edit_block']      = $this->language->get('text_edit_block');
            $local['designer']['text_add_template']    = $this->language->get('text_add_template');
            $local['designer']['text_classic_mode']    = $this->language->get('text_classic_mode');
            $local['designer']['text_backend_editor']  = $this->language->get('text_backend_editor');
            $local['designer']['text_frontend_editor'] = $this->language->get('text_frontend_editor');
            $local['designer']['text_welcome_header']  = $this->language->get('text_welcome_header');
            $local['designer']['text_add_block']       = $this->language->get('text_add_block');
            $local['designer']['text_add_text_block']  = $this->language->get('text_add_text_block');
            $local['designer']['text_add_template']    = $this->language->get('text_add_template');
            $local['designer']['text_save_template']   = $this->language->get('text_save_template');
            $local['designer']['text_search']          = $this->language->get('text_search');
            $local['designer']['text_layout']          = $this->language->get('text_layout');
            $local['designer']['entry_size']           = $this->language->get('entry_size');
            $local['designer']['text_set_custom']      = $this->language->get('text_set_custom');

            $local['designer']['text_yes']     = $this->language->get('text_yes');
            $local['designer']['text_no']      = $this->language->get('text_no');
            $local['designer']['text_enabled'] = $this->language->get('text_enabled');

            $local['designer']['text_left']   = $this->language->get('text_left');
            $local['designer']['text_right']  = $this->language->get('text_right');
            $local['designer']['text_top']    = $this->language->get('text_top');
            $local['designer']['text_bottom'] = $this->language->get('text_bottom');

            $local['designer']['text_phone']   = $this->language->get('text_phone');
            $local['designer']['text_tablet']  = $this->language->get('text_tablet');
            $local['designer']['text_desktop'] = $this->language->get('text_desktop');

            $local['designer']['text_horizontal'] = $this->language->get('text_horizontal');
            $local['designer']['text_vertical']   = $this->language->get('text_vertical');

            $local['designer']['text_complete_version']          = $this->language->get('text_complete_version');
            $local['designer']['text_complete_version_template'] = $this->language->get('text_complete_version_template');

            $local['designer']['entry_border_color']           = $this->language->get('entry_border_color');
            $local['designer']['entry_border_style']           = $this->language->get('entry_border_style');
            $local['designer']['entry_border_radius']          = $this->language->get('entry_border_radius');
            $local['designer']['entry_animate']                = $this->language->get('entry_animate');
            $local['designer']['entry_show_on']                = $this->language->get('entry_show_on');
            $local['designer']['entry_background']             = $this->language->get('entry_background');
            $local['designer']['entry_image']                  = $this->language->get('entry_image');
            $local['designer']['entry_id']                     = $this->language->get('entry_id');
            $local['designer']['entry_additional_css_class']   = $this->language->get('entry_additional_css_class');
            $local['designer']['entry_additional_css_before']  = $this->language->get('entry_additional_css_before');
            $local['designer']['entry_additional_css_content'] = $this->language->get('entry_additional_css_content');
            $local['designer']['entry_additional_css_after']   = $this->language->get('entry_additional_css_after');
            $local['designer']['entry_margin']                 = $this->language->get('entry_margin');
            $local['designer']['entry_padding']                = $this->language->get('entry_padding');
            $local['designer']['entry_border']                 = $this->language->get('entry_border');
            $local['designer']['entry_name']                   = $this->language->get('entry_name');
            $local['designer']['entry_image_style']            = $this->language->get('entry_image_style');
            $local['designer']['entry_image_position']         = $this->language->get('entry_image_position');
            $local['designer']['entry_category']               = $this->language->get('entry_category');
            $local['designer']['entry_image_template']         = $this->language->get('entry_image_template');
            $local['designer']['entry_sort_order']             = $this->language->get('entry_sort_order');

            $local['designer']['tab_general']          = $this->language->get('tab_general');
            $local['designer']['tab_design']           = $this->language->get('tab_design');
            $local['designer']['tab_css']              = $this->language->get('tab_css');
            $local['designer']['tab_save_block']       = $this->language->get('tab_save_block');
            $local['designer']['tab_templates']        = $this->language->get('tab_templates');
            $local['designer']['tab_all_blocks']       = $this->language->get('tab_all_blocks');
            $local['designer']['tab_content_blocks']   = $this->language->get('tab_content_blocks');
            $local['designer']['tab_social_blocks']    = $this->language->get('tab_social_blocks');
            $local['designer']['tab_structure_blocks'] = $this->language->get('tab_structure_blocks');

            $local['designer']['error_size'] = $this->language->get('error_size');
        }

        $blocks = $this->{'model_extension_module_' . $this->codename}->getBlocks();

        $local['blocks'] = array();

        foreach ($blocks as $block) {
            $local['blocks'][$block] = $this->load->controller('extension/d_visual_designer_module/' . $block . '/local', $permission);
        }

        return $local;
    }

    protected function prepareOptions($permission = false)
    {
        $options = array();
        if ($permission) {
            $this->load->model('tool/image');

            $options['designer']['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

            $options['designer']['image_horizontal_positions'] = array(
                'left' => $this->language->get('text_position_left'),
                'center' => $this->language->get('text_position_center'),
                'right' => $this->language->get('text_position_right')
            );

            $options['designer']['image_vertical_positions'] = array(
                'top' => $this->language->get('text_position_top'),
                'center' => $this->language->get('text_position_center'),
                'bottom' => $this->language->get('text_position_bottom')
            );

            $options['designer']['border_styles'] = array(
                'dotted' => $this->language->get('text_dotted'),
                'dashed' => $this->language->get('text_dashed'),
                'solid' => $this->language->get('text_solid'),
                'double' => $this->language->get('text_double'),
                'groove' => $this->language->get('text_groove'),
                'ridge' => $this->language->get('text_ridge'),
                'inset' => $this->language->get('text_inset'),
                'outset' => $this->language->get('text_outset')
            );

            $options['designer']['image_styles'] = array(
                'cover' => $this->language->get('text_cover'),
                'contain' => $this->language->get('text_contain'),
                'no-repeat' => $this->language->get('text_no_repeat'),
                'repeat' => $this->language->get('text_repeat'),
                'parallax' => $this->language->get('text_parallax')
            );

            $options['designer']['animates'] = array(
                '' => $this->language->get('text_none'),
                'fadeInDown' => $this->language->get('text_fade_in_down'),
                'fadeInUp' => $this->language->get('text_fade_in_up'),
                'fadeInLeft' => $this->language->get('text_fade_in_left'),
                'fadeInRight' => $this->language->get('text_fade_in_right'),
                'fadeIn' => $this->language->get('text_fade_in'),
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
                'bounce_jump' => $this->language->get('text_bounce_jump'),
            );

            $options['designer']['libraries'] = array(
                'fontawesome' => 'Font Awesome',
                'glyphicon' => 'Glyphicons',
                'ionicons' => 'Open Ionic',
                'mapicons' => 'Map Icons',
                'material' => 'Material Design Iconic Font',
                'typeicon' => 'Typeicons',
                'elusive' => 'Elusive Icons',
                'octicon' => 'Octicons',
                'weather' => 'Weather Icons'
            );

            $iconSets = $this->{'model_extension_module_' . $this->codename}->getIconSets();

            foreach ($iconSets as $value) {
                $options['designer']['libraries'][$value] = $value;
                $this->scripts[]                          = 'catalog/view/javascript/' . $this->codename . "/iconset/" . $value . '.js';
            }
        }

        $blocks = $this->{'model_extension_module_' . $this->codename}->getBlocks();

        $options['blocks'] = array();

        foreach ($blocks as $block) {
            $options['blocks'][$block] = $this->load->controller('extension/d_visual_designer_module/' . $block . '/options', $permission);
        }


        return $options;
    }

    public function prepareBlocksConfig()
    {
        $blocks = array();

        $this->load->model('tool/image');

        $results = $this->{'model_extension_module_' . $this->codename}->getBlocks();

        foreach ($results as $block) {
            $this->load->language('extension/' . $this->codename . '_module/' . $block);

            $setting = $this->{'model_extension_module_' . $this->codename}->getSettingBlock($block);

            $setting_default = $this->{'model_extension_module_' . $this->codename}->getSetting($setting['setting'], $block);

            if (is_file(DIR_IMAGE . 'catalog/d_visual_designer/' . $block . '.svg')) {
                $image = $this->store_url . 'image/catalog/d_visual_designer/' . $block . '.svg';
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }
            if ($setting['display']) {
                $blocks[$block] = array(
                    'title' => $this->language->get('text_title'),
                    'description' => $this->language->get('text_description'),
                    'image' => $image,
                    'type' => $block,
                    'setting_default' => $setting_default,
                    'category' => ucfirst($setting['category']),
                    'sort_order' => isset($setting['sort_order']) ? $setting['sort_order'] : 0,
                    'setting' => $setting
                );
            }
        }
        usort($blocks, 'ControllerExtensionDVisualDesignerDesigner::sort_block');

        return $blocks;
    }

    public function prepareTemplate()
    {
        $templates = array();

        $this->load->model('tool/image');

        $results = $this->{'model_extension_module_' . $this->codename}->getTemplates();

        foreach ($results as $template) {
            if ($template['image'] && file_exists(DIR_IMAGE . $template['image'])) {
                $image = $this->model_tool_image->resize($template['image'], 160, 205);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 160, 205);
            }
            $templates[] = array(
                'template_id' => $template['template_id'],
                'config' => $template['config'],
                'image' => $image,
                'category' => ucfirst($template['category']),
                'name' => html_entity_decode($template['name'], ENT_QUOTES, "UTF-8")
            );
        }

        return $templates;
    }

    public function updateSetting()
    {
        $json = array();

        if (isset($this->request->post['setting'])) {
            $setting = json_decode(html_entity_decode($this->request->post['setting'], ENT_QUOTES, 'UTF-8'), true);
        }

        if (isset($this->request->post['type'])) {
            $type = $this->request->post['type'];
        }

        if (isset($setting) && isset($type)) {
            $json['setting'] = $this->{'model_extension_module_' . $this->codename}->getSetting($setting['global'], $type);
            $json['success'] = 'success';
        } else {
            $json['error'] = 'error';
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json, JSON_FORCE_OBJECT));
    }

    public function save()
    {
        $json = array();

        if (isset($this->request->post['setting'])) {
            $setting = json_decode(html_entity_decode($this->request->post['setting'], ENT_QUOTES, 'UTF-8'), true);
        }
        if (isset($this->request->post['id'])) {
            $id = $this->request->post['id'];
        }
        if (isset($this->request->post['route'])) {
            $route = $this->request->post['route'];
        }
        if (isset($this->request->post['field_name'])) {
            $field_name = $this->request->post['field_name'];
        }

        if (isset($setting) && !empty($route) && isset($id) && !empty($field_name)) {
            $content = $this->{'model_extension_module_' . $this->codename}->parseSetting($setting);

            $content_text = $this->{'model_extension_module_' . $this->codename}->getText($setting);

            $route_info = $this->{'model_extension_module_' . $this->codename}->getRoute($route);

            $this->{'model_extension_module_' . $this->codename}->saveContent($content, $route, $id, $field_name);


            $this->load->model('setting/setting');
            $setting_module = $this->model_setting_setting->getSetting($this->codename);

            if (!empty($setting_module[$this->codename . '_setting'])) {
                $setting_module = $setting_module[$this->codename . '_setting'];
            } else {
                $setting_module = $this->config->get($this->codename . '_setting');
            }

            if ($setting_module['save_text']) {
                $name = '';

                parse_str($field_name . '=' . $content_text, $name);

                $saveData = array(
                    'content' => $name,
                    'id' => $id
                );

                $result = $this->load->controller($route_info['edit_route'], $saveData);
            }

            if (($setting_module['save_text'] && $result) || !$setting_module['save_text']) {
                $json['success'] = 'success';
            } else {
                $json['error'] = 'error';
            }
        } else {
            $json['error'] = 'error';
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function saveTemplate()
    {
        $this->load->model('setting/setting');

        $json = array();

        if (isset($this->request->post['setting'])) {
            $setting = json_decode(html_entity_decode($this->request->post['setting'], ENT_QUOTES, 'UTF-8'), true);
        }

        if (isset($this->request->post['name'])) {
            $name = $this->request->post['name'];
        }

        if (isset($this->request->post['image'])) {
            $image = $this->request->post['image'];
        }

        if (isset($this->request->post['category'])) {
            $category = $this->request->post['category'];
        }

        if (isset($this->request->post['sort_order'])) {
            $sort_order = $this->request->post['sort_order'];
        }

        if ($this->validateTemplateForm()) {
            $content       = $this->{'model_extension_module_' . $this->codename}->parseSetting($setting);
            $template_info = array(
                'name' => $name,
                'image' => $image,
                'category' => $category,
                'content' => $content,
                'sort_order' => $sort_order
            );

            $this->{'model_extension_module_' . $this->codename}->addTemplate($template_info);
            $json['templates'] = $this->prepareTemplate();
            $json['success']   = 'success';
        } else {
            $json['error'] = $this->error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function sort_block($a, $b)
    {
        if ($a['sort_order'] == $b['sort_order']) {
            return 0;
        }
        return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
    }

    public function getTemplate()
    {
        $json = array();

        if (isset($this->request->post['template_id'])) {
            $template_id = $this->request->post['template_id'];
        }
        if (isset($this->request->post['config'])) {
            $config = $this->request->post['config'];
        }
        if (isset($template_id) && isset($config)) {
            if (!empty($config)) {
                $template_info = $this->model_extension_module_d_visual_designer->getConfigTemplate($template_id, $config);
            } else {
                $template_info = $this->model_extension_module_d_visual_designer->getTemplate($template_id);
            }


            if (!empty($template_info)) {
                $json['setting'] = $this->{'model_extension_module_' . $this->codename}->parseContent($template_info['content']);;
                $json['success'] = 'success';
            } else {
                $json['errorr'] = 'error';
            }
        } else {
            $json['errorr'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function saveProduct($setting)
    {
        $result = false;

        $this->load->model('extension/d_opencart_patch/user');

        if (!$this->model_extension_d_opencart_patch_user->hasPermission('modify', 'catalog/product')) {
            $permission = false;
        } else {
            $permission = true;
        }

        if (!empty($setting['content']['product_description']) && !empty($setting['id']) && $permission) {
            $this->{'model_extension_module_' . $this->codename}->editProduct($setting['id'], $setting['content']);
            $result = true;
        }
        return $result;
    }

    public function saveCategory($setting)
    {
        $result = false;

        $this->load->model('extension/d_opencart_patch/user');

        if (!$this->model_extension_d_opencart_patch_user->hasPermission('modify', 'catalog/category')) {
            $permission = false;
        } else {
            $permission = true;
        }

        if (!empty($setting['content']['category_description']) && !empty($setting['id']) && $permission) {
            $this->{'model_extension_module_' . $this->codename}->editCaregory($setting['id'], $setting['content']);

            $result = true;
        }

        return $result;
    }

    public function saveInformation($setting)
    {
        $result = false;

        $this->load->model('extension/d_opencart_patch/user');

        if (!$this->model_extension_d_opencart_patch_user->hasPermission('modify', 'catalog/information')) {
            $permission = false;
        } else {
            $permission = true;
        }

        if (isset($setting['content']['information_description']) && !empty($setting['id']) && $permission) {
            $this->{'model_extension_module_' . $this->codename}->editInformation($setting['id'], $setting['content']);

            $result = true;
        }

        return $result;
    }

    protected function validateTemplateForm()
    {
        if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
            $this->error['name'] = $this->language->get('error_template_name');
        }

        return !$this->error;
    }

    protected function addStyle($file)
    {
        if (file_exists(DIR_TEMPLATE . $this->theme . $file)) {
            $this->styles[] = 'catalog/view/theme/' . $this->theme . '/' . $file;
        } else {
            $this->styles[] = 'catalog/view/theme/default/' . $file;
        }
    }

    protected function addScript($file)
    {
        if (file_exists(DIR_TEMPLATE . $this->theme . $file)) {
            $this->scripts[] = 'catalog/view/theme/' . $this->theme . '/' . $file;
        } else {
            $this->scripts[] = 'catalog/view/theme/default/' . $file;
        }
    }
}
