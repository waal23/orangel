$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".reportSideA").addClass("activeLi");

    $("#UsersTable").dataTable({
        processing: true,
        serverSide: true,
        serverMethod: "post",
        aaSorting: [[0, "desc"]],
        columnDefs: [
            {
                targets: [0, 2],
                orderable: false,
            },
        ],
        ajax: {
            url: `${domainUrl}fetchAllReport`,
            data: function (data) {},
        },
    });

    $(document).on("click", ".block", function (event) {
        event.preventDefault();

        swal({
            title: app.sure,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                swal(app.thisuserhasbeenblocked, {
                    icon: "success",
                });

                if (user_type == "1") {
                    var element = $(this).parent();

                    var id = $(this).attr("rel");
                    var delete_cat_url = `${domainUrl}blockUser` + "/" + id;

                    $.getJSON(delete_cat_url).done(function (data) {
                        console.log(data);
                        $("#UsersTable").DataTable().ajax.reload(null, false);
                    });
                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            } else {
                swal(app.thisusernotblock);
            }
        });
    });
    $(document).on("click", ".unblock", function (event) {
        event.preventDefault();

        swal({
            title: app.sure,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                swal(app.thisuserhasbeenunblocked, {
                    icon: "success",
                });

                if (user_type == "1") {
                    var element = $(this).parent();

                    var id = $(this).attr("rel");
                    var delete_cat_url = `${domainUrl}unblockUser` + "/" + id;

                    $.getJSON(delete_cat_url).done(function (data) {
                        console.log(data);
                        $("#UsersTable").DataTable().ajax.reload(null, false);
                    });
                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            } else {
                swal(app.thisusernotunblock);
            }
        });
    });
});
