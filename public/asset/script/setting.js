$(document).ready(function () {
    $(".sideBarli").removeClass("activeLi");
    $(".otherSideA").addClass("activeLi");

    $(".appdataForm").on('submit',function(event) {
        event.preventDefault();
        $('.loader').show();
    

        if (user_type == "1") {

            var formdata = new FormData($(this)[0]);


            $.ajax({
                url: `${domainUrl}updateAppdata`,
                type: 'POST',
                data: formdata,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    console.log(response);

                    if(response.status == true){
                    location.reload();

                    }
                  
                },
                error: function(err) {
                    $('.loader').hide();
                
                                console.log(err);
                            

                }

            });
        } else {

            $('.loader').hide();
            iziToast.error({
                title: `${app.Error}!`,
                message: `${app.tester}`,
                        position: 'topRight'
            });
        }

    });
    $(".admobForm").on('submit',function(event) {
            event.preventDefault();
            $('.loader').show();
        

            if (user_type == "1") {

                var formdata = new FormData($(this)[0]);


                $.ajax({
                    url: `${domainUrl}updateAdmob`,
                    type: 'POST',
                    data: formdata,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        if(response.status == true){
                        location.reload();

                        }
                      
                    },
                    error: function(err) {
                        $('.loader').hide();
                    
                                    console.log(JSON.stringify(err));
                                

                    }

                });
            } else {

                $('.loader').hide();
                iziToast.error({
                    title: `${app.Error}!`,
                    message: `${app.tester}`,
                            position: 'topRight'
                });
            }

        });

        $(".otherForm").on('submit',function(event) {
            event.preventDefault();
            $('.loader').show();
        

            if (user_type == "1") {

                var formdata = new FormData($(this)[0]);


                $.ajax({
                    url: `${domainUrl}updateOther`,
                    type: 'POST',
                    data: formdata,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        if(response.status == true){
                        location.reload();

                        }
                      
                    },
                    error: function(err) {
                        $('.loader').hide();
                    
                                    console.log(JSON.stringify(err));
                                

                    }

                });
            } else {

                $('.loader').hide();
                iziToast.error({
                    title: `${app.Error}!`,
                    message: `${app.tester}`,
                            position: 'topRight'
                });
            }

        });
});