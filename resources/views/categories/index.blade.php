@extends('layouts.base')

@inject('carbon', 'Carbon\Carbon')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">

    <style>
        table {
            width: 100% !important;
        }
        
        td.data {
            /* font-size: 8pt !important; */
            /* text-align: center !important; */
            /* vertical-align: middle !important; */
        }

        td.center {
            text-align: center !important;
        }

        .dropleft .dropdown-toggle::before {
            display: none;
        }

        .dropdown-item:hover {
            background-color: #e2e2e5 !important;
        }

        .btn-add {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
        }

        .btn-add:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }
    </style>
@endpush

@section('modal')
    {{-- add categories --}}
    <div class="modal fade categoryModal" tabindex="-1" role="dialog" id="categoryModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Kategori</h5>
                    <button type="button" class="close cancel-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('category.store') }}" method="POST" id="addCategory" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Kategori</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control">
                            <span class="text-danger mt-1 error-text description_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Kategori</label>
                            {{-- <input type="file" name="image" id="image" class="form-control" onchange="readUrl(this)" accept="image/*"> --}}
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <span class="text-danger mt-1 error-text image_error"></span>
                        </div>
                        <input type="hidden" name="hidden_image" id="hidden_image">
                        <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-save" value="create" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit category --}}
    <div class="modal fade categoryModal" tabindex="-1" role="dialog" id="editCategoryModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Edit Kategori</h5>
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('category.update') }}" method="POST" id="editCategory" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="hidden" class="d-none" name="category_id">

                        <div class="form-group">
                            <label for="name">Nama Kategori</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control">
                            <span class="text-danger mt-1 error-text description_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Kategori</label>
                            {{-- <input type="file" name="image" id="image" class="form-control" onchange="readUrl(this)" accept="image/*"> --}}
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <span class="text-danger mt-1 error-text image_error"></span>
                        </div>
                        <input type="hidden" name="hidden_image" id="hidden_image">
                        <img
                            id="modal-preview"
                            src="https://via.placeholder.com/150"
                            alt="Preview"
                            class="form-group hidden"
                            width="100"
                        >

                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-update" value="update" class="btn btn-primary">Ubah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('section-header')
    <h1>Categories</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">Categories</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="mb-3">
                <button
                    class="btn btn-sm btn-add py-1"
                    id="create-category"
                    data-toggle="modal"
                    data-target="#categoryModal"
                >
                    <i class="fas fa-plus"></i>
                    Tambah Kategori
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Kategori</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-stripped tablebordered">
                            <tr>
                                <th>#</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Gambar</th>
                                <th>Ditambahkan pada</th>
                                <th>Act</th>
                            </tr>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="data">
                                            {{ $loop->iteration + $categories->firstItem() - 1 . '.' }}    
                                        </td>
                                        <td class="data">{{ $category->name }}</td>
                                        <td class="data">{{ $category->description }}</td>
                                        <td class="data">
                                            {{-- <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" width="100"> --}}
                                            {{-- <img src="{{ $category->image }}" alt="{{ $category->name }}" width="100"> --}}
                                            {{-- check if category->image start with https --}}
                                            @if (strpos($category->image, 'https') !== false)
                                                <img src="{{ $category->image }}" alt="{{ $category->name }}" width="80">
                                            @else
                                                <img src="{{ asset('images/categories/' . $category->image) }}" alt="{{ $category->name }}" width="80">
                                            @endif
                                        </td>
                                        <td class="data">{{ $carbon->parse($category->created_at)->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
                                        <td class="data">
                                            <div class="dropleft">
                                                <button
                                                    class="btn btn-sm dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                >
                                                    <i class="fas fa-ellipsis-h fa-xs"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a href="javascript:void(0)"
                                                        class="dropdown-item"
                                                        id="editButton"
                                                        data-toggle="modal"
                                                        data-target="#editCategoryModal"
                                                        data-id="{{ $category->id }}"
                                                        data-name="{{ $category->name }}"
                                                        data-description="{{ $category->description }}"
                                                        data-image="{{ $category->image }}"
                                                        title="Edit Kategori"
                                                    >
                                                        <i class="fas fa-edit fa-xs mr-2"></i>
                                                        Edit
                                                    </a>
                                                    <a href="javascript:void(0)"
                                                        class="dropdown-item"
                                                        id="deleteButton"
                                                        data-id="{{ $category->id }}"
                                                        data-name="{{ $category->name }}"
                                                        title="Hapus Kategori"
                                                    >
                                                        <i class="fas fa-trash fa-xs mr-2 text-danger"></i>
                                                        Hapus
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    {{ $categories->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

        // image change
        $('#image').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#modal-preview').attr('src', e.target.result);
                $('#modal-preview').removeClass('hidden');
            }
            reader.readAsDataURL(this.files[0]);
        });

        // cancel button
        $('.cancel-btn').click(function(){
            $('#addCategory')[0].reset();
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
            $('#modal-preview').addClass('hidden');
            $('.error-text').remove();
        });

        // tambah data
        $('#addCategory').submit(function(e){
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('category.store') }}",
                method: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('#btn-save').attr('disabled', true);
                    $('#btn-save').html('<i class="fas fa-spinner fa-spin"></i>');
                },
                complete: function(){
                    $('#btn-save').attr('disabled', false);
                    $('#btn-save').html('Tambah Data');
                },
                success: function(res){
                    console.log("error res: ", res);
                    if(res.code == 1){
                        $('#categoryModal').modal('hide');
                        $('#addCategory')[0].reset();
                        $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#modal-preview').addClass('hidden');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function(){
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr){
                    let res = xhr.responseJSON;
                    console.log(res.error);
                    if($.isEmptyObject(res) == false){
                        console.log("Error receiving data from server: ", res.errors);

                        // clear error messages
                        $('.error-text').remove();

                        $.each(res.errors, function(key, value){
                            $('#' + key).closest('.form-group').append('<span class="text-danger mt-1 error-text '+ key +'_error">'+ value +'</span>');
                        });
                    }

                }
            });
        });

        // edit data
        $(document).on('click', '#editButton', function() {
            let categoryId = $(this).data('id');
            $('#editCategoryModal').find('form')[0].reset();
            $('#editCategoryModal').find('span.error-text').text('');
            $('#editCategoryModal').find('#hidden_image').val('');

            $.ajax({
                url: "{{ route('category.getCategoryDetail') }}",
                type: "POST",
                data: {
                    category_id: categoryId
                },
                dataType: "JSON",
                success: function(res){
                    // console.log("res: ", res);
                    $('#editCategoryModal').find('input[name="category_id"]').val(res.id);
                    $('#editCategoryModal').find('input[name="name"]').val(res.name);
                    $('#editCategoryModal').find('input[name="description"]').val(res.description);
                    $('#editCategoryModal').find('input[name="hidden_image"]').val(res.image);
                    $('#editCategoryModal').find('img#modal-preview').attr('src', '{{ asset('images/categories') }}/' + res.image);
                    $('#editCategoryModal').find('img#modal-preview').removeClass('hidden');
                },
            })
        })

        // update data
        $('#editCategory').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formData = new FormData(this);
            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('#btn-update').attr('disabled', true);
                    $('#btn-update').html('<i class="fas fa-spinner fa-spin"></i>');
                    $(form).find('span.error-text').text('');
                },
                complete: function(){
                    $('#btn-update').attr('disabled', false);
                    $('#btn-update').html('Ubah Data');
                },
                success: function(res){
                    // console.log("error res: ", res);
                    if (res.code == 0) {
                        $.each(res.errors, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                    } else {
                        $('#editCategoryModal').modal('hide');
                        $('#editCategory')[0].reset();
                        $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#modal-preview').addClass('hidden');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function(){
                            window.location.reload();
                        }, 500);
                    }
                },
            })
        })

        // delete data
        $(document).on('click', '#deleteButton', function(){
            let categoryId = $(this).data('id');
            let categoryName = $(this).data('name');
            let categoryImage = $(this).data('image');
            let categoryDescription = $(this).data('description');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menghapus kategori " + categoryName + "!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#02dda5',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/category/delete/${categoryId}`,
                        type: "DELETE",
                        dataType: "JSON",
                        success: function(res){
                            console.log("res: ", res);
                            if(res.code == 1){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(function(){
                                    window.location.reload();
                                }, 1500);
                            }
                        },
                        error: function(xhr){
                            let res = xhr.responseJSON;
                            console.log(res.error);
                            if($.isEmptyObject(res) == false){
                                console.log("Error receiving data from server: ", res.errors);
                            }
                        }
                    });
                }
            })
        })
    </script>
@endpush