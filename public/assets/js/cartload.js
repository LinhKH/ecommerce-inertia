


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(window).on('load', function(){
        var products_id = localStorage.getItem('product_id');
        var colors = localStorage.getItem('color_ids');
        var values = localStorage.getItem('attr');
        var product_price = localStorage.getItem('total_prices');
        var url = $('.demo').val();
        $.ajax({
            url: url + '/save_cart',
            type: 'POST',
            data : {products_id:products_id,color_id:colors,attrvalue:values,product_price:product_price,locastorage:1},
            success: function(dataResult){
                
            }
        });
    
    });

   