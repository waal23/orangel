$(document).ready(function () {

    $(".sideBarli").removeClass("activeLi");

    $("#view_video").on("click", function(event) {

        var video = $('#video').attr('rel');

        var videoFile = `${sourceUrl+video}`;

        $('#video source').attr('src', videoFile);
        $("#video")[0].load();

        });

        $("#video_modal").on("hidden.bs.modal", function () {
            $('#video').trigger('pause');
        });



        $("#btn_reject").on("click", function(event) {
        event.preventDefault();

        swal({
            title: app.sure,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {

            if (willDelete) {
                swal("Application rejected succesfully", {
                    icon: "success",
                });

                if (user_type == "1") {

                    var id = $(this).attr("rel");
                    var delete_cat_url = `${domainUrl}deleteLiveApplication` + "/" + id;

                    $.getJSON(delete_cat_url).done(function(data) {
                        console.log(data);
                        window.history.go(-1);

                    });

                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: 'topRight'
                    });
                }

            } else {
                swal(app.thisusernotunblock);
            }
        });


        });

        $("#btn_approve").on("click", function(event) {
        event.preventDefault();

        swal({
            title: app.sure,
            icon: "success",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {

            if (willDelete) {
                swal("Application Approved succesfully", {
                    icon: "success",
                });

                if (user_type == "1") {

                    var id = $(this).attr("rel");
                    var url = `${domainUrl}approveApplication` + "/" + id;

                    $.getJSON(url).done(function(data) {
                        console.log(data);
                        window.history.go(-1);

                    });

                } else {
                    iziToast.error({
                        title: `${app.Error}!`,
                        message: app.tester,
                        position: 'topRight'
                    });
                }

            } else {
                swal(app.thisusernotunblock);
            }
        });


        });




});
