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

    public function view_product_before(&$view, &$data, &$output)
    {
        if(isset($data['description'])){
            $designer_data = array(
                'config' => 'product',
                'content' => $data['description'],
                'field_name' => 'product_description['.(int)$this->config->get('config_language_id').'][description]',
                'id' => $data['product_id']
                );
            
            $data['description'] = $this->{'model_extension_module_'.$this->codename}->parseDescription($designer_data);
            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8');
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
                'field_name' => 'category_description['.(int)$this->config->get('config_language_id').'][description]',
                'id' => $category_id
                );

            $data['description'] = $this->{'model_extension_module_'.$this->codename}->parseDescription($designer_data);
            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8');
        }
    }

    public function view_module_feautured_before(&$view, &$data, &$output)
    {
        $this->load->model('catalog/product');
        if(!empty($data['products'])){
            foreach ($data['products'] as $key => $value) {
                $product_info = $this->model_catalog_product->getProduct($value['product_id']);
                $data['products'][$key]['description'] = $this->{'model_extension_module_'.$this->codename}->getText($product_info['description']);
                $data['products'][$key]['description'] = utf8_substr(strip_tags(html_entity_decode($data['products'][$key]['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..';
            }
        }
    }
    public function model_getProducts_after(&$route, &$data, &$output)
    {
        if(!empty($output)){
            foreach ($output as $key => $value) {
                $output[$key]['description'] = $this->{'model_extension_module_'.$this->codename}->getText($value['description']);
            }
        }
    }
    public function model_getInformation_after(&$route, &$data, &$output)
    {
        if(!empty($output)&&!empty($data[0])){
            $designer_data = array(
                'config' => 'information',
                'content' => $output['description'],
                'field_name' => 'information_description['.(int)$this->config->get('config_language_id').'][description]',
                'id' => $data[0]
                );
            $output['description'] = $this->{'model_extension_module_'.$this->codename}->parseDescription($designer_data);
        }
    }

    public function model_imageResize_before(&$route, &$data, &$output)
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
