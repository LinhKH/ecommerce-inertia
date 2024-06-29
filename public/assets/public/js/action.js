$(document).ready(function(){
    var uRL = $('.base-url').val();

    function show_formAjax_error(data){
        if (data.status == 422) {
            $('.error').remove();
            $.each(data.responseJSON.errors, function (i, error) {
                var el = $(document).find('[name="' + i + '"]');
                el.after($('<span class="error">' + error[0] + '</span>'));
            });
        }
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ========================================
    // script for Upload Resume module
    // ========================================

    $(document).on('click', '.ShowResume', function () {
        $('#exampleModal').modal('show');
    });

    $('#jobApplie').validate({
        rules: { resume: { required: true }, },
        messages: { resume: { required: "Job Applie Resume is required" }, },
        submitHandler: function (form) {
            $('.message').empty();
            var formdata = new FormData(form);
            $.ajax({
                url: uRL+'/job_applie',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    if (dataResult == '1') {
                        $('.message').append('<div class="alert alert-success">Resume Upload Successful.</div>');
                        setTimeout(function () { $('#exampleModal').modal('hide'); }, 3000);
                        setTimeout(function () { window.location.href = uRL + '/jobs'; }, 3000);
                    } else {
                        $('.message').append('<div class="alert alert-danger">'+dataResult+'</div>');
                    }
                },
                error: function(data){
                    show_formAjax_error(data)
                }
            });
        }
    });

    // ========================================
    // script for User SignUp module
    // ========================================

    $('#user-signup').validate({
        rules: {
            username: { required: true },
            email: { required: true },
            password: { required: true },
        },
        messages: {
            username: { required: "User Name is required" },
            email: { required: "Email Address is required" },
            password: { required: "Password is required" },
        },
        submitHandler: function (form) {
            $('.message').empty();
            var formdata = new FormData(form);
            $.ajax({
                url: uRL+'/signup',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    if (dataResult == '1') {
                        $('.message').append('<div class="alert alert-success">Please Check Your Email to Activate Your Account</div>');
                        setTimeout(function(){ window.location.href = uRL+'/login'; }, 2000);
                    } else {
                        $('.message').append('<div class="alert alert-danger">'+dataResult+'</div>');
                    }
                },
                error: function(data){
                    show_formAjax_error(data)
                }
            });
        }
    });

     // ========================================
    // script for User Login module
    // ========================================

    $('#user-login').validate({
        rules: {
            email: { required: true },
            password: { required: true }
        },
        messages: {
            email: { required: "Email Address is required" },
            password: { required: "Password is required" }
        },
        submitHandler: function (form) {
            $('.message').empty();
            var formdata = new FormData(form);
            $.ajax({
                url: uRL + '/login',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    if (dataResult == '1') {
                        $('.message').append('<div class="alert alert-success">Logged In Succesfully.</div>');
                        setTimeout(function(){ window.location.href = uRL; }, 2000);
                    } else {
                        $('.message').append('<div class="alert alert-danger">'+dataResult+'</div>');
                    }
                },
                error: function(data){
                    show_formAjax_error(data)
                }
            });
        }
    });

    // ========================================
    // script for Change Password User module
    // ========================================
    $('#updatePassword').validate({
        rules: {
            password: { required: true },
            new_pass: { required: true },
            new_confirm: { required: true }
        },
        messages: {
            password: { required: "Password is required" },
            new_pass: { required: "New Password is required" },
            new_confirm: { required: "New Confirm Password is required" }
        },
        submitHandler: function (form) {
            $('.message').empty();
            var formdata = new FormData(form);
            $.ajax({
                url: uRL + '/change-password',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    if (dataResult == '1') {
                        $('.message').append('<div class="alert alert-success">Password Changed Succesfully.</div>');
                        setTimeout(function(){ window.location.href = uRL + '/profile'; }, 3000);
                    }
                    else {
                        $('.message').append('<div class="alert alert-danger">'+dataResult+'</div>');
                    }
                },
                error: function(data){
                    show_formAjax_error(data)
                }
            });
        }
    });

    // ========================================
    // script for User Forgot Password module
    // ========================================

    $('#user-forgotPassword').validate({
        rules: { email: { required: true } },
        messages: { email: { required: "Email Address is required" } },
        submitHandler: function (form) {
            $('.message').empty();
            var formdata = new FormData(form);
            $.ajax({
                url: uRL+'/forgot-password',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    $('.message').append('<div class="alert alert-danger">'+dataResult+'</div>');
                },
                error: function(data){
                    show_formAjax_error(data);
                }
            });
        }
    });

    // ========================================
    // script for User Reset Password module
    // ========================================
    $('#user-resetPassword').validate({
        rules: {
            password: { required: true } ,
            confirm_password: { required: true }
        },
        messages: {
            password: { required: "password is required" },
            confirm_password: { required: "Confirm password is required" },
        },
        submitHandler: function (form) {
            $('.message').empty();
            var formdata = new FormData(form);
            $.ajax({
                url: uRL+'/update-password',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    if (dataResult == '1') {
                        $('.message').append('<div class="alert alert-success">Success.</div>');
                        setTimeout(function(){ window.location.href = uRL + '/login'; }, 3000);
                    } else {
                       $('.message').append('<div class="alert alert-danger">'+dataResult+'</div>');
                    }
                },
                error: function(data){
                    show_formAjax_error(data);
                }
            });
        }
    });

    // ========================================
    // script for Change Password User module
    // ========================================
    $('#updatePassword').validate({
        rules: {
            password: { required: true },
            new_pass: { required: true },
            new_confirm: { required: true }
        },
        messages: {
            password: { required: "Password is required" },
            new_pass: { required: "New Password is required" },
            new_confirm: { required: "New Confirm Password is required" }
        },
        submitHandler: function (form) {
            $('.message').empty();
            $('form').append(loader);
            var formdata = new FormData(form);
            $.ajax({
                url: uRL + '/change-password',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    if (dataResult == '1') {
                        $('.message').append('<div class="alert alert-success">Password Changed Succesfully.</div>');
                        setTimeout(function(){ window.location.href = uRL + '/profile'; }, 3000);
                    }
                    else {
                         $('.loader-container').remove();
                       $('.message').append('<div class="alert alert-danger">'+dataResult+'</div>');
                    }
                },
                error: function(data){
                    $('.loader-container').remove();
                    show_formAjax_error(data)
                }
            });
        }
    });

   // ========================================
    // script for Update Profile module
    // ========================================

    $(document).on('click', '.ShowProfile', function () {
        $('#exampleModal').modal('show');
    });

    $('#EditProfile').validate({
        rules: { username: { required: true }, },
        messages: { username: { required: "User Name is required" }, },
        submitHandler: function (form) {
            var id = $('#exampleModal input[name=id]').val();
            $('.message').empty();
           var formdata = new FormData(form);
            $.ajax({
                url: uRL + '/profile/' + id,
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    if (dataResult == '1') {
                        $('.message').append('<div class="alert alert-success">Updated Succesfully.</div>');
                        setTimeout(function () { $('#exampleModal').modal('hide'); }, 3000);
                        setTimeout(function () { window.location.href = uRL + '/profile'; }, 3000);
                    } else {
                        $('.message').append('<div class="alert alert-danger">' + dataResult + '</div>');
                    }
                },
                error: function (dataResult) {

                    show_formAjax_error(dataResult);
                }
            });
        }
    });

     // ========================================
    // script for Add to cart
    // ========================================
    $('#addcart').click(function(e){
        e.preventDefault();
        var p_id = $(this).attr('data-id');
        var qty = $('.qty').val();
        var user = $(this).attr('data-user');
        var _token = $('meta[name=csrf-token]').attr("content");
        $.ajax({
            url: uRL + '/save_cart',
            type: 'POST',
            data: {_token:_token,product_id:p_id,product_qty:qty},
            success: function(dataResult){
                if(dataResult.result == '1'){
                        Swal.fire({
                        icon: 'success',
                        title: 'Product added in your Cart',
                        showConfirmButton: false,
                        timer: 1000
                    })
                    $('.cartlist').html(dataResult.count);
                }
                if(dataResult == 'false'){
                    setTimeout(function(){ window.location.href = uRL + '/login';}, 100);
                }
            }
        });
    });

    function net_amount() {
        var amount = 0;
        $('.product-total').each(function () {
            var val = $(this).html();
        //    alert(val);
            var total = parseInt(amount) + parseInt(val);
            amount = total;
        });
        $('.grand-total').html(amount);
    };

    // Triggered when the quantity of an item is changed
    $(document).on("change", ".item_qty", function () {
        var id = $('.cart_id').val();
        //alert(id);
        var qty = $(this).val();
        var price = $(this).siblings('.product-price').val();
        var taxRate = parseFloat($('.tax').html()); // Retrieve tax rate as a decimal
        var new_price = (qty * price);
        var total = new_price * taxRate; // Calculate the tax amount
        $(this).parent().siblings('.product-total').html(parseInt(total));
        $('.grand-total').html(total);
        net_amount();
        // Send an AJAX request to update the quantity in the cart database
        $.ajax({
            url: uRL + '/cart/change_qty',
            type: 'Post',
            data: {cart_id:id,product_qty:qty},
            success: function(dataResult){
                if(dataResult.result == '1'){
                        Swal.fire({
                        icon: 'success',
                        title: 'Product Quantity is added',
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
                if(dataResult == 'false'){
                    setTimeout(function(){ window.location.href = uRL + '/cart';}, 100);
                }
            }
        });
    });

     // ========================================
    // script for Remove products from cart
    // ========================================
    $(document).on('click','.remove-cart',function(e){
        e.preventDefault();
        var product_id = $(this).attr('data-id');
        var token = $('meta[name=csrf-token]').attr('content');
        $.ajax({
            url: uRL  + '/remove_cart',
            type: 'POST',
            data: {_token:token,product_id:product_id},
            success: function(dataResult){
                if(dataResult == '1'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Product removed from your cart',
                        showConfirmButton: false,
                        timer: 1000
                    })
                    setTimeout(function(){ location.reload();}, 1000);
                }
            }
        });
    });

     // ========================================
    // script for Add to Order Products
    // ========================================
    $('#Checkout').click(function(e) {
        e.preventDefault();
        var records = [];
        // Iterate over each record's elements and collect the data
        $('.record').each(function() {
            var $record = $(this);
            var product_id = $record.find('.id').val();
            var qty = $record.find('.item_qty').val();
            var sub_total = $record.find('.product-total').html();
            // Create an object for each record and push it to the array
            var recordData = {
                product_id: product_id,
                product_qty: qty,
                sub_total: sub_total
            };
            records.push(recordData);
        });
        var _token = $('meta[name=csrf-token]').attr("content");
        $.ajax({
            url: uRL + '/cart/save_order',
            type: 'POST',
            data: {_token: _token,records:records},
            success: function(dataResult) {
                if (dataResult == '1') {
                    setTimeout(function () { window.location.href = uRL + '/checkout'; }, 3000);
                }else
                setTimeout(function () { window.location.href = uRL + '/cart'; }, 3000);
            }
        });
    });

  // ========================================
    // script for Add to Order Products
    // ========================================
    $('#confirmOrder').click(function(e) {
        e.preventDefault();
        var amount = $('.grand-total').html();
        var _token = $('meta[name=csrf-token]').attr("content");
        $.ajax({
            url: uRL + '/checkout/save_order',
            type: 'POST',
            data: {_token: _token,amount:amount},
            success: function(dataResult) {
                if (dataResult == '1') {
                    setTimeout(function () { window.location.href = uRL + '/success'; }, 3000);
                }else
                setTimeout(function () { window.location.href = uRL + '/checkout'; }, 3000);
            }
        });
    });


});
