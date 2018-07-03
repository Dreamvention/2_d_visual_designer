<vd-popup-save-template>
<div class="vd vd-popup save_template" if={this.status}>
    <div class="popup-header">
        <h2 class="title"><formatted-message path='designer.text_save_template'/></h2>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
    <div class="popup-content">
        <div class="form-group">
            <label class="control-label">{store.getLocal('designer.entry_name')}</label>
            <div class="fg-setting">
                <input type="text" name="name" value="" placeholder="{store.getLocal('designer.entry_name')}" class="form-control" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('designer.entry_category')}</label>
            <div class="fg-setting">
                <input type="text" name="category" value="" placeholder="{store.getLocal('designer.entry_category')}" class="form-control" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('designer.entry_image_template')}</label>
            <div class="fg-setting">
                <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail">
                    <img src="{store.getOptions('designer.placeholder')}" alt="" title="" data-placeholder="{store.getOptions('designer.placeholder')}"/>
                </a>
                <input type="hidden" name="image" value="" id="input-vd-image" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('designer.entry_sort_order')}</label>
            <div class="fg-setting">
                <input type="text" name="sort_order" value="" placeholder="{store.getLocal('designer.entry_sort_order')}" class="form-control" onChange={change}/>
            </div>
        </div>
    </div>
    <div class="popup-footer">
        <a class="vd-btn save" data-loading-text="{store.getLocal('designer.button_saved')}" onClick={saveTemplate}>{store.getLocal('designer.button_save')}</a>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.width = ''
    this.height = ''
    this.left = ''
    this.top = ''

    this.setting = {
        name: '',
        category: '',
        image: '',
        sort_order: ''
    }

    this.closePopup = function () {
        this.status = false;
        this.update();
    }.bind(this)

    this.store.subscribe('block/create/success', function(data){
        this.closePopup()
    }.bind(this))

    this.store.subscribe('block/setting/begin', function(data){
        this.closePopup()
    }.bind(this))
    this.store.subscribe('popup/addBlock', function(data){
        this.closePopup()
    }.bind(this))
    this.store.subscribe('block/layout/begin', function(data){
        this.closePopup()
    }.bind(this))
    this.store.subscribe('template/list', function(data) {
        this.closePopup()
    }.bind(this))

    this.store.subscribe('template/save/popup', function(data){
        if(data.designer_id == this.parent.getState().top.opts.id){
            this.status = true
            this.update()
        }
    }.bind(this))
    this.store.subscribe('template/save/success', function(){
        $('.vd-btn.save', this.root).button('reset')
        this.status = false
        this.update()
    }.bind(this))

    saveTemplate(e) {
        $('.vd-btn.save', this.root).button('loading')
        this.store.dispatch('template/save', {setting: this.setting, designer_id: this.parent.getState().top.opts.id})
    }

    change(e) {
        this.setting[e.target.name] = e.target.value
    }

    this.initPopup = function() {
        $('.vd-popup', this.root).resizable({
            resize: function(event, ui) {
                if(!$('.vd-popup', this.root).hasClass('drag')){
                    this.height = $('.vd-popup', this.root).height()
                    $('.vd-popup', this.root).css({'height': this.height });
                    $('.vd-popup', this.root).addClass('drag')
                }
                
                $('.vd-popup', this.root).css({ 'max-height': '' });
            }.bind(this),
            stop: function( event, ui ) {
                this.width = ui.size.width;
                this.height = ui.size.height;
            }.bind(this)
        });
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if (!ui.helper.hasClass('drag')) {
                    this.height = ui.helper.height()
                    $('.vd-popup', this.root).css({'height': this.height });
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        if (this.width != '' && this.height != '') {
            $('.vd-popup', this.root).css({ 'width': this.width, 'height': this.height });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });

    }.bind(this)
    this.on('updated', function(){
        if(this.status) {
            this.initPopup()
        }
    })

    close() {
        this.status = false
    }
</script>
</vd-popup-save-template>