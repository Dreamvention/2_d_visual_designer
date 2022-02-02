<?php
class ControllerExtensionEventDVisualDesigner extends Controller
{
    private $codename = 'd_visual_designer';

    private $route = 'extension/module/d_visual_designer';

    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->load->language($this->route);
        $this->load->model($this->route);
    }

    public function controller_journal3_product_tabs_after(&$view, &$data, &$output)
    {
        if (!empty($output['contentType']) && $output['contentType'] === 'description') {
            $designer_data = array(
            'config' => 'product',
            'content' => $output['content'],
            'header' => $this->load->controller('common/header'),
            'field_name' => 'product_description['.(int)$this->config->get('config_language_id').'][description]',
            'id' => $this->request->get['product_id']
            );

            $output['content'] = $this->load->controller('extension/d_visual_designer/designer', $designer_data);

            $output['content']= html_entity_decode($output['content'], ENT_QUOTES, 'UTF-8');
        }
    }

    public function view_product_before(&$view, &$data, &$output)
    {
        if (isset($data['description'])) {
            $designer_data = array(
                'config' => 'product',
                'content' => $data['description'],
                'header' => &$data['header'],
                'field_name' => 'product_description['.(int)$this->config->get('config_language_id').'][description]',
                'id' => $data['product_id']
                );

            $data['description'] = $this->load->controller('extension/'.$this->codename.'/designer', $designer_data);

            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8');
        }
    }

    public function view_journal3_module_block_before(&$view, &$data, &$output)
    {
        if (!empty($data['items'])) {
            foreach ($data['items'] as $key => $value) {
                if (!empty($value['contentType']) && $value['contentType'] === 'description') {
                    if (isset($this->request->get['product_id'])) {
                        $designer_data = array(
                          'config' => 'product',
                          'content' => $value['content'],
                          'header' => $this->load->controller('common/header'),
                          'field_name' => 'product_description['.(int)$this->config->get('config_language_id').'][description]',
                          'id' => $this->request->get['product_id']
                        );
        
                        $data['items'][$key]['content'] = $this->load->controller('extension/d_visual_designer/designer', $designer_data);
        
                        $data['items'][$key]['content']= html_entity_decode($data['items'][$key]['content'], ENT_QUOTES, 'UTF-8');
                    } elseif (isset($this->request->get['path'])) {
                        $parts = explode('_', (string)$this->request->get['path']);

                        $category_id = (int)array_pop($parts);
                        $designer_data = array(
                          'config' => 'category',
                          'content' => $value['content'],
                          'header' => $this->load->controller('common/header'),
                          'field_name' => 'category_description['.(int)$this->config->get('config_language_id').'][description]',
                          'id' => $category_id
                        );
      
                        $data['items'][$key]['content'] = $this->load->controller('extension/d_visual_designer/designer', $designer_data);
      
                        $data['items'][$key]['content']= html_entity_decode($data['items'][$key]['content'], ENT_QUOTES, 'UTF-8');
                    }
                }
            }
        }
    }

    public function view_category_before(&$view, &$data, &$output)
    {
        $parts = explode('_', (string)$this->request->get['path']);

        $category_id = (int)array_pop($parts);

        if (isset($data['description'])) {
            $designer_data = array(
                'config' => 'category',
                'content' => $data['description'],
                'header' => &$data['header'],
                'field_name' => 'category_description['.(int)$this->config->get('config_language_id').'][description]',
                'id' => $category_id
                );

            $data['description'] = $this->load->controller('extension/'.$this->codename.'/designer', $designer_data);

            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8');
        }
    }

    public function view_information_before(&$view, &$data, &$output)
    {
        if (isset($data['description']) && !empty($this->request->get['information_id'])) {
            $designer_data = array(
                'config' => 'information',
                'content' => $data['description'],
                'header' => &$data['header'],
                'field_name' => 'information_description['.(int)$this->config->get('config_language_id').'][description]',
                'id' => (int)$this->request->get['information_id']
                );
            $data['description'] = $this->load->controller('extension/'.$this->codename.'/designer', $designer_data);

            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8');
        }
    }

    public function model_imageResize_before(&$route, &$data)
    {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }
            
        if (!empty($data[0])) {
            $image_info = @getimagesize(DIR_IMAGE . $data[0]);
            if (!$image_info) {
                return $server . 'image/' . $data[0];
            }
        } else {
            $data[0] = "no_image.png";
        }
    }
}
