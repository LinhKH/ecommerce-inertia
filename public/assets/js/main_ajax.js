$(function(){
    var origin = window.location.origin;
    var path = window.location.pathname.split('/');
    // var uRL  = origin+'/'+path[1]+'/';
    var uRL = $('.demo').val();
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    var loader = `<div class="loader-container">
    <div class="loader">
        <span class="loader-inner box-1"></span>
        <span class="loader-inner box-2"></span>
        <span class="loader-inner box-3"></span>
        <span class="loader-inner box-4"></span>
    </div>
</div>`; 

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

      // delete data common function
      function destroy_data(name,url){
        var el = name;
        var id= el.attr('data-id');
        var dltUrl = url+id;
        if(confirm('Are you Sure Want to Delete This')){
            $.ajax({
                url: dltUrl,
                type: "DELETE",
                cache: false,
                // dataType: 'json',
                success: function (dataResult) {
                    if (dataResult == '1') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Deleted Successfully.'
                        });
                        el.parent().parent('tr').remove();
                        setTimeout(function(){ window.location.reload();}, 1000);
                    }else{
                        Toast.fire({
                            icon: 'danger',
                            title: dataResult
                        });
                    }
                }
            });
        }
    }

    function show_formAjax_error(data){
        if(data.status == 422){
            
            $('.error').remove();
            $.each(data.responseJSON.errors, function(i, error) {
                var el = $(document).find('[name="' + i + '"]');
                el.after($('<span class="error">' + error[0] + '</span>'));
            });
        }
    }

    // ========================================
    // script for Admin Logout
    // ========================================
    $('.admin-logout').click(function(){
        var url = $('meta[name="site-url"]').attr('content');
        $.ajax({
            url: url+'/admin/logout',
            type: 'GET',
            cache: false,
            success: function(dataResult){
                if(dataResult == '1'){
                    setTimeout(function(){
                        window.location.href = url+'/admin';
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Logged Out Successfully.'
                    });
                }
            }
        }); 
    });

    // ========================================
    // script for General Settings
    // ========================================
    $('#updateGeneralSetting').validate({ 
        rules:{
            site_name: {required: true},
            site_title: {required: true},
            site_copyright: {required: true},
            currency: {required: true},
        },
        message: {
            site_name: {required: "Please Enter Site Name"},
            site_title: {required: "Please Enter Site Title"},
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
                success: function(dataResult){
                    setTimeout(function(){ window.location.href = url;}, 1000);
                    Toast.fire({
                        icon: 'success',
                        title: 'Updated Successfully.'
                    });
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Profile Update
    // ========================================
    $('#updateProfileSetting').validate({
        rules: {
            admin_name: {required: true},
            admin_email: {required: true},
            username: {required: true},
        },
        messages: {
            admin_name: {required: "Please Enter Admin Name"},
            admin_email: {required: "Please Enter Admin Email"},
            username: {required: "Please Enter Admin Username"},
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
                success: function(dataResult){
                    setTimeout(function(){ window.location.href = url;}, 1000);
                    Toast.fire({
                        icon: 'success',
                        title: 'Updated Successfully.'
                    });
                        
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Admin Update Password
    // ========================================
    $('#updateAdminPassword').validate({
        rules: {
            password: { required: true },
            new_pass: { required: true },
            re_pass: { required: true, equalTo: "#new-pass" },
        },
        messages: {
            password: { required: "Old Password is Required" },
            new_pass: { required: "New Password is Required" },
            re_pass: { required: "Please Re-enter Correct New Password" }
        },
        submitHandler: function (form) {
            var url = $('.p-url').val();
            var formdata = new FormData(form);
            $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (dataResult) {
                    if (dataResult == '1') {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Succesfully.'
                        })
                    }else{
                        $.each(dataResult, function (i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="error">' + error + '</span>'));
                        });
                    }
                },
                error: function (data) {
                    if (data.status == 422) {
                        $.each(data.responseJSON.errors, function (i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="error">' + error[0] + '</span>'));
                        });
                    }
                }
            });
        }
    });

    // ========================================
    // script for Add Banner Slider
    // ========================================
    $('#add_banner').validate({
        rules: {
            title: {required: true},
            pagelink: {required: true},
            banner_status: {required: true},
            img: {required: true},
        },
        message: {
            pagelink: {required: "Please Enter Banner Page Link"},
            img: {required: "Please Upload Banner Image"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });


    // ========================================
    // script for Update Banner Slider
    // ========================================
    $('#update_banner').validate({
        rules: {
            pagelink: {required: true},
            banner_status: {required: true},
            // img: {required: true},
        },
        message: {
            pagelink: {required: "Please Enter Banner Page Link"},
            banner_status: {required: "Please Enter Banner Status"},
            // img: {required: "Please Enter Banner Image"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Banner Slider
    // ========================================
    $(document).on("click", ".delete-banner", function(){
        destroy_data($(this),'banner/')
    });


    // ========================================
    // script for Signup Form
    // ========================================
    $('#signup_form').validate({
        rules: {
            name: {required:true},
            email: {required:true},
            phone: {required:true},
            city: {required:true},
            state: {required:true},
            code: {required:true},
            country: {required:true},
            password: {required:true},
        },
        message: {
            name: {required: "Please Enter Your Name"},
            email: {required: "Please Enter Your Email"},
            phone: {required: "Please Enter Your Phone Number"},
            city: {required: "Please Enter Your City"},
            state: {required: "Please Enter Your State"},
            code: {required: "Please Enter Your Pin Code"},
            country: {required: "Please Enter Your Country"},
            password: {required: "Please Enter Your Password"},
        },
        submitHandler: function(form){
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
                        Toast.fire({
                            icon: 'success',
                            title: 'Signup Successfully.'
                        });
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
            var url = $('.url').val();
            var formdata = new FormData(form);
            $.ajax({
                url: url+'/user_login',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Login Successfully.'
                        });
                        setTimeout(function(){ window.location.href=document.referrer;}, 2000);
                    }else{
                        $.each(dataResult, function(i, error) {
                            var el = $(document).find('[name="' + i + '"]').css('border-color','red');
                                Toast.fire({
                                    icon: 'error',
                                    title: error
                            })
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
        var url = $('meta[name="site-url"]').attr('content');
        $.ajax({
            url: url+'/logout',
            type: 'GET',
            cache: false,
            success: function(dataResult){
                if(dataResult == '1'){
                    setTimeout(function(){
                        window.location.href = url+'/';
                    }, 500);
                    Toast.fire({
                        icon: 'success',
                        title: 'Logged Out Successfully.'
                    })
                }
            }
        });
    });

    // ========================================
    // script for Add Brand
    // ========================================
    $('#add_brand').validate({
        rules: {
            name: {required: true},
            brand_cat: {required: true},
        },
        message: {
            name: {required: "Please Enter Brand Name"},
            brand_cat: {required: "Please Select Brand Category"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Brand
    // ========================================
    $('#update_brand').validate({
        rules: {
            name: {required: true},
            brand_status: {required: true},
        },
        message: {
            name: {required: "Please Enter Brand Name"},
            brand_status: {required: "Please Enter Brand Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Brand
    // ========================================
    $(document).on("click", ".delete-brand", function(){
        destroy_data($(this),'brand/')
    });


    // ========================================
    // script for Add Category
    // ========================================
    $('#add_category').validate({
        rules: {
            name: {required: true},
            // category_status: {required: true},
        },
        message: {
            name: {required: "Please Enter Category Name"},
            // category_status: {required: "Please Enter Category Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Category
    // ========================================
    $('#update_category').validate({
        rules: {
            name: {required: true},
            // category_status: {required: true},
        },
        message: {
            name: {required: "Please Enter Category Name"},
            // category_status: {required: "Please Enter Category Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Category
    // ========================================
    $(document).on("click", ".delete-category", function(){
        destroy_data($(this),'category/')
    });

    // ========================================
    // script for Add Sub Category
    // ========================================
    // $('#add_subcategory').validate({
    //     rules: {
    //         name: {required: true},
    //         parent_cat: {required: true},
    //         subcategory_status: {required: true},
    //     },
    //     message: {
    //         name: {required: "Please Enter Sub Category Name"},
    //         parent_cat: {required: "Please Enter Parent Category"},
    //         subcategory_status: {required: "Please Enter Sub Category Status"},
    //     },
    //     submitHandler: function(form){
    //         var url = $('.url').val();
    //         var formdata = new FormData(form);
    //         $.ajax({
    //             url: url,
    //             type: 'POST',
    //             data: formdata,
    //             processData: false,
    //             contentType: false,
    //             success: function(dataResult){
    //                 if(dataResult == '1'){
    //                     Toast.fire({
    //                         icon: 'success',
    //                         title: 'Added Successfully.'
    //                     });
    //                     setTimeout(function(){ window.location = url;}, 1000);
    //                 }
    //             },
    //             error: function (error) {
    //                 show_formAjax_error(error);
    //             }
    //         });
    //     }
    // });

    // ========================================
    // script for Update Sub Category
    // ========================================
    // $('#update_subcategory').validate({
    //     rules: {
    //         name: {required: true},
    //         parent_cat: {required: true},
    //         subcategory_status: {required: true},
    //     },
    //     message: {
    //         name: {required: "Please Enter Sub Category Name"},
    //         parent_cat: {required: "Please Enter Parent Category"},
    //         subcategory_status: {required: "Please Enter Sub Category Status"},
    //     },
    //     submitHandler: function(form){
    //         var url = $('.url').val();
    //         var formdata = new FormData(form);
    //         $.ajax({
    //             url: url,
    //             type: 'POST',
    //             data: formdata,
    //             processData: false,
    //             contentType: false,
    //             success: function(dataResult){
    //                 if(dataResult == '1'){
    //                     Toast.fire({
    //                         icon: 'success',
    //                         title: 'Updated Successfully.'
    //                     });
    //                     setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
    //                 }
    //             },
    //             error: function (error) {
    //                 show_formAjax_error(error);
    //             }
    //         });
    //     }
    // });

    // ========================================
    // script for Delete Category
    // ========================================
    // $(document).on("click", ".delete-subcategory", function(){
    //     destroy_data($(this),'sub-category/')
    // });

    // ========================================
    // script for Add Tax
    // ========================================
    $('#add_tax').validate({
        rules: {
            percent: {required: true},
            tax_status: {required: true},
        },
        message: {
            percent: {required: "Please Enter Tax Percent"},
            tax_status: {required: "Please Enter Tax Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Tax
    // ========================================
    $('#update_tax').validate({
        rules: {
            percent: {required: true},
            tax_status: {required: true},
        },
        message: {
            percent: {required: "Please Enter Tax Percent"},
            tax_status: {required: "Please Enter Tax Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Category
    // ========================================
    $(document).on("click", ".delete-tax", function(){
        destroy_data($(this),'tax/')
    });

    // ========================================
    // script for Add Colors
    // ========================================
    $('#add_color').validate({
        rules: {
            color_name: {required: true},
            color_code: {required: true},
        },
        message: {
            color_name: {required: "Please Enter Color Name"},
            color_code: {required: "Please Enter Color Code"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Colors
    // ========================================
    $('#update_color').validate({
        rules: {
            color_name: {required: true},
            color_code: {required: true},
        },
        message: {
            color_name: {required: "Please Enter Color Name"},
            color_code: {required: "Please Enter Color Code"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Category
    // ========================================
    $(document).on("click", ".delete-color", function(){
        destroy_data($(this),'colors/')
    });

    // ========================================
    // script for Add Attribute
    // ========================================
    $('#add_attribute').validate({
        rules: {
            title: {required: true},
        },
        message: {
            title: {required: "Please Enter Attribute Title"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Attribute
    // ========================================
    $('#update_attribute').validate({
        rules: {
            title: {required: true},
        },
        message: {
            title: {required: "Please Enter Attribute Title"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Attribute
    // ========================================
    $(document).on("click", ".delete-attribute", function(){
        destroy_data($(this),'attribute/')
    });

    // ========================================
    // script for Add Attribute Values
    // ========================================
    $('#add_attr_value').validate({
        rules: {
            attribute: {required: true},
            value: {required: true},
        },
        message: {
            title: {required: "Please Enter Attribute"},
            value: {required: "Please Enter Attribute Value"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Attribute Values
    // ========================================
    $('#update_attr_value').validate({
        rules: {
            attribute: {required: true},
            value: {required: true},
        },
        message: {
            title: {required: "Please Enter Attribute"},
            value: {required: "Please Enter Attribute Value"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Attribute Value
    // ========================================
    $(document).on("click", ".delete-attrvalue", function(){
        destroy_data($(this),'attribute-values/')
    });

     // ========================================
    // script for Add Country
    // ========================================
    $('#add_country').validate({
        rules: {
            name: {required: true},
            code: {required: true},
            country_status: {required: true},
        },
        message: {
            name: {required: "Please Enter Country Name"},
            code: {required: "Please Enter Country Code"},
            country_status: {required: "Please Enter Country Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Country
    // ========================================
    $('#update_country').validate({
        rules: {
            name: {required: true},
            code: {required: true},
            country_status: {required: true},
        },
        message: {
            name: {required: "Please Enter Country Name"},
            code: {required: "Please Enter Country Code"},
            country_status: {required: "Please Enter Country Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Country
    // ========================================
    $(document).on("click", ".delete-country", function(){
        destroy_data($(this),'countries/')
    });

    // ========================================
    // script for Add State
    // ========================================
    $('#add_state').validate({
        rules: {
            name: {required: true},
            country: {required: true},
            state_status: {required: true},
        },
        message: {
            name: {required: "Please Enter State Name"},
            country: {required: "Please Enter Country"},
            state_status: {required: "Please Enter State Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update State
    // ========================================
    $('#update_state').validate({
        rules: {
            name: {required: true},
            country: {required: true},
            state_status: {required: true},
        },
        message: {
            name: {required: "Please Enter State Name"},
            country: {required: "Please Enter Country"},
            state_status: {required: "Please Enter State Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Country
    // ========================================
    $(document).on("click", ".delete-state", function(){
        destroy_data($(this),'states/')
    });

    // ========================================
    // script for Add City
    // ========================================
    $('#add_city').validate({
        rules: {
            name: {required: true},
            state: {required: true},
            cost: {required: true},
            city_status: {required: true},
        },
        message: {
            name: {required: "Please Enter City Name"},
            state: {required: "Please Enter State"},
            cost: {required: "Please Enter Cost"},
            city_status: {required: "Please Enter City Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update City
    // ========================================
    $('#update_city').validate({
        rules: {
            name: {required: true},
            state: {required: true},
            cost: {required: true},
            city_status: {required: true},
        },
        message: {
            name: {required: "Please Enter City Name"},
            state: {required: "Please Enter State"},
            cost: {required: "Please Enter Cost"},
            city_status: {required: "Please Enter City Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Country
    // ========================================
    $(document).on("click", ".delete-city", function(){
        destroy_data($(this),'cities/')
    });

    // ========================================
    // script for Add Page
    // ========================================
    $('#add_page').validate({
        rules: {
            title: {required: true},
            page_status: {required: true},
        },
        message: {
            title: {required: "Please Enter Page Title"},
            page_status: {required: "Please Enter Page Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Page
    // ========================================
    $('#update_page').validate({
        rules: {
            title: {required: true},
            page_status: {required: true},
        },
        message: {
            title: {required: "Please Enter Page Title"},
            page_status: {required: "Please Enter Page Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Country
    // ========================================
    $(document).on("click", ".delete-page", function(){
        destroy_data($(this),'pages/')
    });

    $(document).on('click','.show-in-header',function(){
        var id = $(this).attr('id');
        if($('#'+id).is(':checked')){
           var status = 1;
        }else{
            var status = 0;
        }
        id = id.replace('head','');
        $.ajax({
            url: uRL + '/admin/page_showIn_header',
            type: 'POST',
            data: {id:id,status:status},
            success: function (dataResult) {
            }
        });
    })

    $(document).on('click','.show-in-footer',function(){
        var id = $(this).attr('id');
        if($('#'+id).is(':checked')){
           var status = 1;
        }else{
            var status = 0;
        }
        id = id.replace('foot','');
        $.ajax({
            url: uRL + '/admin/page_showIn_footer',
            type: 'POST',
            data: {id:id,status:status},
            success: function (dataResult) {
            }
        });
    })

    // ========================================
    // script for Add Product
    // ========================================
    $(document).on('change','.attribute-select',function(){
        var val = $(this).children('option:selected').data('attribute');
        // var val = $(this).val();
        var attr_value = $(this).data('attr_value');
        $.ajax({
            url: uRL + '/admin/get-attrvalue',
            type: 'POST',
            data: {attribute:val},
            success:function(dataResult){
                $('#attrvalue'+attr_value).html(dataResult);
            }
        })
    });

    $(document).on('click','.attrvalue-select',function(){
        // var elements = $(this).data('attr_values');
        var elements = $(this).data('attr_values');
    });

    $('#add_product').validate({
        rules: {
            product_name: {required: true},
            category: {required: true},
            min_qty: {required: true},
            tags: {required: true},
            unit_price: {required: true},
            tax: {required: true},
            quantity: {required: true},
            shipping_charges: {required: true},
            shipping_days: {required: true},
        },
        submitHandler: function(form){
            var url = $('.url').val();
            var formdata = new FormData(form);
            formdata.append('gallery', $('input[name^=gallery]').prop('files'));
            formdata.append('taxable_price', $('input[name=taxable_price]').val());
            $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Product
    // ========================================
    $('#update_product').validate({
        rules: {
            product_name: {required: true},
            category: {required: true},
            min_qty: {required: true},
            tags: {required: true},
            unit_price: {required: true},
            tax: {required: true},
            quantity: {required: true},
            shipping_charges: {required: true},
            shipping_days: {required: true},
        },
        submitHandler: function(form){
            var url = $('.url').val();
            var formdata = new FormData(form);
            formdata.append('gallery', $('input[name^=gallery1]').prop('files'));
            $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Product
    // ========================================
    $(document).on("click", ".delete-product", function(){
        destroy_data($(this),'products/')
    });

    //User Change Status
    $(document).on('click','.userBlock',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        if(status == '1'){
            status = '0';
        }else{
            status = '1';
        }
        $.ajax({
            url: uRL+'/admin/users/block',
            type: 'POST',
            data: {uId:id,status:status},
            success: function(dataResult){
                location.reload();
            }
        });
    });

    //Order Confirm
    $(document).on('click','.deliverConfirm',function(){
        var product_id = $(this).attr('data-id');
        var order_id = $(this).attr('data-order-id');
        var qty = $(this).attr('data-qty');
        $.ajax({
            url: uRL+'/admin/order-product/delivered',
            type: 'POST',
            data: {product_id:product_id,order_id:order_id,qty:qty},
            success: function(dataResult){
                location.reload();
            }
        });
    });

    // ========================================
    // script for Add Payment Method
    // ========================================
    $('#add_payment_method').validate({
        rules: {
            payment_name: {required: true},
            payment_status: {required: true},
            img: {required: true},
        },
        message: {
            payment_name: {required: "Please Enter Payment Method Name"},
            payment_status: {required: "Please Enter Payment Method Status"},
            img: {required: "Please Enter Payment Logo"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Update Payment Method
    // ========================================
    $('#update_payment_method').validate({
        rules: {
            payment_name: {required: true},
            payment_status: {required: true},
        },
        message: {
            payment_name: {required: "Please Enter Payment Method Name"},
            payment_status: {required: "Please Enter Payment Method Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Payment Method
    // ========================================
    $(document).on("click", ".delete-payment-method", function(){
        destroy_data($(this),'payment-method/')
    });

    //Payment Method Change Status
    $(document).on('click','.paymentStatus',function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        $.ajax({
            url: uRL+'/admin/payment-method/status',
            type: 'POST',
            data: {payment_id:id,payment_status:status},
            success: function(dataResult){
                location.reload();
            }
        });
    });



    // ========================================
    // script for flash deals
    // ========================================

    $('#products').on('change',function(){
        var val = $(this).val();
        $.ajax({
            url: uRL + '/admin/get-flash',
            type: 'POST',
            data: {flash:val},
            success:function(dataResult){
                $('#flash').append(dataResult);
            }
        })
    });

    $('#editProducts').on('change',function(){
        var val = $(this).val();
        var id = $('input[name=flash_id]').val();
        $.ajax({
            url: uRL + '/admin/get-flash-edit',
            type: 'POST',
            data: {flash:id,products:val},
            success:function(dataResult){
                $('#flash').html(dataResult);
            }
        })
        // var old_val = $('input[name=old_products]').val().split(',');
        // old_val = old_val.filter(function (el) {
        //     return el != null && el != "";
        // });

        // var val_len = val.length;
        // var old_val_len = old_val.length;

        // if(old_val_len > val_len){
        //     var diff = $(old_val).not(val).get();
        //     for(var i=0;i<diff.length;i++){
        //         if($('#prd'+diff[i]).length > 0){
        //             $('#prd'+diff[i]).remove();
        //         }
        //     }
        // }else if(val_len > old_val_len){
        //     var diff = $(val).not(old_val).get();
        //     for(var i=0;i<diff.length;i++){
        //         if($('#prd'+diff[i]).length > 0){
        //             $('#prd'+diff[i]).remove();
        //         }
        //     }
        //     $('#flash tr').each(function(){
        //         var id = $(this).attr("id").split('prd');
        //         if(val.indexOf(id[1]) < 0){
        //             $('#prd'+id[1]).remove();
        //         }
        //     })
        //     get_products_in_flash(diff);
        // }else{
        //     $('#flash tr').each(function(){
        //         var id = $(this).attr("id").split('prd');
        //         if(val.indexOf(id[1]) < 0){

        //             $('#prd'+id[1]).remove();
        //         }
        //     })
        // }
        
        // var val = $(this).val();
        // get_products_in_flash(val);
    });

    // ========================================
    // script for Add Flash Deals
    // ========================================
    $('#add_flash_deal').validate({
        rules: {
            title: {required: true},
            img: {required: true},
            datetimes: {required: true},
            products: {required: true},
            flash_status: {required: true},
        },
        message: {
            title: {required: "Please Enter Flash Deal Title"},
            img: {required: "Please Enter Flash Deal Image"},
            datetimes: {required: "Please Enter Flash Deal Discount Date Range"},
            products: {required: "Please Enter Flash Deal Products"},
            flash_status: {required: "Please Enter Flash Deal Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Added Successfully.'
                        });
                        setTimeout(function(){ window.location = url;}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

     // ========================================
    // script for Update Flash Deals
    // ========================================
    $('#update_flash_deal').validate({
        rules: {
            title: {required: true},
            datetimes: {required: true},
            flash_status: {required: true},
        },
        message: {
            title: {required: "Please Enter Flash Deal Title"},
            datetimes: {required: "Please Enter Flash Deal Discount Date Range"},
            flash_status: {required: "Please Enter Flash Deal Status"},
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
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = $('.rdt-url').val();}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });

    // ========================================
    // script for Delete Flash Deal
    // ========================================
    $(document).on("click", ".delete-flash-deal", function(){
        destroy_data($(this),'flash-deals/')
    });


    // ========================================
    // script for Social Links
    // ========================================
    $('#update_social').validate({
        submitHandler: function(form){
            var formdata = new FormData(form);
            $.ajax({
                url: uRL + '/admin/social-settings',
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.reload();}, 1000);
                    }
                },
                error: function(error){
                    show_formAjax_error(error);
                }
            });
        }
    });


    // ========================================
    // script for flash deals
    // ========================================
    $('#products').on('change',function(){
        var val = $(this).val();
        $.ajax({
            url: uRL + '/admin/get-banner',
            type: 'POST',
            data: {banner:val},
            success:function(dataResult){
                $('#banner-product').append(dataResult);
            }
        })
    });



    // $('.view-review').on('click',function(){
    $(document).on('click','.view-review',function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: uRL + '/admin/view_review',
            type: 'POST',
            data: {view:id},
            success:function(dataResult){
                $('.view-review-modal .modal-body').html(dataResult);
                $('.view-review-modal').modal('show');
            }
        })
    });

    $(document).on('click','.approve-review',function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: uRL + '/admin/approve_review',
            type: 'POST',
            data: {approve:id},
            success:function(dataResult){
                window.location.reload();
            }
        })
    });

    $(document).on('click','.delete-review',function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: uRL + '/admin/delete_review',
            type: 'POST',
            data: {delete:id},
            success:function(dataResult){
                window.location.reload();
            }
        })
    });

    $('#update_review').validate({
        rules: {
            title: {required: true},
            desc: {required: true},
            rating: {required: true},
        },
        submitHandler: function(form){
            var id = $('.id').val();
            var formdata = new FormData(form);
            $.ajax({
                url: uRL+'/admin/reviews/'+id,
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(dataResult){
                    if(dataResult == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated Successfully.'
                        });
                        setTimeout(function(){ window.location.href = uRL+'/admin/reviews'}, 1000);
                    }
                },
                error: function (error) {
                    show_formAjax_error(error);
                }
            });
        }
    });


});