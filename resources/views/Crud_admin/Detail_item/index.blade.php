    @extends('kerangka.master')

    @section('content')
    <div class="container">
        <h3>Detail Barang</h3>

        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Kondisi Barang</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details  as $key => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->kondisi_barang }}</td>
                        <td>{{ $item->status_pinjaman }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a href="{{ route('details.edit', ['kode_barang' => $item->kode_barang]) }}" class="btn btn-primary btn-sm">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data detail barang.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <!-- Pagination -->
        {{-- <div class="d-flex justify-content-center">
            {{ $details->links() }}
        </div> --}}
    </div>
    @endsection
