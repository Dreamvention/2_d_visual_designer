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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-template" class="form-horizontal">
                    <ul class="nav nav-tabs" id="language">
                        <?php foreach ($languages as $language) { ?>
                        <li><a href="#language<?php echo $language['language_id']; ?>" data-language="<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php if(VERSION>='2.2.0.0') echo 'language/'.$language['code'].'/'.$language['code'].'.png'; else echo "view/image/flags/".$language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <?php foreach ($languages as $language) { ?>
                        <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name">
                                    <?php echo $entry_name; ?>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="template_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($template_description[$language['language_id']]) ? $template_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>"
                                        id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                                    <?php if (isset($error_name[$language['language_id']])) { ?>
                                    <div class="text-danger">
                                        <?php echo $error_name[$language['language_id']]; ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?php echo $entry_content; ?></label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote d_visual_designer" name="content" >
                                <?php echo $content; ?>
                            </textarea>
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
<?php echo $footer; ?>