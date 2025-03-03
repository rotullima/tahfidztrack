<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class ProgramController extends Controller
{
    // Tampilkan daftar program
    public function index()
    {
        $programs = Program::all();
        return view('admin.dashboard', compact('programs'));
    }

    // Tampilkan form tambah program
    public function create()
    {
        return view('admin.program.create');
    }

    // Simpan program baru
    public function store(Request $request)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
            'surah' => 'required',
            'deadline' => 'required|numeric|min:1',
        ]);

        // Siapkan data
        $data = $request->all();
        
        // Buat slug dari nama program
        $data['slug'] = Str::slug($data['program_name']);
        
        // Handle surah as array or string
        if (!is_array($request->surah)) {
            $data['surah'] = [$request->surah];
        }
        
        // Tambahkan tanggal deadline berdasarkan input bulan - pastikan nilai integer
        $months = (int)$request->deadline; // Konversi ke integer
        $data['deadline_date'] = Carbon::now()->addMonths($months)->format('Y-m-d');

        Program::create($data);

        return redirect()->route('program.index')->with('success', 'Program berhasil ditambahkan!');
    }

    // Tampilkan form edit program
    public function edit($slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        return view('admin.program.edit', compact('program'));
    }

    // Update program
    public function update(Request $request, $slug)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
            'surah' => 'required',
            'deadline' => 'required|numeric|min:1',
        ]);

        $program = Program::where('slug', $slug)->firstOrFail();
        
        // Siapkan data
        $data = $request->all();
        
        // Update slug dari nama program
        $data['slug'] = Str::slug($data['program_name']);
        
        // Handle surah as array or string
        if (!is_array($request->surah)) {
            $data['surah'] = [$request->surah];
        }
        
        // Perbarui tanggal deadline
        $months = (int)$request->deadline;
        $data['deadline_date'] = Carbon::now()->addMonths($months)->format('Y-m-d');
        
        $program->update($data);

        return redirect()->route('program.index')->with('success', 'Program berhasil diperbarui!');
    }

    // Hapus program
    public function destroy($slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        $program->delete();

        return redirect()->route('program.index')->with('success', 'Program berhasil dihapus!');
    }

    // Show program detail
    public function show($slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        return view('admin.program.show', compact('program'));
    }

    public function generateInviteLink($slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        
        $invitation = Invitation::create([
            'program_id' => $program->id,
            'token' => Invitation::generateToken(),
            'is_active' => true
        ]);
        
        $inviteUrl = URL::to('/join/' . $invitation->token);
        
        return response()->json([
            'success' => true,
            'invite_url' => $inviteUrl
        ]);
    }
}