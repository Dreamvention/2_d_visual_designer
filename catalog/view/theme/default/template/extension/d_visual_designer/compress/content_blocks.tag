<vd-block-column>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
    </script>
</vd-block-column>
<vd-block-image>
<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/d_visual_designer/blocks/image.css">
    <div class="vd-image-container vd-image-align-{setting.global.align} {classContainer}">
        <div class="vd-image-title" if={setting.global.title}>
            <h2>{setting.global.title}</h2>
        </div>
        <div class="vd-image-wrapper vd-image-size-{setting.global.size} {classWrapper}">
            <div class="vd-image {setting.global.style ? 'vd-image-style-' + setting.global.style : ''}">
                <a>
                    <virtual if={setting.global.parallax == '1'}>
                        <div class="parallax-window" style="{parallaxStyles}"></div>
                    </virtual>
                    <virtual if={setting.global.parallax == '0'}>
                        <img src="{setting.user.thumb}" alt="{setting.global.image_alt}" title="{setting.global.image_title}"/>
                    </virtual>
                </a>
            </div>
        </div>
    </div>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.initImage = function (){
            this.parallaxStyles = []
            if(this.setting.global.parallax == '1'){
                this.parallaxStyles.push('background-image: url(\''+this.setting.user.thumb+'\');');
                this.parallaxStyles.push('height:'+this.setting.global.parallax_height+';');
                if(this.setting.global.size != 'responsive') {
                    $('.parallax-window', this.root).css({
                        'width': this.setting.user.desktop_size.width,
                        'height': this.setting.user.desktop_size.height,
                    })
                }
            }
             this.parallaxStyles = this.parallaxStyles.join(' ')
            if(this.setting.global.onclick == 'popup'){
                $('.vd-image', this.root).magnificPopup({
                    type:'image',
                    delegate: 'a',
                    gallery: {
                        enabled:true
                    }
                });
                $('.vd-image > a', this.root).attr('class', 'image-popup')
                $('.vd-image > a', this.root).attr('href', this.setting.user.popup)
            }
            if(this.setting.global.onclick == 'link'){
                if(this.setting.global.link_target == 'new'){
                    $('.vd-image > a', this.root).attr('target', '_blank')
                }
                $('.vd-image > a', this.root).attr('href', this.setting.global.link)
            }
            $('.vd-image img', this.root).css({width: '', height: ''})
            if(this.setting.user.phone_size && _.indexOf(['responsive', 'semi_responsive'], this.setting.global.size_phone) == -1){
                if (window.matchMedia('(max-width: 767px)').matches){
                    $('.vd-image img', this.root).css({width: this.setting.user.phone_size.width, height: this.setting.user.phone_size.height})
                }
            } else if (_.indexOf(['responsive', 'semi_responsive'], this.setting.global.size_phone) == -1 && ! this.setting.user.tablet_size && _.indexOf(['responsive', 'semi_responsive'], this.setting.global.size) == -1){
                if (window.matchMedia('(max-width: 767px)').matches){
                    $('.vd-image img', this.root).css({width: this.setting.user.desktop_size.width, height: this.setting.user.desktop_size.height})
                }
            }

            if(this.setting.user.tablet_size && _.indexOf(['responsive', 'semi_responsive'], this.setting.global.size_tablet) == -1){
                if (window.matchMedia('(min-width: 768px) and (max-width: 992px)').matches){
                    $('.vd-image img', this.root).css({width: this.setting.user.tablet_size.width, height: this.setting.user.tablet_size.height})
                }
            } else if (_.indexOf(['responsive', 'semi_responsive'], this.setting.global.size_tablet) == -1 && ! this.setting.user.phone_size && _.indexOf(['responsive', 'semi_responsive'], this.setting.global.size) == -1){
                if (window.matchMedia('(min-width: 768px) and (max-width: 992px)').matches){
                    $('.vd-image img', this.root).css({width: this.setting.user.desktop_size.width, height: this.setting.user.desktop_size.height})
                }
            }

            if(_.indexOf(['responsive', 'semi_responsive'], this.setting.global.size) == -1) {
                if(this.setting.user.tablet_size || this.setting.user.phone_size){
                    if (window.matchMedia('(min-width: 992px)').matches){
                        $('.vd-image img', this.root).css({width: this.setting.user.desktop_size.width, height: this.setting.user.desktop_size.height})
                    }
                }
            }
        }
        this.initClassContainer = function(){
            this.classContainer = []

            if(this.setting.global.align_phone){
                this.classContainer.push('vd-image-align-phone-'+this.setting.global.align_phone)
            }
            if(this.setting.global.align_tablet){
                this.classContainer.push('vd-image-align-tablet-'+this.setting.global.align_tablet)
            }
            this.classContainer = this.classContainer.join(' ')
        }.bind(this)
        this.initClassWrapper = function(){
            this.classWrapper = []

            if(this.setting.global.size_phone){
                this.classWrapper.push('vd-image-size-phone-'+this.setting.global.size_phone)
            }
            if(this.setting.global.classWrapper){
                this.classWrapper.push('vd-image-size-tablet-'+this.setting.global.size_tablet)
            }
            this.classWrapper = this.classWrapper.join(' ')
        }.bind(this)
        this.initClassContainer()
        this.initClassWrapper()
        this.on('mount', function(){
            this.initImage()
        })
        this.on('update', function(){
            this.setting = this.opts.block.setting
            this.initClassContainer()
            this.initClassWrapper()
            this.initImage()
        })
        $(window).on('resize', function(){
            this.initImage()
        }.bind(this))

        this.vd_semi_responsive = function($block, width, status, semi_responsive, responsive){

        $wrapper = $block.closest('.vd-image-wrapper');
        $block_content = $block.closest('.block-content');

        if (status) {
            if($block_content.outerWidth(true) > width){
                if($wrapper.hasClass(responsive)){
                    $wrapper.removeClass(responsive);
                    $wrapper.addClass(semi_responsive);
                }
            }
            else{
                if($wrapper.hasClass(semi_responsive)){
                    $wrapper.removeClass(semi_responsive);
                    $wrapper.addClass(responsive);
                }
            }
        }
        else{
            if($wrapper.hasClass(responsive)){
                $wrapper.removeClass(responsive);
                $wrapper.addClass(semi_responsive);
            }
        }
    }
    </script>
</vd-block-image>
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
<vd-block-text>
        <raw html={opts.block.setting.user.text}/>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
    </script>
</vd-block-text>
