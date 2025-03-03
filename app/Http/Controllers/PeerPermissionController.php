<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\PeerPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PeerPermissionController extends Controller
{
    // Daftar peer yang dapat diajak untuk review (sesama peserta program)
    public function index()
    {
        $participantId = Cookie::get('participant_id');
        
        if (!$participantId) {
            return redirect('/')->with('error', 'Silakan bergabung dengan program terlebih dahulu.');
        }
        
        $participant = Participant::findOrFail($participantId);
        
        // Ambil semua peserta dalam program yang sama kecuali diri sendiri
        $programPeers = Participant::where('program_id', $participant->program_id)
            ->where('id', '!=', $participantId)
            ->get();
            
        // Ambil izin yang sudah diberikan
        $grantedPermissions = $participant->grantedPermissions()
            ->with('grantee')
            ->get()
            ->keyBy('grantee_id');
            
        // Ambil izin yang sudah diterima
        $receivedPermissions = $participant->receivedPermissions()
            ->with('grantor')
            ->get()
            ->keyBy('grantor_id');
            
        return view('participant.peers', compact('participant', 'programPeers', 
            'grantedPermissions', 'receivedPermissions'));
    }
    
    // Berikan atau cabut izin peer
    public function togglePermission(Request $request)
    {
        $request->validate([
            'peer_id' => 'required|exists:participants,id',
            'grant_permission' => 'required|boolean'
        ]);
        
        $participantId = Cookie::get('participant_id');
        
        if (!$participantId) {
            return redirect('/')->with('error', 'Silakan bergabung dengan program terlebih dahulu.');
        }
        
        $participant = Participant::findOrFail($participantId);
        $peerId = $request->peer_id;
        
        // Pastikan tidak memberikan izin kepada diri sendiri
        if ($participantId == $peerId) {
            return redirect()->back()->with('error', 'Tidak dapat memberikan izin kepada diri sendiri');
        }
        
        // Pastikan peer adalah peserta program yang sama
        $peer = Participant::where('id', $peerId)
            ->where('program_id', $participant->program_id)
            ->firstOrFail();
            
        // Cari izin yang sudah ada
        $permission = PeerPermission::where('grantor_id', $participantId)
            ->where('grantee_id', $peerId)
            ->first();
            
        if ($request->grant_permission) {
            // Jika belum ada izin, buat baru
            if (!$permission) {
                PeerPermission::create([
                    'grantor_id' => $participantId,
                    'grantee_id' => $peerId,
                    'is_active' => true
                ]);
                $message = 'Izin berhasil diberikan kepada ' . $peer->name;
            } else {
                // Jika sudah ada, aktifkan
                $permission->is_active = true;
                $permission->save();
                $message = 'Izin berhasil diaktifkan kembali untuk ' . $peer->name;
            }
        } else {
            // Jika ada izin, nonaktifkan
            if ($permission) {
                $permission->is_active = false;
                $permission->save();
                $message = 'Izin berhasil dicabut dari ' . $peer->name;
            } else {
                $message = $peer->name . ' tidak memiliki izin';
            }
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    // Lihat progress peer yang telah memberikan izin
    public function viewPeerProgress($peerId)
    {
        $participantId = Cookie::get('participant_id');
        
        if (!$participantId) {
            return redirect('/')->with('error', 'Silakan bergabung dengan program terlebih dahulu.');
        }
        
        // Pastikan peer telah memberikan izin
        $permission = PeerPermission::where('grantor_id', $peerId)
            ->where('grantee_id', $participantId)
            ->where('is_active', true)
            ->firstOrFail();
            
        $peer = Participant::with(['program', 'progressItems'])
            ->findOrFail($peerId);
            
        return view('participant.peer_progress', compact('peer'));
    }
}