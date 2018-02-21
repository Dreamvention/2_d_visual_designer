(function(){
    this.subscribe('template/save', function(data) {
        var blocks = this.getState().blocks[data.designer_id]
        var send_data = data.setting;
        send_data.setting = JSON.stringify(blocks)
        $.ajax({
            url: 'index.php?route=extension/d_visual_designer/designer/saveTemplate',
            dataType: 'json',
            type: 'post',
            data: send_data,
            context: this,
            success: function(json){
                if(json.success) {
                    this.updateState({templates: json.templates})
                }
                this.dispatch('template/save/success')
            },
            complete: function(){
                $('body').trigger('save_template_success');
            }
        })
    });
    this.subscribe('template/load', function(data) {
        var send_data = {
            config: data.config,
            template_id: data.template_id
        }
        $.ajax({
            url: 'index.php?route=extension/d_visual_designer/designer/getTemplate',
            dataType: 'json',
            type: 'post',
            data: send_data,
            context: this,
            success: function(json){
                if(json.success){
                    var blocks = this.getState().blocks
                    blocks[data.designer_id] = json.setting
                    this.updateState({blocks:blocks})
                }
                this.dispatch('template/load/success')
            },
            complete: function(){
                $('body').trigger('save_content_success');
            }
        })
    });
}.bind(d_visual_designer))()