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
					<li><a href="<?php echo $href_templates; ?>" class="htab-item"><i class="fa fa-list"></i> <?php echo $text_templates; ?></a></li>
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
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input_status"><?php echo $entry_save_change; ?></label>
						<div class="col-sm-10">
							<input type="hidden" name="<?php echo $id;?>_setting[save_change]" value="0" />
							<input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_checkbox" name="<?php echo $id;?>_setting[save_change]" <?php echo ($setting['save_change'])? 'checked="checked"':'';?> value="1" />
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