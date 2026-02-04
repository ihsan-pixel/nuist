<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class BarcodeController extends Controller
{
    /**
     * Menampilkan halaman barcode untuk user yang sedang login
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Hanya tampilkan barcode untuk user yang sedang login
        $currentUser = $user;

        // Generate QR code using bacon/bacon-qr-code
        $renderer = new ImageRenderer(
            new RendererStyle(500),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($currentUser->nuist_id);

        // Convert SVG to base64 data URI
        $qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        return view('mobile.pengurus.barcode', compact('currentUser', 'qrCodeDataUri'));
    }
}
