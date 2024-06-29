$(document).ready(function(){

    // message methods
    function messageHide(){
        $('.message').animate({ opacity: 0,top: '0px' }, 'slow');
        setTimeout(function(){ $(".message").html(''); }, 1000);
    }
    messageHide();

    function messageShow(data){
        $(".message").html(data);
        $('.message').animate({ opacity: 1,top: '60px' }, 'slow');

        setTimeout(function(){ messageHide() }, 3000);
    }

    



    //Script for add cart
    $('.addcart').click(function() {
        var product_id = [];
        var color_ids = {};
        var total_prices = {};
        var shipping_charges = {};
        var id = $(this).attr('data-id');
        var color_id = $('input[name="color"]:checked').val();
        var total_price = $('input[name="product_total"]').val();
        var shipping_charge = $('input[name="shipping_price"]').val();
        var attr = {};
       
        if (localStorage.getItem('product_id') === null) {
            product_id = [];
        }else if(localStorage.getItem('color_ids') === null){
            color_ids = {};
        }else if(localStorage.getItem('attr') === null){
            attr = {};
        }else if(localStorage.getItem('total_prices') === null){
            total_prices = {};
        }else if(localStorage.getItem('shipping_charges') === null){
            shipping_charges = {};
        }else{
             var items = localStorage.getItem('product_id');
             var colors = localStorage.getItem('color_ids');
             var values = localStorage.getItem('attr');
             var price = localStorage.getItem('total_prices');
             var shipping_price = localStorage.getItem('shipping_charges');
             product_id = items.split(',');
             color_ids = JSON.parse(colors);
            //  attr = values.split(',');
            attr = JSON.parse(values);
            //  total_prices = price.split(',');
            total_prices = JSON.parse(price);
            shipping_charges = JSON.parse(shipping_price);
            // shipping_charges = shipping_price.split(',');
        }
        if ($.inArray(id, product_id) != -1)
        {
            messageShow("<div class='alert alert-danger'>Product is already exist.</div>");
        }else{ 
            messageShow("<div class='alert alert-success'>Add Cart successfully.</div>");
            product_id.push(id);
            // color_ids.push(color_id);
            color_ids[id] = color_id;
            total_prices[id] = total_price;
            shipping_charges[id] = shipping_charge;
            // total_prices.push(total_price);
            // shipping_charges.push(shipping_charge);

            attr[id] = '';
            $('.product-size').each(function(){
                var val = $(this).children('input[class=attrvalue]:checked').val();
                attr[id] += val+',';
            });
            show_cart_count();
            localStorage.setItem('product_id',product_id);
            localStorage.setItem('color_ids',JSON.stringify(color_ids));
            localStorage.setItem('attr',JSON.stringify(attr));
            localStorage.setItem('total_prices',JSON.stringify(total_prices));
            localStorage.setItem('shipping_charges',JSON.stringify(shipping_charges));
        }
    });








});