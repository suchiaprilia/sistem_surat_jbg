<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\JenisSurat;
use App\Models\Agenda;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SuratMasukController extends Controller
{
    /**
     * Tulis audit log (aman karena cek kolom audit_logs dulu).
     * Kamu bisa ubah mapping nama kolom kalau tabel audit_logs kamu beda.
     */
    private function writeAudit(string $aksi, string $keterangan, $dataLama = null, $dataBaru = null): void
    {
        try {
            // kalau tabel audit_logs belum ada, skip biar gak error
            if (!Schema::hasTable('audit_logs')) return;

            // Kandidat nama kolom (biar fleksibel)
            $payloadCandidates = [
                // user id
                'user_id'    => Auth::id() ?? 1,
                'id_user'    => Auth::id() ?? 1,

                // action/aksi
                'aksi'       => $aksi,
                'action'     => $aksi,
                'aktivitas'  => $aksi,

                // description/keterangan
                'keterangan'   => $keterangan,
                'description'  => $keterangan,
                'deskripsi'    => $keterangan,

                // metadata
                'ip_address' => request()->ip(),
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),

                // old/new data
                'data_lama'  => $dataLama ? json_encode($dataLama) : null,
                'old_data'   => $dataLama ? json_encode($dataLama) : null,
                'data_baru'  => $dataBaru ? json_encode($dataBaru) : null,
                'new_data'   => $dataBaru ? json_encode($dataBaru) : null,
            ];

            // Filter hanya kolom yang memang ada di tabel audit_logs
            $payload = [];
            foreach ($payloadCandidates as $col => $val) {
                if (Schema::hasColumn('audit_logs', $col)) {
                    $payload[$col] = $val;
                }
            }

            // Kalau minimal tidak ada kolom penting (misal tabel beda total), skip
            if (empty($payload)) return;

            AuditLog::create($payload);
        } catch (\Throwable $e) {
            // sengaja di-silent biar fitur audit tidak bikin aplikasi crash
            // kalau mau debug, bisa log: \Log::error($e->getMessage());
        }
    }

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
            'agenda_selesai' => 'nullable|date|after_or_equal:agenda_mulai',
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

        // ✅ AUDIT: CREATE SURAT MASUK
        $this->writeAudit(
            'CREATE_SURAT_MASUK',
            'Menambahkan surat masuk: ' . $surat->no_surat,
            null,
            $surat->toArray()
        );

        // ✅ kalau dicentang buat agenda
        if ($request->input('buat_agenda') === '1') {
            $agenda = Agenda::create([
                'judul' => $request->agenda_judul ?: ('Agenda: ' . $surat->subject),
                'tanggal_mulai' => $request->agenda_mulai ?: ($surat->tanggal_terima . ' 09:00:00'),
                'tanggal_selesai' => $request->agenda_selesai ?: null,
                'lokasi' => $request->agenda_lokasi ?: null,
                'keterangan' => $request->agenda_keterangan ?: ('Dibuat otomatis dari Surat Masuk: ' . $surat->no_surat),
                'status' => 'terjadwal',
                'surat_masuk_id' => $surat->id,
                'created_by' => Auth::id() ?? 1, // kalau belum login, aman
            ]);

            // ✅ AUDIT: CREATE AGENDA
            $this->writeAudit(
                'CREATE_AGENDA_DARI_SURAT_MASUK',
                'Membuat agenda dari surat masuk: ' . $surat->no_surat,
                null,
                method_exists($agenda, 'toArray') ? $agenda->toArray() : null
            );
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
        $dataLama = $item->toArray();

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

        // ✅ AUDIT: UPDATE
        $this->writeAudit(
            'UPDATE_SURAT_MASUK',
            'Memperbarui surat masuk: ' . $item->no_surat,
            $dataLama,
            $item->fresh()->toArray()
        );

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = SuratMasuk::findOrFail($id);
        $dataLama = $item->toArray();

        // hapus file kalau ada
        if ($item->file_surat && Storage::disk('public')->exists($item->file_surat)) {
            Storage::disk('public')->delete($item->file_surat);
        }

        $item->delete();

        // ✅ AUDIT: DELETE
        $this->writeAudit(
            'DELETE_SURAT_MASUK',
            'Menghapus surat masuk: ' . ($dataLama['no_surat'] ?? '-'),
            $dataLama,
            null
        );

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus!');
    }

    // ================================
    // 🔥 METHOD UNTUK BUKA FILE
    // ================================
    public function lihatFile($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        // Tandai surat sudah dibaca
        if ($surat->is_read == 0) {
            $surat->update(['is_read' => 1]);

            // ✅ AUDIT: MARK READ (AUTO)
            $this->writeAudit(
                'MARK_READ_SURAT_MASUK',
                'Surat otomatis ditandai dibaca saat file dibuka: ' . $surat->no_surat
            );
        }

        if (
            !$surat->file_surat ||
            !Storage::disk('public')->exists($surat->file_surat)
        ) {
            abort(404, 'File tidak ditemukan');
        }

        // ✅ AUDIT: VIEW FILE
        $this->writeAudit(
            'VIEW_FILE_SURAT_MASUK',
            'Melihat file surat masuk: ' . $surat->no_surat
        );

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
            $surat->update(['is_read' => 1]);
        }

        // ✅ AUDIT: MARK READ (MANUAL)
        $this->writeAudit(
            'MARK_READ_SURAT_MASUK',
            'Menandai surat sudah dibaca: ' . $surat->no_surat
        );

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat ditandai sudah dibaca.');
    }

    // optional route lama
    public function file($id)
    {
        return $this->lihatFile($id);
    }
}
