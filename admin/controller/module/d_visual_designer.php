
<?php
/*
 *  location: admin/controller
 */

class ControllerModuleDVisualDesigner extends Controller {
    private $codename = 'd_visual_designer';
    private $route = 'module/d_visual_designer';
    private $extension = '';
    private $config_file = '';
    private $store_id = 0;
    private $d_shopunity = 0;
    
    private $error = array();

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model($this->route);

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'mbooth/extension/d_shopunity.json'));
    }

    public function index(){

        if(!$this->d_shopunity){
            $this->response->redirect($this->url->link($this->route.'/required', 'codename=d_shopunity&token='.$this->session->data['token'], 'SSL'));
        }

        $this->load->model('d_shopunity/mbooth');

        $this->model_d_shopunity_mbooth->validateDependencies($this->codename);
        $this->load->controller('d_visual_designer/setting');
    }

    public function required(){
        $this->load->language($this->route);
        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_not_found'] = $this->language->get('text_not_found');
        $data['breadcrumbs'] = array();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('error/not_found.tpl', $data));
    }

    public function install() {
        if($this->d_shopunity){
            $this->load->model('d_shopunity/mbooth');
            $this->model_d_shopunity_mbooth->installDependencies($this->codename);
        }
        
        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'access', $this->codename.'/designer');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'modify', $this->codename.'/designer');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'access', $this->codename.'/setting');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'modify', $this->codename.'/setting');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'access', $this->codename.'/template');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'modify', $this->codename.'/template');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'access', $this->codename.'/instruction');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'modify', $this->codename.'/instruction');

        $this->{'model_module_'.$this->codename}->createDatabase();

        $this->{'model_module_'.$this->codename}->increaseFields();
    }

    public function uninstall() {

        $this->{'model_module_'.$this->codename}->dropDatabase();
    }
}
?>
