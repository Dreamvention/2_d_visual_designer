<vd-layout-main class="block-parent block-container {className} {opts.block.id} {activeControl? 'active-control':'deactive-control'}" data-id="{opts.block.id}" id={setting.id? setting.id:null}>
    <div class="block-mouse-toggle" if={permission}></div>
    <div class="control control-{block_config.setting.control_position} {upControl?'control-up':null}"  if={permission}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <div class="vd-border vd-border-left" if={permission}></div>
    <div class="vd-border vd-border-top" if={permission}></div>
    <div class="vd-border vd-border-right" if={permission}></div>
    <div class="vd-border vd-border-bottom" if={permission}></div>
    <layout-style block={opts.block}/>
    <div class="block-content {contentClassName}" data-is="vd-block-{opts.block.type}" block={opts.block} ref="content"></div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting.global
        this.activeControl = false
        this.upControl = false
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })

        this.store.subscribe('block/control/up', function(data){
            if(data.id == this.opts.block.id) {
                this.upControl = true
                this.update()
            }
        }.bind(this))

        this.store.subscribe('block/control/normal', function(data){
            if(data.id == this.opts.block.id && !this.activeControl) {
                this.upControl = false
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
</vd-layout-main>