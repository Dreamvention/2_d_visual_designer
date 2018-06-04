<vd-block-image>
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