<?php

namespace App\Http\Controllers;
use setasign\Fpdi\Fpdi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IniciacionController extends Controller
{
    public function guardarRespuestas(Request $request)
    {
        $respuestas = $request->respuestas;

        $pdf = new Fpdi();

        // Ruta plantilla
        $plantilla = storage_path('app/public/plantilla.pdf');

        // Número de páginas
        $totalPaginas = $pdf->setSourceFile($plantilla);

        // Recorrer páginas
        for ($i = 1; $i <= $totalPaginas; $i++) {

            $template = $pdf->importPage($i);

$size = $pdf->getTemplateSize($template);

$pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

$pdf->useTemplate($template);

            // ESCRIBIR SOLO EN PAGINA 1
            if ($i == 1) {

                $pdf->SetFont('Times', 'B', 100);
                $pdf->SetTextColor(255, 255, 255);

                $y = 342;
                $col = 0;

                foreach ($respuestas as $respuesta) {

                    $texto = $respuesta['respuesta'];

                    $x = ($col == 0) ? 950 : 1550;
                    $pdf->SetXY($x, $y);
                    $pdf->Write(60, utf8_decode($texto));

                    $col++;
                    if ($col == 2) {
                        $col = 0;
                        $y += 110;
                    }
                }
            }
        }

        // Descargar PDF
        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'attachment; filename="resultado.pdf"'
            );
    }
}