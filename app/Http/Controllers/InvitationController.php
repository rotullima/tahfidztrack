<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class InvitationController extends Controller
{
    // Menampilkan daftar invitation untuk program tertentu
    public function index($slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        $invitations = $program->invitations;
        
        return view('admin.invitation.index', compact('program', 'invitations'));
    }
    
    // Membuat invitation baru
    public function store(Request $request, $slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        
        $invitation = new Invitation([
            'program_id' => $program->id,
            'token' => Invitation::generateToken(),
            'is_active' => true
        ]);
        
        $invitation->save();
        
        // Generate invitation URL
        $invitationUrl = URL::to('/join/' . $invitation->token);
        
        return redirect()->route('invitation.index', $program->slug)
            ->with('success', 'Link invitation berhasil dibuat!')
            ->with('invitation_url', $invitationUrl);
    }
    
    // Nonaktifkan invitation
    public function destroy($id)
    {
        $invitation = Invitation::findOrFail($id);
        $programSlug = $invitation->program->slug;
        
        $invitation->is_active = false;
        $invitation->save();
        
        return redirect()->route('invitation.index', $programSlug)
            ->with('success', 'Link invitation berhasil dinonaktifkan!');
    }
    
    // Halaman Join untuk peserta
    public function showJoinForm($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('is_active', true)
            ->firstOrFail();
            
        $program = $invitation->program;
        
        return view('participant.join', compact('invitation', 'program'));
    }
}