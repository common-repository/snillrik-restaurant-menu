jQuery(document).ready(function ($) {
    
    $(".snillrik_restaurant_dishbox_img").on("click", function () {
        let url = $(this).data("url");
        if(url != undefined && url != ""){
            window.location.href = url;
        }
    });
});
