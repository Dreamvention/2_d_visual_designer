<visual-designer>
    <div class="content vd">
        <div class="vd" id="sortable"><virtual data-is="wrapper-blocks" selector={"#"+top.opts.id+" #sortable"}/></div>
        <virtual if={store.getState().config.permission}>
            <div class="vd-helper">
                <a id="vd-add-button" class="vd-button vd-add-block vd-btn-add" onClick={addBlock}></a>
            </div>
            <div class="vd-welcome">
                <div class="vd-welcome-header"><formatted-message path='designer.text_welcome_header'/></div>
                <div class="vd-button-group">
                    <a id="vd-add-button" class="vd-button vd-add-block" title="Add Element" onClick={addBlock}><formatted-message path='designer.text_add_block'/></a>
                    <a id="vd-add-text-block" class="vd-button vd-add-text-block" title="Add text block">
                        <i class="fa fa-pencil-square-o"></i>
                        <formatted-message path='designer.text_add_text_block'/>
                    </a>
                    <a id="vd-add-template" class="vd-button vd-add-template"><formatted-message path='designer.text_add_template'/></a>
                </div>
                <div class="vc_welcome-visible-ne">
                    <a id="vc_not-empty-add-element" class="vc_add-element-not-empty-button" title="Add Element" data-vc-element="add-element-action" onClick={addBlock}></a>
                </div>
            </div>
        </virtual>
    </div>
    <vd-popup-new-block/>
    <vd-popup-setting-block/>
    <vd-popup-layout-block/>
    <vd-popup-save-template/>
    <vd-popup-load-template/>
    <script>
        this.mixin({store:d_visual_designer})
        this.top = this.parent ? this.parent.top : this
        addBlock() {
            this.store.dispatch('popup/addBlock', {level: 0, parent_id: '', designer_id: this.top.opts.id});
        }
        this.initHover = function(designer_id){
            if(this.store.getState().config.permission){
                $('#sortable',this.root).find('.block-container').off( "mouseenter mouseleave" );
                $('#sortable',this.root).find('.block-container').hover(function(){
                    if($(this).hasClass('block-child')){
                        var margin_left = (-1)*($(this).children('.control').width()/2);
                        var margin_top = (-1)*($(this).children('.control').height()/2);
                        $(this).children('.control').css({
                            'margin-left': margin_left,
                            'margin-top': margin_top
                        })
                    }
                    $(this).removeClass('deactive-control');
                    $(this).addClass('active-control');
                }, function(){
                    $(this).addClass('deactive-control');
                    $(this).removeClass('active-control');
                });
                $('#sortable',this.root).off('mouseover', '.block-button')
                $('#sortable',this.root).on('mouseover', '.block-button', function(){
                    $(this).closest('.block-container').addClass('active-border');
                });
                $('#sortable',this.root).off('mouseout',  '.block-button')
                $('#sortable',this.root).on('mouseout', '.block-button', function(){
                    $(this).closest('.block-container').removeClass('active-border');
                });
            }
        }
        this.store.subscribe('block/create/success', function(){
            this.initHover();
        }.bind(this))
        this.store.subscribe('block/move/success', function(){
            this.initHover();
        }.bind(this))
        this.store.subscribe('block/clone/success', function(){
            this.initHover();
        }.bind(this))
        this.store.subscribe('block/layout/update/success', function(){
            this.initHover();
        }.bind(this))
        $('body').on('designerSave', function(e, data){
            this.store.dispatch('content/save', {designer_id: data.designer_id});
        }.bind(this));
        $('body').on('designerTemplate', function(e, data){
            this.store.dispatch('template/list', {designer_id: data.designer_id});
        }.bind(this));
        $('body').on('designerSaveTemplate', function(e, data){
            this.store.dispatch('template/save/popup', {designer_id: data.designer_id});
        }.bind(this));
        $('body').on('designerAddBlock',function(e, data){
            this.store.dispatch('popup/addBlock', {level: 0, target: '', designer_id: data.designer_id});
        }.bind(this))
        this.on('mount', function(){
            this.initHover();
            if(!this.store.getState().config.permission){
                $('.vd-frontent-text').hide();
            }
        })
    </script>
</visual-designer>