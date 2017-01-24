<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8" />
    <title><?php echo $text_frontend_title; ?></title>
    <base href="<?php echo $base; ?>" />
    <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
    <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
    <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
    <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
    <link type="text/css" href="view/stylesheet/d_visual_designer/frontend.css" rel="stylesheet" media="all" />
    <script type="text/javascript">
        function resize_iframe(){
            $('iframe').height(window.innerHeight-60);
        }
        $(document).ready(function(){
            $('iframe').load(function(){
                $('iframe').contents().find('body').get(0).onsave_content_success = function() {
                    $('a[id=button-save]').empty();
                    $('a[id=button-save]').html('<i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i>');
                    $('span.notify').html('<?php echo $text_success_update; ?>');
                    $('span.notify').fadeIn('slow');
                    setTimeout(function(){
                        $('span.notify').fadeOut('slow');
                    }, 2000);
               };
                $('iframe').contents().find('body').get(0).onsave_template_success = function() {
                    $('span.notify').html('<?php echo $text_success_template_save; ?>');
                    $('span.notify').fadeIn('slow');
                    setTimeout(function(){
                        $('span.notify').fadeOut('slow');
                    }, 2000);
               };
                $('iframe').contents().find('body').get(0).onclone_block_success = function(e, args) {
                    $('span.notify').html(args['title']+'<?php echo $text_success_clone_block; ?>');
                    $('span.notify').fadeIn('slow');
                    setTimeout(function(){
                        $('span.notify').fadeOut('slow');
                    }, 2000);
               };
                $('iframe').contents().find('body').get(0).onremove_block_success = function(e, args) {
                    $('span.notify').html(args['title']+'<?php echo $text_success_remove_block; ?>');
                    $('span.notify').fadeIn('slow');
                    setTimeout(function(){
                        $('span.notify').fadeOut('slow');
                    }, 2000);
               };
            });
        });
        
                
        $(document).on('click','#button-save', function (){
            $('iframe')[0].contentWindow.$('body').trigger('designerSave');
            $(this).html('<div class="la-line-spin-clockwise-fade"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>');
        }); 
        $(document).on('click','#button-save-template', function (){
            $('iframe')[0].contentWindow.$('body').trigger('designerSaveTemplate');
        });
        
        $(document).on('click','#button-add', function (){
            $('iframe')[0].contentWindow.$('body').trigger('designerAddBlock');
        });
        $(document).on('click','#button-add-template', function (){
            $('iframe')[0].contentWindow.$('body').trigger('designerTemplate');
        });
        
        $(document).on('click','#button-reload', function (){
            $('iframe').get(0).contentDocument.location.reload(true);;
        });
        $(document).on('click','#button-close', function (){
            location.href = '<?php echo $url; ?>';
        });
        
        $(document).on('click','#button-backend', function(){
            var url = $(this).data('url');
            location.href = url;
        });
        
        $(document).on('click', '[id=mobile-size]', function() {
            $('iframe').animate({width:'320px'}, 500);
            return 0;
        });
        
        $(document).on('click', '[id=tablet-size]', function() {
            $('iframe').animate({width:'768px'}, 500);
            return 0;
        });
        
        $(document).on('click', '[id=desctop-size]', function() {
            percent = 1;
            add_width = (percent*$(window).width())+'px';
            $('iframe').animate({width:add_width}, 500);
            return 0;
        });
        
        $(window).on('resize',function(){
            $('iframe').removeAttr('style');
            $('iframe').attr('style','height:'+(window.innerHeight-60)+'px');
        });
        $(document).ready(function(){
            resize_iframe();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body>
    <div class="">
        <span class="notify"></span>
        <div class="vd-navbar">
            <div class="pull-left">
                <a id="button-add"  class="" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_add_block;?>">
                    <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                </a>
                <a id="button-add-template"  class="" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_templates;?>">
                    <i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i>
                </a>
            </div>
            <div class="pull-right">
                <button type="button" id="button-backend" data-url="<?php echo $backend_url; ?>" class="btn btn-primary"><?php echo $text_backend_editor; ?></button>
                
                <a id="mobile-size" class="" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_mobile;?>">
                    <i class="fa fa-mobile fa-2x" aria-hidden="true"></i>
                </a>
                <a id="tablet-size" class="" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_tablet;?>">
                    <i class="fa fa-tablet fa-2x" aria-hidden="true"></i>
                </a>
                <a id="desctop-size" class="" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_desktop;?>">
                    <i class="fa fa-desktop fa-2x" aria-hidden="true"></i>
                </a>
                <a id="button-reload" class="" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_reload;?>">
                    <i class="fa fa-refresh fa-2x" aria-hidden="true"></i>
                </a>
                <a id="button-save-template" class=""><i class="fa fa-clone fa-2x" aria-hidden="true"></i></a>
                <a id="button-save" class=""><i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i></a>
                <a id="button-close" class=""></a>
            </div>
            
        </div>
        <iframe src="<?php echo $url.'&edit'; ?>" onload="resize_iframe()" frameborder="0" border="0"/>
    </div>
</body>
</html>