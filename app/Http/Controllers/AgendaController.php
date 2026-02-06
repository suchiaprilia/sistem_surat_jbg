<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    // LIST (dengan filter: today/week/month)
    public function index(Request $request)
    {
        $range = $request->query('range'); // today|week|month|null

        $query = Agenda::query()->orderBy('tanggal_mulai', 'asc');

        if ($range === 'today') {
            $start = Carbon::today();
            $end = Carbon::tomorrow();
            $query->whereBetween('tanggal_mulai', [$start, $end]);
        } elseif ($range === 'week') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
            $query->whereBetween('tanggal_mulai', [$start, $end]);
        } elseif ($range === 'month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $query->whereBetween('tanggal_mulai', [$start, $end]);
        }

        // Optional: filter status
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $agendas = $query->paginate(10)->withQueryString();

        // ✅ AUDIT: view agenda list (opsional)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            $ket = "Melihat daftar agenda";
            $ket .= $range ? " | Range: {$range}" : "";
            $ket .= $request->filled('status') ? " | Status: " . $request->query('status') : "";
            AuditLog::tulis('view', 'agenda', null, $ket, $actor);
        }

        // Web view (blade)
        if (!$request->wantsJson()) {
            return view('agenda', compact('agendas', 'range'));
        }

        // API JSON
        return response()->json($agendas);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['nullable', 'in:terjadwal,selesai,ditunda,dibatalkan'],
            'surat_masuk_id' => ['nullable', 'integer'],
            'surat_keluar_id' => ['nullable', 'integer'],
        ]);

        $data['created_by'] = auth()->id() ?? 1;

        $agenda = Agenda::create($data);

        // ✅ AUDIT: create agenda
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'create',
                'agenda',
                $agenda->id ?? null,
                "Menambah agenda: " . ($agenda->judul ?? '-'),
                $actor
            );
        }

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Agenda berhasil ditambahkan.');
        }

        return response()->json(['message' => 'created', 'data' => $agenda], 201);
    }

    public function show(Request $request, Agenda $agenda)
    {
        // ✅ AUDIT: view detail agenda (opsional)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'view',
                'agenda',
                $agenda->id ?? null,
                "Melihat detail agenda: " . ($agenda->judul ?? '-'),
                $actor
            );
        }

        if (!$request->wantsJson()) {
            return view('agenda.show', compact('agenda'));
        }

        return response()->json($agenda);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $data = $request->validate([
            'judul' => ['sometimes', 'required', 'string', 'max:255'],
            'tanggal_mulai' => ['sometimes', 'required', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['nullable', 'in:terjadwal,selesai,ditunda,dibatalkan'],
        ]);

        // validasi after_or_equal manual kalau tanggal_mulai ikut diupdate
        if (array_key_exists('tanggal_selesai', $data) && $data['tanggal_selesai']) {
            $mulai = array_key_exists('tanggal_mulai', $data)
                ? Carbon::parse($data['tanggal_mulai'])
                : $agenda->tanggal_mulai;

            if (Carbon::parse($data['tanggal_selesai'])->lt($mulai)) {
                return back()->withErrors(['tanggal_selesai' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.']);
            }
        }

        $beforeStatus = $agenda->status ?? null;
        $agenda->update($data);

        // ✅ AUDIT: update agenda
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';

            $ket = "Mengubah agenda: " . ($agenda->judul ?? '-');
            // kalau status berubah, kasih keterangan lebih jelas
            if (array_key_exists('status', $data) && $data['status'] !== $beforeStatus) {
                $ket .= " | Status: " . ($beforeStatus ?? '-') . " -> " . ($data['status'] ?? '-');
            }

            AuditLog::tulis(
                'update',
                'agenda',
                $agenda->id ?? null,
                $ket,
                $actor
            );
        }

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Agenda berhasil diupdate.');
        }

        return response()->json(['message' => 'updated', 'data' => $agenda]);
    }

    public function destroy(Request $request, Agenda $agenda)
    {
        // ✅ AUDIT: delete agenda (log dulu sebelum delete)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'delete',
                'agenda',
                $agenda->id ?? null,
                "Menghapus agenda: " . ($agenda->judul ?? '-'),
                $actor
            );
        }

        $agenda->delete();

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Agenda berhasil dihapus.');
        }

        return response()->json(['message' => 'deleted']);
    }

    // Shortcut: tandai selesai
    public function markDone(Request $request, Agenda $agenda)
    {
        $beforeStatus = $agenda->status ?? null;
        $agenda->update(['status' => 'selesai']);

        // ✅ AUDIT: change status to done
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'change_status',
                'agenda',
                $agenda->id ?? null,
                "Menandai agenda selesai: " . ($agenda->judul ?? '-') . " | Status: " . ($beforeStatus ?? '-') . " -> selesai",
                $actor
            );
        }

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Agenda ditandai selesai.');
        }

        return response()->json(['message' => 'done', 'data' => $agenda]);
    }
}
