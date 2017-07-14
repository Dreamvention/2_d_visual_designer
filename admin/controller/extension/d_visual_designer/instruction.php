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

        $this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');

        $this->load->model('setting/setting');


        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['version'] = $this->extension['version'];
        $data['route'] = $this->route;
        $data['token'] =  $this->session->data['token'];

        $data['text_templates'] = $this->language->get('text_templates');
        $data['text_routes'] = $this->language->get('text_routes');
        $data['text_setting'] = $this->language->get('text_setting');
        $data['text_instructions'] = $this->language->get('text_instructions');
        $data['text_instruction_full'] = $this->language->get('text_instruction_full');

        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
            );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
            );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title_main'),
            'href'      => $this->url->link('extension/module/d_visual_designer', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
            );
        
        if(VERSION>='2.3.0.0'){
            $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token']."&type=module", 'SSL');
        }
        else{
            $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        }
        
        $data['href_templates'] = $this->url->link('extension/'.$this->codename.'/template','token='.$this->session->data['token'], 'SSL');
        $data['href_routes'] = $this->url->link('extension/'.$this->codename.'/route','token='.$this->session->data['token'], 'SSL');
        $data['href_setting'] = $this->url->link('extension/'.$this->codename.'/setting','token='.$this->session->data['token'], 'SSL');
        $data['href_instruction'] = $this->url->link('extension/'.$this->codename.'/instruction','token='.$this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view($this->route.'.tpl', $data));
    }
}
