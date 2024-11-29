@extends('kerangka.master')

@section('title', 'Daftar Produk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4 text-center">Daftar Produk</h4>

    <hr>
    <a href="#" class="btn btn-primary mb-3">Tambah Produk</a>

    <div class="card shadow-sm border-light">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td class="text-muted">Smartphone XYZ</td>
                        <td class="text-muted">Rp 3.500.000</td>
                        <td class="text-muted">Smartphone dengan layar 6.5 inci dan kamera 12 MP.</td>
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
                        <td class="text-muted">Sepatu Olahraga ABC</td>
                        <td class="text-muted">Rp 750.000</td>
                        <td class="text-muted">Sepatu lari ringan dan nyaman untuk olahraga.</td>
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
                        <td class="text-muted">Laptop ASUS ROG</td>
                        <td class="text-muted">Rp 15.000.000</td>
                        <td class="text-muted">Laptop gaming dengan prosesor Intel Core i7 dan kartu grafis GTX 1660Ti.</td>
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
                        <td class="text-muted">Meja Makan Kayu</td>
                        <td class="text-muted">Rp 2.000.000</td>
                        <td class="text-muted">Meja makan elegan dengan desain modern, terbuat dari kayu jati.</td>
                        <td>
                            <a href="#" class="btn btn-outline-primary btn-sm">Edit</a>
                            <form action="#" method="POST" style="display:inline display: flex">
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
