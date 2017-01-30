<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
        <ul class="nav menu">
          <li><a href="<?php echo $href_setting; ?>" class="htab-item"><i class="fa fa-cog fa-fw"></i> <?php echo $text_setting; ?></a></li>
          <li><a href="<?php echo $href_templates; ?>" class="htab-item"><i class="fa fa-list"></i> <?php echo $text_templates; ?></a></li>
          <li class="active"><a href="<?php echo $href_instruction; ?>" class="htab-item"><i class="fa fa-graduation-cap fa-fw"></i> <?php echo $text_instructions; ?></a></li>
        </ul>
        </h3>
      </div>
      <div class="panel-body">
        <?php echo $text_instruction_full; ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>