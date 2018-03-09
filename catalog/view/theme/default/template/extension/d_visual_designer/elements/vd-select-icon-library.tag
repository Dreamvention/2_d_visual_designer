<vd-select-icon-library>
    <select class="form-control" name="{opts.name}" onChange={change}>
        <option each={value, key in store.getOptions('designer.libraries')} value="{key}" selected={key == opts.riotValue}>{value}</option>
    </select>
    <script>
        this.mixin({store:d_visual_designer})
        change(e) {
            console.log(e.target.value)
            console.log(this.opts.riotValue)

            if(e.target.value !== this.opts.riotValue) {
                this.opts.change(e)
            }
        }
    </script>
</vd-select-icon-library>