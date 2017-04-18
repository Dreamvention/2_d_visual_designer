<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style; ?>" rel="stylesheet" />
<?php } ?>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<form id="form-vd-<?php echo $designer_id; ?>">
    <div class="content vd" id="<?php echo $designer_id; ?>">
        <div class="vd" id="sortable"><?php echo $content; ?></div>
        <div class="vd-helper">
            <a id="vd-add-button" class="vd-button vd-add-block vd-btn-add"></a>
        </div>
        <div class="vd-welcome">
            <div class="vd-welcome-brand">
                <!-- <img src="http://localhost/wordpress44/wp-content/plugins/js_composer/assets/vc/logo/64x64.png" alt=""> -->
            </div>
            <div class="vd-welcome-header">
                <?php echo $text_welcome_header; ?>
            </div>
            <div class="vd-button-group">
                <a id="vd-add-button" class="vd-button vd-add-block" title="Add Element"><?php echo $text_add_block; ?></a>
                <a id="vd-add-text-block" class="vd-button vd-add-text-block" title="Add text block">
                    <i class="fa fa-pencil-square-o">
                    </i>
                    <?php echo $text_add_text_block; ?>
                </a>
                <a id="vd-add-template" class="vd-button vd-add-template"><?php echo $text_add_template; ?></a>
            </div>
            <div class="vc_welcome-visible-ne">
                <a id="vc_not-empty-add-element" class="vc_add-element-not-empty-button" title="Add Element" data-vc-element="add-element-action">
                </a>
            </div>
        </div>
    </div>
</form>
<script type="text/html" id="template-helper-sortable">
    <div class="helper-sortable {{type}}">
        <img class="icon" src="{{{image}}}" width="32px" height="32px"/>{{{title}}}
    </div>
