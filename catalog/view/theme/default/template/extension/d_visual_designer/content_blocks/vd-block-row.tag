<vd-block-row>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>

    <div class="video-background" if={getLink() && setting.global.background_video}>
        <iframe src="{getLink()}" frameborder="0" allowfullscreen="1" width="100%" height="100%" volume="0" onload={loadIframe}></iframe>
    </div>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        this.on('updated', function(){
            this.reCalculate()
        })
        this.on('mount', function(){
            this.reCalculate()
        })
        this.loadIframe = function(e) {
            this.reCalculate()
        }.bind(this)

        this.getLink = function(){
            var link = ''
            if(this.setting.global.link.indexOf('youtube') != -1){
                var matches = this.setting.global.link.match(/(v=)([a-zA-Z0-9]+)/)
                if(matches != null){
                    var youtube_id = matches[2]
                    link = this.setting.global.link.replace('watch?v=', 'embed/') + "?playlist="+youtube_id+"&autoplay=1&controls=0&showinfo=0&disablekb=1&loop=1&rel=0&modestbranding"
                }
            } else if (this.setting.global.link.indexOf('vimeo') != -1){
                link = this.setting.global.link.replace('vimeo.com', 'player.vimeo.com/video') + '?autoplay=1&background=1&loop=1'
            }
            return link
        }.bind(this)
        
        this.reCalculate = function(){
            var content = $(this.root).closest('.block-container')
            content.css('position','');
            content.css('z-index','');
            content.css('left','');
            content.css('width','');
            if(this.setting.global.design_padding_left == ''){
                content.css('padding-left','');
            }
            if(this.setting.global.design_padding_right == ''){
                content.css('padding-right','');
            }
            var width_content = content.outerWidth();
            if(this.setting.global.row_stretch !== '') {
                var left = content.offset().left - $('body').offset().left;
                var width_window = $('body').width();
                var right = width_window - left - content.width();
                content.css('position','relative');
                content.css('z-index','2');
                var direction = $('body').css('direction');
                if(direction == 'rtl'){
                    content.css('right','-'+right+'px');
                } else {
                    content.css('left','-'+left+'px');
                }
                content.css('width',width_window+'px');
                width_content = width_window;
                if(this.setting.global.row_stretch === 'stretch_row'){
                    content.css('padding-left',left+'px');
                    content.css('padding-right',right+'px');
                }
                if(this.setting.global.row_stretch === 'stretch_row_content_left'){
                    content.css('padding-right',right+'px');
                }
                if(this.setting.global.row_stretch === 'stretch_row_content_right'){
                    content.css('padding-left',left+'px');
                }
            }
            if(this.getLink() && this.setting.global.background_video){
                var video = $('.video-background', this.root);
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
        }.bind(this)
        $(window).on('resize', function(){
            this.reCalculate()
        }.bind(this))
    </script>
</vd-block-row>