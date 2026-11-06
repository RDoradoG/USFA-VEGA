<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Models\AbandonoDesercion;
use App\Models\Estudiante;
use App\Models\SeguimientoDesercion;
use App\Models\SeguimientoEstudiante;

class AbandonoDesercionController extends Controller
{

    private const DROPOUT_ID = 1;
    private const ATTRITION_ID = 2;
    private const STATE_INIT_ID = 1;

    public function sync(array $dropout, array $sedes, array $carreras, array $attritionTypes = [], $type_attrition = false)
    {
        $carrera_id =  $carreras[$dropout['IdCarrera']] ?? null;
        $sede_id =  $sedes[$dropout['IdSede']] ?? null;
        $estudiante = Estudiante::where('id_persona', $dropout['IdPersona'])->first();
        $dropout_type = ($type_attrition) ? self::ATTRITION_ID : self::DROPOUT_ID;
        $message = '';

        if (is_null($carrera_id)) $message .= 'La carrera no existe.';
        if (is_null($sede_id)) $message .= 'La Sede no existe.';
        if (is_null($estudiante)) $message .= 'El estudiante no existe.';

        if (!is_null($carrera_id) && !is_null($sede_id) && !is_null($estudiante)) {
            $seguimiento_estudiante = SeguimientoEstudiante::where('estudiante_id', $estudiante->id)->first();

            if (is_null($seguimiento_estudiante)) {
                $seguimiento_estudiante = SeguimientoEstudiante::create([
                    'estudiante_id' => $estudiante->id,
                    'seguimiento_estado_id' => self::STATE_INIT_ID
                ]);
            }

            //delete Abandonos/deserciones previos

            $abandono_desercion = AbandonoDesercion::where('seguimiento_estudiante_id', $seguimiento_estudiante->id)
                ->where('sede_id', $sede_id)
                ->where('carrera_id', $carrera_id)
                ->where('seguimiento_tipo_id', $dropout_type)
                ->first();

            if (is_null($abandono_desercion)) {
                $abandono_desercion = AbandonoDesercion::create([
                    'seguimiento_estudiante_id' => $seguimiento_estudiante->id,
                    'sede_id' => $sede_id,
                    'carrera_id' => $carrera_id,
                    'seguimiento_tipo_id' => $dropout_type//,
                    //'seguimiento_estado_id' => self::STATE_INIT_ID,
                ]);
            }

            if ($dropout_type == self::ATTRITION_ID) $this->updateSeguimientoDesercion($abandono_desercion->id, $dropout['Obs'], $attritionTypes);

            return [
                'success' => true,
                'message' => null,
                'key' => $dropout['IdPersona']
            ];
        } else {
            //save on log
            return [
                'success' => false,
                'message' => $message,
                'key' => $dropout['IdPersona']
            ];
        }
    }

    private function updateSeguimientoDesercion(Int $followup_id, string $obs, array $attritionTypes)
    {
        SeguimientoDesercion::where('abandono_desercion_id', $followup_id)->delete();
        $this->saveSeguimientoDesercion($followup_id, $obs, $attritionTypes);
    }

    private function saveSeguimientoDesercion(Int $followup_id, string $obs, array $attritionTypes)
    {
        $followAttritions = $this->getFromObs($obs);
        foreach ($followAttritions as $followAttrition) {
            $attritionType_id = $attritionTypes[$followAttrition['type']];
            SeguimientoDesercion::create([
                'abandono_desercion_id' => $followup_id,
                'desercion_tipo_id' => $attritionType_id,
                'materia' => $followAttrition['subject'],
                'nota' => $followAttrition['grade']
            ]);
        }
    }

    private function getFromObs(string $obs)
    {
        $result = [];

        foreach (explode(';', $obs) as $item) {
            if (preg_match('/^([^-]+)-([^(]+)(?:\(([^)]*)\))?$/', $item, $matches)) {
                $result[] = [
                    'subject' => $matches[1],
                    'type'    => $this->getAssertionTypeKey($matches[2]),
                    'grade'   => isset($matches[3]) && $matches[3] !== ''
                        ? (int) $matches[3]
                        : 0,
                ];
            }
        }

        return $result;
    }

    private function getAssertionTypeKey(string $type)
    {
        if ($type == 'SinAsignacion') return 'Sin Asignación';

        if ($type == 'SinNota') return 'Sin Nota';

        if ($type == 'Nota') return 'Nota';

        return null;
    }

    public function deleteNewFollowUps() {
        SeguimientoDesercion::join('abandonos_deserciones', 'abandonos_deserciones.id', '=', 'seguimiento_deserciones.abandono_desercion_id')
            ->join('seguimiento_estudiantes', 'seguimiento_estudiantes.id', '=', 'abandonos_deserciones.seguimiento_estudiante_id')
            ->where('seguimiento_estudiantes.seguimiento_estado_id', self::STATE_INIT_ID)->delete();

        AbandonoDesercion::join('seguimiento_estudiantes', 'seguimiento_estudiantes.id', '=', 'abandonos_deserciones.seguimiento_estudiante_id')
            ->where('seguimiento_estudiantes.seguimiento_estado_id', self::STATE_INIT_ID)->delete();

        SeguimientoEstudiante::where('seguimiento_estado_id', self::STATE_INIT_ID)->delete();
    }
}
