/* eslint-disable no-undef */
/* eslint-disable no-unused-vars */
// fetch data like ajax
$(".datepicker").datepicker({
    format: "yyyy-mm-dd"
});

$(".deleteButton").on("click", function() {
    const id = $(this).data("id");
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById("delete-form-" + id).submit();
        }
    });
});

async function sendData(url = "", method = "GET", data = {}) {
    const response = await fetch(url, {
        method: method,
        cache: "no-cache",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify(data) // body data type must match "Content-Type" header
    });
    return response.json();
}

const rupiahFormatOnDom = id => {
    // tambahkan id pada form price / harga
    const rupiah = document.getElementById(id);
    rupiah.addEventListener("keyup", function(e) {
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
        return (rupiah.value = formatRupiah(this.value, "Rp. "));
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
    }
};
