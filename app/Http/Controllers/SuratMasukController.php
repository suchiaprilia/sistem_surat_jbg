<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\JenisSurat;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::with('jenisSurat')
            ->orderBy('id', 'desc')
            ->get();

        $jenisSurat = JenisSurat::all();

        return view('surat-masuk', compact('suratMasuk', 'jenisSurat'));
    }

    public function create()
    {
        return $this->index();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

            // agenda optional
            'buat_agenda' => 'nullable|in:1',
            'agenda_judul' => 'nullable|string|max:255',
            'agenda_mulai' => 'nullable|date',
            'agenda_selesai' => 'nullable|date',
            'agenda_lokasi' => 'nullable|string|max:255',
            'agenda_keterangan' => 'nullable|string',
        ]);

        // upload file
        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')->store('surat-masuk', 'public');
        }

        // simpan surat masuk
        $surat = SuratMasuk::create([
            'no_surat' => $validated['no_surat'],
            'tanggal' => $validated['tanggal'],
            'tanggal_terima' => $validated['tanggal_terima'],
            'penerima' => $validated['penerima'],
            'pengirim' => $validated['pengirim'],
            'subject' => $validated['subject'],
            'tujuan' => $validated['tujuan'],
            'id_jenis_surat' => $validated['id_jenis_surat'],
            'file_surat' => $validated['file_surat'] ?? null,
        ]);

        // ✅ kalau dicentang buat agenda
        if ($request->input('buat_agenda') === '1') {
            Agenda::create([
                'judul' => $request->agenda_judul ?: ('Agenda: ' . $surat->subject),
                'tanggal_mulai' => $request->agenda_mulai ?: ($surat->tanggal_terima . ' 09:00:00'),
                'tanggal_selesai' => $request->agenda_selesai ?: null,
                'lokasi' => $request->agenda_lokasi ?: null,
                'keterangan' => $request->agenda_keterangan ?: ('Dibuat otomatis dari Surat Masuk: ' . $surat->no_surat),
                'status' => 'terjadwal',
                'surat_masuk_id' => $surat->id,
                'created_by' => 1, // hardcode dulu karena belum login
            ]);
        }

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        return redirect()->route('surat-masuk.index');
    }

    public function update(Request $request, $id)
    {
        $item = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // kalau upload file baru, hapus file lama
        if ($request->hasFile('file_surat')) {
            if ($item->file_surat && Storage::disk('public')->exists($item->file_surat)) {
                Storage::disk('public')->delete($item->file_surat);
            }

            $validated['file_surat'] = $request->file('file_surat')->store('surat-masuk', 'public');
        }

        $item->update($validated);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = SuratMasuk::findOrFail($id);

        // hapus file kalau ada
        if ($item->file_surat && Storage::disk('public')->exists($item->file_surat)) {
            Storage::disk('public')->delete($item->file_surat);
        }

        $item->delete();

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus!');
    }

    /**
     * ✅ INI YANG DIPANGGIL ROUTE surat-masuk.file (lihatFile)
     */
    // ================================
// 🔥 METHOD UNTUK BUKA FILE
// ================================
public function lihatFile($id)
{

    $surat = SuratMasuk::findOrFail($id);

    // Tandai surat sudah dibaca
    if ($surat->is_read == 0) {
        $surat->update([
            'is_read' => 1
        ]);
    }

    if (
        !$surat->file_surat ||
        !Storage::disk('public')->exists($surat->file_surat)
    ) {
        abort(404, 'File tidak ditemukan');
    }

    return response()->file(
        storage_path('app/public/' . $surat->file_surat),
        [
            'Content-Type' => Storage::disk('public')->mimeType($surat->file_surat)
        ]
    );
}

public function markAsRead($id)
{
    $surat = SuratMasuk::findOrFail($id);

    if ($surat->is_read == 0) {
        $surat->update([
            'is_read' => 1
        ]);
    }

    return redirect()
        ->route('surat-masuk.index')
        ->with('success', 'Surat ditandai sudah dibaca.');
}


    /**
     * (optional) kalau masih ada route lama yang manggil ->file()
     * biar aman, aku arahkan ke lihatFile juga.
     */
    public function file($id)
    {
        return $this->lihatFile($id);
    }
}
