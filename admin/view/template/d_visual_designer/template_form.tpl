<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <?php if(!$config) { ?>
                    <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <?php } ?>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-template" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                            <?php if (!empty($error_name)) { ?>
                            <div class="text-danger">
                                <?php echo $error_name; ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_image; ?></label>
                        <div class="col-sm-10">
                            <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                            <img id="thumb-image" src="<?php echo $thumb; ?>"  class="img-thumbnail" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                            <span class="fa fa-close fa-fw delete-image"></span>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_category; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="category" value="<?php echo $category; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_content; ?></label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote d_visual_designer" name="content" ><?php echo $content; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="sort_order" value="<?php echo $sort_order; ?>"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#language a:first').tab('show');
    $('textarea[name=content]').summernote({height: 300});
    
</script>
<script type="text/javascript">
			
			updateFileManager();
			
			function updateFileManager() {
				$('body').on('click', '.img-thumbnail', function (e) {
					uploadImage($(this).prev().attr("id"), $(this).attr("id"));
					e.stopPropagation();
				}); 
				
				$('body').on('click', '.delete-image', function(e){
					$(this).prev().prev().val("");
					$(this).prev().attr("src", "<?php echo $placeholder; ?>");
					e.stopPropagation();
				});
			}
			
			function uploadImage(field, thumb) {
				$('#modal-image').remove();
		
				$('body').append('<div id="modal-image" class="modal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button><h4 class="modal-title"><?php echo $text_file_manager; ?></h4></div><div class="modal-body"><iframe src="index.php?route=d_visual_designer/template/getFileManager&token=<?php echo $token; ?>&field=' + field + '&thumb=' + thumb + '" style="padding:0; margin: 0; display: block; width: 100%; height: 560px;" frameborder="no" scrolling="no"></iframe></div><div class="modal-footer"></div></div></div></div>');
		
				$('#modal-image').modal('show');
				$('.modal-backdrop').remove();
				//$('#modal-image').after('<div class="modal-backdrop  in"></div>');
			}

			function addSingleImage(imageName, field, thumb) {
				$.ajax({
					url: 'index.php?route=d_visual_designer/template/getImage&token=<?php echo $token; ?>&image=' + encodeURIComponent(imageName),
					dataType: 'text',
					success: function(imageCacheName) {
						$('#' + thumb).attr('src', imageCacheName);
						$('#' + field).val(imageName).trigger('change');
					}
				});
			}
			
			</script>
			<style type="text/css">
			.img-thumbnail {
				width: 100px;
				height: 100px;
				cursor: pointer;
			}
			.delete-image {
				vertical-align: top;
				cursor: pointer;
			}
			</style>
<?php echo $footer; ?>