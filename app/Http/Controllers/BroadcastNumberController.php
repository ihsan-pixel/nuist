<?php

namespace App\Http\Controllers;

use App\Jobs\SendBroadcastMessage;
use App\Models\BroadcastNumber;
use App\Models\Madrasah;
use App\Services\OpenWAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BroadcastNumberController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'super_admin' || $user->role === 'pengurus') {
            $broadcastNumbers = BroadcastNumber::with('madrasah')->get();
        } else {
            abort(403, 'Unauthorized access');
        }

        $madrasahs = Madrasah::all();
        return view('masterdata.broadcast-numbers.index', compact('broadcastNumbers', 'madrasahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'madrasah_id' => 'required|exists:madrasahs,id',
            'whatsapp_number' => 'required|string|max:20|unique:broadcast_numbers,whatsapp_number,NULL,id,madrasah_id,' . $request->madrasah_id,
            'description' => 'nullable|string|max:255',
        ]);

        BroadcastNumber::create($validated);

        return redirect()->route('admin_masterdata.broadcast-numbers.index')->with('success', 'Nomor broadcast berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $broadcastNumber = BroadcastNumber::findOrFail($id);

        $validated = $request->validate([
            'madrasah_id' => 'required|exists:madrasahs,id',
            'whatsapp_number' => 'required|string|max:20|unique:broadcast_numbers,whatsapp_number,' . $id . ',id,madrasah_id,' . $request->madrasah_id,
            'description' => 'nullable|string|max:255',
        ]);

        $broadcastNumber->update($validated);

        return redirect()->route('admin_masterdata.broadcast-numbers.index')->with('success', 'Nomor broadcast berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $broadcastNumber = BroadcastNumber::findOrFail($id);
        $broadcastNumber->delete();

        return redirect()->route('admin_masterdata.broadcast-numbers.index')->with('success', 'Nomor broadcast berhasil dihapus.');
    }

    /**
     * Send broadcast message to all numbers in a school
     */
    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:madrasahs,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Dispatch job to queue
            SendBroadcastMessage::dispatch(
                $request->school_id,
                $request->message,
                $user->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Broadcast sedang diproses. Pesan akan dikirim ke semua nomor WhatsApp yang terdaftar.'
            ]);

        } catch (\Exception $e) {
            Log::error('Broadcast dispatch failed', [
                'school_id' => $request->school_id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim broadcast: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test OpenWA connection
     */
    public function testConnection()
    {
        $user = auth()->user();
        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $openWAService = new OpenWAService();
            $result = $openWAService->checkSession();

            return response()->json([
                'success' => $result['success'] ?? false,
                'message' => $result['message'] ?? 'Connection test completed',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
