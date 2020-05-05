$(function() {
  $( "#post_type_producto_venta_producto_id" ).change(function() {
    
    

    $.ajax({
        url: '/productos/traer/stock/'+this.value, 
        dataType: 'JSON',
        type: 'POST',
        success: function(data)
        {
          $('#stock_procuto').html("<i class='fas fa-cart-plus'></i> "+data);                        
        }, 
        error: function(x, status, error)
        {
          alert(error);
        }
    });  

  });

});

$('#search').autocomplete({
     
     minLength: 3,
     change: function( event, ui ) {
       /*
       $('input[id="post_type_producto_venta_cantidad"]').empty();
       $('input[id="post_type_producto_venta_cantidad"]').attr('type','hidden');
       $('#label_cantidad').hide(); */
     },
     source:'/search',
     select: function(event, ui)   
     {     
           //$('#post_type_producto_venta').show();
           //$('#post_type_producto_venta_submit').show();
           $('input[id="post_type_producto_venta_producto_id"]').val(ui.item.id);  
           $('input[id="post_type_producto_venta_precio_costo"]').val(ui.item.producto.precio_costo);               
           $('input[id="post_type_producto_venta_precio_venta"]').val(ui.item.producto.precio_venta);
           $('input[id="producto-elegido"]').val( '( '+ui.item.producto.codigo+' ) '+ui.item.producto.nombre );
           
          //  $('input[id="post_type_producto_venta_cantidad"]').attr('type','text');
          //  $('<label id="producto-elegido"> ('+ui.item.producto.codigo+') '+ui.item.producto.nombre +' </label>').insertBefore('input[id="post_type_producto_venta_cantidad"]');
          //  $('<a href="#" id="cerrar-producto-elegido"><i class="far fa-2x fa-times-circle" style="color:#138496; padding-right:10px"></i></a>').insertBefore("#producto-elegido");

           $( "#cerrar-producto-elegido" ).click(function() {
               cerrar_opcion();
           });
     },
     close: function(event, ui) 
     {
          $('#search').val('');
     },
     response: function(event, ui) {

       if (ui.content.length === 0) 
       {   

           /* $('input[id="post_type_producto_venta_cantidad"]').empty();
           $('input[id="post_type_producto_venta_cantidad"]').attr('type','hidden');
           $('#label_cantidad').hide(); */
           
       } 
       else 
       {
            //$('#sin_resultados_profesor').hide();
       }

     }

           

});

 