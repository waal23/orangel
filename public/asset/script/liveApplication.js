$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".liveapplicationSideA").addClass("activeLi");

    $("#table-22").dataTable({
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
            url: `${domainUrl}fetchLiveApplications`,
            data: function (data) {},
        },
    });


    $("#table-22").on("click",".delete-cat",function(event) {

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
                        var delete_cat_url = `${domainUrl}deleteLiveApplication`+"/"+cat_id;

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



});
