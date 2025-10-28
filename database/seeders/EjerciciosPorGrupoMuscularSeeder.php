<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;   
use App\Models\GrupoMuscular;
class EjerciciosPorGrupoMuscularSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que estos grupos existen en la tabla grupos_musculares
        $map = [
            'Pecho'  => ['Press inclinado con mancuernas','Press declinado con mancuernas','Aperturas planas con mancuernas','Aperturas inclinadas con mancuernas','Pullover con mancuerna','Press de pecho en máquina (plano/inclinado/declinado)',
            'Aperturas en máquina','Press en máquina tipo Hammer Strength','Press vertical asistido'],
            'Espalda'=> ['Remo con mancuerna a una mano','Remo inclinado con mancuernas (ambas manos)','Pullover con mancuerna','Remo renegado (renegade row)','Peso muerto con mancuernas','Jalón al pecho (polea alta)','Jalón tras nuca (opcional, cuidado con hombros)','Remo sentado en máquina o polea baja','Pull-over en máquina','Máquina Hammer de jalón o remoRemo en T (con soporte de máquina)'],
            'Pierna' => ['Sentadillas con mancuernas','Zancadas (walking lunges, búlgaras)','Peso muerto rumano con mancuernas','Step-up en banco con mancuernas','Sentadilla goblet (con mancuerna)','Hip thrust con mancuerna',' máquina','Prensa de pierna','Extensiones de cuádriceps en máquina','Curl femoral tumbado (isquios)','Curl femoral sentado','Sentadilla Hack en máquina','Glute bridge / hip thrust en máquina específica','Abducción/adducción de cadera en máquina','Elevación de gemelos sentado o de pie'],
            'Hombro' => ['Press militar sentado o de pie','Press Arnold','Elevaciones laterales','Elevaciones frontales','Pájaros (elevaciones posteriores)','Encogimientos de hombros','Press de hombros en máquina','Elevaciones laterales en máquina','Pájaros en máquina (peck deck invertida)','Encogimientos en máquina Smith'],
            'Bíceps' => ['Curl de bíceps alterno','Curl de bíceps sentado inclinado','Curl martillo','Curl concentrado','Curl Zottman','Curl de bíceps en máquina (Predicador)','Curl de bíceps en polea baja (barra o cuerda)','Curl unilaterales en polea','Curl Scott en máquina'],
            'Tríceps'=> ['Extensión de tríceps sobre la cabeza','Extensión de tríceps acostado (skull crushers)','Kickback con mancuerna','Press cerrado con mancuernas máquina','Jalón de tríceps en polea alta (barra recta, cuerda o V)','Extensión unilaterales en polea','Press de tríceps en máquina HammerFondos asistidos en máquina'],
            'Abdomen'=> ['Russian twist con mancuerna','Crunch con mancuerna en pecho','Side bend (oblicuos con mancuerna)','Plancha con remo de mancuerna','Crunch en máquina de abdominales','Crunch en polea alta (cuerda)','Elevación de piernas asistida (máquina de fondos y dominadas)','Rotaciones de tronco en polea'],
        ];

        foreach ($map as $nombreGrupo => $ejercicios) {
            $grupo = GrupoMuscular::where('nombre_grupo', $nombreGrupo)->first();

            if (!$grupo) {
                // Si no existe el grupo, lo creamos
                $grupo = GrupoMuscular::create(['nombre_grupo' => $nombreGrupo]);
            }

            foreach ($ejercicios as $nombre) {
                DB::table('ejercicios_por_grupo_muscular')->updateOrInsert(
                    ['grupo_muscular_id' => $grupo->id, 'nombre_ejercicio' => $nombre],
                    ['updated_at' => now(), 'created_at' => now()]
                );
            }
        }
    }
}
