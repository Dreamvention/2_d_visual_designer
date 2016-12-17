var d_visual_designer = {
    //Настройки
    setting:{
        //url адрес
        url_designer: 'index.php?route=module/d_visual_designer',
        //формы
        form:'',
        //id,
        id:'',
        //field name
        field_name:''
    },
    settings:{},
    //Данные
    data: {
    },
    //Шаблоны
    template:{
        //шаблон колонки
        column:'',
        //шаблон строки
        row:'',
        //шаблон настроек строки
        row_layout:'',
        //шаблон блока
        block:'',
        //шаблон popup окна
        popup:'<div class="vd popup" style="max-height:75vh;"></div>',
        //шаблона фона при popup окне
        popup_overlay:'<div class="popup-overlay"></div>',
        //Шаблон добавления нового блока
        add_block:'',
        //Шаблон каркаса popup окна
        edit_block:'',
        //Шаблон добавления нового шаблона
        add_template:'',
    },
    //Инициализация начальных значений
    init: function(setting){
        that = this;
        this.setting = $.extend({}, this.setting, setting); 
		this.settings[setting.form.attr('id')] =  $.extend({}, this.setting, setting);

        that.initSortable();
        that.initPartial();    
    },
    //инициализация данных
    initData:function(data, designer_id){
        this.data[designer_id]  = $.extend({}, this.data[designer_id], data);
    },
    
    //Инициализация шаблонов
    initTemplate:function(template){
        this.template = $.extend({}, this.template, template);
    },
    //Инициализация Sortable
    initSortable:function(){
        var that = this;
        this.setting.form.find('.block-content:not(.child)').each(function() {
            $(this).sortable({
                forcePlaceholderSize: true,
				forceHelperSize: false,
                connectWith: ".block-content:not(.child)",
                placeholder: "element-placeholder col-sm-12",
                items:".block-child, .block-inner",
                // helper: 'clone',
                helper: function(event, ui){
                    var data = {
                        title:ui.data('title'),
                        image: ui.data('image')
                    };
                    
                    var helper = that.templateСompile(that.template.helper, data);
                    return helper;
                },
                distance: 3,
                scroll: true,
				scrollSensitivity: 70,
                cursor: 'move',
				cursorAt: { top: 20, left: 16 },
                handle:' .drag',
                tolerance: 'intersect',
                activate: function( event, ui ) {
                    var html = $(ui.helper).wrap('<div>').parent().html();
                    console.log(html);
                },
                stop: function( event, ui ) {
                    var designer_id = $(this).parents('.vd.content').attr('id');
                    
                    that.updateSortOrder($(ui.item).closest('.block-inner').attr('id'), designer_id);
                    that.updateSortOrder(designer_id, $(this).parents('.vd.content').attr('id'));
                    that.updateParent($(ui.item).attr('id'), designer_id, $(ui.item).closest('.block-inner').attr('id'));    
                }
            })
        });
        this.setting.form.find("#sortable" ).sortable({
            forcePlaceholderSize: true,
            forceHelperSize: false,
            connectWith: ".block-parent",
            placeholder: "row-placeholder col-sm-12",
            items:"> .block-parent",
            // helper: 'clone',
            helper: function(event, ui){
                var data = {
                    title:ui.data('title'),
                    image: ui.data('image')
                };
                
                var helper = that.templateСompile(that.template.helper, data);
                return helper;
            },
            distance: 3,
            scroll: true,
            scrollSensitivity: 70,
            cursor: 'move',
            cursorAt: { top: 5, left: 5 },
            handle:' > .control > .drag',
            stop: function( event, ui ) {
                that.updateSortOrderRow($(this).parents('.vd.content').attr('id'));
            },
            start: function(event,ui){
                // ui.helper.css({width:'150px',height:'30px'});
                // ui.helper.children('.block-content').remove();
            }
        });
    },
    //обновление родителя
    updateParent:function(block_id, designer_id, parent_id){
        this.data[designer_id][block_id]['parent'] = parent_id;
    },
    //обновление sort_order
    updateSortOrder:function(block_id, designer_id){
        var that = this;
        this.settings[designer_id].form.find('.block-content[data-id=\''+block_id+'\']').children('.block-child, .block-inner').each(function(index, value){
            that.data[designer_id][$(value).attr('id')]['sort_order'] = index;
        });
    },
    //обновление sort_order для строк
    updateSortOrderRow:function(designer_id){
        var that = this;
        this.settings[designer_id].form.find('#sortable').children().each(function(index, value){
            that.data[designer_id][$(value).attr('id')]['sort_order'] = index;
        });
    },
    //Инициализация Handlebars Partial
    initPartial:function(){
        if(window.Handlebars !== undefined){
            console.log('d_visual_designer:init_partials');
            window.Handlebars.registerHelper('select', function( value, options ){
                var $el = $('<select />').html( options.fn(this) );
                $el.find('[value="' + value + '"]').attr({'selected':'selected'});
                return $el.html();
            });      
        }
          
    },
    //Инициализация ColorPicker
    initColorpicker:function(element){
        $(element).find('[id=color-input]').colorpicker();
    },
    //закрыть все popup окна
    closePopup:function(){
        $('.popup').remove();
        $('.popup-overlay').remove();
    },
    //Включение дизайнера
    enable:function(element){
        var designer_id = $(element).data('id');
        this.settings[designer_id].form.removeAttr('style');
        this.settings[designer_id].form.parents('.form-group').find('.note-editor').css('display','none');
    },
    //Выключение дизайнера
    disable:function(element){
        var designer_id = $(element).data('id');
        this.settings[designer_id].form.attr('style','display:none;');
        this.settings[designer_id].form.parents('.form-group').find('.note-editor').css('display','block');
    },
    //Компиляция шаблона
    templateСompile: function(template,data){
        var source = template.html();
        Handlebars.registerHelper('if_eq', function(a, b, opts) {
            if(a == b) // Or === depending on your needs
            return opts.fn(this);
            else
            return opts.inverse(this);
        });
        var template = Handlebars.compile(source);
        var html = template(data);
        return html;
    },
    //Возвращает рандомную строку
    getRandomString:function(){
        return Math.random().toString(36).substring(2,9);
    },
    //Развернуть дизайнер на весь экран
    fullscreen:function(designer_id){
        if(this.settings[designer_id].form.hasClass('fullscreen')){
            this.settings[designer_id].form.removeClass('fullscreen');
            $('body').removeAttr('style');
        }
        else{
            this.settings[designer_id].form.addClass('fullscreen');
            $('body').attr('style','overflow:hidden');
        }
    },
    //Вызов окна добавления блока
    showAddBlock:function(designer_id, target=''){
        
        var level = 0;
        
        var that = this;
        
        if(target!='')
        {
            level = this.getLevelBlock(target,designer_id)+1;
        }
        
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/getBlocks',
            dataType: 'json',
            data: 'level='+level,
            success: function( json ) {
                if(json['success']){
                    
                    var data = json;
                    data['target'] = target;
                    data['designer_id'] = designer_id;
                    data['level'] = level;
                    
                    var content = that.templateСompile(that.template.add_block, json);
                    $('footer').before(content);
                    // that.setting.form.append(that.template.popup_overlay);
                    $('.popup').resizable({
                        resize:function(event,ui){
                            $('.popup').css({'max-height':''});
                        }
                    });
                    $('.popup').draggable({
                        handle:'.popup-header'
                    });
                }
            }
        });    
    },
    //Добавление блока
    addBlock: function(type, title, target, designer_id,level) {
        var that = this;
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/getModule',
            dataType: 'json',
            data: 'type='+type+'&parent='+target+'&level='+level,
            success: function( json ) {
                if(json['success']){
                    
                    console.log('d_visual_designer:add_block');
                    
                    var setting = JSON.parse(json['setting']);
                    
                    for (var key in setting) {
                        that.data[designer_id][key] = setting[key];
                    }
                    console.log(that.data[designer_id]);
                    
                    // if(target == ''){
                    //     that.settings[designer_id].form.find('.vd#sortable').append(json['content']);
                    // }
                    // else{
                    //     that.settings[designer_id].form.find('.vd#sortable').find('#'+target+' > .block-content').find('.block-content-empty').remove();
                    //     that.settings[designer_id].form.find('.vd#sortable').find('#'+target+' > .block-content').append(json['content']);
                    //     var block =  that.settings[designer_id].form.find('.vd#sortable').find('#'+target+' > .block-content').children('.block-container:last');
                    //     if(block.children('.control').size() == 1){
                    //             that.editBlock(block.children('.control').find('a#button_edit'), designer_id);
                    //     }
                    //     else{
                    //             that.editBlock(block.children('.control-integr').find('a#button_edit'), designer_id);
                    //     }
                    // }
                    if(target == ''){
                        that.settings[designer_id].form.find('.vd#sortable').append(json['content']);
                    }
                    else{
                        that.settings[designer_id].form.find('.vd#sortable').find('.block-content[data-id=\''+target+'\']').append(json['content']);
                    }
                    var block =  that.settings[designer_id].form.find('.vd#sortable').find('#'+json['target']);
                    if(block.children('.control').size() == 1){
                            that.editBlock(block.children('.control').find('a#button_edit'), designer_id);
                    }
                    else{
                            that.editBlock(block.children('.control-integr').find('a#button_edit'), designer_id);
                    }

                }
                that.initSortable();
                that.closePopup();
            }
        });    
    },
    //Добавить children блок
    addChildBlock:function(block_id,designer_id){
        var block_info = this.data[designer_id][block_id];
        var level = this.getLevelBlock(block_id,designer_id)+1;
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/getChildBlock',
            dataType: 'json',
            data: 'type='+block_info['type']+'&parent='+block_id+'&level='+level,
            success: function( json ) {
                
                console.log('d_visual_designer:add_child_block');
                
                var setting = JSON.parse(json['setting']);
                
                for (var key in setting) {
                    that.data[designer_id][key] = setting[key];
                }
                
                that.settings[designer_id].form.find('.vd#sortable').find('.block-content[data-id=\''+block_id+'\']').append(json['content']);
                
                that.updateContentBlock(block_id, designer_id);
            }
        });
    },
    //Вызов окна редактирование блока
    editBlock: function(element, designer_id){
        
        var block_id = $(element).closest('.block-container').data('id');
        var that = this;
        
        var block_info = that.data[designer_id][block_id];
        
        if(that.data[designer_id][block_id]['setting'].length == 0){
            var send_data = {};
        }
        else{
            var send_data = that.data[designer_id][block_id]['setting'];
        }

    
        send_data['type'] = block_info['type'];
        
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/getSettingModule',
            dataType: 'json',
            data: send_data,
            success: function( json ) {
                if(json['success']){
                    var data = {
                        'module_setting': json['content'],
                        'block_id': block_id,
                        'designer_id': designer_id,
                        'type' : block_info['type']
                    };
                    data = Object.assign(data,block_info['setting']);
                    var html = that.templateСompile(that.template.edit_block,data);
                    $('footer').before(that.template.popup);
                    $('.popup').html(html);
                    // that.settings[designer_id].form.append(that.template.popup_overlay);
                    that.initColorpicker($('.popup'));
                    $('.popup').resizable({
                        resize:function(event,ui){
                            $('.popup').css({'max-height':''});
                        }
                    });
                    $('.popup').draggable({
                        handle:'.popup-header'
                    });
                }
            }
        });
    },
    //сохранение настроек блока
    saveBlock:function(block_id,designer_id){
        
        var that = this;
        
        // this.settings[designer_id].form.find('.popup a#save').addClass('loading');
        this.data[designer_id][block_id]['setting'] = $('.popup').find('input[name]:not([class^=note]),textarea[name]:not([class^=note]),select[name]:not([class^=note])').serializeObject();
        this.updateContentBlock(block_id, designer_id);
        
    },
    //Удаление выбранного блока
    removeBlock:function(element, designer_id){
        console.log('d_visual_designer:remove_block');
        var block_id = $(element).closest('.block-container').data('id');
        
        var trigger_data = {
            'title' : that.settings[designer_id].form.find('#'+block_id).data('title')
        };
        
        if(this.data[designer_id][block_id]['child']){
            var parent_id = this.data[designer_id][block_id]['parent'];
        }
        
        delete this.data[designer_id][block_id];
        
        if(parent_id != undefined){
            this.updateContentBlock(parent_id, designer_id);
        }
        
        $(element).parent().parent().remove();
        
        
        $('body').trigger('remove_block_success', trigger_data);
    },
    //Возвращает уровень влдожености блока
    getLevelBlock:function(block_id, designer_id){
        
        var level = 0;
        
        var parent = this.data[designer_id][block_id]['parent'];
        
        while(parent != ''){
            parent = this.data[designer_id][parent]['parent'];
            level++;
        }
        return level;
        
    },
    //Вызов окна добавление шаблона
    showAddTemplate:function(designer_id){
        var that = this;
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/getTemplates',
            dataType: 'json',
            success: function( json ) {
                if(json['success']){
                    
                    var data = json;
                    
                    data['designer_id'] = designer_id;
                    
                    var content = that.templateСompile(that.template.add_template, data);
                    $('footer').before(content);
                    // that.setting.form.append(that.template.popup_overlay);
                    $('.popup').resizable({
                        resize:function(event,ui){
                            $('.popup').css({'max-height':''});
                        }
                    });
                    $('.popup').draggable({
                        handle:'.popup-header'
                    });
                }
            }
        });    
    },
    //Добавление шаблона
    addTemplate:function(template_id, designer_id){
        var that = this;
        
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/getTemplate',
            dataType: 'json',
            data:{'template_id':template_id},
            success: function( json ) {
                if(json['success']){
                     that.settings[designer_id].form.find('.vd.container-fluid').html(json['content']);
                     that.data[designer_id] = json['setting'];
                     that.closePopup();
                }
            }
        });
    },

    //Сохранения шаблона
    saveTemplate:function(){
        var designer_id = $('.popup input[name=designer_id]').val();
        var content = this.getText(designer_id,'');
        var template_description = $('.popup input[name^=\'template_description\']').serializeObject();
        
        var send_data = {
            template_description,
            'content': content
        }
        
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/saveTemplate',
            dataType: 'json',
            data: send_data,
            success: function( json ) {
                if(json['success']){
                    that.closePopup();
                    $('body').trigger('save_template_success')
                }
                
            }
        });    
    },   
    //Поиск блоков
    search:function(text, items, target){
        console.log(text);
        $(items).addClass('hide');
        $(items).each(function(){
            var content = $(this).find(target).text();
            var content = content.toLowerCase();
            if(content.indexOf(text) != -1){
                $(this).removeClass('hide');
            }
        });
    },
    //Возвращает массив дочерних блоков
    getChildBlock:function(block_id, designer_id, child=false){
        
        if(!child){
            this.tmpSetting = {'items':{},'relateds':{}};
        }
        
        var results = this.getBlockByParent(designer_id, block_id);
        if(!$.isEmptyObject(results)){
            this.tmpSetting['relateds'][block_id] = [];
            this.tmpSetting['items'][block_id] = {};
            this.tmpSetting['items'][block_id] = this.data[designer_id][block_id];
            this.tmpSetting['items'][block_id]['level'] = this.getLevelBlock(block_id,designer_id);
            this.tmpSetting['items'][block_id]['block_id'] = block_id;
            for (var key in results) {
                this.tmpSetting['relateds'][block_id].push(key);
                this.tmpSetting['items'][key] = that.data[designer_id][key];
                this.tmpSetting['items'][key]['level'] = this.getLevelBlock(key,designer_id);
                this.tmpSetting['items'][key]['block_id'] = key;
                this.getChildBlock(key,designer_id, true);
            }
        }
        else{
            this.tmpSetting['items'][block_id] = {};
            console.log(this.data[designer_id][block_id]);
            this.tmpSetting['items'][block_id] = this.data[designer_id][block_id];
            this.tmpSetting['items'][block_id]['level'] = this.getLevelBlock(block_id,designer_id);
            this.tmpSetting['items'][block_id]['block_id'] = block_id;
        }
    },
    //Обновление содержимого
    updateContentBlock:function(block_id, designer_id){
        var that = this;
                
        var block_info = this.data[designer_id][block_id];
        if(block_info['child']){
            block_id = block_info['parent'];
        }
        
        this.getChildBlock(block_id,designer_id);
        
        var setting = {
            'blocks':this.tmpSetting,
            'main_block_id': block_id
        };
        
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/getContent',
            dataType: 'json',
            data: setting,
            success: function( json ) {
                if(json['success']){                
                    $('.popup a#save').button('loading')
                    $('.popup a#save').addClass('saved');
                    console.log('d_visual_designer:update_content_block');
                    that.setting.form.find('#'+block_id).replaceWith(json['content']);
                    setTimeout(function(){
                        $('.popup a#save').button('reset')
                        $('.popup a#save').removeClass('saved');
                        
                    },2000);
                }
            }
        });    
    },
    //Возвращает массив дочерних блоков
    getCloneBlock:function(block_id, designer_id, block_id_new = false, parent_id = false, child=false){
        
        if(!child){
            this.tmpSetting = {'items':{},'relateds':{}};
        }
        
        var results = this.getBlockByParent(designer_id, block_id);
        
        if(!$.isEmptyObject(results)){
            
            this.tmpSetting['relateds'][block_id_new] = [];
            this.tmpSetting['items'][block_id_new] = {};
            this.tmpSetting['items'][block_id_new] = jQuery.extend({}, this.data[designer_id][block_id]);
            this.tmpSetting['items'][block_id_new]['level'] = this.getLevelBlock(block_id, designer_id);
            this.tmpSetting['items'][block_id_new]['parent'] = parent_id;
            this.tmpSetting['items'][block_id_new]['block_id'] = block_id_new;
            
            for (var key in results) {
                var new_child_block_id = this.data[designer_id][key]['type']+'_'+this.getRandomString();
                this.tmpSetting['relateds'][block_id_new].push(new_child_block_id);
                this.tmpSetting['items'][new_child_block_id] = jQuery.extend({}, this.data[designer_id][key]);
                this.tmpSetting['items'][new_child_block_id]['level'] = this.getLevelBlock(key, designer_id);
                this.tmpSetting['items'][new_child_block_id]['parent'] = block_id_new;
                this.tmpSetting['items'][new_child_block_id]['block_id'] = new_child_block_id;
                this.getCloneBlock(key, designer_id, new_child_block_id, block_id_new, true);
            }
        }
        else{
            this.tmpSetting['items'][block_id_new] = {};
            
            this.tmpSetting['items'][block_id_new] = jQuery.extend({}, this.data[designer_id][block_id]);
            this.tmpSetting['items'][block_id_new]['level'] = this.getLevelBlock(block_id, designer_id);
            this.tmpSetting['items'][block_id_new]['parent'] = parent_id;
            this.tmpSetting['items'][block_id_new]['block_id'] = block_id_new;
        }
    },
    //Клонирование блока
    cloneBlock:function(designer_id, block_id){
        var that = this;
        
        var new_block_id = this.data[designer_id][block_id]['type']+'_'+this.getRandomString();
        var parent_id = this.data[designer_id][block_id]['parent'];
        this.getCloneBlock(block_id, designer_id, new_block_id, parent_id);
        
        var setting = {
            'blocks':this.tmpSetting,
            'main_block_id': new_block_id
        };
        
        
        $.ajax({
            type: 'post',
            url: 'index.php?route=module/d_visual_designer/getContent',
            dataType: 'json',
            data: setting,
            success: function( json ) {
                if(json['success']){                
                    console.log('d_visual_designer:clone_content_block');
                    Object.assign(that.data[designer_id],that.tmpSetting['items']);
                    that.settings[designer_id].form.find('#'+block_id).after(json['content']);
                    that.initSortable();
                    var trigger_data = {
                        'title' : that.settings[designer_id].form.find('#'+new_block_id).data('title')
                    };
                    $('body').trigger('clone_block_success', trigger_data);
                }
            }
        });    
    },
    
    saveContent:function(designer_id){
        var text = this.getText(designer_id);
        var setting = this.settings[designer_id];
        
        var send_data = {};
        send_data[setting.field_name] = text;
        
        this.settings[designer_id].form.find('input[type=hidden][name=status_save]').val(1);
        
        $.ajax({
            type: 'post',
            url:setting.edit_url+'&id='+setting.id,
            dataType:'json',
            data: send_data,
            success:function(json){
                that.settings[designer_id].form.find('input[type=hidden][name=status_save]').val(0);
                console.log(json);
                
                $('body').trigger('save_content_success');
            }
        });
    },
    //Задание параметра для блока
    setValue: function(block_id, designer_id, name, value){
        this.data[designer_id][block_id]['setting'][name] = value;
    },
    //Получение текста из формы
    getText: function(designer_id,parent = ""){;
        
        var results = this.getBlockByParent(designer_id, parent);
        
        var result = '';
        
        for (var key in results) {

            var countChild = this.getCountBlockWithParent(designer_id,key);
            var shortcode = this.getShortcode(results[key], countChild>0?true:false);
            if(countChild == 0){
                result+=shortcode;
            }
            else{
                var childBlock = this.getText(designer_id, key);
                parentBlock = shortcode.replace('][',']'+childBlock+'[');
                result+=parentBlock;
            }
        }
        return result;
    },
    //Сортировка объекта
    sortProperties:function(obj)
    {
        var sortable=[];
        for(var key in obj)
        if(obj.hasOwnProperty(key))
        sortable.push([key, obj[key]]); 
        
        sortable.sort(function(a, b)
        {
            return a[1]['sort_order']-b[1]['sort_order']; 
        });
        
        var result = {};
        
        for(key in sortable){
            result[sortable[key][0]] = sortable[key][1];
        }
        
        return result; 
    },
    //Возвращает блоки с указаным родителем
    getBlockByParent:function(designer_id, parent){
        var results = {};
        $.each(this.data[designer_id], function( index, value ) {
            if(value.parent === parent){
                results[index] = value;
            }
        });
        results = this.sortProperties(results);
        return results;
    },
    //Возвращает количество болков с указаным родителем
    getCountBlockWithParent:function(designer_id, parent){
        var count = 0;
        $.each(this.data[designer_id], function( index, value ) {
            if(value.parent === parent){
                count++;
            }
        });
        return count;
    },
    //возвращет шорткод
    getShortcode:function(block_info, child){
        var type = block_info['type'];
        var setting = block_info['setting'];
        var shortcode = '[';
        shortcode += 'vd_'+type;
        for (var key in setting) {
            var name  =key;
            var value = setting[key];
            if(value instanceof Array || value instanceof Object){
                var array_values = this.convert(name, value);
                
                for (var key2 in array_values){
                    name = key2.replace('][',':');
                    name = name.replace('[','::');
                    name = name.replace(']','');
                    if(array_values[key2] != ''){
                        shortcode+= ' '+name+'=\''+array_values[key2]+'\''+' ';
                    }
                }
            }
            else{
                if(value != ''){
                    shortcode+= ' '+name+'=\''+value+'\''+' ';
                }
            }
        }
        if(!child){
            shortcode += '/]';
        }
        else{
            shortcode += '][/vd_'+type+']';
        }
        return shortcode;
    },
    convert:function(key, obj) {
        var collector = {};
        
        function recurse(key, obj) {
            var property, name;
            if( typeof obj === "object" ) {
                for( property in obj ) {
                    if( obj.hasOwnProperty(property) ) {
                        name = key + "[" + property + "]";
                        recurse(name, obj[property]);
                    }
                }
            } else {
                collector[key] = String(obj);
            }
        }
        
        recurse(key, obj);
        return collector;
    },
};


