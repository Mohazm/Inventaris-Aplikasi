@extends('kerangka.master')

@section('title', 'Daftar Kategori')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4 text-center">Daftar Kategori</h4>

    <hr>
    <a href="{{route('category.create')}}" class="btn btn-primary mb-3">Tambah Kategori</a>

    <div class="card shadow-sm border-light">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td class="text-muted">Elektronik</td>
                        <td>
                            <a href="#" class="btn btn-outline-primary btn-sm">Edit</a>
                            <form action="#" method="POST" style="display:inline;">
                                {{-- @csrf
                                @method('DELETE') --}}
                                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td class="text-muted">Pakaian</td>
                        <td>
                            <a href="#" class="btn btn-outline-primary btn-sm">Edit</a>
                            <form action="#" method="POST" style="display:inline;">
                                {{-- @csrf
                                @method('DELETE') --}}
                                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td class="text-muted">Makanan</td>
                        <td>
                            <a href="#" class="btn btn-outline-primary btn-sm">Edit</a>
                            <form action="#" method="POST" style="display:inline;">
                                {{-- @csrf
                                @method('DELETE') --}}
                                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td class="text-muted">Perabotan Rumah</td>
                        <td>
                            <a href="#" class="btn btn-outline-primary btn-sm">Edit</a>
                            <form action="#" method="POST" style="display:inline;">
                                {{-- @csrf
                                @method('DELETE') --}}
                                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
