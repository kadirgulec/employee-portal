<?php

namespace App\Http\Controllers;

use App\Models\IllnessNotification;
use Spatie\Browsershot\Browsershot;

class IllnessNotificationPDFController extends Controller
{
    public function generatePDF(IllnessNotification $illnessNotification)
    {

        // Re-fetch the latest data
        $illnessNotification = $illnessNotification->fresh();

        // Render the view to HTML
        $html = view('pdf-layout', compact('illnessNotification'))->render();

        // Define a unique PDF file path
        $pdfFilePath = storage_path('app/public/pdfFiles/Arbeitsunfaehigkeit.pdf');

        // Generate the PDF
        Browsershot::html($html)
            ->noSandbox() // Ensure it works on environments without sandbox permissions
            ->showBackground() // Render the background styles
            ->margins(0, 30, 10, 30) // Set the margins (top, right, bottom, left)
            ->savePdf($pdfFilePath); // Save the PDF to the defined path

        // Prepare the file name for download
        $date = date('d.m.Y', strtotime($illnessNotification->illness_notification_at));
        $firstName = $illnessNotification->user->first_name;
        $lastName = $illnessNotification->user->last_name;
        $id = $illnessNotification->user->id;
        $personal_number = $illnessNotification->user->personal_number;

        $fileName = "Pers Nr. {$personal_number} {$firstName} {$lastName} Personalstammblatt AU {$date}.pdf";

        // Return the PDF as a download response
        return response()->download($pdfFilePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(); // Optionally delete the file after it's sent
    }


}
