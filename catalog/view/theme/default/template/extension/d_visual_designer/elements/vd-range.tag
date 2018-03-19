<vd-range>
    <div class="range">
        <input type="range" step="{opts.step?opts.step:1}" min="{opts.min?opts.min:1}" max="{opts.max?opts.max:100}" name="font_size_range" value="{opts.riotValue.replace('px','')}" onChange={changeRange} onInput={changeRange}>
        <input type="text" class="form-control" name="{opts.name}" value="{getFullText()}" onchange={change}/>
    </div>
    <script>
        var d = new Date();
        this.previewRangeChange = d.getTime();

        this.getFullText = function(){
            var er = /^-?[0-9]+$/;
            if(er.test(this.opts.riotValue)){
                return this.opts.riotValue+'px'
            }
            return this.opts.riotValue
        }

        changeRange(e) {
            var d = new Date();
            var currentTime = d.getTime();
            if(currentTime - this.previewRangeChange > 100){
                this.opts.evchange({target:{
                    name: opts.name,
                    value: e.target.value
                }});

                var d = new Date();
                this.previewRangeChange = d.getTime();
            }
        }.bind(this)

        change(e) {
            this.opts.evchange(e);
        }.bind(this)
    </script>
</vd-range>