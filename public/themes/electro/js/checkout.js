$(document).ready(function() {
    $("select option:first").attr("disabled", true);

    $("#province_id, #shipping_province").select2();

    $("#city_id, #shipping_city").select2({
        placeholder: "Select City",
        allowClear: true
    });

    const getCities = (province_id, elementId) => {
        $.ajax({
            url: "/orders/get-cities",
            method: "GET",
            data: {
                province_id: province_id
            },
            success: function(results) {
                $(elementId).empty();
                $(elementId).append(
                    `<option disabled='disabled'>-- Please Select --</option>`
                );

                $.each(results, function(city_id, city) {
                    $(elementId).append(
                        `<option value=${city_id}>${city}</option>`
                    );
                });
            }
        });
    };

    $("#province_id").on("change", function() {
        const province_id = $(this).val();
        getCities(province_id, "#city_id");
    });

    $("#shipping_province").on("change", function() {
        const province_id = $(this).val();
        getCities(province_id, "#shipping_city");
    });

    // ============ show shipping cost option ===========
    const getShippingOptions = city_id => {
        $.ajax({
            url: "/orders/shipping-cost",
            method: "post",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                city_id: city_id
            },
            success: function(results) {
                $("#kurir").empty();
                $("#kurir").append(
                    `<option disabled='disabled' selected>-- Select Courier --</option>`
                );

                $.each(results.results, function(key, result) {
                    $("#kurir").append(
                        `<option value=${result.services.replace(/\s/g, "")}>${
                            result.services
                        } ${
                            result.etd
                        } | Rp. ${result.costAmount
                            .toString()
                            .replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</option>`
                    );
                });
            }
        });
    };

    $("#city_id").on("change", function(e) {
        const city_id = $(this)
            .find(":selected")
            .attr("value");

        if (!$("#shiping-address").is(":checked")) {
            getShippingOptions(city_id);
        }
    });

    $("#shipping_city").on("change", function(e) {
        const city_id = $(this)
            .find(":selected")
            .attr("value");
        getShippingOptions(city_id);
    });

    // =========== set shipping cost ==============
    $("#kurir").on("change", function(e) {
        const shippingService = $(this)
            .find(":selected")
            .attr("value");
        let city_id = $("#city_id")
            .find(":selected")
            .attr("value");

        if ($("#shiping-address").is(":checked")) {
            city_id = $("#shipping_city")
                .find(":selected")
                .attr("value");
        }

        $.ajax({
            url: "/orders/set-shipping",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                shippingService: shippingService,
                city_id: city_id
            },
            success: function(results) {
                $(".order-total").html(results.data.total);
            }
        });
    });
});
