// custom script
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// ajax loading image
$(document).on({
    ajaxStart: function() {
        $("body").addClass("loading");
    },
    ajaxStop: function() {
        $("body").removeClass("loading");
    }
});

$(".add-to-cart-btn").on("click", function() {
    const productId = $(this).data("productid");
    $.ajax({
        url: "carts",
        dataType: "json",
        type: "POST",
        data: {
            productId: productId
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function(data) {
            productList(data);
        }
    });
});

$(".cart-list").on("click", ".product-widget .delete", function() {
    const productId = $(".product-widget").data("id");
    $.ajax({
        url: "carts",
        type: "DELETE",
        data: {
            productId: productId
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function(data) {
            productList(data);
        }
    });
});

function productList(data) {
    let { result } = data;
    let carts = "";
    if (result.length > 0) {
        result.forEach(r => {
            carts += `<div class="product-widget" data-id="${r.id}">
                    <div class="product-img">
                        <img src="${window.location.origin}/storage/${r.image}">
                    </div>
                    <div class="product-body">
                        <h3 class="product-name"><a href="#">${r.name}</a></h3>
                        <h4 class="product-price"><span class="qty">${
                            r.quantity
                        }x</span>Rp. ${numberWithCommas(r.price)}</h4>
                    </div>
                    <button class="delete"><i class="fa fa-close"></i></button>
                </div>`;
        });
        $(".cart-list").html(carts);
        $(".subTotal").html("SUBTOTAL: Rp. " + numberWithCommas(data.subTotal));
        $(".cartCount").html(result.length);
        $(".cart-summary .cartCount").html(result.length + " Item(s) selected");
    } else {
        $(".cart-list").html("");
        $(".subTotal").html("SUBTOTAL: Rp. " + "0");
        $(".cartCount").html(0);
        $(".cart-summary .cartCount").html(0 + " Item(s) selected");
    }
}
