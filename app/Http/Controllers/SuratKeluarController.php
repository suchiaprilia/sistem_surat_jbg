<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\JenisSurat;
use App\Models\Agenda; // ✅ tambah
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::with('jenisSurat')->get();
        $jenisSurat = JenisSurat::all();
        return view('surat-masuk', compact('suratMasuk', 'jenisSurat'));
    }

    public function create()
    {
        return view('surat-masuk-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required',
            'pengirim' => 'required',
            'subject' => 'required',
            'tujuan' => 'required',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'file_surat' => 'nullable|mimes:pdf,jpg,png',

            // ✅ tambahan untuk agenda otomatis (optional)
            'buat_agenda' => 'nullable|in:1',
            'agenda_tanggal_mulai' => 'nullable|date',
            'agenda_tanggal_selesai' => 'nullable|date|after_or_equal:agenda_tanggal_mulai',
            'agenda_lokasi' => 'nullable|string|max:255',
            'agenda_keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')->store('surat-masuk', 'public');
        }

        $surat = SuratMasuk::create($validated);

        // ✅ kalau user centang "buat agenda"
        if ($request->input('buat_agenda') == '1') {
            $mulai = $request->input('agenda_tanggal_mulai');

            // kalau user tidak isi tanggal mulai agenda, default ambil "tanggal_terima" jam 08:00
            if (!$mulai) {
                $mulai = $surat->tanggal_terima . ' 08:00:00';
            }

            Agenda::create([
                'judul' => 'Tindak lanjut surat: ' . ($surat->no_surat ?? '-'),
                'tanggal_mulai' => $mulai,
                'tanggal_selesai' => $request->input('agenda_tanggal_selesai'),
                'lokasi' => $request->input('agenda_lokasi'),
                'keterangan' => $request->input('agenda_keterangan')
                    ?: ('Dari surat masuk: ' . ($surat->subject ?? '-')),
                'status' => 'terjadwal',
                'surat_masuk_id' => $surat->id,
                'created_by' => 1, // karena kamu belum pakai login
            ]);
        }

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = SuratMasuk::findOrFail($id);
        $jenisSurat = JenisSurat::all();
        return view('surat-masuk-edit', compact('item', 'jenisSurat'));
    }

    public function update(Request $request, $id)
    {
        $item = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required',
            'pengirim' => 'required',
            'subject' => 'required',
            'tujuan' => 'required',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'file_surat' => 'nullable|mimes:pdf,jpg,png',
        ]);

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')->store('surat-masuk', 'public');
        }

        $item->update($validated);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = SuratMasuk::findOrFail($id);
        $item->delete();
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }
}
