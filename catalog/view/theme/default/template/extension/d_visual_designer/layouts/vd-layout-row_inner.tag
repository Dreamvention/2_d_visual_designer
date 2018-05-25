<vd-layout-row_inner class="block-inner block-container {className} {opts.block.id} {activeControl? 'active-control':'deactive-control'}" data-id="{opts.block.id}" id={setting.id? setting.id:null}>
    <virtual if={permission}>
        <div class="block-mouse-toggle"></div>
        <div class="control control-{block_config.setting.control_position} {downControl?'control-down':null}">
            <virtual data-is="control-buttons" block={opts.block}/>
        </div>
        <div class="vd-border vd-border-left"></div>
        <div class="vd-border vd-border-top"></div>
        <div class="vd-border vd-border-right"></div>
        <div class="vd-border vd-border-bottom"></div>
    </virtual>
    <layout-style block={opts.block}/>
    <div class="block-content {contentClassName}" data-is="vd-block-{opts.block.type}" block={opts.block} ref="content"></div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting.global
        this.activeControl = false
        this.downControl = false
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })

        this.store.subscribe('block/control/down', function(data){
            if(data.id == this.opts.block.id) {
                this.downControl = true
                this.update()
            }
        }.bind(this))

        this.store.subscribe('block/control/normal', function(data){
            if(data.id == this.opts.block.id && !this.activeControl) {
                this.downControl = false
                this.update()
            }
        }.bind(this))

        this.checkPermission = function(){
            this.permission = false
            if(this.store.getState().config.permission[this.top.opts.id] && this.block_config.setting.display_control){
                this.permission = true
            }
        }

        $(this.root).mouseenter(function(){
            if(!this.activeControl) {
                this.activeControl = true;
                this.store.dispatch('block/control/active', {id: this.opts.block.id})
                this.update()
            }
        }.bind(this))
        $(this.root).mouseleave(function(e){
            var relatedTarget = $(e.target)
            if(this.activeControl) {
                this.activeControl = false;
                this.store.dispatch('block/control/deactive', {id: this.opts.block.id})
                this.update()
            }
        }.bind(this))

        this.initClassNames = function(){
            this.className = []
            this.contentClassName = []

            if(this.setting.background_video){
                this.className.push('video')
            }
            if(this.block_config.setting.child) {
                this.contentClassName.push('child')
            }
            if(this.setting.align){
                if(this.setting.align == 'left'){
                    this.contentClassName.push('justify-content-start')
                }
                if(this.setting.align == 'center'){
                    this.contentClassName.push('justify-content-center')
                }
                if(this.setting.align == 'right'){
                    this.contentClassName.push('justify-content-end')
                }
            }
            if(this.setting.design_show_on){
                this.className.push(_.map(this.setting.design_show_on, function(value){ return value }).join(' '))
            }
            if(this.setting.design_animate){
                this.className.push('animated '+this.setting.design_animate)
            }
            if(this.setting.additional_css_class){
                this.className.push(this.setting.additional_css_class)
            }
            this.className = this.className.join(' ')
            this.contentClassName = this.contentClassName.join(' ')
        }

        this.initChildBlocks = function(){
            this.childBlocks = this.store.getBlocks(this.parent.top.opts.id, this.opts.block.id)

            for (var key in this.childBlocks) {
                var childItems = this.store.getBlocks(this.parent.top.opts.id, key)
            }
        }

        this.initChildBlocks()
        this.checkPermission()
        this.initClassNames()


        this.on('update', function(){
            this.block_config = _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            })
            this.setting = this.opts.block.setting.global
            this.initChildBlocks()
            this.checkPermission()
            this.initClassNames()
        })
    </script>
</vd-layout-row_inner>