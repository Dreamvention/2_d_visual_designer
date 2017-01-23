<?php
class ControllerDVisualDesignerTemplate extends Controller {
    public $codename = 'd_visual_designer';
    public $route = 'd_visual_designer/template';
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

			$this->{'model_'.$this->codename.'_template'}->addTemplate($this->request->post);

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

			$this->response->redirect($this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

    public function edit() {

		$this->document->setTitle($this->language->get('heading_title_main'));
		$this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->{'model_'.$this->codename.'_template'}->editTemplate($this->request->get['template_id'], $this->request->post);

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

			$this->response->redirect($this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {

		$this->document->setTitle($this->language->get('heading_title_main'));
		$this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $template_id) {
				$this->{'model_'.$this->codename.'_template'}->deleteTemplate($template_id);
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

			$this->response->redirect($this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
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

		$data['add'] = $this->url->link($this->route.'/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link($this->route.'/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $data['href_templates'] = $this->url->link($this->codename.'/template','token='.$this->session->data['token'], 'SSL');
        $data['href_routes'] = $this->url->link($this->codename.'/route','token='.$this->session->data['token'], 'SSL');
        $data['href_setting'] = $this->url->link($this->codename.'/setting','token='.$this->session->data['token'], 'SSL');
        $data['href_instruction'] = $this->url->link($this->codename.'/instruction','token='.$this->session->data['token'], 'SSL');


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
		$data['column_sort_order'] = $this->language->get('column_sort_order');
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

        $data['templates'] = array();

		$filter_data = array(
			'sort'				=> $sort,
			'order'				=> $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
			);

		$template_total = $this->{'model_'.$this->codename.'_template'}->getTotalTemplates($filter_data);

		$results = $this->{'model_'.$this->codename.'_template'}->getTemplates($filter_data);

		foreach ($results as $result) {

			$data['templates'][] = array(
				'template_id' => $result['template_id'],
				'name'   => $result['name'],
				'sort_order'   => $result['sort_order'],
				'edit'       => $this->url->link($this->route.'/edit', 'token=' . $this->session->data['token'] . '&template_id=' . $result['template_id'] . $url, 'SSL')
				);
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

		$data['sort_name'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $template_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($template_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($template_total - $this->config->get('config_limit_admin'))) ? $template_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $template_total, ceil($template_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('d_visual_designer/template_list.tpl', $data));

	}

    public function getForm() {

		$this->document->setTitle($this->language->get('heading_title_main'));
		$this->document->addStyle('view/stylesheet/d_visual_designer/menu.css');
        $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js');

        if(VERSION >= '2.2.0.0'){
            $this->document->addStyle('view/javascript/summernote/summernote.css');
            $this->document->addScript('view/javascript/summernote/summernote.min.js');
        }

		$this->load->model('setting/setting');

		$data['heading_title'] = $this->language->get('heading_title_main');
		$data['version'] = $this->extension['version'];
		$data['route'] = $this->route;
		$data['token'] =  $this->session->data['token'];

		$data['text_form'] = !isset($this->request->get['template_id']) ? $this->language->get('text_add_template') : $this->language->get('text_edit_template');

        $data['text_templates'] = $this->language->get('text_templates');
        $data['text_routes'] = $this->language->get('text_routes');
        $data['text_setting'] = $this->language->get('text_setting');
        $data['text_instructions'] = $this->language->get('text_instructions');
        $data['text_file_manager'] = $this->language->get('text_file_manager');

		$data['entry_name'] = $this->language->get('entry_name');
        $data['entry_content'] = $this->language->get('entry_content');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_category'] = $this->language->get('entry_category');
        
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
			$data['error_name'] = array();
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

		if (!isset($this->request->get['template_id'])) {
			$data['action'] = $this->url->link($this->route.'/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link($this->route.'/edit', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (!empty($this->request->get['template_id'])) {
            $template_info = $this->{'model_'.$this->codename.'_template'}->getTemplate($this->request->get['template_id']);
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

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($template_info)) {
			$data['name'] = $template_info['name'];
		} else {
			$data['name'] = '';
		}

        if (isset($this->request->post['category'])) {
			$data['category'] = $this->request->post['category'];
		} elseif (!empty($template_info)) {
			$data['category'] = $template_info['category'];
		} else {
			$data['category'] = '';
		}
        
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($template_info)) {
			$data['image'] = $template_info['image'];
		} else {
			$data['image'] = '';
		}
        
        $this->load->model('tool/image');
        
        if(file_exists(DIR_IMAGE.$data['image'])){
            $data['thumb'] = $this->model_tool_image->resize($data['image'], 100, 100);
        }
        else{
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }
        
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($template_info)) {
			$data['sort_order'] = $template_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->post['content'])) {
			$data['content'] = $this->request->post['content'];
		} elseif (!empty($template_info)) {
			$data['content'] = $template_info['content'];
		} else {
			$data['content'] = '';
		}

        if (isset($this->request->post['template_description'])) {
            $data['template_description'] = $this->request->post['template_description'];
        } elseif (isset($this->request->get['template_id'])) {
            $data['template_description'] = $this->{'model_'.$this->codename.'_template'}->getTemplateDescriptions($this->request->get['template_id']);
        } else {
            $data['template_description'] = array();
        }

		if (isset($this->request->post['store_id'])) {
			$data['store_id'] = $this->request->post['store_id'];
		} elseif (!empty($subscriber_info)) {
			$data['store_id'] = $subscriber_info['store_id'];
		} else {
			$data['store_id'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('d_visual_designer/template_form.tpl', $data));

	}

    protected function validateForm($new = false) {
        if (!$this->user->hasPermission('modify', 'd_visual_designer/template')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        foreach ($this->request->post['template_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function getTemplates(){
        $json = array();

        $templates = $this->model_d_visual_designer_template->getTemplates();

        $json['templates'] = array();
        $json['categories'] = array();

        foreach ($templates as $template) {
            
            $this->load->model('tool/image');
            
            if(file_exists(DIR_IMAGE.$template['image'])){
                $thumb = $this->model_tool_image->resize($template['image'], 156, 171);
            }
            else{
                $thumb = $this->model_tool_image->resize('no_image.png', 156, 171);
            }

            if(!empty($template['category']) && !in_array(ucfirst($template['category']), $json['categories'])){
            	$json['categories'][] = ucfirst($template['category']);
            }
            $json['templates'][] = array(
                'template_id' => $template['template_id'],
                'image' => $thumb,
                'category' => ucfirst($template['category']),
                'name' => html_entity_decode($template['name'], ENT_QUOTES, "UTF-8")
            );
        }

        $json['success'] = 'success';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getTemplate(){
        $json = array();

        if(!empty($this->request->post['template_id'])){
            $template_id = $this->request->post['template_id'];
        }
        if(isset($template_id)){

            $template_info = $this->model_d_visual_designer_template->getTemplate($template_id);

            if(!empty($template_info)){
                $this->load->model('d_visual_designer/designer');

                $result = $this->model_d_visual_designer_designer->parseDescription($template_info['content']);
                $json['content'] = $result['content'];
                $json['setting'] = $result['setting'];
                $json['text'] = $template_info['content'];
                $json['success'] = 'success';
            }
            else{
                $json['errorr'] = 'error';
            }
        }
        else{
            $json['errorr'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function save(){
        $this->load->model('setting/setting');

        $json = array();

        if(isset($this->request->post['content'])){
            $content = $this->request->post['content'];
        }

        if(isset($this->request->post['template_description'])){
            $template_description = $this->request->post['template_description'];
        }

        if(isset($template_description) && isset($content)){
        	$this->{'model_'.$this->codename.'_template'}->addTemplate($template_description+array('content' => $content,'image'=>'', 'sort_order'=>0));
        	$json['success'] = 'success';
        }
        else{
        	$json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$this->load->model('d_visual_designer/template');

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
    public function getFileManager() {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $data['base'] = HTTPS_CATALOG;
        } else {
            $data['base'] = HTTP_CATALOG;
        }
        $this->load->model('module/'.$this->codename);
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'access', 'common/d_elfinder');
        $this->model_user_user_group->addPermission($this->{'model_module_'.$this->codename}->getGroupId(), 'modify', 'common/d_elfinder'); 
        
        $data['route'] = 'common/d_elfinder';
        
        $data['token'] = $this->session->data['token'];
        
        $this->response->setOutput($this->load->view('common/d_elfinder.tpl', $data));
    }
    
    public function getImage() {
        $this->load->model('tool/image');
        
        if (isset($this->request->get['image'])) {
            $this->response->setOutput($this->model_tool_image->resize(html_entity_decode($this->request->get['image'], ENT_QUOTES, 'UTF-8'), 100, 100));
        }
    }
}
