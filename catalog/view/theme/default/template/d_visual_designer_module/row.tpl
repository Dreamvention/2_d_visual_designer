{{{inner-block}}}

<script type="text/javascript">
    $(document).ready(function(){
        console.log('ready');
        $("span.row-<?php echo $unique_id; ?>").on('re-calculate', function(){
            var content = $(".row-<?php echo $unique_id; ?>").closest('.block-container');
            content.css('position','');
            content.css('z-index','');
            content.css('left','');
            content.css('width','');
            content.css('padding-left','');
            content.css('padding-right','');
            var width_content = content.outerWidth();
            var row_stretch = '<?php echo $setting['row_stretch']; ?>';
            var background_video = '<?php echo $setting['background_video']; ?>';
            var background_video_link = '<?php echo !empty($link)?$link:''; ?>';
            if(row_stretch !== ''){
                var left = content.offset().left;
                var width_window = $(window).width();
                var right = width_window - left - content.width();
                content.css('position','relative');
                content.css('z-index','2');
                content.css('left','-'+left+'px');
                content.css('width',width_window+'px');
                width_content = width_window;
                if(row_stretch === 'stretch_row'){
                    content.css('padding-left',left+'px');
                    content.css('padding-right',right+'px');
                }
            }

            if(background_video_link && background_video){
                var video = $('.video-<?php echo $unique_id; ?>');
                var height_content = content.outerHeight();
                var width = height_content/9*16;
                var height = height_content;

                if(width < width_content){
                    width = width_content;
                    height = width/16*9;
                    var margintop = (height-height_content)/2;
                }
                else{
                    var margintop = 0;
                }

                var marginleft =(width - width_content)/2;
                video.find('iframe').css('height',height+'px');
                video.find('iframe').css('width',width+'px');
                video.find('iframe').css('max-width','1000%');
                video.find('iframe').css('margin-left','-'+marginleft+'px');
                video.find('iframe').css('margin-top','-'+margintop+'px');
            }
        })
        $("span.row-<?php echo $unique_id; ?>").trigger('re-calculate');
    });
    $(window).on('resize', function(){
        setTimeout(function(){$("span.row-<?php echo $unique_id; ?>").trigger('re-calculate');},250);
    });
</script>
<span class="row-<?php echo $unique_id; ?>"></span>
<?php if(!empty($link) && !empty($setting['background_video'])) { ?>
<div class="video-background video-<?php echo $unique_id; ?>">
    <iframe src="<?php echo $link; ?>" frameborder="0" allowfullscreen="1" width="100%" height="100%" volume="0"></iframe>
</div>
<?php } ?>
