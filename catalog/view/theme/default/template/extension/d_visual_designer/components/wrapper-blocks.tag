<wrapper-blocks>
    <div class="vd-new-child-block" onClick={addBlock} if={_.isEmpty(getState().blocks)}></div>
    <div each={block in getState().blocks} data-is="vd-layout-{block.layout}" block={block}></div>
<script>
    this.mixin(new vd_component(this, false))
    this.initState({
        level: _.isUndefined(this.parent.getState().level)? 0 : this.parent.getState().level + 1,
        parent_id: !_.isUndefined(this.opts.block)? this.opts.block.id : '',
        blocks: {}
    })
    
    addBlock(e) {
        this.store.dispatch('popup/addBlock', {parent_id: this.getState().parent_id, designer_id: this.getState().top.opts.id, level: this.getState().level});
    }.bind(this)

    this.initSortable = function (){
        var parent_root = this.opts.selector ? this.opts.selector : this.parent.root
        var that = this;
        $(parent_root).sortable({
                forcePlaceholderSize: true,
                forceHelperSize: false,
                connectWith: this.getState().parent_id == ''? ".block-parent":".block-content:not(.child)",
                placeholder:  this.getState().parent_id == ''? "row-placeholder col-sm-12" : "element-placeholder col-sm-12",
                items: this.getState().parent_id == ''? "> .block-parent" : ".block-child, .block-inner",
                helper: function(event, ui) {
                    if (ui.hasClass('.block-inner')) {
                        var type = "inner";
                    } else {
                        var type = "child";
                    }
                    var block_id = $(ui).closest('.block-container').data('id')
                    var block_info = that.store.getState().blocks[that.getState().top.opts.id][block_id]
                    var block_config = _.find(that.store.getState().config.blocks, function(block){
                        return block.type == block_info.type
                    })
                    var helper = '<div class="helper-sortable '+type+'"><img class="icon" src="'+block_config.image+'" width="32px" height="32px"/>'+block_config.title+'</div>'
                    return helper;
                },
                distance: 3,
                scroll: true,
                scrollSensitivity: 70,
                zIndex: 9999,
                appendTo: 'body',
                cursor: 'move',
                revert: 0,
                cursorAt: { top: 20, left: 16 },
                handle: this.getState().parent_id == ''? ' > .control > .drag' :' .drag',
                tolerance: 'intersect',
                stop: function(event, ui){
                    var block_id = $(ui.item).closest('.block-container').data('id')
                    if(that.parent_id == '') {
                        var parent_id = ''
                    }  else {
                        var parent_id = $(ui.item).parent().closest('.block-container').data('id')
                    }

                    that.store.dispatch('block/move', {block_id: block_id, target: parent_id, designer_id: that.getState().top.opts.id, success: function(){
                            $(parent_root).sortable('cancel')
                    }.bind(this)})
                }
            })
    }

    this.initBlocks = function () {
        var blocks = this.store.getBlocks(this.getState().top.opts.id, this.getState().parent_id)

        blocks = _.mapObject(blocks, function(value, key){
            var block_info = _.find(this.store.getState().config.blocks, function(block){
                return block.type == value.type
            })
            if(block_info.setting.custom_layout) {
                value.layout = block_info.setting.custom_layout
            } else if(block_info.setting.child_blocks && block_info.type == 'row') {
                value.layout = 'main'
            } else if(block_info.setting.child_blocks) {
                value.layout = 'medium'
            } else {
                value.layout = 'children'
            }
            return value
        }.bind(this))
        this.setState('blocks', blocks)
    }

    this.initBlocks()

    this.on('mount', function(){
        if(this.store.getState().config.permission[this.getState().top.opts.id]){
            this.initSortable()
        }
    })

    this.on('update', function(){
        this.setState('parent_id', this.opts.block? this.opts.block.id : '')
        this.initBlocks()
        if(this.store.getState().config.permission[this.getState().top.opts.id]){
            this.initSortable()
        }
    }.bind(this))

</script>
</wrapper-blocks>