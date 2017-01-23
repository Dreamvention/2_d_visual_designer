<?php
class ControllerDVisualDesignerRoute extends Controller {
    public $codename = 'd_visual_designer';
    public $route = 'd_visual_designer/route';
    public $extension = '';
    private $error = array();
    private $input = array();

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language($this->route);
        $this->load->language('module/d_visual_designer');
        $this->load->model($this->route);
        $this->load->model('module/d_visual_designer');

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'mbooth/extension/d_shopunity.json'));
		if($this->d_shopunity){
			$this->load->model('d_shopunity/mbooth');
			$this->extension = $this->model_d_shopunity_mbooth->getExtension($this->codename);
		}
		$this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;
    }

    public function index(){
        $this->getList();
    }

    public function add() {

		$this->document->setTitle($this->language->get('heading_title_main'));
		$this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm(true)) {

			$this->{'model_'.$this->codename.'_route'}->addRoute($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('d_visual_designer/route', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

    public function edit() {

		$this->document->setTitle($this->language->get('heading_title_main'));
		$this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->{'model_'.$this->codename.'_route'}->editRoute($this->request->get['route_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('d_visual_designer/route', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {

		$this->document->setTitle($this->language->get('heading_title_main'));
		$this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $route_id) {
				$this->{'model_'.$this->codename.'_route'}->deleteRoute($route_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('d_visual_designer/route', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

    public function getList() {

		$this->document->setTitle($this->language->get('heading_title_main'));
		$this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

		$this->load->model('setting/setting');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

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
			'href'      => $this->url->link('module/d_visual_designer', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);

		$data['add'] = $this->url->link('d_visual_designer/route/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('d_visual_designer/route/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $data['href_templates'] = $this->url->link($this->codename.'/template','token='.$this->session->data['token'], 'SSL');
        $data['href_routes'] = $this->url->link($this->codename.'/route','token='.$this->session->data['token'], 'SSL');
        $data['href_setting'] = $this->url->link($this->codename.'/setting','token='.$this->session->data['token'], 'SSL');
        $data['href_instruction'] = $this->url->link($this->codename.'/instruction','token='.$this->session->data['token'], 'SSL');


		$data['routes'] = array();

		$filter_data = array(
			'sort'				=> $sort,
			'order'				=> $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
			);

		$route_total = $this->{'model_'.$this->codename.'_route'}->getTotalRoutes($filter_data);

		$results = $this->{'model_'.$this->codename.'_route'}->getRoutes($filter_data);

		foreach ($results as $result) {

			$data['routes'][] = array(
				'route_id' => $result['route_id'],
				'name'   => $result['name'],
				'edit'       => $this->url->link('d_visual_designer/route/edit', 'token=' . $this->session->data['token'] . '&route_id=' . $result['route_id'] . $url, 'SSL')
				);
		}

		$data['heading_title'] = $this->language->get('heading_title_main');
		$data['version'] = $this->extension['version'];
		$data['route'] = $this->route;
		$data['token'] =  $this->session->data['token'];

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

        $data['text_templates'] = $this->language->get('text_templates');
        $data['text_routes'] = $this->language->get('text_routes');
        $data['text_setting'] = $this->language->get('text_setting');
        $data['text_instructions'] = $this->language->get('text_instructions');

    	$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('d_visual_designer/route', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

        foreach ($data['languages'] as $key =>  $language){
            if(VERSION >= '2.2.0.0'){
                $data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
            }else{
                $data['languages'][$key]['flag'] = 'view/image/flags/'.$language['image'];
            }
        }

		$this->load->model('setting/store');

		$results = $this->model_setting_store->getStores();
		if($results){
			$data['stores'][] = array('store_id' => 0, 'name' => $this->config->get('config_name'));
			foreach ($results as $result) {
				$data['stores'][] = array(
					'store_id' => $result['store_id'],
					'name' => $result['name']
					);
			}
		} else $data['stores'][] = array('store_id' => 0, 'name' => $this->config->get('config_name'));

		$pagination = new Pagination();
		$pagination->total = $route_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('d_visual_designer/route', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($route_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($route_total - $this->config->get('config_limit_admin'))) ? $route_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $route_total, ceil($route_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('d_visual_designer/route_list.tpl', $data));
	}

    public function getForm() {

		$this->document->setTitle($this->language->get('heading_title_main'));

		$this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

        $this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');

		$this->load->model('setting/setting');

		$data['heading_title'] = $this->language->get('heading_title_main');
		$data['version'] = $this->extension['version'];
		$data['route'] = $this->route;
		$data['token'] = $this->session->data['token'];

		$data['text_form'] = !isset($this->request->get['route_id']) ? $this->language->get('text_add_route') : $this->language->get('text_edit_route');

        $data['text_routes'] = $this->language->get('text_routes');
        $data['text_routes'] = $this->language->get('text_routes');
        $data['text_setting'] = $this->language->get('text_setting');
        $data['text_instructions'] = $this->language->get('text_instructions');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

		$data['entry_name'] = $this->language->get('entry_name');
        $data['entry_key'] = $this->language->get('entry_key');
        $data['entry_backend_route'] = $this->language->get('entry_backend_route');
        $data['entry_frontend_route'] = $this->language->get('entry_frontend_route');
        $data['entry_params'] = $this->language->get('entry_params');
        $data['entry_edit_url'] = $this->language->get('entry_edit_url');
        $data['entry_frontend_status'] = $this->language->get('entry_frontend_status');
        $data['entry_frontend_param'] = $this->language->get('entry_frontend_param');
        $data['entry_backend_param'] = $this->language->get('entry_backend_param');
        $data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

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
			'href'      => $this->url->link('module/d_visual_designer', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);

		if (!isset($this->request->get['route_id'])) {
			$data['action'] = $this->url->link('d_visual_designer/route/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('d_visual_designer/route/edit', 'token=' . $this->session->data['token'] . '&route_id=' . $this->request->get['route_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('d_visual_designer/route', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (!empty($this->request->get['route_id'])) {
            $route_info = $this->{'model_'.$this->codename.'_route'}->getRoute($this->request->get['route_id']);
        }


		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

        foreach ($data['languages'] as $key =>  $language){
            if(VERSION >= '2.2.0.0'){
                $data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
            }else{
                $data['languages'][$key]['flag'] = 'view/image/flags/'.$language['image'];
            }
        }
        
        if (isset($this->request->post['token'])) {
			$data['token'] = $this->request->post['token'];
		} elseif (!empty($route_info)) {
			$data['token'] = $route_info['token'];
		} else {
			$data['token'] = uniqid();
		}

        if (isset($this->request->post['backend_route'])) {
			$data['backend_route'] = $this->request->post['backend_route'];
		} elseif (!empty($route_info)) {
			$data['backend_route'] = $route_info['backend_route'];
		} else {
			$data['backend_route'] = '';
		}
        
        if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($route_info)) {
			$data['name'] = $route_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['frontend_route'])) {
			$data['frontend_route'] = $this->request->post['frontend_route'];
		} elseif (!empty($route_info)) {
			$data['frontend_route'] = $route_info['frontend_route'];
		} else {
			$data['frontend_route'] = '';
		}

		if (isset($this->request->post['backend_param'])) {
			$data['backend_param'] = $this->request->post['backend_param'];
		} elseif (!empty($route_info)) {
			$data['backend_param'] = $route_info['backend_param'];
		} else {
			$data['backend_param'] = '';
		}

		if (isset($this->request->post['frontend_param'])) {
			$data['frontend_param'] = $this->request->post['frontend_param'];
		} elseif (!empty($route_info)) {
			$data['frontend_param'] = $route_info['frontend_param'];
		} else {
			$data['frontend_param'] = '';
		}

		if (isset($this->request->post['frontend_status'])) {
			$data['frontend_status'] = $this->request->post['frontend_status'];
		} elseif (!empty($route_info)) {
			$data['frontend_status'] = $route_info['frontend_status'];
		} else {
			$data['frontend_status'] = '0';
		}

		if (isset($this->request->post['edit_url'])) {
			$data['edit_url'] = $this->request->post['edit_url'];
		} elseif (!empty($route_info)) {
			$data['edit_url'] = $route_info['edit_url'];
		} else {
			$data['edit_url'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($route_info)) {
			$data['status'] = $route_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('d_visual_designer/route_form.tpl', $data));

	}

    protected function validateForm($new = false) {
        if (!$this->user->hasPermission('modify', 'd_visual_designer/route')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }


        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'd_visual_designer/route')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$this->load->model('d_visual_designer/route');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_email' => $filter_email,
				'start'        => 0,
				'limit'        => 5
				);

			$results = $this->{'model_module_'.$this->codename}->getSubscribers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'subscriber_id'       => $result['subscriber_id'],
					'name'                => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'subscriber_group'    => $result['subscriber_group'],
					'firstname'           => $result['firstname'],
					'lastname'            => $result['lastname'],
					'email'               => $result['email'],
					'subscribed'          => $result['subscribed'],
					'language_id'         => $result['language_id'],
					'store_id'            => $result['store_id']
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
