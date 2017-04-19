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
            $this->uninstallEvents();
            
            if(!empty($this->request->post[$this->codename.'_status'])){
                $this->installEvents($this->request->post[$this->codename.'_setting']['use']);
            }
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
        $data['text_product'] = $this->language->get('text_product');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_information'] = $this->language->get('text_information');
        $data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');
        $data['text_complete_version'] = $this->language->get('text_complete_version');
        
        $data['button_add'] = $this->language->get('button_add');
        $data['button_delete'] = $this->language->get('button_delete');

        $data['help_event_support'] = $this->language->get('help_event_support');
        
        // Button
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_remove'] = $this->language->get('button_remove');
        
        // Entry
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_save_change'] = $this->language->get('entry_save_change');
        $data['entry_use_designer'] = $this->language->get('entry_use_designer');
        $data['entry_access'] = $this->language->get('entry_access');
        $data['entry_limit_access_user'] = $this->language->get('entry_limit_access_user');
        $data['entry_limit_access_user_group'] = $this->language->get('entry_limit_access_user_group');
        $data['entry_user'] = $this->language->get('entry_user');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        
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
        $data['module_link'] = $this->url->link('extension/module/'.$this->codename, 'token=' . $this->session->data['token'], 'SSL');
        
        $data['action'] = $this->url->link($this->codename.'/setting', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        
        $data['text_install_event_support'] = $this->language->get('text_install_event_support');
        $data['install_event_support'] = $this->url->link($this->route.'/install_event_support', 'token=' . $this->session->data['token'], 'SSL');

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
        
        $this->load->model($this->codename.'/designer');

        $data['notify'] = $this->{'model_'.$this->codename.'_designer'}->checkCompleteVersion();

        $data['landing_notify'] = (!file_exists(DIR_SYSTEM.'mbooth/extension/d_visual_designer_landing.json'));
        $data['module_notify'] = (!file_exists(DIR_SYSTEM.'mbooth/extension/d_visual_designer_module.json'));

        if (isset($this->request->post[$this->codename.'_status'])) {
            $data[$this->codename.'_status'] = $this->request->post[$this->codename.'_status'];
        } else {
            $data[$this->codename.'_status'] = $this->config->get($this->codename.'_status');
        }
        
        //get setting
        $data['setting'] = $this->model_d_shopunity_setting->getSetting($this->codename);
        
        $this->load->model('user/user');
        
        $data['users'] = array();
        
        if(!empty($data['setting']['access_user'])){
            foreach ($data['setting']['access_user'] as $user_id) {
                $user_info = $this->model_user_user->getUser($user_id);
                $data['users'][$user_info['user_id']] = $user_info['username'];
            }
        }
        
        $this->load->model('user/user_group');
        
        $data['user_groups'] = array();
        if(!empty($data['setting']['access_user_group'])){
            foreach ($data['setting']['access_user_group'] as $user_group_id) {
                $user_group_info = $this->model_user_user_group->getUserGroup($user_group_id);
                $data['user_groups'][$user_group_id] = $user_group_info['name'];
            }
        }
        $this->load->model($this->codename.'/designer');
        $data['routes'] = array();
        $results = $this->{'model_'.$this->codename.'_designer'}->getRoutes();
        
        foreach ($results as $key => $value) {
            $data['routes'][$key] = $value['name'];
        }
        
        $this->load->model('setting/store');

        $event_support = (file_exists(DIR_SYSTEM.'mbooth/extension/d_event_manager.json'));
        $data['event_support'] = false;
        if($event_support){
            $this->load->model('d_shopunity/ocmod');
            $data['event_support'] = $this->model_d_shopunity_ocmod->getModificationByName('d_event_manager');
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('d_visual_designer/setting.tpl', $data));
    }
    
    public function installEvents($status){
        $this->load->model('module/d_event_manager');
        $this->load->model($this->codename.'/designer');
        foreach ($status as $value) {
            $route_info = $this->{'model_'.$this->codename.'_designer'}->getRoute($value);
            if(!empty($route_info['events'])){
                foreach ($route_info['events'] as $trigger => $action) {
                    $this->model_module_d_event_manager->addEvent($this->codename, $trigger, $action);
                }
            }
        }
        
        $this->model_module_d_event_manager->addEvent($this->codename, 'admin/model/tool/image/resize/before', 'event/'.$this->codename.'/model_imageResize_before');
        $this->model_module_d_event_manager->addEvent($this->codename, 'catalog/model/tool/image/resize/before', 'event/'.$this->codename.'/model_imageResize_before');
        
        
    }
    public function uninstallEvents(){
        $this->load->model('module/d_event_manager');
        $this->model_module_d_event_manager->deleteEvent($this->codename);
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
    
    public function install_event_support(){
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->session->data['error'] = $this->language->get('error_permission');
            $this->response->redirect($this->url->link($this->route, 'token='.$this->session->data['token'], 'SSL'));
        }
        if(file_exists(DIR_SYSTEM.'mbooth/extension/d_event_manager.json')){
            $this->load->model('module/d_event_manager');
            $this->model_module_d_event_manager->installCompatibility();
        }
        $this->response->redirect($this->url->link($this->route, 'token='.$this->session->data['token'], 'SSL'));
    }

    public function autocompleteUser() {
        $json = array();
        
        if (isset($this->request->get['filter_name'])) {
            $this->load->model('user/user');
            
            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start'       => 0,
                'limit'       => 5
                );
            
            $results = $this->model_user_user->getUsers($filter_data);
            
            foreach ($results as $result) {
                $json[] = array(
                    'user_id' => $result['user_id'],
                    'username' => strip_tags(html_entity_decode($result['username'], ENT_QUOTES, 'UTF-8'))
                    );
            }
        }
        
        $sort_order = array();
        
        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['username'];
        }
        
        array_multisort($sort_order, SORT_ASC, $json);
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function autocompleteUserGroup() {
        $json = array();
        
        if (isset($this->request->get['filter_name'])) {
            $this->load->model('user/user_group');
            
            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start'       => 0,
                'limit'       => 5
                );
            
            $results = $this->model_user_user_group->getUserGroups($filter_data);
            
            foreach ($results as $result) {
                $json[] = array(
                    'user_group_id' => $result['user_group_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                    );
            }
        }
        
        $sort_order = array();
        
        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        
        array_multisort($sort_order, SORT_ASC, $json);
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}