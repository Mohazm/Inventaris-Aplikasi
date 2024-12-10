<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityController extends Controller
{
    // Menampilkan daftar activities
    public function index()
    {
        // Menampilkan hanya aktivitas milik pengguna yang sedang login
        $activities = Activity::where('user_id', auth()->id())->get();  
        return view('staff.Activity.index', compact('activities'));
    }

    // Form untuk membuat activities baru
    public function create()
    {
        return view('staff.Activity.create');
    }

    // Menyimpan activities baru
    public function store(Request $request)
    {
        $currentTime = Carbon::now();

        // Memeriksa apakah waktu sekarang berada dalam rentang 3 sore sampai 8 malam
        if ($currentTime->hour < 8 || $currentTime->hour >= 11) {
            return redirect()->back()->withErrors(['error' => 'Aktivitas hanya bisa diisi antara jam 3 sore hingga 8 malam.']);
        }

        // Cek apakah pengguna sudah mengisi aktivitas hari ini
        $existingActivity = Activity::where('user_id', auth()->id())
                                    ->whereDate('tanggal_isi', Carbon::today())
                                    ->first();
        if ($existingActivity) {
            return redirect()->back()->withErrors(['error' => 'Anda sudah mengisi aktivitas hari ini. Aktivitas hanya dapat diisi sekali sehari.']);
        }

        $validated = $request->validate([
            'activitas' => 'required|string|max:255',
        ], [
            'activitas.required' => 'Judul aktivitas harus diisi.',
            'activitas.string' => 'Judul aktivitas harus berupa string.',
            'activitas.max' => 'Judul aktivitas maksimal 255 karakter.',
        ]);

        // Menambahkan informasi pengguna yang mengisi dan tanggal otomatis
        $validated['user_id'] = auth()->id();
        $validated['tanggal_isi'] = $currentTime->toDateString();

        Activity::create($validated);

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil disimpan.');
    }

    // Menampilkan form untuk mengedit activities
    public function edit($id)
    {
        $activities = Activity::findOrFail($id);

        // Memeriksa apakah user yang sedang login adalah pemilik aktivitas ini
        if ($activities->user_id !== auth()->id()) {
            return redirect()->route('activities.index')->withErrors(['error' => 'Anda tidak memiliki hak akses untuk mengedit aktivitas ini.']);
        }

        $currentTime = Carbon::now();

        // Membatasi agar tidak bisa mengedit setelah jam 12 malam
        if ($currentTime->hour >= 11) {
            return redirect()->route('activities.index')->withErrors(['error' => 'Tidak bisa mengedit aktivitas setelah jam 12 malam.']);
        }

        return view('staff.Activity.edit', compact('activities'));
    }

    // Memperbarui activities
    public function update(Request $request, $id)
    {
        $activities = Activity::findOrFail($id);

        $currentTime = Carbon::now();

        // Memeriksa apakah waktu sekarang berada dalam rentang 3 sore sampai 8 malam
        if ($currentTime->hour < 8 || $currentTime->hour >= 11) {
            return redirect()->route('activities.index')->withErrors(['error' => 'Aktivitas hanya bisa diperbarui antara jam 3 sore hingga 8 malam.']);
        }

        // Mengecek apakah aktivitas bisa diedit (belum lewat jam 12 malam pada tanggal yang bersangkutan)
        if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($activities->tanggal_isi)->setTime(23, 59))) {
            return redirect()->route('activities.index')->withErrors(['error' => 'Aktivitas ini tidak dapat diedit setelah jam 12 malam.']);
        }

        $validated = $request->validate([
            'activitas' => 'required|string|max:255',
        ], [
            'activitas.required' => 'Judul aktivitas harus diisi.',
            'activitas.string' => 'Judul aktivitas harus berupa string.',
            'activitas.max' => 'Judul aktivitas maksimal 255 karakter.',
        ]);

        // Update aktivitas
        $activities->update($validated);

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    // Menghapus activities
    public function destroy($id)
    {
        $activities = Activity::findOrFail($id);

        // Memeriksa apakah user yang sedang login adalah pemilik aktivitas ini
        if ($activities->user_id !== auth()->id()) {
            return redirect()->route('activities.index')->withErrors(['error' => 'Anda tidak memiliki hak akses untuk menghapus aktivitas ini.']);
        }

        $activities->delete();

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil dihapus.');
    }
}
