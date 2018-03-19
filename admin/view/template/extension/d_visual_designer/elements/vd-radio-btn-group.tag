<vd-radio-btn-group>
    <div class="btn-group" data-toggle="buttons">
        <label each={value, key in opts.options} class="btn btn-success {parent.opts.riotValue == key?'active':''}" onClick={changeRadioGroup}>
            <input type="radio" name="{opts.name}" value="{key}" checked={parent.opts.riotValue == key} onChange={change}>{value}
        </label>
    </div>
    <script>
        change(e) {
            this.opts.change(e)
        }
        changeRadioGroup(e){
            this.opts.change({target: {
                name: this.opts.name,
                value: e.target.childNodes[1].value
            }})
        }
    </script>
</vd-radio-btn-group>