</script>
<script type="text/html" id="template-add-block">
    <div class="vd vd-popup add_block">
        <div class="popup-header">
            <h2 class="title"><?php echo $text_add_block; ?></h2>
            <div class="search">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="text" name="search" placeholder="<?php echo $text_search; ?>" value=""/>
            </div>
            <a class="close"></a>
        </div>
        {{#if categories}}
        <div class="popup-tabs">
            <ul class="vd-nav">
                <li class="active"><a href="#tab-get-template" data-toggle="tab" data-category="{{all}}"><?php echo $tab_all_blocks; ?></a></li>
                {{#categories}}
                <li><a id="new-block-tab"  data-toggle="tab" data-category="{{this}}">{{this}}</a></li>
                {{/categories}}
            </ul>
        </div>
        {{/if}}
        <div class="popup-content">
            {{#if notify}}
            <div class="notify alert alert-warning">
                <?php echo $text_complete_version; ?>
            </div>
            {{/if}}
            <div class="row popup-new-block">
                {{#blocks}}
                <div class="col-md-3 col-sm-6 col-xs-12 element">
                    <div class="block">
                        <a id="add_block" name="type" data-title="{{{title}}}" data-type="{{{type}}}" data-category="{{category}}">
                            <span><img src="{{{image}}}" class="image"></span>
                            {{{title}}}
                            <i class="description">
                                {{{description}}}
                            </i>
                        </a>
                    </div>
                </div>
                {{/blocks}}
            </div>
            <input type="hidden" name="target" value='{{{target}}}'/>
            <input type="hidden" name="designer_id" value='{{{designer_id}}}'/>
            <input type="hidden" name="level" value='{{{level}}}'/>
        </div>
    </div>
</script>
<script type="text/x-handlebars-template" id="template-add-template">
    <div class="vd vd-popup add_template" style="max-height:75vh;">
        <div class="popup-header">
            <h2 class="title"><?php echo $text_add_template; ?></h2>
            <div class="search">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="text" name="search" placeholder="<?php echo $text_search; ?>" value=""/>
            </div>
            <a class="close"></a>
        </div>
        {{#if categories}}
        <div class="popup-tabs">
            <ul class="vd-nav">
                <li class="active"><a href="#tab-get-template" data-toggle="tab" data-category=""><?php echo $tab_all_blocks; ?></a></li>
                {{#categories}}
                <li><a id="new-template-tab"  data-toggle="tab" data-category="{{this}}">{{this}}</a></li>
                {{/categories}}
            </ul>
        </div>
        {{/if}}
        <div class="popup-content">
            {{#if notify}}
            <div class="notify alert alert-warning">
            <?php echo $text_complete_version_template; ?>
            </div>
            {{/if}}
            <div class="popup-new-template">
                {{#templates}}
                <div class="col-md-3 col-sm-6 col-xs-12 element">
                    <div class="template">
                        <a id="add_template" data-id="{{template_id}}" data-config="{{config}}" name="type" data-category="{{category}}">
                            <img src="{{{image}}}"/>
                            <p class="title">{{{name}}}</p>
                        </a>
                    </div>
                </div>
                {{/templates}}
            </div>
        </div>
        <input type="hidden" name="target" value=''/>
        <input type="hidden" name="designer_id" value='{{designer_id}}'/>
    </div>
</script>
<script type="text/x-handlebars-template" id="template-save-template">
    <div class="vd vd-popup save_template" style="max-height:75vh;">
        <div class="popup-header">
            <h2 class="title"><?php echo $text_save_template; ?></h2>
            <a class="close"></a>
        </div>
        <div class="popup-content">
            <div class="form-group">
                <label class="control-label"><?php echo $entry_name; ?></label>
                <div class="fg-setting">
                    <input type="text" name="name" value="" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo $entry_category; ?></label>
                <div class="fg-setting">
                    <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo $entry_image_template; ?></label>
                <div class="fg-setting">
                    <a href="" id="thumb-vd-image" data-toggle="image" class="img-thumbnail">
                        <img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>"/>
                    </a>
                    <input type="hidden" name="image" value="" id="input-vd-image" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo $entry_sort_order; ?></label>
                <div class="fg-setting">
                    <input type="text" name="sort_order" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
                </div>
            </div>
        </div>
        <div class="popup-footer">
            <a id="saveTemplate" class="vd-btn save" data-designer-id="{{designer_id}}" data-loading-text="<?php echo $button_saved; ?>"><?php echo $button_save; ?></a>
        </div>
    </div>
</script>

<script type="text/html" id="template-border">
    <div class="vd-border vd-border-left"></div>
    <div class="vd-border vd-border-right"></div>
    <div class="vd-border vd-border-top"></div>
    <div class="vd-border vd-border-bottom"></div>
</script>

<script type="text/html" id="template-edit-block">
    <div class="vd vd-popup {{class_popup}}" style="max-height:75vh;">
        <div class="popup-header">
            <h2 class="title">{{{block_title}}} <?php echo $text_edit_block; ?></h2>
            <a class="stick-left"></a>
            <a class="close"></a>
        </div>
        <div class="popup-tabs">
            <ul class="vd-nav">
                <li class="active"><a href="#tab-edit-block" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                <li><a href="#tab-design-block" data-toggle="tab"><?php echo $tab_design; ?></a></li>
                <li><a href="#tab-css-block" data-toggle="tab"><?php echo $tab_css; ?></a></li>
            </ul>
        </div>
        <div class="popup-content">
            <div class="tab-content body">
                <div class="tab-pane active" id="tab-edit-block">
                    {{{module_setting}}}
                </div>
                <div class="tab-pane" id="tab-design-block">
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_margin; ?></label>
                        <div class="fg-setting">
                            <div class=wrap-setting>
                                <input type="text" name="design_margin_top" class="form-control pixels-procent" value="{{{design_margin_top}}}">
                                <span class="label-helper"><?php echo $text_top; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_margin_right" class="form-control pixels-procent" value="{{{design_margin_right}}}">
                                <span class="label-helper"><?php echo $text_right; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_margin_bottom" class="form-control pixels-procent" value="{{{design_margin_bottom}}}">
                                <span class="label-helper"><?php echo $text_bottom; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_margin_left" class="form-control pixels-procent" value="{{{design_margin_left}}}">
                                <span class="label-helper"><?php echo $text_left; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_padding; ?></label>
                        <div class="fg-setting">
                            <div class="wrap-setting">
                                <input type="text" name="design_padding_top" class="form-control pixels-procent" value="{{{design_padding_top}}}">
                                <span class="label-helper"><?php echo $text_top; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_padding_right" class="form-control pixels-procent" value="{{{design_padding_right}}}">
                                <span class="label-helper"><?php echo $text_right; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_padding_bottom" class="form-control pixels-procent" value="{{{design_padding_bottom}}}">
                                <span class="label-helper"><?php echo $text_bottom; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_padding_left" class="form-control pixels-procent" value="{{{design_padding_left}}}">
                                <span class="label-helper"><?php echo $text_left; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_border; ?></label>
                        <div class="fg-setting">
                            <div class="wrap-setting">
                                <input type="text" name="design_border_top" class="form-control pixels-procent" value="{{{design_border_top}}}">
                                <span class="label-helper"><?php echo $text_top; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_border_right" class="form-control pixels-procent" value="{{{design_border_right}}}">
                                <span class="label-helper"><?php echo $text_right; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_border_bottom" class="form-control pixels-procent" value="{{{design_border_bottom}}}">
                                <span class="label-helper"><?php echo $text_bottom; ?></span>
                            </div>
                            <div class="wrap-setting">
                                <input type="text" name="design_border_left" class="form-control pixels-procent" value="{{{design_border_left}}}">
                                <span class="label-helper"><?php echo $text_left; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_border_color; ?></label>
                        <div class="fg-setting">
                            <div id="color-input" class="input-group colorpicker-component fg-color">
                                <input type="text" name="design_border_color" class="form-control" value="{{{design_border_color}}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_border_style; ?></label>
                        <div class="fg-setting">
                            <select name="design_border_style" class="form-control">
                                {{#select design_border_style}}
                                <?php foreach($border_styles as $key => $value){ ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                                {{/select}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_border_radius; ?></label>
                        <div class="fg-setting">
                            <input type="text" name="design_border_radius" class="form-control pixels" value="{{{design_border_radius}}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_image; ?></label>
                        <div class="fg-setting">
                            <a href="" id="thumb-vd-image" data-toggle="image" class="img-thumbnail">
                                <img src="{{{design_background_thumb}}}" alt="" title="" data-placeholder="<?php echo $placeholder; ?>"/>
                            </a>
                            <input type="hidden" name="design_background_image" value="{{{design_background_image}}}" id="input-vd-image" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_image_style; ?></label>
                        <div class="fg-setting">
                            <select name="design_background_image_style" class="form-control">
                                {{#select design_background_image_style}}
                                <?php foreach($image_styles as $key => $value){ ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                                {{/select}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group" {{#ifCond design_background_image_style 'parallax'}} style="display:none;" {{/ifCond}}>
                        <label class="control-label"><?php echo $entry_image_position; ?></label>
                        <div class="fg-setting">
                            <div class="wrap-setting wrap-50">
                                <select name="design_background_image_position_horizontal" class="form-control">
                                    {{#select design_background_image_position_horizontal}}
                                    <?php foreach($image_horizontal_positions as $key => $value){ ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                    {{/select}}
                                </select>
                                <span class="label-helper"><?php echo $text_horizontal; ?></span>
                            </div>
                            <div class="wrap-setting wrap-50">
                                <select name="design_background_image_position_vertical" class="form-control">
                                    {{#select design_background_image_position_vertical}}
                                    <?php foreach($image_vertical_positions as $key => $value){ ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                    {{/select}}
                                </select>
                                <span class="label-helper"><?php echo $text_vertical; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_background; ?></label>
                        <div class="fg-setting">
                            <div id="color-input" class="input-group colorpicker-component fg-color">
                                <input type="text" name="design_background" class="form-control" value="{{{design_background}}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-css-block">
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_additional_css_class; ?></label>
                        <div class="fg-setting">
                            <input type="text" name="additional_css_class" class="form-control" value="{{{additional_css_class}}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_additional_css_before; ?></label>
                        <div class="fg-setting">
                            <textarea name="additional_css_before" class="form-control">{{{additional_css_before}}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_additional_css_content; ?></label>
                        <div class="fg-setting">
                            <textarea name="additional_css_content" class="form-control">{{{additional_css_content}}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo $entry_additional_css_after; ?></label>
                        <div class="fg-setting">
                            <textarea name="additional_css_after" class="form-control">{{{additional_css_after}}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup-footer">
            <a id="save" class="vd-btn save" data-id="{{{block_id}}}" data-designer_id="{{{designer_id}}}" data-type="{{{type}}}" data-loading-text="<?php echo $button_saved; ?>"><?php echo $button_save; ?></a>
        </div>
        <input type="hidden" name="designer_id" value="{{{designer_id}}}"/>
    </div>
</script>
<script type="text/html" id="template-row-layout">
    <div class="vd vd-popup edit-layout">
        <div class="popup-header">
            <h2 class="title"><?php echo $text_layout; ?></h2>
            <a class="close"></a>
        </div>
        <div class="popup-content">
            <div class="row layout-edit">
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '12'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="12">
                        <span class="layout-12"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '6+6'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="6+6">
                        <span class="layout-6"><span></span></span>
                        <span class="layout-6"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '4+4+4'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="4+4+4">
                        <span class="layout-4"><span></span></span>
                        <span class="layout-4"><span></span></span>
                        <span class="layout-4"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '3+3+3+3'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="3+3+3+3">
                        <span class="layout-3"><span></span></span>
                        <span class="layout-3"><span></span></span>
                        <span class="layout-3"><span></span></span>
                        <span class="layout-3"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '8+4'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="8+4">
                        <span class="layout-8"><span></span></span>
                        <span class="layout-4"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '4+8'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="4+8">
                        <span class="layout-4"><span></span></span>
                        <span class="layout-8"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '3+9'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="3+9">
                        <span class="layout-3"><span></span></span>
                        <span class="layout-9"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '9+3'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="9+3">
                        <span class="layout-9"><span></span></span>
                        <span class="layout-3"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '6+3+3'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="6+3+3">
                        <span class="layout-6"><span></span></span>
                        <span class="layout-3"><span></span></span>
                        <span class="layout-3"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '3+3+6'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="3+3+6">
                        <span class="layout-3"><span></span></span>
                        <span class="layout-3"><span></span></span>
                        <span class="layout-6"><span></span></span>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 layout {{#ifCond size '3+6+3'}} active {{/ifCond}}">
                    <a id="edit-layout" data-layout="3+6+3">
                        <span class="layout-3"><span></span></span>
                        <span class="layout-6"><span></span></span>
                        <span class="layout-3"><span></span></span>
                    </a>
                </div>
            </div>
            <div class="setting">
                <div class="form-group">
                    <label class="control-label"><?php echo $entry_size; ?></label>
                    <div class="fg-setting">
                        <div class="input-group">
                            <input type="text" class="form-control" name="size" value="{{size}}"/>
                            <span class="input-group-btn">
                                <button id="layoutSet" class="btn btn-default" type="button"><?php echo $text_set_custom; ?></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="target" value="{{{target}}}"/>
            <input type="hidden" name="designer_id" value="{{{designer_id}}}"/>
        </div>
    </div>
</script>
<script type="text/html" id="template-popover">
    <button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button>
    <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
</script>
<script>
    var setting = {
        form: $('#<?php echo $designer_id; ?>'),
        designer_id: '<?php echo $designer_id; ?>',
        edit_url: '<?php echo $edit_url ?>',
        field_name:'<?php echo $field_name; ?>',
        id:'<?php echo $id; ?>',
        save_change: <?php echo $save_change; ?>
    };
    d_visual_designer = d_visual_designer||{};
    d_visual_designer.init(setting);

    var teplate = {
        row_layout:$('script#template-row-layout:first'),
        add_block:$('script#template-add-block:first'),
        edit_block:$('script#template-edit-block:first'),
        add_template:$('script#template-add-template:first'),
        save_template:$('script#template-save-template:first'),
        helper:$('script#template-helper-sortable:first')
    };
    d_visual_designer.initTemplate(teplate);

    var data = <?php echo htmlentities(json_encode($settings)); ?>;

    d_visual_designer.initData(data,'<?php echo $designer_id ?>');

    var designer_id = '#<?php echo $designer_id; ?>';

    $('#<?php echo $designer_id; ?>').on('click','a[id=button_edit]',function(){
        var block_id = $(this).parent().data('control');
        d_visual_designer.editBlock(block_id, '<?php echo $designer_id; ?>');
    });
    $('#<?php echo $designer_id; ?>').on('click','a[id=button_layout]',function(){
        var block_id = $(this).closest('.block-container').attr('id');
        d_visual_designer.showEditLayout(block_id, '<?php echo $designer_id; ?>');
    });
    $(document).off('click','a[id=edit-layout]');
    $(document).on('click','a[id=edit-layout]',function(){
        var size = $(this).data('layout');
        var target = $('.vd-popup').find('input[name=target]').val();
        d_visual_designer.editLayout({'size': size}, target, '<?php echo $designer_id; ?>');
    });
    $(document).off('click','#layoutSet');
    $(document).on('click','#layoutSet',function(){
        var setting = $('.vd-popup').find('input, select, textarea').serializeJSON();
        var target = $('.vd-popup').find('input[name=target]').val();
        d_visual_designer.editLayout(setting, target, '<?php echo $designer_id; ?>');
    });

    $('body').on('designerSave',function(){
        d_visual_designer.saveContent('<?php echo $designer_id; ?>');
    });
    $('body').on('designerSaveTemplate',function(){
        d_visual_designer.showSaveTemplate('<?php echo $designer_id; ?>');
    });
    $('body').on('designerAddBlock',function(){
        d_visual_designer.showAddBlock('<?php echo $designer_id; ?>');
        return false;
    });
    $('body').on('designerTemplate',function(){
        d_visual_designer.showAddTemplate('<?php echo $designer_id; ?>');
        return false;
    });
    $('#<?php echo $designer_id; ?>').on('click','a[id=button_copy]',function(){
        var block_id = $(this).closest('.block-container').attr('id');
        d_visual_designer.cloneBlock('<?php echo $designer_id; ?>', block_id);
    });
    $(document).off('click', '.vd-popup.add_block > .popup-tabs > .vd-nav > li > a');
    $(document).on('click', '.vd-popup.add_block > .popup-tabs > .vd-nav > li > a', function(){
        d_visual_designer.search($(this).data('category'), '.vd-popup > .popup-content .popup-new-block > .element', 'a', 'data-category');
    });
    $(document).off('click', '.vd-popup.add_template > .popup-tabs > .vd-nav > li > a');
    $(document).on('click', '.vd-popup.add_template > .popup-tabs > .vd-nav > li > a', function(){
        d_visual_designer.search($(this).data('category'), '.vd-popup > .popup-content .popup-new-template > .element', 'a', 'data-category');
    });
    $(document).off('keyup', '.vd-popup.add_block > .popup-header input[name=search]');
    $(document).on('keyup', '.vd-popup.add_block > .popup-header input[name=search]', function(){
        d_visual_designer.search($(this).val(), '.vd-popup > .popup-content .popup-new-block > .element', 'a')
    });
    $(document).off('keyup', '.vd-popup.add_template > .popup-header input[name=search]');
    $(document).on('keyup', '.vd-popup.add_template > .popup-header input[name=search]', function(){
        d_visual_designer.search($(this).val(), '.vd-popup > .popup-content .popup-new-template > .element', 'a')
    });

    $(document).off('click','a[id=save]');
    $(document).on('click','a[id=save]',function(){
        var block_id = $(this).data('id');
        var designer_id = $('.vd-popup input[type=hidden][name=designer_id]').val();
        d_visual_designer.saveBlock(block_id, designer_id);
    });
    $(document).off('click','a[id=saveTemplate]');
    $(document).on('click','a[id=saveTemplate]',function(){
        d_visual_designer.saveTemplate('<?php echo $designer_id; ?>');
    });

    $('#<?php echo $designer_id; ?>').on('click','#button_add_child',function(){
        var block_id = $(this).parents('.block-container').attr('id');
        d_visual_designer.addChildBlock(block_id, '<?php echo $designer_id; ?>');
    });
    $('#<?php echo $designer_id; ?>').on('click','#button_add_block',function(){
        var block_id = $(this).closest('.block-inner, .block-section').attr('id');
        d_visual_designer.showAddBlock('<?php echo $designer_id; ?>', block_id);
        return false;
    });
    $('#<?php echo $designer_id; ?>').off('click','#vd-add-button');
    $('#<?php echo $designer_id; ?>').on('click','#vd-add-button',function(){
        var designer_id = $(this).parents('.vd.content').attr('id');
        d_visual_designer.showAddBlock(designer_id);
        return false;
    });
    $('#<?php echo $designer_id; ?>').off('click','[id=vd-add-text-block]');
    $('#<?php echo $designer_id; ?>').on('click','[id=vd-add-text-block]',function(){
        d_visual_designer.addBlock('text','', '', '<?php echo $designer_id; ?>', 0);
        return false;
    });
    $('#<?php echo $designer_id; ?>').off('click','#vd-add-template');
    $('#<?php echo $designer_id; ?>').on('click','#vd-add-template',function(){
        var designer_id = $(this).parents('.vd.content').attr('id');
        d_visual_designer.showAddTemplate(designer_id);
        return false;
    });

    $('#<?php echo $designer_id; ?>').on('click','.block-content:empty',function(){
        var designer_id = $(this).parents('.vd.content').attr('id');
        d_visual_designer.showAddBlock(designer_id, $(this).closest('.block-container').attr('id'));
        return false;
    });

    $(document).off('click','#add_template');
    $(document).on('click','#add_template', function(){
        var template_id = $(this).data('id');
        var config = $(this).data('config');
        d_visual_designer.addTemplate(template_id, config, '<?php echo $designer_id; ?>');
    });
    $(document).off('click','#add_block');
    $(document).on('click','#add_block', function(){
        var type = $(this).data('type');
        var title = $(this).data('title');
        var target = $('.vd-popup').find('input[name=target]').val();
        var designer_id = $('.vd-popup').find('input[name=designer_id]').val();
        var level = $('.vd-popup').find('input[name=level]').val();
        d_visual_designer.addBlock(type,title, target, designer_id, level);
    });
    $(document).off('click','.vd-popup a.close');
    $(document).on('click','.vd-popup a.close',function(){
        d_visual_designer.closePopup();
    });
    $(document).off('click','.vd-popup a.stick-left');
    $(document).on('click','.vd-popup a.stick-left',function(){
        d_visual_designer.stickPopup();
    });


    $('#<?php echo $designer_id; ?>').on('click','#button_remove',function(){
        var block_id = $(this).parent().data('control');
        d_visual_designer.removeBlock(block_id, '<?php echo $designer_id; ?>');
    });
    $('#<?php echo $designer_id; ?>').on('mouseover', '.block-button', function(){
        $(this).closest('.block-container').addClass('active-border');
    });
    $('#<?php echo $designer_id; ?>').on('mouseout', '.block-button', function(){
        $(this).closest('.block-container').removeClass('active-border');
    });
    $(document).off('change',  'input.percents');
    $(document).on('change', 'input.percents', function(){
        var value = $(this).val();
        var er = /^-?[0-9]+$/;
        var er2 = /^-?[0-9]+%$/;

        if(er.test(value)){
            $(this).val(value+'%');
        }
        else if(!er2.test(value)){
            $(this).val('');
        }
    });
    $(document).off('change',  'input.pixels');
    $(document).on('change', 'input.pixels', function(){
        var value = $(this).val();
        var er = /^-?[0-9]+$/;
        var er2 = /^-?[0-9]+px$/;

        if(er.test(value)){
            $(this).val(value+'px');
        }
        else if(!er2.test(value)){
            $(this).val('');

        }
    });
    $(document).off('change',  'input.pixels-procent');
    $(document).on('change', 'input.pixels-procent', function(){
        var value = $(this).val();
        var er = /^-?[0-9]+$/;
        var er2 = /^-?[0-9]+(px|%)$/;

        if(er.test(value)){
            $(this).val(value+'px');
        }
        else if(!er2.test(value)){
            $(this).val('');
        }
    });
    $(document).on('input', 'input[type=range]', function () {
        var id = $(this).data('input');
        if (id != 'undefined') {
            $('#' + id).val($(this).val() + 'px');
        }
    });
    $(document).on('change', 'input[type=range]+input[type=text]', function () {
        var id = $(this).attr('id');
        if (id != 'undefined') {
            var value = $(this).val();
            value = value.replace('px', '');
            $('input[data-input=' + id + ']').val(value);
        }
    });
    $(document).off('change', 'select[name=design_background_image_style]');
    $(document).on('change', 'select[name=design_background_image_style]', function(){
        var style = $(this).val();
        if(style!= 'parallax'){
            $('select[name=design_background_image_position_horizontal]').closest('.form-group').show();
        }
        else{
            $('select[name=design_background_image_position_horizontal]').closest('.form-group').hide();
        }
    });
    $(document).on('click', 'a[data-toggle=\'image\']', function(e){
        e.preventDefault();

        $('.popover').popover('hide', function() {
            $('.popover').remove();
        });

        var element = this;

        $(element).popover({
            html: true,
            placement: 'right',
            trigger: 'manual',
            content: function() {
                return $('script#template-popover').html();
            }
        });

        $(element).popover('show');

        $('#button-image').on('click', function() {
            $('#modal-image').remove();

            $.ajax({
                url: '<?php echo $filemanager_url; ?>' + '&target=' + $(element).parent().find('input').attr('id') + '&thumb=' + $(element).attr('id'),
                dataType: 'html',
                beforeSend: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    $('#button-image').prop('disabled', true);
                },
                complete: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-pencil"></i>');
                    $('#button-image').prop('disabled', false);
                },
                success: function(html) {
                    $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                    $('#modal-image').modal('show');
                }
            });

            $(element).popover('hide', function() {
                $('.popover').remove();
            });
            $('.block-container').hover(function(){
                console.log('hover');
                var block_id = $(this).data('id');

                $('.control[data-control='+block_id+'] a').effect('bounce');
            },function(){

            });
        });



        $('#button-clear').on('click', function() {
            $(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));

            $(element).parent().find('input').attr('value', '');

            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });
    });

</script>