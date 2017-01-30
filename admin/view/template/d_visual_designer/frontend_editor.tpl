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
            $('iframe').height(window.innerHeight);
        }
        $(document).ready(function(){
            $('iframe').load(function(){
                $('iframe').contents().find('body').get(0).onsave_content_success = function() {
                    $('a[id=button-save]').empty();
                    $('a[id=button-save]').button('reset');
                    $('span.notify').html('<?php echo $text_success_update; ?>');
                    $('span.notify').fadeIn('slow');
                    setTimeout(function(){
                        $('span.notify').fadeOut('slow');
                    }, 2000);
               };
               $('iframe').contents().find('body').get(0).onpopup_left_active = function() {
                   $('.vd-navbar.left-bar').css({'left':'calc(5% + 340px)'});
                };
               $('iframe').contents().find('body').get(0).onpopup_left_noactive = function() {
                   $('.vd-navbar.left-bar').css({'left':'5%'});
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
            $('a[id=button-save]').button('loading');
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
            $('iframe').attr('style','height:'+(window.innerHeight)+'px');
        });
        $(document).ready(function(){
            resize_iframe();
            $('[data-toggle="tooltip"]').tooltip();
        });
        $(document).on('mouseover','.vd-dropdown', function(e){
            $(this).children('.vd-dropdown-list').css({'visibility':'visible'});
        });
        $(document).on('mouseout','.vd-dropdown', function(e){
            $(this).children('.vd-dropdown-list').css({'visibility':'hidden'});
        });
        $(document).on('click', '.vd-dropdown > .vd-dropdown-list > li > a', function(){
            var currentMode = $(this).closest('.vd-dropdown').children('a').data('mode');
            var newMode = $(this).data('mode');
            $(this).closest('.vd-dropdown').children('a').removeClass('vd-btn-'+currentMode);
            $(this).closest('.vd-dropdown').children('a').addClass('vd-btn-'+newMode);
            $(this).closest('.vd-dropdown').children('a').data('mode', newMode);
        });
    </script>
</head>
<body>
    <div class="">
        <span class="notify"></span>
            <div class="vd-navbar left-bar">
                <a id="button-add" class="vd-btn vd-btn-add-block" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_add_block;?>"></a>
                <a id="button-add-template" class="vd-btn vd-btn-add-template" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_add_template;?>"></a>
                <a id="button-save-template" class=" vd-btn vd-btn-save-template" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_save_template;?>"></a>
                <div class="vd-dropdown">
                    <a class="vd-btn vd-btn-desktop" id="switch-view" data-mode="desktop"></a>
                    <ul class="vd-dropdown-list">
                        <li><a id="mobile-size" class="vd-btn vd-btn-mobile" data-mode="mobile" data-toggle="tooltip" data-placement="right" title="<?php echo $button_mobile;?>"></a></li>
                        <li><a id="tablet-size" class="vd-btn vd-btn-tablet" data-mode="tablet" data-toggle="tooltip" data-placement="right" title="<?php echo $button_tablet;?>"></a></li>
                        <li><a id="desctop-size" class="vd-btn vd-btn-desktop" data-mode="desktop" data-toggle="tooltip" data-placement="right" title="<?php echo $button_desktop;?>"></a></li>
                    </ul>
                </div>
                
                
                
            </div>
            <div class="vd-navbar right-bar">
                <a id="button-backend" data-url="<?php echo $backend_url; ?>"><?php echo $button_backend_editor; ?></a>
                <a id="button-save" data-loading-text="Loading..."><?php echo $button_publish; ?></a>
                <a id="button-close"><?php echo $button_cancel; ?></a>
            </div>
        <iframe src="<?php echo $url.'&edit'; ?>" onload="resize_iframe()" frameborder="0" border="0"/>
    </div>
</body>
</html>