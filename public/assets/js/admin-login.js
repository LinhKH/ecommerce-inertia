$(function () {
    var Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
    });

    $("#adminLogin").validate({
        rules: {
            username: { required: true },
            password: { required: true },
        },
        messages: {
            username: { required: "Username is required" },
            password: { required: "Password is required" },
        },
        submitHandler: function (form) {
            var url = $(".url").val();
            var formdata = new FormData(form);
            $.ajax({
                url: url + "/admin",
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (dataResult) {
                    if (dataResult == "1") {
                        Toast.fire({
                            icon: "success",
                            title: "Logged In Succesfully.",
                        });
                        setTimeout(function () {
                            window.location.href = url + "/admin/dashboard";
                        }, 3000);
                    } else {
                        $.each(dataResult, function (i, error) {
                            console.log(dataResult);
                            var el = $(document)
                                .find('[name="' + i + '"]')
                                .css("border-color", "red");
                            Toast.fire({
                                icon: "error",
                                title: error,
                            });
                        });
                    }
                },
            });
        },
    });
});
