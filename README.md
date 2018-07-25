Visual Designer
===============

The first Visual Page Builder for Opencart. 

You can purchase the full version from [Opencart](https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=28530) or [Shopunity](https://shopunity.net/index.php?route=extension/extension&extension_id=84)

## Installation and Update
### With [shopunity](https://shopunity.net/download)
1. go to shopunity admin panel
2. go to tab market 
3. search for Visual Designer
4. click install

### With FTP  (shopunity required)
1. Download the archive
2. Unzip
3. Upload into your opencart root folder

### When the Visual designer is visible in the admin panel
1. Click install Visual Designer
2. Click edit Visual Designer
3. Set status to enabled and click save.

Now you can edit your Category, Product and Information Description using the best visual page builder for opencart.


# API
You can extend the functionality of Visual Designer by creating your very own Visual Designer Module. 

To create a module you will need to setup a special file structure and specify the api methods. You can find an example here.

## File structure of a module
This is a minimum file structure your module should have to properly work with VD.
```
my_vd_module
│
├─admin 
│    ├─controller
│    │	└─extension
|    |      └─d_visual_designer_module
│    │    	└─my_vd_module.php				#Methods index($setting) and setting($setting) are required
│    ├─language
│    │	└─en-gb 
│    │	    └─extension						#before 2.2.0.0 it was english.
│    │	    	 └─d_visual_designer_module
│    │	    		 └─my_vd_module.php			#language text. $_['text_title'] and $_['text_description'] are required.
│    └─view
│       └─extension
│    	    ├─d_visual_designer
│    	    |	├─content_blocks
│    	    |	|	└─vd-block-my_vd_block.tag		#display the $setting data. Also you can add styles directly here.
|    	    |	└─settings_block
│    	    |		└─vd-setting-block-my_vd_block.tag	#add setting form input
│    	    └─d_visual_designer_module
│    	    	└─my_vd_module.twig				#display the $setting data. Also you can add styles directly here.
├─catalog
│    ├─controller
│    │	└─extension
|    |      └─d_visual_designer_module
│    │    	└─my_vd_module.php				#Methods index($setting) and setting($setting) are required
│    ├─language
│    │	└─en-gb
│    │	    └─extension
│    │	    	└─d_visual_designer_module
│    │		    	└─my_vd_module.php			#language text. $_['text_title'] and $_['text_description'] are required.
│    ├─model
│    │	└─extension
|    |      └─d_visual_designer_module
│    │		    	└─my_vd_module.php			#add model methods here if you module require.
│    └─view
│       └─extension
│    	    ├─d_visual_designer
│    	    |	├─content_blocks
│    	    |	|	└─vd-block-my_vd_block.tag		#display the $setting data. Also you can add styles directly here.
│    	    |	└─settings_block
│    	    |		└─vd-setting-block-my_vd_block.tag	#add setting form input
│    	    └─d_visual_designer_module
│    		└─my_vd_module.twig				#display the $setting data. Also you can add styles directly here
└─system
    ├─config
    │	└─d_visual_designer
    │		└─my_vd_module.php				#default settings for your module
    └─system
    	└─library
    		└─d_shopunity
    			└─extension
    				└─my_vd_module.json		#Add d_shopunity mbooth.json
			
```

## admin
### admin/controller
> The controller must have the following methods

```php
<?php
/*
 *	location: admin/controller
 */

class ControllerExtensionDVisualDesignerModuleMyVDModule extends Controller {
	/**
	 * module codename - keep it simple yet unique. add prefix
	 */
	private $codename = 'my_vd_module';
	private $route = 'extension/d_visual_designer_module/my_vd_module';

	/**
	 * share loaded language files and models with all methods
	 */
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->load->language($this->route);
	}
	
	/**
	 * returns user settings. Optional
	 */
	public function index($setting){

		$data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
		
		return $data;
	}
	
	/**
	 * returns the settings for editing. Optional
	 */
	public function setting($setting){

		$data['text'] = html_entity_decode(htmlspecialchars_decode($data['text']), ENT_QUOTES, 'UTF-8');
		
		return $data;
	}

	/**
	 * returns the localization. Optional
	 */
	public function local() {
		$data['entry_text'] = $this->language->get('entry_text');

		return $data;
	}
	
	/**
	 * returns the options. Optional
	 */
	public function options() {
		return array();
	}

	/**
	 * returns the styles. Optional
	 */
	public function styles() {
		return array();
	}

	/**
	 * returns the scripts. Optional
	 */
	public function scripts() {
		return array();
	}
}
```


### admin/language
every module gets it's own language file. It must have the following parameters:

```php
<?php
//text. They are used by VD to add title and description to the building blocks.
$_['text_title']			= 'My Vd Module';
$_['text_description']		= 'Helps me display text';

//entry. Further on you will specify the setting input labels
$_['entry_text']			= 'Text';
```

### admin/view
the view will always have two tpl files: one to display the building block and one to display settings input fields. It is best to add all the html, custom javascript and custom styles directly here - this way you will have a clean component, which is much easier to manager. Please, always keep your styles and javascript scoped to your component, so that they do not conflict with the rest of your modules.

**admin/view/template/d_visual_designer/content_blocks/vd-block-my_vd_module.tag**
```html
<vd-block-my_vd_module>
    <span>{block_config.title}</span>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })
    </script>
</vd-block-my_vd_module>
```

**admin/view/template/d_visual_designer/settings_block/vd-setting-block-my_vd_module.tag**
```html
<vd-setting-block-my_vd_module>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.my_vd_module.entry_text')}</label>
    <div class="fg-setting">
        <vd-summernote name={'text'} value={setting.edit.text} evchange={change}/>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.setting = this.opts.block.setting
    this.on('update', function(){
        this.setting = this.opts.block.setting
    })
    change(name, value){
        this.setting.global[name] = value
        this.setting.user[name] = value
        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        this.update()
    }
</script>
</vd-setting-block-my_vd_module>
```

**admin/view/template/d_visual_designer_module/my_vd_module.twig**
```twig
<div class="{{ setting.global.additional_css_class }}" style="{{ styles }}">{{setting.user.text}}</div>
```



## Catalog
### catalog/controller
same as in admin, the controller must have `index($setting)`, `local()`, `options()`, `scripts()` and `styles()` methods.

```php
<?php

class ControllerExtensionDVisualDesignerModuleMyVDModule extends Controller {
	/**
	 * module codename - keep it simple yet unique. add prefix
	 */
	private $codename = 'my_vd_module';
	private $route = 'extension/d_visual_designer_module/my_vd_module';

	/**
	 * share loaded language files and models with all methods
	 */
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->load->language($this->route);
	}
	
	/**
	 * returns user settings. Optional
	 */
	public function index($setting){

		$data['text'] = html_entity_decode(htmlspecialchars_decode($setting['text']), ENT_QUOTES, 'UTF-8');
		
		return $data;
	}

	/**
	 * returns the localization. Optional
	 */
	public function local() {
		$data['entry_text'] = $this->language->get('entry_text');

		return $data;
	}
	
	/**
	 * returns the options. Optional
	 */
	public function options() {
		return array();
	}

	/**
	 * returns the styles. Optional
	 */
	public function styles() {
		return array();
	}

	/**
	 * returns the scripts. Optional
	 */
	public function scripts() {
		return array();
	}
}
```

### catalog/language
add required block title and description, followed by setting labels.

```php
<?php
//text
$_['text_title']       = 'Text Box';
$_['text_description'] = 'A block of text with WYSIWYG editor';

//setting entry
$_['entry_text']       = 'Text';
```

### catalog/model/d_visual_designer_module
If you need to add methods to work with the database etc. you must add your model files here to keep all VD files in one location.

### catalog/view
The view like in admin must have the tpl for displaying the block and settings form. It is also recomended to keep all the styles and javascript together with html and scoped to this html only. 
> in many cases your catalog will be a mirror of your admin files. It is ok. 

**catalog/view/theme/default/template/d_visual_designer/content_blocks/vd-block-my_vd_module.tag**
```html
<vd-block-my_vd_module>
        <raw html={getState().setting.user.text}/>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-my_vd_module>
```

**catalog/view/theme/default/template/d_visual_designer_module/my_vd_module.twig**
```twig
<div class=" {{ setting.global.additional_css_class }}" style="{{ styles }}">{{setting.user.text}}</div>
```


## System
### system/config
You will need to specify the default values of you module, as well as toggle the basic styling options that Visual Designer offers to every builing block module.
```php
<?php
//should this block be displayed in popup block list window 
$_['display']         = true;
//Sort order
$_['sort_order']       = 1;
//Pick a category for you module (three at the moment content, social, structure)
$_['category'] = 'cotent';
//Should the name of the module be displayed
$_['display_title']   = false;
//Can this module have inner modules (used for tabs etc.)
$_['child_blocks']    = false;
//There are 0-6 levels a module can be placed in. 0 level - is the row 1 - column level, starting from 2 till 6 are all inner levels. Usually your module should be from 2 till 6.
$_['level_min']       = 2;
$_['level_max']       = 6;
//the position of the controls is popup by default and should be used almost for every module, but some modules may use others like: top, top-bordered, advanced. 
$_['control_position'] ='popup';
//display contolls
$_['display_control'] = true;
//display drag button
$_['button_drag']     = true;
//display edit button
$_['button_edit']     = true;
//display copy button
$_['button_copy']     = true ;
//display collapse button
$_['button_collapse'] = true;
//display remove button
$_['button_remove']   = true;
//Available pre-renderer
$_['pre_render'] = true;
//Save to html
$_['save_html'] = true;
//Field types
$_['types'] = array(
    'text' => 'string'
);
//custom settings of your module
$_['setting'] = array(
    'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus in erat eu lacus varius venenatis ut ac urna.'
);
```
### system/library/d_shopunity
Mbooth (module booth) is part of the Shopunity.net extension manager module. It keeps the most important information about you module like codename, version, files and folders and even changelog. It is your responsibility as a developer to keep this file accurate and upto date. 

**system/library/d_shopunity/extension/my_vd_module.json**
```json
{
    "codename": "my_vd_module",
    "version": "1.0.0",
    "name": "My first VD module",
    "description": "A nice VD module for adding text to your landing page",
    "author": {
        "name": "Developer",
        "email": "info@developer.com",
        "url": "https://developer.com/"
    },
    "opencart_version": [
        "2.0.0.0",
        "2.0.1.0",
        "2.0.1.1",
        "2.0.2.0",
        "2.0.3.1",
        "2.1.0.1",
        "2.1.0.2",
        "2.2.0.0",
        "2.3.0.0",
        "2.3.0.1",
        "2.3.0.2",
        "3.0.0.0",
        "3.0.1.1",
        "3.0.1.2",
        "3.0.2.0"
    ],
    "type": "module",
    "license": {
        "type": "free",
        "url": "https://shopunity.net/licenses/free"
    },
    "support": {
        "email": "support@developer.com",
        "url": "https://developer.com/support"
    },
    "required": {
        "d_visual_designer": ">= 3.0.0"
    },
    "files": [
        "admin/controller/extension/d_visual_designer_module/my_vd_module.php",
        "admin/language/en-gb/extension/d_visual_designer_module/my_vd_module.php",
        "admin/language/english/extension/d_visual_designer_module/my_vd_module.php",
        "admin/view/template/extension/d_visual_designer/content_blocks/vd-block-my_vd_module.tag",
        "admin/view/template/extension/d_visual_designer/settings_block/vd-setting-block-my_vd_module.tag",
        "admin/view/template/extension/d_visual_designer_module/my_vd_module.twig",

        "catalog/controller/extension/d_visual_designer_module/my_vd_module.php",
        "catalog/language/en-gb/extension/d_visual_designer_module/my_vd_module.php",
        "catalog/language/english/extension/d_visual_designer_module/my_vd_module.php",
        "catalog/model/extension/d_visual_designer_module/my_vd_module.php",
        "catalog/view/theme/default/template/extension/d_visual_designer/content_blocks/vd-block-my_vd_module.tag",
        "catalog/view/theme/default/template/extension/d_visual_designer_module/my_vd_module.twig",
        
        "system/library/d_shopunity/extension/my_vd_module.json",
        "system/config/d_visual_designer/my_vd_module.php"
    ],
    "changelog": [
        {
            "version":"1.0.0",
            "change":"Stable version"
        }
    ]
}

```
