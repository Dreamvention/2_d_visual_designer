<?php
class ControllerExtensionDVisualDesignerInstruction extends Controller {
    public $codename = 'd_visual_designer';
    public $route = 'extension/d_visual_designer/instruction';
    public $extension = '';
    private $error = array();
    private $input = array();

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language($this->route);
        $this->load->language('extension/module/d_visual_designer');
        $this->load->model('extension/module/d_visual_designer');

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/d_visual_designer.json'), true);
        
        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;
    }

    public function index(){

        $this->document->setTitle($this->language->get('heading_title_main'));
        $this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

        $this->document->addStyle('view/stylesheet/d_bootstrap_extra/bootstrap.css');

        $this->load->model('setting/setting');
        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/load');
        $this->load->model('extension/d_opencart_patch/user');


        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['version'] = $this->extension['version'];
        $data['route'] = $this->route;
        $data['token'] =  $this->model_extension_d_opencart_patch_user->getToken();

        $data['text_templates'] = $this->language->get('text_templates');
        $data['text_routes'] = $this->language->get('text_routes');
        $data['text_setting'] = $this->language->get('text_setting');
        $data['text_instructions'] = $this->language->get('text_instructions');
        $data['text_instruction_full'] = $this->language->get('text_instruction_full');

        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->model_extension_d_opencart_patch_url->link('common/home'),
            'separator' => false
            );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->model_extension_d_opencart_patch_url->link('marketplace/extension','type=module'),
            'separator' => ' :: '
            );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title_main'),
            'href'      => $this->model_extension_d_opencart_patch_url->link('extension/module/d_visual_designer'),
            'separator' => ' :: '
            );

        $data['cancel'] = $this->model_extension_d_opencart_patch_url->link('marketplace/extension', 'type=module');

        $data['href_templates'] = $this->model_extension_d_opencart_patch_url->link('extension/'.$this->codename.'/template');
        $data['href_routes'] = $this->model_extension_d_opencart_patch_url->link('extension/'.$this->codename.'/route');
        $data['href_setting'] = $this->model_extension_d_opencart_patch_url->link('extension/'.$this->codename.'/setting');
        $data['href_instruction'] = $this->model_extension_d_opencart_patch_url->link('extension/'.$this->codename.'/instruction');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view($this->route, $data));
    }
}
