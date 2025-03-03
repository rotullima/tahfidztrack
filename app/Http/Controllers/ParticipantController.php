<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Invitation;
use App\Models\Participant;
use App\Models\ProgressItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ParticipantController extends Controller
{
    // Proses pendaftaran peserta baru
    public function register(Request $request, $token)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        
        $invitation = Invitation::where('token', $token)
            ->where('is_active', true)
            ->firstOrFail();
            
        $program = $invitation->program;
        
        // Buat participant baru
        $participant = Participant::create([
            'name' => $request->name,
            'email' => $request->email,
            'program_id' => $program->id,
        ]);
        
        // Buat progress items untuk setiap surah dalam program
        foreach ($program->surah as $surah) {
            ProgressItem::create([
                'participant_id' => $participant->id,
                'surah' => $surah,
                'is_completed' => false
            ]);
        }
        
        // Set cookie untuk mengidentifikasi participant
        Cookie::queue('participant_id', $participant->id, 60 * 24 * 30); // 30 hari
        
        return redirect()->route('participant.dashboard')
            ->with('success', 'Selamat datang di program hafalan ' . $program->program_name);
    }
    
    // Dashboard peserta
    public function dashboard()
    {
        // Ambil ID participant dari cookie
        $participantId = Cookie::get('participant_id');
        
        if (!$participantId) {
            return redirect('/')->with('error', 'Silakan bergabung dengan program terlebih dahulu.');
        }
        
        $participant = Participant::with(['program', 'progressItems'])->findOrFail($participantId);
        
        return view('participant.dashboard', compact('participant'));
    }
    
    // Lihat progress peserta (untuk admin)
    public function viewProgress($slug, $participantId)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        $participant = Participant::where('id', $participantId)
            ->where('program_id', $program->id)
            ->with('progressItems')
            ->firstOrFail();
            
        return view('admin.participant.progress', compact('program', 'participant'));
    }
    
    // Update progress peserta (oleh peserta)
    public function updateProgress(Request $request)
    {
        $participantId = Cookie::get('participant_id');
        
        if (!$participantId) {
            return redirect('/')->with('error', 'Silakan bergabung dengan program terlebih dahulu.');
        }
        
        $request->validate([
            'progress_item_id' => 'required|exists:progress_items,id',
            'notes' => 'nullable|string'
        ]);
        
        $progressItem = ProgressItem::where('id', $request->progress_item_id)
            ->where('participant_id', $participantId)
            ->firstOrFail();
            
        $progressItem->notes = $request->notes;
        $progressItem->save();
        
        return redirect()->route('participant.dashboard')
            ->with('success', 'Catatan hafalan berhasil disimpan!');
    }
    
    // Update status progress (oleh admin)
    public function markProgress(Request $request, $progressItemId)
    {
        $progressItem = ProgressItem::findOrFail($progressItemId);
        
        $progressItem->is_completed = $request->is_completed ? true : false;
        
        if ($request->is_completed) {
            $progressItem->completed_at = now();
        } else {
            $progressItem->completed_at = null;
        }
        
        $progressItem->save();
        
        return redirect()->back()
            ->with('success', 'Status hafalan berhasil diperbarui!');
    }
    
    // Daftar peserta program (untuk admin)
    public function index($slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        $participants = $program->participants;
        
        return view('admin.participant.index', compact('program', 'participants'));
    }
}