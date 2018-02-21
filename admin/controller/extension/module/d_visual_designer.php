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
        $this->d_opencart_patch = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_opencart_patch.json'));
        $this->d_twig_manager = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_twig_manager.json'));
        $this->d_event_manager = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_event_manager.json'));
        
    }

    public function index()
    {
        if($this->d_twig_manager){
            $this->load->model('extension/module/d_twig_manager');
            $this->model_extension_module_d_twig_manager->installCompatibility();
        }

        if ($this->d_event_manager) {
            $this->load->model('extension/module/d_event_manager');
            $this->model_extension_module_d_event_manager->installCompatibility();
        }

        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
        }

        $this->load->controller('extension/d_visual_designer/setting');
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

    }

    public function uninstall()
    {
        $this->{'model_extension_module_'.$this->codename}->dropDatabase();
    }
}
