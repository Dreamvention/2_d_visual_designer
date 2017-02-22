<?php
class ControllerDVisualDesignerSetting extends Controller
{
    private $codename = 'd_visual_designer';
    private $route = 'd_visual_designer/setting';
    private $extension = '';
    private $store_id = 0;
    private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language('module/d_visual_designer');
        $this->load->language($this->route);
        $this->load->model('module/d_visual_designer');

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'mbooth/extension/d_shopunity.json'));
        if ($this->d_shopunity) {
            $this->load->model('d_shopunity/mbooth');
            $this->extension = $this->model_d_shopunity_mbooth->getExtension($this->codename);
        }
        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;
    }

    public function index()
    {
        $this->load->model('setting/setting');
        $this->load->model('d_shopunity/setting');

        //save post
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting($this->codename, $this->request->post, $this->store_id);
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        // styles and scripts
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
        $this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

        $this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');

        $url_params = array();

        if (isset($this->response->get['store_id'])) {
            $url_params['store_id'] = $this->store_id;
        }

        $url = ((!empty($url_params)) ? '&' : '') . http_build_query($url_params);

        // Breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
        );
        
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        // Notification
        foreach ($this->error as $key => $error) {
            $data['error'][$key] = $error;
        }

        // Heading
        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_edit'] = $this->language->get('text_edit');

        // Variable
        $data['id'] = $this->codename;
        $data['route'] = $this->route;
        $data['store_id'] = $this->store_id;
        $data['stores'] = $this->model_d_shopunity_setting->getStores();
        $data['extension'] = $this->extension;

        if (!empty($this->extension['support']['email'])) {
            $data['support_email'] = $this->extension['support']['email'];
        }
        $data['version'] = $this->extension['version'];
        $data['token'] =  $this->session->data['token'];

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_save_change'] = $this->language->get('entry_save_change');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_delete'] = $this->language->get('button_delete');

        // Button
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_remove'] = $this->language->get('button_remove');
   
        // Entry
        $data['entry_status'] = $this->language->get('entry_status');
    
        // Text
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
    
        //column
        $data['column_action'] = $this->language->get('column_action');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_route'] = $this->language->get('column_route');
        $data['column_frontend_route'] = $this->language->get('column_frontend_route');
        $data['column_params'] = $this->language->get('column_params');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_text'] = $this->language->get('column_text');

        $data['tab_routes'] = $this->language->get('tab_routes');
        $data['tab_templates'] = $this->language->get('tab_templates');
        
        //action
        $data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL');
        
        $data['action'] = $this->url->link($this->codename.'/setting', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');


        $data['tab_setting'] = $this->language->get('tab_setting');
        //support
        $data['tab_support'] = $this->language->get('tab_support');
        $data['text_support'] = $this->language->get('text_support');
        $data['entry_support'] = $this->language->get('entry_support');
        $data['button_support_email'] = $this->language->get('button_support_email');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['text_templates'] = $this->language->get('text_templates');
        $data['text_setting'] = $this->language->get('text_setting');
        $data['text_instructions'] = $this->language->get('text_instructions');

        //instruction
        $data['tab_instruction'] = $this->language->get('tab_instruction');
        $data['text_instruction'] = $this->language->get('text_instruction');


        $data['href_templates'] = $this->url->link($this->codename.'/template', 'token='.$this->session->data['token'], 'SSL');
        $data['href_setting'] = $this->url->link($this->codename.'/setting', 'token='.$this->session->data['token'], 'SSL');
        $data['href_instruction'] = $this->url->link($this->codename.'/instruction', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->request->post[$this->codename.'_status'])) {
            $data[$this->codename.'_status'] = $this->request->post[$this->codename.'_status'];
        } else {
            $data[$this->codename.'_status'] = $this->config->get($this->codename.'_status');
        }
        
        //get setting
        $data['setting'] = $this->model_d_shopunity_setting->getSetting($this->codename);

        $this->load->model('setting/store');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('d_visual_designer/setting.tpl', $data));
    }

    private function validate($permission = 'modify')
    {
        $this->language->load($this->route);

        if (!$this->user->hasPermission($permission, $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        return true;
    }
}
