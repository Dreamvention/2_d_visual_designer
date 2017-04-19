<?php
/*
 *  location: admin/view
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="form-inline pull-right">
                <?php if($stores){ ?>
                <select class="form-control" onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()">
                    <?php foreach($stores as $store){ ?>
                    <?php if($store['store_id'] == $store_id){ ?>
                    <option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
                    <?php }else{ ?>
                    <option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
                <?php } ?>
                <button id="save_and_stay" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if (!empty($error['warning'])) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning']; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (!empty($success)) { ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <ul class="nav menu">
                        <li class="active"><a href="<?php echo $href_setting; ?>" class="htab-item"><i class="fa fa-cog fa-fw"></i> <?php echo $text_setting; ?></a></li>
                        <li><a href="<?php echo $href_templates; ?>" class="htab-item"><i class="fa fa-list"></i> <?php echo $text_templates; ?></a></li>
                        <li><a href="<?php echo $href_instruction; ?>" class="htab-item"><i class="fa fa-graduation-cap fa-fw"></i> <?php echo $text_instructions; ?></a></li>
                    </ul>
                </h3>
            </div>
            <div class="panel-body">
                <?php if($notify) { ?>
                <style>
                    .notify > a{
                        color:inherit;
                        padding: 10px;
                        margin:-10px;
                        display:block;
                        font-size: 13px;
                        font-weight: 700;
                        text-align: center;
                    }
                </style>
                <div class="notify alert alert-warning"><?php echo $text_complete_version; ?></div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-<?php echo $notify?'9':'12'; ?>">
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input_status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                    <?php if(!$event_support) { ?>
                                    <div class="alert alert-info" style="overflow: inherit;">
                                        <div class="row">
                                            <div class="col-md-10"><?php echo $help_event_support; ?> </div>
                                            <div class="col-md-2"><a href="<?php echo $install_event_support; ?>" class="btn btn-info btn-block"><?php echo $text_install_event_support; ?></a></div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <input type="hidden" name="<?php echo $id;?>_status" value="0" />
                                    <?php if ($event_support) {?>
                                    <input type="checkbox" name="<?php echo $id;?>_status" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input_status"  <?php echo (${$id.'_status'})? 'checked="checked"':'';?> value="1" />
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input_save_change"><?php echo $entry_save_change; ?></label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="<?php echo $id;?>_setting[save_change]" value="0" />
                                    <input type="checkbox" name="<?php echo $id;?>_setting[save_change]" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input_save_change"  <?php echo ($setting['save_change'])? 'checked="checked"':'';?> value="1" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_use_designer; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                                        <?php foreach ($routes as $key => $value) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array($key, $setting['use'])) { ?>
                                                <input type="checkbox" name="<?php echo $id;?>_setting[use][]" value="<?php echo $key; ?>" checked="checked" />
                                                <?php echo $value; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="<?php echo $id;?>_setting[use][]" value="<?php echo $key; ?>" />
                                                <?php echo $value; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <a onclick="$(this).parent().find(':checkbox').prop('checked', true);" style="cursor:pointer;"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" style="cursor:pointer;"><?php echo $text_unselect_all; ?></a>
                                    <br/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-limit-access-user"><?php echo $entry_limit_access_user; ?></label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="<?php echo $id;?>_setting[limit_access_user]" value="0" />
                                    <input type="checkbox" name="<?php echo $id;?>_setting[limit_access_user]" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input-limit-access-user"  <?php echo ($setting['limit_access_user'])? 'checked="checked"':'';?> value="1" />
                                </div>
                            </div>
                            <div class="form-group" id="users">
                                <label class="col-sm-2 control-label" for="input-user"><?php echo $entry_user; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="user" value="" placeholder="<?php echo $entry_user; ?>" id="input-user" class="form-control" />
                                    <div id="access-user" class="well well-sm" style="height: 150px; overflow: auto;">
                                        <?php foreach ($users as $key => $value) { ?>
                                        <div id="access-user<?php echo $key; ?>">
                                            <i class="fa fa-minus-circle"></i>
                                            <?php echo $value; ?>
                                            <input type="hidden" name="<?php echo $id;?>_setting[access_user][]" value="<?php echo $key; ?>" />
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-limit-access-user-group"><?php echo $entry_limit_access_user_group; ?></label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="<?php echo $id;?>_setting[limit_access_user_group]" value="0" />
                                    <input type="checkbox" name="<?php echo $id;?>_setting[limit_access_user_group]" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input-limit-access-user-group"  <?php echo ($setting['limit_access_user_group'])? 'checked="checked"':'';?> value="1" />
                                </div>
                            </div>

                            <div class="form-group" id="user_groups">
                                <label class="col-sm-2 control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="user_group" value="" placeholder="<?php echo $entry_user_group; ?>" id="input-user-group" class="form-control" />
                                    <div id="access-user-group" class="well well-sm" style="height: 150px; overflow: auto;">
                                        <?php foreach ($user_groups as $key => $value) { ?>
                                        <div id="access-user-group<?php echo $key; ?>">
                                            <i class="fa fa-minus-circle"></i>
                                            <?php echo $value; ?>
                                            <input type="hidden" name="<?php echo $id;?>_setting[access_user_group][]" value="<?php echo $key; ?>" />
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="button_support_email"><?php echo $entry_support; ?></label>
                                <div class="col-sm-2">
                                    <a href="mailto:<?php echo $support_email; ?>?Subject=Request Support: <?php echo $heading_title; ?>&body=Shop: <?php echo HTTP_SERVER; ?>" id="button_support_email" class="btn btn-primary btn-block"><i class="fa fa-support"></i> <?php echo $button_support_email; ?></a>

                                </div>
                                <div class="col-sm-8">
                                    <label class="form-control-static"><?php echo $support_email; ?></label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php if($notify&&$module_notify) { ?>
                    <div class="col-md-3">
                        <div class="d_shopunity_widget_1"></div>
                        <script src="view/javascript/d_shopunity/d_shopunity_widget.js" type="text/javascript"></script>
                        <script type="text/javascript">
                            var d_shopunity_widget_1 = jQuery.extend(true, {}, d_shopunity_widget);
                            d_shopunity_widget_1.init({
                                class: '.d_shopunity_widget_1',
                                token: '<?php echo $_GET['token']; ?>',
                                extension_id: '99'
                            })
                        </script>
                    </div>
                    <?php } ?>
                    <?php if($notify&&!$module_notify&&$landing_notify) { ?>
                    <div class="col-md-3">
                        <div class="d_shopunity_widget_2"></div>
                        <script src="view/javascript/d_shopunity/d_shopunity_widget.js" type="text/javascript"></script>
                        <script type="text/javascript">
                            var d_shopunity_widget_2 = jQuery.extend(true, {}, d_shopunity_widget);
                            d_shopunity_widget_2.init({
                                class: '.d_shopunity_widget_2',
                                token: '<?php echo $_GET['token']; ?>',
                                extension_id: '120'
                            })
                        </script>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $(document).ready(function(){

        $(".switcher[type='checkbox']").bootstrapSwitch({
            'onColor': 'success',
            'onText': '<?php echo $text_yes; ?>',
            'offText': '<?php echo $text_no; ?>',
        });

        $('input[type=checkbox][name$="[limit_access_user]"]').on('switchChange.bootstrapSwitch', function(event, state) {
            if(state){
                $('input[type=checkbox][name$="[limit_access_user_group]"]').bootstrapSwitch('state', false);
                $('#users').show();
            }
            else{
                $('#users').hide();
            }
        });

        $('input[type=checkbox][name$="[limit_access_user]"]').trigger('switchChange.bootstrapSwitch', <?php echo $setting['limit_access_user']; ?>);

        $('input[type=checkbox][name$="[limit_access_user_group]"]').on('switchChange.bootstrapSwitch', function(event, state) {
            if(state){
                $('input[type=checkbox][name$="[limit_access_user]"]').bootstrapSwitch('state', false);
                $('#user_groups').show();
            }
            else{
                $('#user_groups').hide();
            }
        });

        $('input[type=checkbox][name$="[limit_access_user_group]"]').trigger('switchChange.bootstrapSwitch', <?php echo $setting['limit_access_user_group']; ?>);

        $('input[name=\'user\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=d_visual_designer/setting/autocompleteUser&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['username'],
                                value: item['user_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'user\']').val('');

                $('#access-user' + item['value']).remove();

                $('#access-user').append('<div id="access-user' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="<?php echo $id; ?>_setting[access_user][]" value="' + item['value'] + '" /></div>');
            }
        });

        $('#access-user').delegate('.fa-minus-circle', 'click', function() {
            $(this).parent().remove();
        });

        $('input[name=\'user_group\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=d_visual_designer/setting/autocompleteUserGroup&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['user_group_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'user_group\']').val('');

                $('#access-user-group' + item['value']).remove();

                $('#access-user-group').append('<div id="access-user-group' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="<?php echo $id; ?>_setting[access_user_group][]" value="' + item['value'] + '" /></div>');
            }
        });

        $('#access-user-group').delegate('.fa-minus-circle', 'click', function() {
            $(this).parent().remove();
        });

        $('body').on('click', '#save_and_stay', function(){
            $.ajax( {
                type: 'post',
                url: $('#form').attr('action') + '&save',
                data: $('#form').serialize(),
                beforeSend: function() {
                    $('#form').fadeTo('slow', 0.5);
                },
                complete: function() {
                    $('#form').fadeTo('slow', 1);   
                },
                success: function( response ) {
                    console.log( response );
                }
            });  
        });
    });
    //--></script>
    <?php echo $footer; ?>