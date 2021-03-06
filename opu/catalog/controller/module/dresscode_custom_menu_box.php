<?php  
class ControllerModuleDresscodeCustomMenuBox extends Controller {
	protected function index($setting) {
		$this->language->load('module/dresscode_custom_menu_box');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
        if (isset($setting['description'][$this->config->get('config_language_id')])) {
            $this->data['message'] = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
        } else {
            $this->data['message'] = html_entity_decode($setting['description'][1], ENT_QUOTES, 'UTF-8');
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/dresscode_custom_menu_box.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/dresscode_custom_menu_box.tpl';
		} else {
			$this->template = 'default/template/module/dresscode_custom_menu_box.tpl';
		}
		
		$this->render();
	}
}