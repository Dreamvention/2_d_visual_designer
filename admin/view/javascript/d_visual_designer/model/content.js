(function(){
    this.initContent = function(){
        $('.d_visual_designer').each(function(index, el){
            var send_data = {
                field_name: el.name,
                content: el.value,
                id: this.getState().config.id,
                route: this.getState().config.route
            }
            $.ajax({
                url: 'index.php?route=extension/d_visual_designer/designer/loadSetting&'+this.getState().config.url_token,
                data: send_data,
                type: 'post',
                dataType: 'json',
                context: this,
                beforeSend: function() {
                    $(el).closest('div').append('<div id="visual-designer-loader" class="la-ball-scale-ripple-multiple la-dark la-2x"><div></div><div></div><div></div></div>');
                    if ($(el).next().hasClass('note-editor')) {
                        $(el).next().fadeTo('slow', 0.5);
                    }else if ($(el).next().hasClass('cke')) {
                        $(el).next().fadeTo('slow', 0.5);
                    }  else {
                        $(el).fadeTo('slow', 0.5);
                    }
                },
                success: function(json){
                   if(json.success) {
                        var content = this.getState().content
                        content[json.designer_id] = json.content
                        this.updateState({content: content})

                        if(json.text) {
                            this.dispatch('content/update/text', {designer_id: json.designer_id, text: json.text})
                        }

                        var blocks = this.getState().blocks
                        blocks[json.designer_id] = json.blocks
                        this.updateState({blocks: blocks})
                        $(el).before('<visual-designer id="'+json.designer_id+'" class="'+json.designer_id+'"></visual-designer>');
                        riot.mount('visual-designer')
                        this.dispatch('content/designer/init', {designer_id: json.designer_id})
                        setTimeout(function(){
                            this.dispatch('content/mode/update', {designer_id: json.designer_id, 'mode': 'designer'})
                        }.bind(this), 500)
                       
                   }
                },
                complete: function(){
                    $(el).closest('div').find('div#visual-designer-loader').remove();
                    if ($(el).next().hasClass('note-editor')) {
                        $(el).next().fadeTo('slow', 1);
                    } else if ($(el).next().hasClass('cke')) {
                        $(el).next().fadeTo('slow', 1);
                    } else {
                        $(el).fadeTo('slow', 1);
                    }
                }
            })

        }.bind(this));
    };

    this.subscribe('block/setting/update/end', function(data){
        this.dispatch('designer/update/content', {designer_id: data.designer_id})
    });

    this.subscribe('content/mode/update',function(data){
        var config = this.getState().config
        config.mode[data.designer_id] = data.mode
        this.updateState({config: config})
        this.dispatch('content/mode/update/success', {designer_id: data.designer_id})
    });

    this.subscribe('content/update',function(data){
        var content = this.getState().content
        content[data.designer_id] = data.content
        this.updateState({content: content})
        this.dispatch('designer/update/blocks', {designer_id: data.designer_id, post_action: ['content/update/success'].concat(data.post_action)})
    });

    this.subscribe('content/codeview', function(data) {
        this.dispatch('designer/update/content', {designer_id: data.designer_id, post_action: ['content/codeview/success']})
    })
}.bind(d_visual_designer))()