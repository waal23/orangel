$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".verificationRequestSideA").addClass("activeLi");

    $(".addModalBtn").on("click", function (event) {
        event.preventDefault();
        $("#addForm")[0].reset();
    });

    $("#table-22").dataTable({
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5, 6],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchverificationRequests`,
            data: function (data) {},
        },
    });

    $("#table-22").on("click", ".img-preview", function (event) {
        event.preventDefault();

        var imgUrl = $(this).attr("rel");
        $("#previewItem").modal().show();
        $("#img-preview").attr("src", imgUrl);
    });

    $("#table-22").on("click", ".reject", function (event) {
        event.preventDefault();
        swal({
            title: `${app.sure}`,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                if (user_type == "1") {
                    var id = $(this).attr("rel");
                    var url =
                        `${domainUrl}rejectVerificationRequest` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        $("#table-22").DataTable().ajax.reload(null, false);

                        iziToast.success({
                            title: "Success!",
                            message: "Request Rejected successfully.",
                            position: "topRight",
                        });
                    });
                } else {
                    iziToast.error({
                        title: app.Error,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            }
        });
    });

    $("#table-22").on("click", ".approve", function (event) {
        event.preventDefault();

        swal({
            title: `${app.sure}`,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                if (user_type == "1") {
                    var id = $(this).attr("rel");
                    var url =
                        `${domainUrl}approveVerificationRequest` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        $("#table-22").DataTable().ajax.reload(null, false);

                        iziToast.success({
                            title: "Success!",
                            message: "Request Approved successfully.",
                            position: "topRight",
                        });
                    });
                } else {
                    iziToast.error({
                        title: app.Error,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            }
        });
    });
});
