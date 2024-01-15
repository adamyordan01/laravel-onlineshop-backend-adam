@extends('layouts.base', ['title' => 'Products'])

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
    {{-- add products --}}
    <div class="modal fade productModal" tabindex="-1" role="dialog" id="productModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Produk</h5>
                    <button type="button" class="close cancel-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('product.store') }}" method="POST" id="addProduct" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">-- Pilih kategori --</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control">
                            <span class="text-danger mt-1 error-text description_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Produk</label>
                            {{-- <input type="file" name="image" id="image" class="form-control" onchange="readUrl(this)" accept="image/*"> --}}
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <span class="text-danger mt-1 error-text image_error"></span>
                        </div>
                        <input type="hidden" name="hidden_image" id="hidden_image">
                        <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100">
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="text" name="price" id="price" class="form-control">
                            <span class="text-danger mt-1 error-text price_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stok</label>
                            <input type="text" name="stock" id="stock" class="form-control">
                            <span class="text-danger mt-1 error-text stock_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="is_available" class="d-block">Apakah Produk Tersedia?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="tersedia" value="1" name="is_available">
                                <label class="form-check-label" for="tersedia">Tersedia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="tidak_tersedia" value="0" name="is_available">
                                <label class="form-check-label" for="tidak_tersedia">Tidak Tersedia</label>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-save" value="create" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit products --}}
    <div class="modal fade productModal" tabindex="-1" role="dialog" id="editProductModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Edit Kategori</h5>
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('product.update') }}" method="POST" id="editproduct" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="hidden" class="d-none" name="product_id">

                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">-- Pilih kategori --</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control">
                            <span class="text-danger mt-1 error-text description_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Produk</label>
                            {{-- <input type="file" name="image" id="image" class="form-control" onchange="readUrl(this)" accept="image/*"> --}}
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <span class="text-danger mt-1 error-text image_error"></span>
                        </div>
                        <input type="hidden" name="hidden_image" id="hidden_image">
                        <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100">
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="text" name="price" id="price" class="form-control">
                            <span class="text-danger mt-1 error-text price_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stok</label>
                            <input type="text" name="stock" id="stock" class="form-control">
                            <span class="text-danger mt-1 error-text stock_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="is_available" class="d-block">Apakah Produk Tersedia?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="tersedia" value="1" name="is_available">
                                <label class="form-check-label" for="tersedia">Tersedia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="tidak_tersedia" value="0" name="is_available">
                                <label class="form-check-label" for="tidak_tersedia">Tidak Tersedia</label>
                            </div>
                            
                        </div>

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
    <h1>Products</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">Products</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="mb-3">
                <button
                    class="btn btn-sm btn-add py-1"
                    id="create-product"
                    data-toggle="modal"
                    data-target="#productModal"
                >
                    <i class="fas fa-plus"></i>
                    Tambah Produk
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
                                <th>Nama Produk</th>
                                <th>Deskripsi</th>
                                <th>Gambar</th>
                                <th>Harga</th>
                                <th>Stock</th>
                                <th>Tersedia</th>
                                <th>Ditambahkan pada</th>
                                <th>Act</th>
                            </tr>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td class="data">
                                            {{ $loop->iteration + $products->firstItem() - 1 . '.' }}    
                                        </td>
                                        <td>
                                            {{ $product->category->name }}
                                        </td>
                                        <td class="data">{{ $product->name }}</td>
                                        <td class="data">{{ $product->description }}</td>
                                        <td class="data">
                                            {{-- check if product->image start with https --}}
                                            @if (strpos($product->image, 'https') !== false)
                                                <img src="{{ $product->image }}" alt="{{ $product->name }}" width="80">
                                            @else
                                                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" width="80">
                                            @endif
                                        </td>
                                        <td class="data">Rp.{{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="data">{{ $product->stock }}</td>
                                        <td class="data">
                                            @if ($product->is_available == 1)
                                                <span class="badge badge-success">Tersedia</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Tersedia</span>
                                            @endif
                                        <td class="data">{{ $carbon->parse($product->created_at)->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
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
                                                        data-target="#editProductModal"
                                                        data-id="{{ $product->id }}"
                                                        title="Edit Produk"
                                                    >
                                                        <i class="fas fa-edit fa-xs mr-2"></i>
                                                        Edit
                                                    </a>
                                                    <a href="javascript:void(0)"
                                                        class="dropdown-item"
                                                        id="deleteButton"
                                                        data-id="{{ $product->id }}"
                                                        data-name="{{ $product->name }}"
                                                        title="Hapus Produk"
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
                    {{ $products->onEachSide(1)->links() }}
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

        $('#image').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#modal-preview').attr('src', e.target.result);
                $('#modal-preview').removeClass('hidden');
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.cancel-btn').click(function(){
            $('#addCategory')[0].reset();
            $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
            $('#modal-preview').addClass('hidden');
            $('.error-text').remove();
        });

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $(document).ready(function () {
            $('#category_id').select2({
                // theme: 'bootstrap4',
                placeholder: '-- Pilih kategori --',
                dropdownParent: $('#productModal')
            });
        })

        // tambah data
        $('#addProduct').on('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('product.store') }}",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('#btn-save').attr('disabled', true);
                    $('#btn-save').html('<i class="fas fa-spinner fa-pulse"></i> Menyimpan...');
                },
                complete: function(){
                    $('#btn-save').attr('disabled', false);
                    $('#btn-save').html('Tambah Data');
                },
                success: function(response){
                    if(response.code == 0){
                        $.each(response.errors, function(key, value){
                            $(`.${key}_error`).text(value);
                        });
                    }else if(response.code == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#addProduct')[0].reset();
                        $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#modal-preview').addClass('hidden');
                        $('.error-text').remove();
                        $('#productModal').modal('hide');
                        setTimeout(function(){
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr){
                    let res = xhr.responseJSON;
                    if($.isEmptyObject(res) == false){
                        $('.error-text').remove();

                        $.each(res.errors, function(key, value){
                            $('#' + key).closest('.form-group').append('<span class="text-danger mt-1 error-text '+ key +'_error">'+ value +'</span>');
                        });
                    }
                }
            });
        });

        // edit data
        $(document).on('click', '#editButton', function(){
            let productId = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "{{ route('product.getProductDetail') }}",
                data: {product_id:productId},
                dataType: "json",
                success: function(response){
                    $('#editProductModal').modal('show');
                    $('#editproduct input[name="product_id"]').val(response.data.id);
                    $('#editproduct input[name="name"]').val(response.data.name);
                    $('#editproduct input[name="description"]').val(response.data.description);
                    $('#editproduct input[name="price"]').val(response.data.price);
                    $('#editproduct input[name="stock"]').val(response.data.stock);
                    $('#editproduct input[name="hidden_image"]').val(response.data.image);
                    $('#editproduct #category_id').val(response.data.category_id).trigger('change');
                    if(response.data.is_available == 1){
                        $('#editproduct #tersedia').prop('checked', true);
                    }else{
                        $('#editproduct #tidak_tersedia').prop('checked', true);
                    }
                    $('#editProductModal').find('input[name="hidden_image"]').val(response.image);
                    $('#editProductModal').find('img#modal-preview').attr('src', '{{ asset('images/products') }}/' + response.data.image);
                    $('#editProductModal').find('img#modal-preview').removeClass('hidden');
                }
            });
        });

        // update data
        $('#editproduct').on('submit', function(e){
            e.preventDefault();
            var form = this;
            var formData = new FormData(this);

            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('#btn-update').attr('disabled', true);
                    $('#btn-update').html('<i class="fas fa-spinner fa-pulse"></i> Menyimpan...');
                },
                complete: function(){
                    $('#btn-update').attr('disabled', false);
                    $('#btn-update').html('Ubah Data');
                },
                success: function(response){
                    if(response.code == 0){
                        $.each(response.errors, function(key, value){
                            $(`.${key}_error`).text(value);
                        });
                    }else if(response.code == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#editproduct')[0].reset();
                        $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#modal-preview').addClass('hidden');
                        $('.error-text').remove();
                        $('#editProductModal').modal('hide');
                        setTimeout(function(){
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr){
                    let res = xhr.responseJSON;
                    if($.isEmptyObject(res) == false){
                        $('.error-text').remove();

                        $.each(res.errors, function(key, value){
                            $('#' + key).closest('.form-group').append('<span class="text-danger mt-1 error-text '+ key +'_error">'+ value +'</span>');
                        });
                    }
                }
            });
        });

        // delete data
        $(document).on('click', '#deleteButton', function(){
            let productId = $(this).data('id');
            let productName = $(this).data('name');
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: `Produk ${productName} akan dihapus!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: `/product/delete/${productId}`,
                        data: {product_id:productId},
                        success: function(response){
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function(){
                                window.location.reload();
                            }, 1500);
                        }
                    });
                }
            });
        });
    </script>
@endpush