<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
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

        $data['created_by'] = auth()->id(); // kalau ada auth

        $agenda = Agenda::create($data);

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Agenda berhasil ditambahkan.');
        }

        return response()->json(['message' => 'created', 'data' => $agenda], 201);
    }

    public function show(Request $request, Agenda $agenda)
    {
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
            $mulai = array_key_exists('tanggal_mulai', $data) ? Carbon::parse($data['tanggal_mulai']) : $agenda->tanggal_mulai;
            if (Carbon::parse($data['tanggal_selesai'])->lt($mulai)) {
                return back()->withErrors(['tanggal_selesai' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.']);
            }
        }

        $agenda->update($data);

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Agenda berhasil diupdate.');
        }

        return response()->json(['message' => 'updated', 'data' => $agenda]);
    }

    public function destroy(Request $request, Agenda $agenda)
    {
        $agenda->delete();

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Agenda berhasil dihapus.');
        }

        return response()->json(['message' => 'deleted']);
    }

    // Shortcut: tandai selesai
    public function markDone(Request $request, Agenda $agenda)
    {
        $agenda->update(['status' => 'selesai']);

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Agenda ditandai selesai.');
        }

        return response()->json(['message' => 'done', 'data' => $agenda]);
    }
}
