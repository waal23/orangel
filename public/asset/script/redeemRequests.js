$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".redeemrequestsSideA").addClass("activeLi");

    $("#table-pending").dataTable({
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchPendingRedeems`,
            data: function (data) {},
        },
    });

    $("#table-completed").dataTable({
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchCompletedRedeems`,
            data: function (data) {},
        },
    });



    $("#table-pending").on("click",".delete-cat",function(event) {

        event.preventDefault();
        swal({
                title: `${app.sure}`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Item Delete Successfully", {
                        icon: "success",
                    });

                    if (user_type == "1") {

                        var cat_id = $(this).attr("rel");
                        var delete_cat_url = `${domainUrl}deleteRedeemRequest`+"/"+cat_id;

                        $.getJSON(delete_cat_url).done(function(data) {
                            console.log(data);
                            $('#table-22').DataTable().ajax.reload(null, false);
                        });
                    } else {
                        iziToast.error({
                            title: app.Error,
                            message: app.tester,
                            position: 'topRight'
                        });
                    }

                } 
            });

    });

    $("#table-completed").on("click",".delete-cat",function(event) {

        event.preventDefault();
        swal({
                title: `${app.sure}`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Item Delete Successfully", {
                        icon: "success",
                    });

                    if (user_type == "1") {

                        var cat_id = $(this).attr("rel");
                        var delete_cat_url = `${domainUrl}deleteRedeemRequest`+"/"+cat_id;

                        $.getJSON(delete_cat_url).done(function(data) {
                            console.log(data);
                            $('#table-completed').DataTable().ajax.reload(null, false);
                        });
                    } else {
                        iziToast.error({
                            title: app.Error,
                            message: app.tester,
                            position: 'topRight'
                        });
                    }

                } 
            });

    });

    $("#table-completed").on("click",".view-request",function(event) {
        event.preventDefault();

        var  id = $(this).attr('rel');

        // $('#editId').val($(this).attr('rel'));

        var url =  `${domainUrl}getRedeemById`+"/"+id;

        $.getJSON(url).done(function(data) {


           if(data.user.image == null){
            var image = 'http://placehold.jp/150x150.png';
           }else{
               var image = `${sourceUrl}`+data.user.image;
           }

            $('#user-img').attr('src', image);
            $('#user-fullname').text(data.user.fullname);
            $('#request-id').text(data.request_id);
            $('#coin_amount').val(data.coin_amount);
            $('#amount_paid').val(data.amount_paid);
            $('#payment_gateway').val(data.payment_gateway);
            $('#account_details').val(data.account_details);

            $('#amount_paid').attr("readonly", true);
            $('#div-submit').addClass('d-none');


        });
        $('#viewRequest').modal('show');
    });

    $("#table-pending").on("click",".complete-redeem",function(event) {
        event.preventDefault();

        var  id = $(this).attr('rel');

        $('#editId').val($(this).attr('rel'));

        var url =  `${domainUrl}getRedeemById`+"/"+id;

        $.getJSON(url).done(function(data) {


           if(data.user.image == null){
            var image = 'http://placehold.jp/150x150.png';
           }else{
               var image = `${sourceUrl}`+data.user.image;
           }

            $('#user-img').attr('src', image);
            $('#user-fullname').text(data.user.fullname);
            $('#request-id').text(data.request_id);
            $('#coin_amount').val(data.coin_amount);
            $('#payment_gateway').val(data.payment_gateway);
            $('#account_details').val(data.account_details);

            $('#amount_paid').attr("readonly", false);
            $('#div-submit').removeClass('d-none');

        });
        $('#viewRequest').modal('show');
    });

    $("#completeForm").on("submit", function (event) {
        event.preventDefault();
        $(".loader").show();

        if (user_type == "1") {
            var formdata = new FormData($("#completeForm")[0]);

            $.ajax({
                url: `${domainUrl}completeRedeem`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    $("#table-pending").DataTable().ajax.reload(null, false);
                    $("#table-completed").DataTable().ajax.reload(null, false);

                    $(".loader").hide();
                    $("#viewRequest").modal("hide");


                    if (response.status == false) {
                        iziToast.error({
                            title: app.Error,
                            message: response.message,
                            position: "topRight",
                        });
                    }
                },
                error: function (err) {
                    console.log(err);
                },
            });
        } else {
            iziToast.error({
                title: app.Error,
                message: app.tester,
                position: "topRight",
            });
        }
    });




});
