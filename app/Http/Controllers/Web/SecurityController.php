<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SecurityController extends Controller
{
    /**
     * Display the encryption methodology and Zero-Knowledge Proof explanation page.
     */
    public function encryption(): View
    {
        // return view('security.encryption');
        return view('security.encryption_gov');

    }

    /**
     * Display the privacy policy page.
     */
    public function privacy(): View
    {
        return view('security.privacy');
    }

    /**
     * Display the data protection policy page.
     */
    public function dataProtection(): View
    {
        return view('security.data-protection');
    }

    /**
     * Generate and download the ZKP verification documentation.
     * Returns a printable HTML report that can be saved as PDF.
     */
    public function downloadVerificationReport(): BinaryFileResponse|Response
    {
        $pdfPath = public_path('documents/zkp-verification-report.pdf');

        // If static PDF exists, serve it
        if (file_exists($pdfPath)) {
            return response()->download($pdfPath, 'ZKP-Verification-Report.pdf');
        }

        // Generate HTML report that can be printed as PDF
        return response()->view('security.verification-report', [
            'generatedAt' => now()->format('Y-m-d H:i:s'),
            'reportId' => 'ZKP-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8)),
        ]);
    }
}

