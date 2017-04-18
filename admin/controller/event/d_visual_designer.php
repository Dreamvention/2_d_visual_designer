<?php
class ControllerEventDVisualDesigner extends Controller {

    private $codename = 'd_visual_designer';
    private $route = 'module/d_visual_designer';
    private $extension = '';

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model($this->route);
        $this->load->model($this->codename.'/designer');

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'mbooth/extension/d_shopunity.json'));
        if ($this->d_shopunity) {
            $this->load->model('d_shopunity/mbooth');
            $this->extension = $this->model_d_shopunity_mbooth->getExtension($this->codename);
        }
    }

    public function view_product_after(&$route, &$data, &$output){

        $html_dom = new d_simple_html_dom();
        $html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $html_dom->find('textarea[name^="product_description['.$language['language_id'].'][description]"]', 0)->class .=' d_visual_designer';
        }

        if($this->{'model_'.$this->codename.'_designer'}->checkPermission()){
            $html_dom->find('head', 0)->innertext  .= '<script src="view/javascript/d_visual_designer/d_visual_designer.js?'.$this->extension['version'].'" type="text/javascript"></script>';
        }

        $output = (string)$html_dom;
    }

    public function view_category_after(&$route, &$data, &$output){
        $html_dom = new d_simple_html_dom();
        $html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $html_dom->find('textarea[name^="category_description['.$language['language_id'].'][description]"]', 0)->class .=' d_visual_designer';
        }

        if($this->{'model_'.$this->codename.'_designer'}->checkPermission()){
            $html_dom->find('head', 0)->innertext  .= '<script src="view/javascript/d_visual_designer/d_visual_designer.js?'.$this->extension['version'].'" type="text/javascript"></script>';
        }

        $output = (string)$html_dom;
    }

    public function view_information_after(&$route, &$data, &$output){
        $html_dom = new d_simple_html_dom();
        $html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $html_dom->find('textarea[name^="information_description['.$language['language_id'].'][description]"]', 0)->class .=' d_visual_designer';
        }

        if($this->{'model_'.$this->codename.'_designer'}->checkPermission()){
            $html_dom->find('head', 0)->innertext  .= '<script src="view/javascript/d_visual_designer/d_visual_designer.js?'.$this->extension['version'].'" type="text/javascript"></script>';
        }

        $output = (string)$html_dom;
    }
    public function model_imageResize_before(&$route, &$data, &$output)
    {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
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
