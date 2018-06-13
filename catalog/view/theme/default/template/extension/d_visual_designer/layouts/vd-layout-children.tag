<vd-layout-children class="block-child block-container {getState().className} {activeControl? 'active-control':'deactive-control'}" data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <div class="control control-{getState().block_config.setting.control_position}" if={getState().permission} style={getState().controlStyle}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <layout-style block={opts.block}/>
    <div class="block-content {getState().block_config.setting.child? 'child' : ''}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            activeControl: false,
            controlStyle: '',
            permission: false,
            className: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            })
        })

        $(this.root).on('active-control', function(){
            var block = this.opts.block
            var parent = this.opts.block.parent
            var parentHeigth = $('[data-id='+parent+']').height()
            var parentRight = parentBlock.offset().left + parentBlock.width()
            var currentRight = $(this.root).offset().left + $(this.root).width()
            var currentControlWidth = $(this.root).children('.control').width()

            if((currentRight - currentControlWidth) < parentRight) {
                $('[data-id='+parent+'] > .control-top').css({'top': '-40px'})
            } else {
                $('[data-id='+parent+'] > .control-advanced').css({'top': ''})
            }
            this.update()
        }.bind(this))

        $(this.root).mouseenter(function(){
            if(!this.getState().activeControl) {
                this.setState('activeControl', true);
                this.store.dispatch('block/control/active', {id: this.opts.block.id})
                this.update()
            }
        }.bind(this))
        $(this.root).mouseleave(function(e, e1){
            if(this.getState().activeControl) {
                this.setState('activeControl', false);
                this.store.dispatch('block/control/deactive', {id: this.opts.block.id})
                this.update()
            }
        }.bind(this))

        this.initClassNames = function(){
            var className = []
            var setting = this.getState().setting
            
            if(setting.design_show_on){
                className.push(_.map(setting.design_show_on, function(value){ return value }).join(' '))
            }
            if(setting.design_animate){
                className.push('animated '+setting.design_animate)
            }
            if(setting.additional_css_class){
                className.push(setting.additional_css_class)
            }
            this.setState('className', className.join(' '))
        }
        this.checkPermission = function(){
            var top = this.getState().top
            var block_config = this.getState().block_config
            if(this.store.getState().config.permission[top.opts.id] && block_config.setting.display_control){
                this.setState('permission', true)
            }
        }
        this.initClassNames()
        this.checkPermission()
        this.on('mount', function(){
            var margin_left = (-1)*($(this.root).children('.control').width()/2);
            var margin_top = (-1)*($(this.root).children('.control').height()/2);
            this.setState({
                controlStyle: 'margin:'+margin_top+'px 0 0 '+margin_left+'px;'
            })
        })
        this.on('update', function(){
            this.setState({
                block_config: _.find(this.store.getState().config.blocks, function(block){
                    return block.type == opts.block.type
                }),
                setting: this.opts.block.setting.global
            })
            this.initClassNames()
            this.checkPermission()
        })
    </script>
</vd-layout-children>