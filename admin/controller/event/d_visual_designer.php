<?php
class ControllerEventDVisualDesigner extends Controller {

    public $codename = 'd_visual_designer';

    public function view_product_after(&$route, &$data, &$output){

        $html_dom = new d_simple_html_dom();
        $html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $html_dom->find('textarea[name^="product_description['.$language['language_id'].'][description]"]', 0)->class .=' d_visual_designer';
        }

        $html_dom->find('head', 0)->innertext  .= $this->addScript();

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

        $html_dom->find('head', 0)->innertext  .= $this->addScript();

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

        $html_dom->find('head', 0)->innertext  .= $this->addScript();

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

    private function addScript(){
        $setting = $this->config->get($this->codename.'_setting');
        $status = false;
        if(!empty($setting)){
            if(!empty($setting['limit_access_user'])){
                if(!empty($setting['access_user']) && in_array($this->user->getId(), $setting['access_user'])){
                    $status = true;
                }
            }
            elseif(!empty($setting['limit_access_user_group'])){
                if(!empty($setting['access_user_group']) && in_array($this->user->getGroupId(), $setting['access_user_group'])){
                    $status = true;
                }
            }
            else{
                $status = true;
            }
        }
        else{
            $status = true;
        }

        if($status){
            return '<script src="view/javascript/d_visual_designer/d_visual_designer.js?'.rand(5,10).'" type="text/javascript"></script>';
        }
        return '';
    }
}
