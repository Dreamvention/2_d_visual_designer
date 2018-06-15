<vd-layout-column_inner class="block-inner block-container {getState().className} {getState().activeControl? 'active-control':'deactive-control'}" data-id="{opts.block.id}" id={getState().setting.id? getState().setting.id:null}>
    <virtual if={getState().permission}>
        <div class="block-mouse-toggle"></div>
        <div class="control control-{getState().block_config.setting.control_position} {getState().downControl? 'control-down': ''}">
            <virtual data-is="control-buttons" block={opts.block}/>
        </div>
        <div class="vd-border vd-border-left"></div>
        <div class="vd-border vd-border-top"></div>
        <div class="vd-border vd-border-right"></div>
        <div class="vd-border vd-border-bottom"></div>
    </virtual>
    <layout-style block={opts.block}/>
    <div class="block-content {getState().block_config.setting.child? 'child' : ''}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            activeControl: false,
            upControl: false,
            permission: false,
            className: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            })
        })

        this.store.subscribe('block/control/active', function(data){
            if(data.id == this.opts.block.id) {
                var parent = this.opts.block.parent
                if($(this.root).children('.block-content').height() < 100) {
                    this.store.dispatch('block/control/down', {id: parent})
                    this.setState({
                        downControl: true
                    })
                } else {
                    this.store.dispatch('block/control/normal', {id: parent})
                    this.setState({
                        downControl: false
                    })
                }
            }
        }.bind(this))

        $(this.root).mouseenter(function(){
            if(!this.getState().activeControl) {
                this.setState({activeControl: true})
                this.store.dispatch('block/control/active', {id: this.opts.block.id})
                this.update()
            }
        }.bind(this))
        $(this.root).mouseleave(function(e, e1){
            if(this.getState().activeControl) {
                this.setState({activeControl: false})
                this.store.dispatch('block/control/deactive', {id: this.opts.block.id})
                this.setState({downControl: false})
                this.update()
            }
        }.bind(this))

        this.initClassNames = function(){
            var className = []
            var setting = this.getState().setting
            if(setting.offset){
                className.push('offset-md-'+setting.offset)
            }
            if(setting.offset_phone){
                className.push('offset-xs-'+setting.offset_phone)
            }
            if(setting.offset_tablet){
                className.push('offset-sm-'+setting.offset_tablet)
            }
            if(!_.isUndefined(setting.size)){
                if(setting.size == 'fill') {
                    className.push('col-md')
                } else {
                    className.push('col-md-'+setting.size)
                }
            }
            if(setting.size_phone){
                if(setting.size_phone == 'fill') {
                    className.push('col-xs')
                } else {
                    className.push('col-xs-'+setting.size_phone)
                }
            }
            if(setting.size_tablet){
                if(setting.size_tablet == 'fill') {
                    className.push('col-sm')
                } else {
                    className.push('col-sm-'+setting.size_tablet)
                }
            }
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
</vd-layout-column_inner>