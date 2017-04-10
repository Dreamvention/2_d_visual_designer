<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style; ?>" rel="stylesheet" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<?php if(!empty($edit_url)) { ?>
    <div class="btn-group-xs btn-edit" >
        <a class="btn btn-default" href="<?php echo $edit_url; ?>" target="_blank">
        <i class="fa fa-pencil"></i><?php echo $text_edit; ?></a>
        <br/><br/>
    </div>
<?php } ?>
<?php echo $content; ?>