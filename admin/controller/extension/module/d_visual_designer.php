<?php
/*
 *	location: admin/controller
 */

class ControllerExtensionModuleDVisualDesigner extends Controller
{
    private $codename = 'd_visual_designer';
    private $route = 'extension/module/d_visual_designer';
    private $extension = '';
    private $config_file = '';
    private $store_id = 0;
    
    private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model($this->route);
        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
    }

    public function index()
    {
        if (!$this->d_shopunity) {
            $this->response->redirect($this->url->link($this->route.'/required', 'codename=d_shopunity&token='.$this->session->data['token'], 'SSL'));
        }
        $this->load->model('extension/d_shopunity/mbooth');

        $this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);

        $this->load->model('extension/d_shopunity/ocmod');
        
        $twig_support = $this->model_extension_d_shopunity_ocmod->getModificationByName('d_twig_manager');

        if(!$twig_support){
            $this->load->model('extension/module/d_twig_manager');
            $this->model_extension_module_d_twig_manager->installCompatibility();
        }

        $this->load->controller('extension/d_visual_designer/setting');
    }

    public function required()
    {
        $this->load->language($this->route);
        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_not_found'] = $this->language->get('text_not_found');
        $data['breadcrumbs'] = array();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('error/not_found', $data));
    }

    public function install()
    {
        if ($this->d_shopunity) {
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
        }

        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename);
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'modify', 'extension/'.$this->codename);
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename.'/designer');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'modify', 'extension/'.$this->codename.'/designer');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename.'/setting');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'modify', 'extension/'.$this->codename.'/setting');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename.'/template');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'modify', 'extension/'.$this->codename.'/template');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename.'/instruction');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'modify', 'extension/'.$this->codename.'/instruction');

        $this->{'model_extension_module_'.$this->codename}->createDatabase();

        $this->{'model_extension_module_'.$this->codename}->increaseFields();
    }

    public function uninstall()
    {
        $this->{'model_extension_module_'.$this->codename}->dropDatabase();
    }
}
