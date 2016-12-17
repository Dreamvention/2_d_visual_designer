<?php
/*
 *	location: admin/view
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
                    <li><a href="<?php echo $href_templates; ?>" class="htab-item"><i class="fa fa-envelope"></i> <?php echo $text_templates; ?></a></li>
                    <li><a href="<?php echo $href_routes; ?>" class="htab-item"><i class="fa fa-user"></i> <?php echo $text_routes; ?></a></li>
                    <li><a href="<?php echo $href_instruction; ?>" class="htab-item"><i class="fa fa-graduation-cap fa-fw"></i> <?php echo $text_instructions; ?></a></li>
                  </ul>
                </h3>
			</div>
			<div class="panel-body">
	            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input_status"><?php echo $entry_status; ?></label>
							<div class="col-sm-10">
                                <input type="hidden" name="<?php echo $id;?>_status" value="0" />
								<input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_checkbox" name="<?php echo $id;?>_status" <?php echo (${$id.'_status'})? 'checked="checked"':'';?> value="1" />
							</div>
						</div><!-- //status -->
						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input_status"><?php echo $entry_save_change; ?></label>
							<div class="col-sm-10">
                                <input type="hidden" name="<?php echo $id;?>_setting[save_change]" value="0" />
								<input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_checkbox" name="<?php echo $id;?>_setting[save_change]" <?php echo ($setting['save_change'])? 'checked="checked"':'';?> value="1" />
							</div>
						</div>
						
						<?php if ($config_files) { ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="select_config"><?php echo $entry_config_files; ?></label>
							<div class="col-sm-10">
								<select id="select_config" name="<?php echo $id;?>_setting[config]"  class="form-control">
									<?php foreach ($config_files as $config_file) { ?>
									<option value="<?php echo $config_file; ?>" <?php echo ($config_file == $config)? 'selected="selected"' : ''; ?>><?php echo $config_file; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>
						<!-- //config -->

						<div class="form-group">
							<label class="col-sm-2 control-label" for="button_support_email"><?php echo $entry_support; ?></label>
							<div class="col-sm-2">
									<a href="mailto:<?php echo $support_email; ?>?Subject=Request Support: <?php echo $heading_title; ?>&body=Shop: <?php echo HTTP_SERVER; ?>" id="button_support_email" class="btn btn-primary btn-block"><i class="fa fa-support"></i> <?php echo $button_support_email; ?></a>
									
							</div>
							<div class="col-sm-8">
								<label class="form-control-static"><?php echo $support_email; ?></label>
							</div>
						</div><!-- //support_email -->
                    </form>
				</div>
			
		</div>
	</div>
</div>
<script type="text/html" id="template-template">
    <tr>
        <td class="text-center">
            <input type="text" class="form-control" name='<?php echo $id;?>_setting[template][{{key}}][title]' value="<?php echo $value['title']; ?>">
        </td>
        <td class="text-center">
            <textarea class="form-control" name='<?php echo $id;?>_setting[template][{{key}}][description]'></textarea>
        </td>
        <td class="text-center"><a onclick="$(this).parents('tr').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
        </td>
    </tr>
</script>
<script type="text/html" id="template-permission">
    <tr>
        <td class="text-center">
            <input type="text" class="form-control" name='<?php echo $id;?>_setting[permission][{{key}}][route]' value="">
        </td>
        <td class="text-center">
            <input type="hidden" name="<?php echo $id;?>_setting[permission][{{key}}][status]" value="0" />
            <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_checkbox" name="<?php echo $id;?>_setting[permission][{{key}}][status]" value="1" />
        </td>
        <td class="text-center">
            <a onclick="$(this).parents('tr').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
        </td>
    </tr>
</script>
<script type="text/javascript"><!--
	// sorting fields


	$(function () {
        
    var count_permission = <?php echo !empty($count_permission)?$count_permission:0; ?>;
	//checkbox
	$(".switcher[type='checkbox']").bootstrapSwitch({
		'onColor': 'success',
		'onText': '<?php echo $text_yes; ?>',
		'offText': '<?php echo $text_no; ?>',
	});

	$('body').on('change', '#select_config', function(){
		console.log('#select_config changed')
		var config = $(this).val();
		$('body').append('<form action="<?php echo $module_link; ?><?php echo ($stores) ? "&store_id='+$('#store').val() +'" : ''; ?>" id="config_update" method="post" style="display:none;"><input type="text" name="config" value="' + config + '" /></form>');
		$('#config_update').submit();
	});
	
	$(document).on('click','a[id=template_remove]', function(){
		var template_id = $(this).data('id');
		var that = this;
		$.ajax( {
			type: 'post',
			url: 'index.php?route=module/d_visual_designer/removeTemplate&token='+getURLVar('token'),
			data: 'template_id='.template_id,
			success: function( response ) {
				$(that).parents('tr').remove();
			}
		});  
		
	});
    
    
    $(document).on('click','#button-add-template', function(){
        count_permission++;
        var source = $("#template-template").html();
        var template = Handlebars.compile(source);
        var context = {key: count_permission};
        var html = template(context);
        if(count_permission == 1){
            $('#table-template > tbody').html(html);
        }
        else{
            $('#table-template > tbody').append(html);
        }
        $(".switcher[type='checkbox']").bootstrapSwitch({
    		'onColor': 'success',
    		'onText': '<?php echo $text_yes; ?>',
    		'offText': '<?php echo $text_no; ?>',
    	});
    });

    
    $(document).on('click','#button-add', function(){
        count_permission++;
        var source = $("#template-permission").html();
        var template = Handlebars.compile(source);
        var context = {key: count_permission};
        var html = template(context);
        if(count_permission == 1){
            $('#table-permission > tbody').html(html);
        }
        else{
            $('#table-permission > tbody').append(html);
        }
        $(".switcher[type='checkbox']").bootstrapSwitch({
    		'onColor': 'success',
    		'onText': '<?php echo $text_yes; ?>',
    		'offText': '<?php echo $text_no; ?>',
    	});
    });

	$('body').on('click', '#save_and_stay', function(){

		$('.summernote').each( function() {
		    $(this).val($(this).code());
		});
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