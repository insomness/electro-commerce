@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Categories</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        @include('admin.partials.flash')
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title ">Responsive Hover Table</h3>
                <div class="card-tools ml-auto">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" id="createButton">
                        <i class="fa fa-pen-alt"></i>
                        New Category
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap" id="datatables" class="display">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="categoryLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="categoryLabel">New Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="">
          <div id="forms">
                <div class="form-group row">
                    {!! Form::label('name', 'Name', ['class' => 'col-sm-2 col-form-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control col-sm']) !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="btn-group">
                <button type="button" class="btn btn-secondary add">New</button>
                <button type="button" class="btn btn-secondary remove">Remove</button>
            </div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary submit" >Save changes</button>
        </div>
        </form>
      </div>
    </div>
</div>
{{-- counting amount of input in modal --}}
<input type="hidden" value="1" id="total_chq">
@stop

@section('js')
<script>
const alertToast = status => {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: toast => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: "success",
        title: status
    });
};

// delete data on server
$(document).on('click', '.deleteButton', function(){
    const id = $(this).data('id');
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
            sendData(`categories/${id}`, "DELETE")
                .then(result => {
                    if (result.status == true) {
                        table.DataTable().ajax.reload();
                        return alertToast("Category has been deleted!");
                    }
                })
                .catch(error => console.log(error));
        }
    });
});

$("#createButton").on("click", function(e) {
    $(".add").prop("disabled", false);
    $(".remove").prop("disabled", false);
    $('#forms input').val('');

    $('.modal-footer button[type=submit]').attr('class', 'btn btn-primary submit');
});

$(document).on("click", '.submit', function(e) {
        e.preventDefault();
        const data = $(".modal-body form").serialize();

        sendData(`categories`, "post", { data: data })
            .then(result => {
                $('#myModal').modal('hide')
                if (result.status == true) {
                    table.DataTable().ajax.reload();
                    return alertToast("Category has been added!");
                }
            })
            .catch(error => console.log(error));
    });


let id;
$(document).on("click", ".updateButton", function() {
    $("#myModal").modal("show");
    $(".add").prop("disabled", true);
    $(".remove").prop("disabled", true);
    $("#forms input").val("");

    $("#forms").html(`
            <div class="form-group row">
                <label for="new_name" class="col-sm-2 col-form-label">Name</label>
                <input class="form-control col-sm" name="name" type="text" value="${
                    this.closest("tr").firstChild.textContent
                }">
            </div>
        `);

    $(".modal-footer button[type=submit]").attr('class', 'btn btn-primary updateSubmit');
    return id = $(this).data('id');
});

$(document).on("click", '.updateSubmit', function(e) {
    e.preventDefault();
    const data = $(".modal-body form").serialize();

    sendData(`categories/${id}`, "put", { data: data })
        .then(result => {
            if (result.status == true) {
                $('#myModal').modal('hide')
                table.DataTable().ajax.reload();
                return alertToast("Category has been updated!");
            }
        })
        .catch(error => console.log(error));
});

// datatables
const table = $("#datatables");
table.DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('admin.categories.index') }}",
    columns: [
        { data: "name", name: "name" },
        { data: "slug", name: "slug" },
        { data: "action", name: "action", orderable: false, searchable: false }
    ]
});

// add new form input in modal when click new button
$(".add").on("click", function() {
    const new_chq_no = parseInt($("#total_chq").val()) + 1;
    const new_input = `<div class="form-group row" id=new_${new_chq_no}>
                <label for="new_name" class="col-sm-2 col-form-label">Name</label>
                <input class="form-control col-sm" name="name" type="text" id="new_name${new_chq_no}">
            </div>`;

    $("#forms").append(new_input);
    $("#total_chq").val(new_chq_no);
});

$(".remove").on("click", function() {
    var last_chq_no = $("#total_chq").val();

    if (last_chq_no > 1) {
        $("#new_" + last_chq_no).remove();
        $("#total_chq").val(last_chq_no - 1);
    }
});

</script>
@stop
