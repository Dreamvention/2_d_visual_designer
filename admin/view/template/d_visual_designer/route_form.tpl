<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?>  <?php echo $version; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-route" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>"/>
                            <?php if (!empty($error_name)) { ?>
                            <div class="text-danger">
                                <?php echo $error_name; ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_key; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="token" value="<?php echo $token; ?>" readonly/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_backend_route; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="backend_route" value="<?php echo $backend_route; ?>"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_frontend_status; ?></label>
                        <div class="col-sm-10">
                            <input type="hidden" name="frontend_status" value="0" />
                            <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"
                            id="input_checkbox" name="frontend_status" <?php echo ($frontend_status)? 'checked="checked"':'';?> value="1" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <input type="hidden" name="status" value="0" />
                            <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"
                            id="input_checkbox" name="status" <?php echo ($status)? 'checked="checked"':'';?> value="1" />
                        </div>
                    </div>
                    <div id="frontend" <?php if(!$frontend_status) { echo 'style="display:none"'; } ?>>
                        <div class="form-group">
                            <label class="control-label col-sm-2"><?php echo $entry_frontend_route; ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="frontend_route" value="<?php echo $frontend_route; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2"><?php echo $entry_backend_param; ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="backend_param" value="<?php echo $backend_param; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2"><?php echo $entry_frontend_param; ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="frontend_param" value="<?php echo $frontend_param; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2"><?php echo $entry_edit_url; ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="edit_url" value="<?php echo $edit_url; ?>"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#language a:first').tab('show');
    $(".switcher[type='checkbox']").bootstrapSwitch({
		'onColor': 'success',
		'onText': '<?php echo $text_yes; ?>',
		'offText': '<?php echo $text_no; ?>',
	});
    $(".switcher[type='checkbox'][name=frontend_status]").on('switchChange.bootstrapSwitch', function(event, state) {
        if(state){
            $('div#frontend').removeAttr('style');
        }
        else{
            $('div#frontend').attr('style','display:none;');
        }
    });
</script>
<?php echo $footer; ?>