<?php
class ControllerModuleDresscodefeatured extends Controller {
	protected function index($setting) {
		$this->language->load('module/dresscode_featured');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('catalog/product'); 
		
		$this->load->model('tool/image');

		$this->data['products'] = array();

		$products = explode(',', $this->config->get('featured_product'));		

		if (empty($setting['limit'])) {
			$setting['limit'] = 1;
		}
		
		$products = array_slice($products, 0, (int)$setting['limit']);
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}

                //youtube
                $this->data['youtubes'] = array();
                $query = $this->db->query("SELECT youtube as video FROM ".DB_PREFIX."product WHERE product_id='".(int)$product_info['product_id']."'");
                $youtubes = explode(',',$query->row['video']);
                $this->data['youtubes'] = $youtubes;
                //youtube

                /* vars from admin panel labels settings */
                $this->config->get('bs_general');
                $this->data['buyshop_general_settings'] = $this->config->get('bs_general');
                if (isset($this->data['buyshop_general_settings']['ajaxcart'])) {
                    $this->data['ajaxcart'] = $this->data['buyshop_general_settings']['ajaxcart'];
                }
                $this->data['buyshop_productlabels_settings'] = $this->config->get('bs_productlabels');
                if (isset($this->data['buyshop_productlabels_settings']['sale'])) {
                    $this->data['sale'] = $this->data['buyshop_productlabels_settings']['sale'];
                } else {
                    $this->data['sale'] = '';
                }
                if (isset($this->data['buyshop_productlabels_settings']['video'])) {
                    $this->data['video'] = $this->data['buyshop_productlabels_settings']['video'];
                } else {
                    $this->data['video'] = '';
                }
                if (isset($this->data['buyshop_productlabels_settings']['saleposition'])) {
                    $this->data['saleposition'] = $this->data['buyshop_productlabels_settings']['saleposition'];
                } else {
                    $this->data['saleposition'] = '';
                }
                if (isset($this->data['buyshop_productlabels_settings']['videoposition'])) {
                    $this->data['videoposition'] = $this->data['buyshop_productlabels_settings']['videoposition'];
                } else {
                    $this->data['videoposition'] = '';
                }

                $this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'   	 => $image,

                    //youtube video
                    'youtube_video' => $youtubes,


                    'name-main'        => strlen($product_info['name']) > 43 ? utf8_substr(strip_tags(html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')), 0, 43) . '..' : $product_info['name'],
                    'name'        => $product_info['name'],
                    'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/dresscode_featured.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/dresscode_featured.tpl';
		} else {
			$this->template = 'default/template/module/dresscode_featured.tpl';
		}

		$this->render();
	}
}
?>