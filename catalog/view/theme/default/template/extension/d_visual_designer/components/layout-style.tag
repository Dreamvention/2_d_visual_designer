<layout-style>
    <script>
        this.mixin(new vd_component(this))
        this.initState({
            block_parent: {},
        })
        this.initParentSetting = function(){
            var top = this.getState().top
            if(this.opts.block.parent != ''){
                this.setState('block_parent', this.store.getState().blocks[top.opts.id][this.opts.block.parent])
            }
        }
        this.initStyle = function(){
            var element = this.parent.root
            var setting = this.opts.block.setting.global
            var block_parent = this.getState().block_parent


            if(this.opts.block.parent !== '' && !_.isUndefined(block_parent.setting.global.float) && block_parent.setting.global.float){
                $(element).css({'float': 'left', 'width': 'auto'});
                if(block_parent.setting.global.align) {
                    if(block_parent.setting.global.align == 'left') {
                        $(element).css({'float': 'left'})
                    }
                    if(block_parent.setting.global.align == 'right') {
                        $(element).css({'float': 'right'})
                    }
                }
            } else {
                $(element).css({'float': '', 'width': ''});
            }

            if(setting.align && setting.float) {
                if(setting.align == 'center') {
                    $(element).children('.block-content').css({'display': 'flex', 'justify-content': 'center'})
                } else {
                    $(element).children('.block-content').css({'display': '', 'justify-content': ''})
                }
            } else {
                $(element).children('.block-content').css({'display': '', 'justify-content': ''})
            }

            if(setting.design_margin_top){
                $(element).css({'margin-top': setting.design_margin_top})
            } else {
                $(element).css({'margin-top': ''})
            }
            if( setting.design_margin_left){
                $(element).css({'margin-left': setting.design_margin_left})
            }
            if( setting.design_margin_right){
                $(element).css({'margin-right': setting.design_margin_right})
            } else {
                $(element).css({'margin-right': ''})
            }
            if( setting.design_margin_bottom){
                $(element).css({'margin-bottom': setting.design_margin_bottom})
            } else {
                $(element).css({'margin-bottom': ''})
            }
            if( setting.design_padding_top){
                $(element).css({'padding-top': setting.design_padding_top})
            } else {
                $(element).css({'padding-top': ''})
            }
            if( setting.design_padding_left){
                $(element).css({'padding-left': setting.design_padding_left})
            } else {
                $(element).css({'padding-left': ''})
            }
            if( setting.design_padding_right){
                $(element).css({'padding-right': setting.design_padding_right})
            } else {
                $(element).css({'padding-right': ''})
            }
            if( setting.design_padding_bottom){
                $(element).css({'padding-bottom': setting.design_padding_bottom})
            } else {
                $(element).css({'padding-bottom': ''})
            }
            if( setting.design_border_top){
                $(element).css({'border-top': setting.design_border_top+' '+setting.design_border_style+' '+setting.design_border_color})
            } else {
                $(element).css({'border-top': ''})
            }
            if( setting.design_border_left){
                $(element).css({'border-left': setting.design_border_left+' '+setting.design_border_style+' '+setting.design_border_color})
            } else {
                $(element).css({'border-left': ''})
            }
            if( setting.design_border_right){
                $(element).css({'border-right': setting.design_border_right+' '+setting.design_border_style+' '+setting.design_border_color})
            } else {
                $(element).css({'border-right': ''})
            }
            if( setting.design_border_bottom){
                $(element).css({'border-bottom': setting.design_border_bottom+' '+setting.design_border_style+' '+setting.design_border_color})
            } else {
                $(element).css({'border-bottom': ''})
            }
            if( setting.design_border_radius){
                $(element).css({'border-radius': setting.design_border_radius})
            } else {
                $(element).css({'border-radius': ''})
            }
            if( setting.design_background){
                $(element).css({'background-color': setting.design_background})
            } else {
                $(element).css({'background-color': ''})
            }
            if(setting.design_background_image){
                $(element).css({'background-image': 'url('+this.opts.block.setting.user.design_background_image+')'})
                if(setting.design_background_image_position_vertical && setting.design_background_image_position_horizontal){
                    $(element).css({'background-position': setting.design_background_image_position_vertical+' '+setting.design_background_image_position_horizontal})
                }
                if(setting.design_background_image_style == 'cover'){
                     $(element).css({'background-size': 'cover', 'background-repeat': 'no-repeat'})
                }
                if(setting.design_background_image_style == 'contain'){
                     $(element).css({'background-size': 'contain', 'background-repeat': 'no-repeat'})
                }
                if(setting.design_background_image_style == 'repeat'){
                     $(element).css({'background-repeat': 'repeat'})
                }
                if(setting.design_background_image_style == 'no-repeat'){
                     $(element).css({'background-repeat': 'no-repeat'})
                }
                if(setting.design_background_image_style == 'parallax'){
                     $(element).css({
                        'display': 'block',
                        'background-attachment': 'fixed',
                        'background-position': 'center',
                        'background-repeat': 'no-repeat',
                        'background-size': 'cover'
                     })
                }
            } else {
                $(element).css({
                    'display': '',
                    'background-attachment': '',
                    'background-position': '',
                    'background-repeat': '',
                    'background-size': '',
                    'background-image': ''
                })
            }
        }

        this.renderStyle = function(styles) {
            $('body').find('style#layout-'+this.opts.block.id).remove();

            var styleTag = '<style id="layout-'+this.opts.block.id+'">';
            styleTag += '.'+this.opts.block.id+':before{'+this.opts.block.setting.global.additional_css_before+'}';
            styleTag += '.'+this.opts.block.id+'{'+this.opts.block.setting.global.additional_css_content+'}';
            styleTag += '.'+this.opts.block.id+':after{'+this.opts.block.setting.global.additional_css_after+'}';
            styleTag += '<style>'
            $('body').append(styleTag);
        }

        this.initParentSetting();
        this.initStyle();
        this.renderStyle()

        this.on('mount', function(){
            this.initParentSetting();
            this.initStyle();
            this.renderStyle()
        })

        this.on('update', function(){
            this.initParentSetting()
            this.initStyle()
            this.renderStyle()
        })
    </script>
</layout-style>