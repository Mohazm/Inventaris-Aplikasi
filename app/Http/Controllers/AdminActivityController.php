<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class AdminActivityController extends Controller
{
    // Menampilkan daftar semua aktivitas
    public function index(Request $request)
    {
        // Pencarian berdasarkan nama aktivitas atau nama pengguna
        $search = $request->input('search');

        $activities = Activity::with('user') // Pastikan relasi ke model User telah didefinisikan
            ->when($search, function ($query) use ($search) {
                $query->where('activitas', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan yang terbaru
            ->paginate(10); // Pagination

        return view('Crud_admin.Activity.index', compact('activities', 'search'));
    }

    // Menghapus aktivitas
    public function destroy($id)
    {
        try {
            $activity = Activity::findOrFail($id);

            // Hapus aktivitas
            $activity->delete();

            return redirect()->route('admin.activities.index')->with('success', 'Aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.activities.index')->withErrors(['error' => 'Terjadi kesalahan saat menghapus aktivitas.']);
        }
    }
}
