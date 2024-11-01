jQuery(document).ready(function ($) {

    /**
     * for setting and unsetting the snillrik_restaurant_dish-boxes in menu-admin.
     */
    $(".snillrik_restaurant_dishbox_admin_clickable").on("click change", function () {
        let thisid = $(this).data("dish-id");
        if ($(this).parent().hasClass("snillrik_restaurant_dishbox_selected"))
            $(this).parent().removeClass("snillrik_restaurant_dishbox_selected");
        else
            $(this).parent().addClass("snillrik_restaurant_dishbox_selected");

        let set_to_selected = "";

        $(".snillrik_restaurant_dishbox_selected").each(function () {
            let did = $(this).attr("id").split("_")[3];
            set_to_selected += did + ",";
        });

        $("#_selected_boxes").val(set_to_selected);

    });
    $(".snillrik_restaurant_dishbox_admin_price_clickable").on("change", function () {
        let thisid = $(this).data("dish-id");

        let set_to_selected_price = "";

        $(".snillrik_restaurant_dishbox_selected").each(function () {
            let did = $(this).attr("id").split("_")[3];
            let price = $("#snillrik_restaurant_menu_dish_price_"+did).val();
            set_to_selected_price +=  price + ",";
        });

        $("#_selected_boxes_prices").val(set_to_selected_price);

    });    

    $(".snillrik_restaurant_shortcode_generator select").on("change", function () {
        //console.log("snillrik_restaurant_shortcode_generator");
        var shortcode = "[snillrik_restaurant_menu ";

        shortcode += "menuid=\"" + $("#snrest_menuid").val() + "\" ";
        if ($("#snrest_showcategory").val() != 1)
            shortcode += "showcategory=\"" + $("#snrest_showcategory").val() + "\" ";
        if ($("#snrest_hideimage").val() != 1)
            shortcode += "hideimage=\"" + $("#snrest_hideimage").val() + "\" ";
        if ($("#snrest_linktitle").val() != 1)
            shortcode += "linktitle=\"" + $("#snrest_linktitle").val() + "\" ";
        if ($("#snrest_category").val() != undefined)
            shortcode += "category=\"" + $("#snrest_category").val() + "\" ";
        shortcode += "orderby=\"" + $("#snrest_orderby").val() + "\" ";
        shortcode += "]";
        $("#snillrik_restaurant_shortcode_placer").html(shortcode);
    });

});