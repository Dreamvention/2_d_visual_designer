<control-buttons>
    <a each={button in buttons} class="vd-btn vd-btn-small {button.className} {button.animate}" onClick={button.handleClick}></a>
    <div class="block-button" if={!block_config.setting.child}>
        <a id="button_add_block" class="vd-btn vd-btn-add button-add-bottom {buttons[0].animate}" onClick={addBottomBlock}></a>
    </div>
<script>
    this.top = this.parent ? this.parent.top : this
    this.mixin({store:d_visual_designer})
    this.active = false
    this.block_config = _.find(this.store.getState().config.blocks, function(block){
        return block.type == opts.block.type
    })
    this.on('update', function(){
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })
    })
    this.initButton = function(){
        var buttons = []
        var setting = this.block_config.setting
        if(setting.button_drag) {
            buttons.push({
                className: 'drag vd-btn-drag',
                animate: '',
                handleClick: null
            })
        }
        if(setting.button_edit) {
            buttons.push({
                className: 'vd-btn-edit',
                animate: '',
                handleClick: function(){
                    this.store.dispatch('block/setting/begin', {block_id: this.opts.block.id, type: this.opts.block.type, designer_id: this.top.opts.id})
                }.bind(this)
            })
        }
        if(setting.button_copy) {
            buttons.push({
                className: 'vd-btn-copy',
                animate: '',
                handleClick: function(){
                    this.store.dispatch('block/clone', { designer_id:this.parent.top.opts.id, target: this.opts.block.id})
                }.bind(this)
            })
        }
        if(setting.button_layout) {
            buttons.push({
                className: 'vd-btn-layout',
                animate: '',
                handleClick: function(){
                    this.store.dispatch('block/layout/begin', {block_id: this.opts.block.id, type: this.opts.block.type, designer_id: this.top.opts.id})
                }.bind(this)
            })
        }
        if(setting.child) {
            buttons.push({
                className: 'vd-btn-add-child',
                animate: '',
                handleClick: function(){
                    this.store.dispatch('block/new', {type: this.block_config.setting.child, designer_id:this.parent.top.opts.id, target: this.opts.block.id, level: this.level})
                }.bind(this)
            })
        }
        if(setting.button_remove) {
            buttons.push({
                className: 'vd-btn-remove',
                animate: '',
                handleClick: function(){
                    this.store.dispatch('block/remove', {designer_id: this.top.opts.id, block_id: this.opts.block.id, type: this.opts.block.type})
                }.bind(this)
            })
        }
        this.buttons = buttons
    }
    this.store.subscribe('block/control/active',function(data){
        if(this.opts.block.id == data.id) {
            if(!this.active) {
                this.active = true
                this.activeAnimation(0)
            }
        }
    }.bind(this))
    this.store.subscribe('block/control/deactive',function(data){
        if(this.opts.block.id == data.id) {
            if(this.active) {
                this.active = false
                this.deactiveAnimation(0)
            }
        }
    }.bind(this))

    activeAnimation(number){
        setTimeout(function(){
            this.buttons[number].animate = 'animate'
            if(_.size(this.buttons) > (number + 1)){
                this.activeAnimation(number + 1)
            } 
            this.update()
        }.bind(this), 15)

    }.bind(this)
    deactiveAnimation(number){
        setTimeout(function(){
            this.buttons[number].animate = ''
            if(_.size(this.buttons) > (number + 1)){
                this.deactiveAnimation(number + 1)
            } 
            this.update()
        }.bind(this), 15)

    }.bind(this)
    cloneBlock(e) {
        this.store.dispatch('block/clone', { designer_id:this.parent.top.opts.id, target: this.opts.block.id})
    }
    addChildBlock(e) {
         this.store.dispatch('block/new', {type: this.block_config.setting.child, designer_id:this.parent.top.opts.id, target: this.opts.block.id, level: this.level})
    }
    addBottomBlock(e) {
        this.store.dispatch('popup/addBlock', {parent_id: this.opts.block.parent, designer_id: this.top.opts.id, level: this.parent.level});
    }
    editBlock (e) {
        
    }
    layoutBlock (e) {
        this.store.dispatch('block/layout/begin', {block_id: this.opts.block.id, type: this.opts.block.type, designer_id: this.top.opts.id})
    }
    removeBlock (e) {
        this.store.dispatch('block/remove', {designer_id: this.top.opts.id, block_id: this.opts.block.id, type: this.opts.block.type})
    }
    this.initButton()
</script>
</control-buttons>