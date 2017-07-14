Visual Designer
===============

The first Visual Page Builder for Opencart. 

You can purchase the full version from [Opencart](https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=28530) or [Shopunity](https://shopunity.net/index.php?route=extension/extension&extension_id=84)

##Installation and Update
###With [shopunity](https://shopunity.net/download)
1. go to shopunity admin panel
2. go to tab market 
3. search for Visual Designer
4. click install

###With FTP  (shopunity required)
1. Download the archive
2. Unzip
3. Upload into your opencart root folder

###When the Visual designer is visible in the admin panel
1. Click install Visual Designer
2. Click edit Visual Designer
3. Set status to enabled and click save.

Now you can edit your Category, Product and Information Description using the best visual page builder for opencart.


#API
You can extend the functionality of Visual Designer by creating your very own Visual Designer Module. 

To create a module you will need to setup a special file structure and specify the api methods. You can find an example here.

##File structure of a module
This is a minimum file structure your module should have to properly work with VD.
```
my_vd_module
│
├─admin 
│	├─controller
│	│	└─d_visual_designer_module
│	│		└─my_vd_module.php				#Methods index($setting) and setting($setting) are required
│	├─language
│	│	└─en-gb 							#before 2.2.0.0 it was english.
│	│		└─d_visual_designer_module
│	│			└─my_vd_module.php			#language text. $_['text_title'] and $_['text_description'] are required.
│	└─view
│		└─d_visual_designer_module
│			├─my_vd_module_setting.tpl		#add setting form input
│			└─my_vd_module.tpl				#display the $setting data. Also you can add styles directly here.
├─catalog
│	├─controller
│	│	└─d_visual_designer_module
│	│		└─my_vd_module.php				#Methods index($setting) and setting($setting) are required
│	├─language
│	│	└─en-gb
│	│		└─d_visual_designer_module
│	│			└─my_vd_module.php			#language text. $_['text_title'] and $_['text_description'] are required.
│	├─model
│	│	└─d_visual_designer_module
│	│		└─my_vd_module.php				#add model methods here if you module require.
│	└─view
│		└─d_visual_designer_module
│			├─my_vd_module_setting.tpl		#add setting form input
│			└─my_vd_module.tpl				#display the $setting data. Also you can add styles directly here
└─system
	├─config
	│	└─d_visual_designer
	│		└─my_vd_module.php				#default settings for your module
	└─mbooth
		└─extension
			└─my_vd_module.json				#Add d_shopunity mbooth.json
			
```

##admin
###admin/controller
> The controller must have the following methods

```php
<?php
/*
 *	location: admin/controller
 */

class ControllerDVisualDesignerModuleMyVDModule extends Controller {
	/**
	 * module codename - keep it simple yet unique. add prefix
	 */
	private $codename = 'my_vd_module';
	private $route = 'd_visual_designer_module/my_vd_module';

	/**
	 * share loaded language files and models with all methods
	 */
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->load->language($this->route);
		$this->load->model('d_visual_designer/designer');
	}
	
	/**
	 * returns the module block view. Required
	 */
	public function index($setting){

		$data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
		
		$data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
		
		return $this->load->view($this->route.'.tpl', $data);
	}
	
	/**
	 * returns the module settings view. Required
	 */
	public function setting($setting){

		$data['entry_text'] = $this->language->get('entry_text');
		$data['setting'] = $this->model_d_visual_designer_designer->getSetting($setting, $this->codename);
		$data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
		
		return $this->load->view($this->route.'_setting.tpl', $data);
	}
}
```


###admin/language
every module gets it's own language file. It must have the following parameters:

```php
<?php
//text. They are used by VD to add title and description to the building blocks.
$_['text_title']			= 'My Vd Module';
$_['text_description']		= 'Helps me display text';

//entry. Further on you will specify the setting input labels
$_['entry_text']			= 'Text';
```

###admin/view
the view will always have two tpl files: one to display the building block and one to display settings input fields. It is best to add all the html, custom javascript and custom styles directly here - this way you will have a clean component, which is much easier to manager. Please, always keep your styles and javascript scoped to your component, so that they do not conflict with the rest of your modules.


**catalog/view/template/d_visual_designer_module/my_vd_module.tpl**
```
<div><?php echo $setting['text']; ?></div>
```

**catalog/view/template/d_visual_designer_module/my_vd_module_setting.tpl**
```html
<div class="form-group">
    <label class="control-label"><?php echo $entry_text; ?></label>
    <div class="fg-setting">
        <textarea class="form-control" name="text"><?php echo $setting['text']; ?></textarea>
    </div>
</div>
<script>
	//add summernote to your textarea
    $('textarea[name=text]').summernote({height:'200px'});
</script>

```


##Catalog
###catalog/controller
same as in admin, the controller must have `index($setting)` and `setting($setting)` methods.

```
<?php
/*
 *	location: admin/controller
 */

class ControllerDVisualDesignerModuleText extends Controller {
	private $codename = 'text';
	private $route = 'd_visual_designer_module/text';

	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->load->language($this->route);
		$this->load->model('module/d_visual_designer');
	}
	
	public function index($setting){
		
		$data['setting'] = $this->model_module_d_visual_designer->getSetting($setting, $this->codename);
		$data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
		
		$this->model_module_d_visual_designer->loadView($this->route, $data);
	}
	
	public function setting($setting){

		$data['entry_text'] = $this->language->get('entry_text');
		$data['setting'] = $this->model_module_d_visual_designer->getSetting($setting, $this->codename);
		$data['setting']['text'] = html_entity_decode(htmlspecialchars_decode($data['setting']['text']), ENT_QUOTES, 'UTF-8');
		
		$this->model_module_d_visual_designer->loadView($this->route.'_setting', $data);
	}
}
```

###catalog/language
add required block title and description, followed by setting labels.

```
<?php
//text
$_['text_title']       = 'Text Box';
$_['text_description'] = 'A block of text with WYSIWYG editor';

//setting entry
$_['entry_text']       = 'Text';
```

###catalog/model/d_visual_designer_module
If you need to add methods to work with the database etc. you must add your model files here to keep all VD files in one location.

###catalog/view
The view like in admin must have the tpl for displaying the block and settings form. It is also recomended to keep all the styles and javascript together with html and scoped to this html only. 
> in many cases your catalog will be a mirror of your admin files. It is ok. 

**catalog/view/theme/default/template/d_visual_designer_module/my_vd_module.tpl**
```
<div><?php echo $setting['text']; ?></div>
```

**catalog/view/theme/default/template/d_visual_designer_module/my_vd_module_setting.tpl**
```
<div class="form-group">
    <label class="control-label"><?php echo $entry_text; ?></label>
    <div class="fg-setting">
        <textarea class="form-control" name="text"><?php echo $setting['text']; ?></textarea>
    </div>
</div>
<script>
    $('textarea[name=text]').summernote({height:'200px'});
</script>
```


##System
###system/config
You will need to specify the default values of you module, as well as toggle the basic styling options that Visual Designer offers to every builing block module.
```php
<?php
//should this block be displayed in popup block list window 
$_['display']         = true;
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
//custom settings of your module
$_['setting'] = array(
    'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus in erat eu lacus varius venenatis ut ac urna.',
    'design_margin_bottom' => '15px'
);
```
###system/mbooth
Mbooth (module booth) is part of the Shopunity.net extension manager module. It keeps the most important information about you module like codename, version, files and folders and even changelog. It is your responsibility as a developer to keep this file accurate and upto date. 

**system/mbooth/extension/my_vd_module.json**
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
        "2.0.1.1",
        "2.0.1.1",
        "2.0.2.0",
        "2.0.3.1",
        "2.1.0.1",
        "2.1.0.2",
        "2.2.0.0",
        "2.3.0.2"
    ],
    "mbooth_version": "^4.0.0",
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
        "d_visual_designer": ">= 1.0.0"
    },
    "files": [
        "admin/controller/d_visual_designer_module/my_vd_module.php",
        "admin/language/en-gb/d_visual_designer_module/my_vd_module.php",
        "admin/language/english/d_visual_designer_module/my_vd_module.php",
        "admin/view/template/d_visual_designer_module/my_vd_module.tpl",
        "admin/view/template/d_visual_designer_module/my_vd_module_setting.tpl",

        "catalog/controller/d_visual_designer_module/my_vd_module.php",
        "catalog/language/en-gb/d_visual_designer_module/my_vd_module.php",
        "catalog/language/english/d_visual_designer_module/my_vd_module.php",
        "catalog/model/d_visual_designer_module/my_vd_module.php",
        "catalog/view/theme/default/template/d_visual_designer_module/my_vd_module.tpl",
        "catalog/view/theme/default/template/d_visual_designer_module/my_vd_module_setting.tpl",
        
        "system/mbooth/extension/my_vd_module.json",
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
