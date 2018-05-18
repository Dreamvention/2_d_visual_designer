<visual-designer>
    <div class="content vd">
        <div class="vd" id="sortable"><virtual data-is="wrapper-blocks" selector={"#"+top.opts.id+" #sortable"}/></div>
        <virtual if={store.getState().config.permission[top.opts.id]}>
            <div class="vd-helper">
                <a id="vd-add-button" class="vd-button vd-add-block vd-btn-add" onClick={addBlock}><i class="far fa-plus"></i></a>
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
        $('body').on('designerSave', function(e, data){
            if(this.top.opts.id == data.designer_id) {
                this.store.dispatch('content/save', {designer_id: data.designer_id});
            }
        }.bind(this));
        $('body').on('designerTemplate', function(e, data){
            if(this.top.opts.id == data.designer_id) {
                this.store.dispatch('template/list', {designer_id: data.designer_id});
            }
        }.bind(this));
        $('body').on('designerSaveTemplate', function(e, data){
            this.store.dispatch('template/save/popup', {designer_id: data.designer_id});
        }.bind(this));
        $('body').on('designerAddBlock',function(e, data){
            if(this.top.opts.id == data.designer_id) {
                this.store.dispatch('popup/addBlock', {level: 0, target: '', designer_id: data.designer_id});
            }
        }.bind(this))
        this.on('mount', function(){
            if(!this.store.getState().config.permission[this.top.opts.id]){
                $('.vd-frontent-text').hide();
            }
        })
    </script>
</visual-designer>