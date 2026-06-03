<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;

class FormularioApiController extends Controller
{
    public function generarPdf(Request $request)
    {
        try {
            $respuestas = $request->respuestas;
            if (!$respuestas || !is_array($respuestas)) {
                return response()->json(['error' => 'Faltan datos de respuestas'], 400);
            }

            // Guardar en BD
            FormSubmission::updateOrCreate(
                ['user_id' => $request->user()->id],
                ['respuestas' => $respuestas]
            );

            $pdf = new Fpdi();
            $plantilla = storage_path('app/public/plantilla.pdf');
            if (!file_exists($plantilla)) {
                return response()->json(['error' => 'Plantilla no encontrada'], 500);
            }
            $totalPaginas = $pdf->setSourceFile($plantilla);

            for ($i = 1; $i <= $totalPaginas; $i++) {
                $template = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($template);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($template);

                if ($i == 1) {
                    $pdf->SetFont('Times', 'B', 100);
                    $pdf->SetTextColor(255, 255, 255);
                    $y = 350;
                    $col = 0;
                    foreach ($respuestas as $respuesta) {
                        $texto = $respuesta['respuesta'] ?? '';
                        $x = ($col == 0) ? 1000 : 1600;
                        $pdf->SetXY($x, $y);
                        $pdf->Write(60, mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8'));
                        $col++;
                        if ($col == 2) { $col = 0; $y += 110; }
                    }
                }
            }

            $base64 = base64_encode($pdf->Output('S'));
            return response()->json(['pdf' => $base64]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function yaEnviado(Request $request)
    {
        $existe = FormSubmission::where('user_id', $request->user()->id)->exists();
        return response()->json(['enviado' => $existe]);
    }
}
