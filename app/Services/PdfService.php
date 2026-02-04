<?php

namespace App\Services;

use App\Models\ApprovalRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /**
     * Generate approval proof PDF.
     */
    public function generateApprovalProof(ApprovalRequest $approval): \Barryvdh\DomPDF\PDF
    {
        $data = [
            'approval' => $approval,
            'company' => config('approval.pdf.company_name'),
            'generated_at' => now(),
        ];

        return Pdf::loadView('pdf.approval-proof', $data)
            ->setPaper(config('approval.pdf.paper_size'))
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
    }

    /**
     * Download approval proof.
     */
    public function downloadApprovalProof(ApprovalRequest $approval): \Illuminate\Http\Response
    {
        $pdf = $this->generateApprovalProof($approval);
        $filename = 'approval-' . $approval->id . '-' . $approval->status . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Stream approval proof (view in browser).
     */
    public function streamApprovalProof(ApprovalRequest $approval): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $pdf = $this->generateApprovalProof($approval);

        return $pdf->stream();
    }
}
