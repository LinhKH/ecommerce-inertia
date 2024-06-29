$(document).ready(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var site_url = $('meta[name="site-url"]').attr('content');

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

    var loader = `<div class="loader-container">
        <div class="loader">
            <span class="loader-inner box-1"></span>
            <span class="loader-inner box-2"></span>
            <span class="loader-inner box-3"></span>
            <span class="loader-inner box-4"></span>
        </div>
    </div>`; 

    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    // show ajax error
    function show_formAjax_error(data){
        if(data.status == 422){
            $('.loader-container').remove();
            $('.error').remove();
            $.each(data.responseJSON.errors, function(i, error) {
                var el = $(document).find('[name="' + i + '"]');
                el.after($('<span class="error">' + error[0] + '</span>'));
            });
        }
    }


    // ========================================
    // script for Signup Form
    // ========================================
    $('#signup_form').validate({
        rules: {
            name: {required:true},
            email: {required:true},
            phone: {required:true},
            password: {required:true},
            con_password: {required:true,equalTo:"#password"},
        },
        message: {
            name: {required: "Please Enter Your Name"},
            email: {required: "Please Enter Your Email"},
            phone: {required: "Please Enter Your Phone Number"},
            password: {required: "Please Enter Your Password"},
        },
        submitHandler: function(form){
            $('#site-content').append(loader);
            var url = $('.url').val();
            var login = $('.url-login').val();
            var formdata = new FormData(form);
            $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(dataResult){
                    if(dataResult == '1'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Signed Up Successfully',
                            showConfirmButton: false,
                            timer: 1500
                          })
                        setTimeout(function(){ window.location = login;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Profile module
    // ========================================
    $('#EditProfile').validate({
        rules: {
            name: { required: true },
            phone: { required: true },
        },
        messages: {
            name: { required: "User Name is required" },
            phone: { required: "Phone is required" },
        },
        submitHandler: function (form) {
            var id = $('.url').val();
            var formdata = new FormData(form);
            $.ajax({
                url: site_url + '/my-profile',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult){
                    if(dataResult == '1') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Profile Updated Succesfully',
                            showConfirmButton: false,
                            timer: 1500
                          })
                        setTimeout(function () { window.location.reload(); }, 3000);
                    }else {
                        $.each(dataResult, function(i, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: dataResult[i],
                              })
                            $('.loader-container').remove();
                        });
                    }
                },
                error: function (dataResult){
                    show_formAjax_error(dataResult);
                }
            });
        }
    });

    // ========================================
    // script for User Change Password
    // ========================================
    $('#changepassword').validate({
        rules: {
            password: {required: true},
            new_pass: {required: true},
            re_pass: {required: true},
        },
        message: {
            password: {required: "Old Password is required."},
            new_pass: {required: "New Password is required."},
            re_pass: {required: "Please Re-enter Correct New Password."},
        },
        submitHandler: function(form){
            var url = $('.url').val();
            var formdata = new FormData(form);
            $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(dataResult){
                    if(dataResult == '1'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Updated Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        setTimeout(function(){
                            window.location.reload();
                        }, 1000);
                    }else{
                        $.each(dataResult, function(i, error){
                            var el = $(document).find('[name="' + i +'"]');
                            el.after($('<span class="error">' + error + '</span>'));
                        });
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Login Form
    // ========================================
    $('#user_login').validate({
        rules: {
            username: {required:true},
            password: {required:true},
        },
        message: {
            username: {required: "Email Address is required"},
            password: {required: "Password is required"},
        },
        submitHandler: function(form){
            $('#site-content').append(loader);
            var formdata = new FormData(form);
            $.ajax({
                url: site_url+'/user_login',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(dataResult){
                    if(dataResult == '1'){
                        insert_cart();
                        Swal.fire({
                            icon: 'success',
                            title: 'Logged In Successfully',
                            showConfirmButton: false,
                            timer: 1500
                          })
                        setTimeout(function(){ window.location.href=document.referrer;}, 2000);
                    }else{
                        $.each(dataResult, function(i, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: dataResult[i],
                              })
                            $('.loader-container').remove();
                        });
                    }
                }
            });
        }
    });

    // ========================================
    // script for User Logout
    // ========================================
    $('.user-logout').click(function(){
        $.ajax({
            url: site_url+'/logout',
            type: 'GET',
            success: function(dataResult){
                if(dataResult == '1'){
                    setTimeout(function(){
                        window.location.reload();
                    }, 500);
                    Swal.fire({
                        icon: 'success',
                        title: 'Logged Out Successfully',
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            }
        });
    });



    // ========================================
    // script for Add to cart
    // ========================================
    $('#addcart').click(function(e){
        e.preventDefault();
        var p_id = $(this).attr('data-id');
        var user = $(this).attr('data-user');
        var location = $('.shipping option:selected').val();

        if(location == ''){
            Swal.fire({
                icon: 'warning',
                title: 'Select Location',
                showConfirmButton: false,
                timer: 1000
            })
        }else{
            if(user != ''){
                var color_id = '';
                if($('input[name=product_color]').length > 0){
                    var color_id = $('input[name="product_color"]:checked').val();
                    if(color_id == ''){
                        alert('Select Color');
                    }
                }
                var attr = '';
                if($('.product-attributes').length > 0){
                    $('.product-attributes').each(function(){
                        var key = $(this).children('input[class=attrvalue]:checked').attr('data-attr');
                        var val = $(this).children('input[class=attrvalue]:checked').val();
                        attr += key+':'+val+','
                    });
                }
                var token = $('meta[name=csrf-token]').attr('content');
                $.ajax({
                    url: site_url + '/save_cart',
                    type: 'POST',
                    data: {_token:token,product_id:p_id,color_id:color_id,attr:attr,location:location},
                    success: function(dataResult){
                        if(dataResult.result == '1'){
                            Swal.fire({
                                icon: 'success',
                                title: 'Product added in your cart',
                                showConfirmButton: false,
                                timer: 1000
                            })
                            $('.cartlist').html(dataResult.count);
                        }
                        if(dataResult == 'false'){
                            setTimeout(function(){ window.location.href = site_url + '/user_login';}, 100);
                        }
                    }
                });
            }else{
                var product_list = [];
                var color_ids = {};
                var attr = {};
                var location = '';
                var product_ids = '';
                if (localStorage.getItem('product_id') !== null) {
                    product_ids = localStorage.getItem('product_id');
                    product_list = product_ids.split(',');
                }
                if(localStorage.getItem('color_ids') !== null){
                    color_ids = localStorage.getItem('color_ids');
                    color_ids = JSON.parse(color_ids);
                }
                if(localStorage.getItem('attr') !== null){
                    attr = localStorage.getItem('attr');
                    attr = JSON.parse(attr);
                }
                if(localStorage.getItem('location') !== null){
                   location = localStorage.getItem('location');
                }
                
                if ($.inArray(p_id, product_list) == -1){
                    product_list.push(p_id);
    
                    if($('input[name=product_color]').length > 0){
                        var color_id = $('input[name="color"]:checked').val();
                        if(color_id != ''){
                            alert('Select Color');
                        }else{
                            if(!color_ids.hasOwnProperty(p_id) || color_ids[p_id] != color_id){
                                color_ids[p_id] = color_id;
                            }
                        }
                    }
                    if($('.product-attributes').length > 0){
                        var attr_val = '';
                        $('.product-attributes').each(function(){
                            var key = $(this).children('input[class=attrvalue]:checked').attr('data-attr');
                            var val = $(this).children('input[class=attrvalue]:checked').val();
                            attr_val += key+':'+val+',';
                        }); 
                        attr[p_id] = attr_val;
                    }
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Product Already in your cart',
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
    
                localStorage.setItem('product_id',product_list);
                localStorage.setItem('color_ids',JSON.stringify(color_ids));
                localStorage.setItem('attr',JSON.stringify(attr));
                localStorage.setItem('location',location);
                Swal.fire({
                    icon: 'success',
                    title: 'Product added in your cart',
                    showConfirmButton: false,
                    timer: 1000
                });
                show_cart_count();
            }
        }
    });
    function show_cart_count(){
        var items = [];
        if (localStorage.getItem('product_id') != null) {
            var items = localStorage.getItem('product_id');
            if(items.length > 1){
                $('.cartlist').html(items.split(',').length);
            }else{
                $('.cartlist').html(0);
            }
        }
        
    }
    show_cart_count();

    // ========================================
    // script for add local cart to database at login time 
    // ========================================
    function insert_cart(){
        if(localStorage.getItem('product_id') != null){
            var products_id = localStorage.getItem('product_id');
            var colors = localStorage.getItem('color_ids');
            var values = localStorage.getItem('attr');
            var location = localStorage.getItem('location');
            var token = $('meta[name=csrf-token]').attr('content');
            $.ajax({
                url: site_url + '/save_cart',
                type: 'POST',
                data : {_token:token,products_id:products_id,color_id:colors,attrvalue:values,location:location,localstorage:1},
                success: function(dataResult){
                    localStorage.removeItem('product_id');
                    localStorage.removeItem('color_ids');
                    localStorage.removeItem('attr');
                    
                }
            });
        }
    }

    // ========================================
    // script for Remove products from cart
    // ========================================
    // $(document).on('click','.remove-cart',function(e){
    //     e.preventDefault();
    //     var product_id = $(this).attr('data-id');
    //     if($(this).attr('data-type')){
            
    //         var items = localStorage.getItem('product_id');
    //         var colors = localStorage.getItem('color_ids');
    //         var values = localStorage.getItem('attr');
    //         var p_ids = items.split(',');
    //         var color_ids = colors.split(',');
    //         var attr = values.split(',');
            
    //         p_ids.splice( $.inArray(product_id, p_ids) );

    //         if(color_ids.hasOwnProperty(product_id)) {
    //             delete color_ids[product_id];
    //         }
    //         if(attr.hasOwnProperty(product_id)) {
    //             delete colors[product_id];
    //         }
    //         if(p_ids.length > 0){
    //             localStorage.setItem('product_id',p_ids);
    //             localStorage.setItem('color_ids',color_ids);
    //             localStorage.setItem('attr',attr);
    //         }else{
    //             localStorage.removeItem('product_id',p_ids);
    //             localStorage.removeItem('color_ids',color_ids);
    //             localStorage.removeItem('attr',attr);
    //         }
    //         window.location.reload();
    //     }else{
    //         var token = $('meta[name=csrf-token]').attr('content');
    //         $.ajax({
    //             url: site_url + '/remove_cart',
    //             type: 'POST',
    //             data: {_token:token,product_id:product_id},
    //             success: function(dataResult){
    //                 if(dataResult == '1'){
    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: 'Product removed from your cart',
    //                         showConfirmButton: false,
    //                         timer: 1000
    //                     })
    //                     setTimeout(function(){ location.reload();}, 1000);
    //                 }
    //             }
    //         });
    //     }
    // });

    //========================================
    // show net amount in cart and checkout page 
    //========================================
    function net_amount(){
        var amount = 0;
        $('.product-total').each(function(){
            var val = $(this).html();
            var total = parseInt(amount) + parseInt(val);
            amount = total;
        });
        $('.total-amount').html(amount);
    }
    net_amount();

    //========================================
    // change amount on quantity change in cart page
    //========================================
    $(document).on('change','.item-qty',function(){
        var qty = $(this).val();
        var price = $(this).siblings('.product-price').val();
        var shipping_price = $(this).siblings('.product-shipping').val();
        var new_price = (qty * price);
        if(shipping_price != 0){
            new_price = (qty * price) + parseInt(shipping_price);
        }
        $(this).parent().siblings().children('.product-total').html(parseInt(new_price));
        net_amount();
    });

    
    //========================================
    // Script for add to wishlist
    //========================================
    $('.addwishlist').click(function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url: site_url + '/add-wishlist',
            type: "POST",
            data: {id:id},
            cache: false,
            success: function (dataResult) {
                $('.wishlist-count').html(dataResult.count);
                if(dataResult.result == '1'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Added to Wishlist',
                        showConfirmButton: false,
                        timer: 1000
                    })
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: dataResult,
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            
            }
        });
    });

    // ===========================
    //Script for remove wishlist
    // ===========================
    $(document).on('click','.remove-wishlist',function(){
        var id = $(this).attr('data-id');
        $('#site-content').append(loader);
        $.ajax({
            url: site_url + '/remove-wishlist',
            type: "POST",
            data: {id:id},
            success: function (dataResult) {
                if(dataResult == '1'){
                    setTimeout(function(){
                        $('.loader-container').remove();
                        window.location.reload();
                    }, 1000);
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Deleted From Wishlist',
                        showConfirmButton: false,
                        timer: 1000
                    })

                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: dataResult,
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            
            }
        });
    });


    // ================================
    // script for show search suggestions
    // ================================
    $('#search').keyup(function(){
        var val = $(this).val();
        var token = $('meta[name=csrf-token]').attr('content');
        if(val.length > 0){
            $.ajax({
                url: site_url + '/get-suggestions',
                type: "POST",
                data: {_token:token,search:val},
                // cache: false,
                success: function (dataResult) {
                    if(dataResult != '0'){
                        $('.search-content').html(dataResult);
                    }else{
                        $('.search-content').empty();
                    }
                }
            });
        }else{
            $('.search-content').empty();
        }
    });

    // =================================
    // Script for submit user review
    // =================================
    $('#createReview').validate({
        rules: {
            title: {required:true},
            review: {required:true},
        },
        message: {
            title: {required: "Please enter your title"},
            review: {required: "Please enter your review"},
        },
        submitHandler: function(form){
            $('#site-content').append(loader);
            var formdata = new FormData(form);
            $.ajax({
                url: site_url+'/review/store',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(dataResult){
                    if(dataResult == '1'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Review Submitted Successfully',
                            showConfirmButton: false,
                            timer: 1500
                          })
                        setTimeout(function(){ window.location.href = site_url+'/my-reviews';}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });


    //==================================
    // get states list according to country
    //==================================
    // $(document).on('change','.select-country',function(){
    //     var token = $('meta[name=csrf-token]').attr('content');
    //     var val = $(this).children('option:selected').data('country');
    //     $.ajax({
    //         url: site_url + '/my-profile/get-state',
    //         type: 'POST',
    //         data: {_token:token,country_id:val},
    //         success:function(dataResult){
    //             $('#state').html(dataResult);
    //         }
    //     })
    // });

    //==================================
    // get cities list according to state
    //==================================
    // $(document).on('change','.select-state',function(){
    //     var token = $('meta[name=csrf-token]').attr('content');
    //     var val = $(this).children('option:selected').data('state');
    //     $.ajax({
    //         url: site_url + '/my-profile/get-city',
    //         type: 'POST',
    //         data: {_token:token,state_id:val},
    //         success:function(dataResult){
    //             $('#city').html(dataResult);
    //         }
    //     })
    // });

    //======================================
    // Script for show order Products list
    //======================================
    $('.show-product').click(function(){
        var id = $(this).attr('data-id');
        var token = $('meta[name=csrf-token]').attr('content');
        $.ajax({
            url: site_url + '/show_order_product',
            type: 'POST',
            data: {_token:token,id:id},
            success:function(dataResult){
                $('.show-product-content').html(dataResult);
            }
        })
    });

    // change address at checkout
    $(document).on('change','.checkout-city',function(){
        $('#form-1').append(loader);
        var city = $(this).val();
        var state = $('.select-state option:selected').val();
        var country = $('.select-country option:selected').val();
        $.ajax({
            url: site_url + '/change_address',
            type: 'POST',
            data: {city:city,state:state,country:country},
            success:function(dataResult){
                setTimeout(function(){ window.location.reload(); },1000);
            }
        })
    });

    $(document).on('click','.product-list span',function(){
        var val = $(this).children('').text();
        var id = $(this).attr('data-id');
        // alert(id);
        $('#product').val(val);
        $('.product-no').val(id);
        $('.product-list').hide();
    });

});



