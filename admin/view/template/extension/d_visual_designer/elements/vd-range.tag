<vd-range>
    <div class="range">
        <input type="range" step="{opts.step?opts.step:1}" min="{opts.min?opts.min:1}" max="{opts.max?opts.max:100}" name="font_size_range" value="{opts.riotValue.replace('px','')}" onChange={changeRange} onInput={changeRange}>
        <input type="text" class="form-control" name="{opts.name}" value="{opts.riotValue}" onChange={change}/>
    </div>
    <script>
        var d = new Date();
        this.previewColorChange = d.getTime();

        changeRange(e) {
            $('input[type=text]',this.root).val(e.target.value + 'px')
            this.update()
        }.bind(this)

        change(e) {
            var d = new Date();
            var currentTime = d.getTime();
            if(currentTime - this.previewColorChange > 100){
                this.opts.change(e);
                 var d = new Date();
                this.previewColorChange = d.getTime();
            }
        }.bind(this)
    </script>
</vd-range>