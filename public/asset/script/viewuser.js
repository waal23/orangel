$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".usersSideA").addClass("activeLi");

    var id = $("#userId").val();
    console.log(id);

    $("#btnAddImage").on("click", function (event) {
        event.preventDefault();
        $("#addImageModal").modal("show");
    });

    // Image Add
    $("#addForm").submit(function (e) {
        e.preventDefault();
        if (user_type == 1) {
            var formdata = new FormData($("#addForm")[0]);

            console.log(formdata);
            $.ajax({
                url: `${domainUrl}addUserImage`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    if (response.status == true) {
                        console.log(response.status);
                        window.location.href = "";
                    } else if (response.status == false) {
                        console.log(err);
                    }
                },
                error: function (err) {
                    console.log(err);
                },
            });
        } else {
            iziToast.error({
                title: "Tester Login",
                message: "you are tester",
                position: "topRight",
                timeOut: 4000,
            });
        }
    });

    $(document).on("click", ".btnRemove", function (event) {
        event.preventDefault();
        var imgId = $(this).data("imgid");
        console.log(imgId);
        swal({
            title: app.sure,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                if (user_type == "1") {
                    var url = `${domainUrl}deleteUserImage` + "/" + imgId;
                    $.getJSON(url).done(function (response) {
                        if (response.status == true) {
                            console.log(response.status);
                            location.reload();
                        } else if (response.status == false) {
                            console.log(response);
                            iziToast.error({
                                title: `${app.Error}!`,
                                message: response.message,
                                position: "topRight",
                            });
                        }
                    });
                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            }
        });
    });

    // form data update
    $("#userUpdate").submit(function (e) {
        e.preventDefault();
        if (user_type == 1) {
            var formdata = new FormData($("#userUpdate")[0]);

            console.log(formdata);
            $.ajax({
                url: `${domainUrl}updateUser`,
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    if (response.status == true) {
                        console.log(response.status);
                        window.location.href = "";
                    } else if (response.status == false) {
                        console.log(err);
                    }
                },
                error: function (err) {
                    console.log(err);
                },
            });
        } else {
            iziToast.error({
                title: "Tester Login",
                message: "you are tester",
                position: "topRight",
                timeOut: 4000,
            });
        }
    });

    $(document).on("click", ".allow-live", function (event) {
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
                    var url = `${domainUrl}allowLiveToUser` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        location.reload();
                    });
                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            } else {
                swal(app.noChangesDone);
            }
        });
    });

    $(document).on("click", ".restrict-live", function (event) {
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
                    var url = `${domainUrl}restrictLiveToUser` + "/" + id;

                    $.getJSON(url).done(function (data) {
                        console.log(data);
                        location.reload();
                    });
                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            } else {
                swal(app.noChangesDone);
            }
        });
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
                        location.reload();
                    });
                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            } else {
                swal(app.noChangesDone);
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
                    var id = $(this).attr("rel");
                    var delete_cat_url = `${domainUrl}unblockUser` + "/" + id;

                    $.getJSON(delete_cat_url).done(function (data) {
                        console.log(data);
                        location.reload();
                    });
                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: "topRight",
                    });
                }
            } else {
                swal(app.noChangesDone);
            }
        });
    });
});
