

// Show Product by ID
function init_modal_product(){
    
    $('.quick-view-popup').click(function (e) { 
        e.preventDefault(); 
        var id = $(this).attr('data-id'); 
        $.ajax({
            type: 'POST', 
            url: "/show/product/"+id,
            data: "data",
            dataType: "JSON",
            success: function (response) {
                console.log("entro a click PRODUCT");
                $(".product-single__title").html(response.nombre);
                $(".codigo").html(response.codigo);
                $(".money_regular").html('$' + ( response.precioVenta + 50 ) );
                $(".money_discount").html('$' + response.precioVenta);
                $(".img-modal-producto").attr('src','/uploads/imagenes/'+response.imagen);
                $("#content_quickview").modal(); 
            }
        });
    });
}

// Show Products by CATEGORY
function init_menu_categories(){
    
    $('.caterogy').click(function (e) { 
        e.preventDefault(); 
        var id = $(this).attr('id');  
        $.ajax({
            type: 'POST', 
            url: "/get/product/type/"+id,
            data: "data",
            dataType: "JSON",
            success: function (response) {
                // console.log(response.length);
                $(".grid-products > .row").html('');
                //$(".grid-products > .row").html(response);
                for (let index = 0; index < response.length; index++) {
                    console.log(response[index]);
                    $(".grid-products > .row").append(`<div class="col-6 col-sm-6 col-md-4 col-lg-3 item d-block">
                                                            <div class="product-image">
                                                            <a href="#">
                                                                <img class="primary blur-up lazyload" data-src="/uploads/imagenes/${response[index].imagen }"  alt="image" title="product">
                                                                <img class="hover blur-up lazyload" data-src="/uploads/imagenes/${response[index].imagen }" alt="image" title="product"> 
                                                                <div class="product-labels rectangular"> 
                                                                    <span class="lbl on-sale">-10%</span>
                                                                    <span class="lbl pr-label1">nuevo</span>
                                                                </div>
                                                            </a>
                                                            <div class="saleTime desktop" data-countdown="2022/03/01"></div>
                                                            <form class="variants add" action="#" onclick="window.location.href='cart.html'" method="post">
                                                                    <button class="btn btn-addto-cart" type="button">Agregar al carrito</button>
                                                            </form>
                                                            <div class="button-set">
                                                                    <a href="#" title="Ver producto" class="quick-view-popup quick-view" data-id="IDPRODUCTO">
                                                                        <i class="icon anm anm-search-plus-r"></i>
                                                                    </a> 
                                                            </div>
                                                            </div> 
                                                            <div class="product-details text-center">
                                                            <div class="product-name">
                                                                    <a href="#">${response[index].nombre} </a>
                                                            </div> 
                                                            <div class="product-price">
                                                                    <span class="old-price">${response[index].precioVenta + 10 } </span>
                                                                    <span class="price">${response[index].precioVenta }</span>
                                                            </div>
                                                        
                                                            <div class="product-review">
                                                                    <i class="font-13 fa fa-star"></i>
                                                                    <i class="font-13 fa fa-star"></i>
                                                                    <i class="font-13 fa fa-star"></i>
                                                                    <i class="font-13 fa fa-star-o"></i>
                                                                    <i class="font-13 fa fa-star-o"></i>
                                                            </div>
                                                            </div> 
                                                            <div class="timermobile">
                                                            <div class="saleTime desktop" data-countdown="2022/03/01"></div>
                                                            </div>
                                                        </div>`);

                 
                } 
                init_modal_product();
            }
        });
    });
}


$(function() {  

    init_modal_product();
    init_menu_categories();

    
    
});