<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return view('admin.program.edit', compact('program'));
    }

    // Update program
    public function update(Request $request, $id)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
            'surah' => 'required',
            'deadline' => 'required|numeric|min:1',
        ]);

        $program = Program::findOrFail($id);
        
        // Siapkan data
        $data = $request->all();
        
        // Update slug dari nama program
        $data['slug'] = Str::slug($data['program_name']);
        
        // Handle surah as array or string
        if (!is_array($request->surah)) {
            $data['surah'] = [$request->surah];
        }
        
        // Perbarui tanggal deadline berdasarkan input bulan - pastikan nilai integer
        $months = (int)$request->deadline; // Konversi ke integer
        $data['deadline_date'] = Carbon::now()->addMonths($months)->format('Y-m-d');
        
        $program->update($data);

        return redirect()->route('program.index')->with('success', 'Program berhasil diperbarui!');
    }

    // Hapus program
    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('program.index')->with('success', 'Program berhasil dihapus!');
    }

    // Show program detail
    public function show($id)
    {
        $program = Program::findOrFail($id);
        return view('admin.program.show', compact('program'));
    }
}