<vd-product >
    <div class="image">
        <a href="{opts.product.href}">
            <img src="{opts.product.thumb}" alt="{opts.product.name}" title="{opts.product.name}" class="img-responsive" />
        </a>
    </div>
    <div class="caption">
        <h4><a href="{opts.product.href}">{opts.product.name}</a></h4>
        <p>{opts.product.description}</p>
        <div class="rating" if={opts.product.rating}>
            <virtual each={rating in [1,2,3,4,5]}>
                    <span if={opts.product.rating < rating} class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                    <span if={opts.product.rating >= rating} class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
            </virtual>
        </div>
        <p if={opts.product.price} class="price">
            <virtual if={!opts.product.special}>{opts.product.symbolLeft}{opts.product.price}{opts.product.symbolRight}</virtual>
            <span if={opts.product.special} class="price-new">{opts.product.symbolLeft}{opts.product.special}{opts.product.symbolRight}</span> <span class="price-old">{opts.product.symbolLeft}{opts.product.price}{opts.product.symbolRight}</span>
            <span if={opts.product.tax} class="price-tax">{store.getLocal('designer.text_tax')} {opts.product.symbolLeft}{opts.product.tax}{opts.product.symbolRight}</span>
        </p>
    </div>
    <div class="button-group">
        <button type="button" onclick="cart.add('{opts.product.product_id}');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">{store.getLocal('designer.button_cart')}</span></button>
        <button type="button" data-toggle="tooltip" title="{store.getLocal('designer.button_wishlist')}" onclick="wishlist.add('{opts.product.product_id}');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="{store.getLocal('designer.button_compare')}" onclick="compare.add('{opts.product.product_id}');"><i class="fa fa-exchange"></i></button>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
    </script>
</vd-product>