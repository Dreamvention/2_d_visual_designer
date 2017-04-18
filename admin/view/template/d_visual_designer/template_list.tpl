<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-template').submit() : false;"><i class="fa fa-trash-o"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <ul class="nav menu">
                        <li><a href="<?php echo $href_setting; ?>" class="htab-item"><i class="fa fa-cog fa-fw"></i> <?php echo $text_setting; ?></a></li>
                        <li class="active"><a href="<?php echo $href_templates; ?>" class="htab-item"><i class="fa fa-list"></i> <?php echo $text_templates; ?></a></li>
                        <li><a href="<?php echo $href_instruction; ?>" class="htab-item"><i class="fa fa-graduation-cap fa-fw"></i> <?php echo $text_instructions; ?></a></li>
                    </ul>
                </h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-template">
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td class="text-center" style="width:30px;"><input class="form-control"  type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-center col-sm-1"><?php echo $column_image; ?></td>
                                    <td class="text-center"><?php if ($sort == 'name') { ?>
                                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center"><?php if ($sort == 'sort_order') { ?>
                                        <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                                        <?php } else { ?>
                                        <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center"><?php echo $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($templates) { ?>
                                <?php foreach ($templates as $template) { ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if (empty($template['config'])) { ?>
                                        <?php if (in_array($template['template_id'], $selected)) { ?>
                                        <input class="form-control" type="checkbox" name="selected[]"
                                        value="<?php echo $template['template_id']; ?>" checked="checked"/>
                                        <?php } else { ?>
                                        <input class="form-control" type="checkbox" name="selected[]"
                                        value="<?php echo $template['template_id']; ?>"/>
                                        <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center"><img style="width:40px;" class="img-thumbnail" src="<?php echo $template['image']; ?>"/></td>
                                    <td class="text-center"><?php echo $template['name']; ?></td>
                                    <td class="text-center"><?php echo $template['sort_order']; ?></td>
                                    <td class="text-center"><a href="<?php echo $template['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>