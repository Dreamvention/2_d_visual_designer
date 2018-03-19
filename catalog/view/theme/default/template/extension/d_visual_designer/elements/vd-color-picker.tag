<vd-color-picker>
    <div id="color" class="input-group colorpicker-component fg-color">
        <input type="text" name="{opts.name}" value="{opts.riotValue}" class="form-control" onChange={change}/>
        <span class="input-group-addon"><i></i></span>
    </div>
    <script>
        var d = new Date();
        this.previewColorChange = d.getTime();

        change(e){
            this.opts.evchange(e)
        }

        this.on('mount', function(){
            $('.colorpicker-component', this.root).colorpicker().on('changeColor', function(e) {
                var d = new Date();
                var currentTime = d.getTime();
                if(currentTime - this.previewColorChange > 500){
                    var event = new Event('change');
                    $('input', this.root)[0].dispatchEvent(event);

                    var d = new Date();
                    this.previewColorChange = d.getTime();
                }
            }.bind(this))
        })
    </script>
</vd-color-picker>