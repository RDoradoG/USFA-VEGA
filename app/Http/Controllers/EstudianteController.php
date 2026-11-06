<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\EstudianteCarrera;

class EstudianteController extends Controller
{

    public function sync(array $student, array $sedes, array $carreras)
    {
        $message = '';
        $studentDB = Estudiante::where('id_persona', $student['IdPersona'])->first();

        if (is_null($studentDB)) {
            $studentDB = Estudiante::create([
                'id_persona' => $student['IdPersona'],
                'nombres' => $student['Nombres'],
                'apellidos' => $student['Apellidos'],
                'telefonos' => $student['Telefonos'],
                'email' => $student['Email'],
                'genero' => null,
                'codigo_pais' => null,
                'ciudad' => null
            ]);
        } else {
            $studentDB->update([
                'nombres' => $student['Nombres'],
                'apellidos' => $student['Apellidos'],
                'telefonos' => $student['Telefonos'],
                'email' => $student['Email']
            ]);    
        }

        $carrera_id =  $carreras[$student['IdCarrera']] ?? null;
        $sede_id =  $sedes[$student['IdSede']] ?? null;

        if (is_null($carrera_id)) $message .= 'La carrera no existe.';
        if (is_null($sede_id)) $message .= 'La Sede no existe.';

        if (!is_null($carrera_id) && !is_null($sede_id)) {
            $existStudentAssign = EstudianteCarrera::where('estudiante_id', $studentDB->id)
                            ->where('sede_id', $sede_id)
                            ->where('carrera_id', $carrera_id)
                            ->exists();

            if (!$existStudentAssign) {
                EstudianteCarrera::create([
                    'estudiante_id' => $studentDB->id,
                    'sede_id' => $sede_id,
                    'carrera_id' => $carrera_id
                ]);
            }

            return [
                'success' => true,
                'message' => null,
                'key' => $student['IdPersona']
            ];
        } else {
            //save on log
            return [
                'success' => false,
                'message' => $message,
                'key' => $student['IdPersona']
            ];
        }
    }
}
