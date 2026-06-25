<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereUpdatedAt($value)
 */
	class Carrera extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string $color
 * @property int $orden
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado whereOrden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Estado whereUpdatedAt($value)
 */
	class Estado extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fuente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fuente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fuente query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fuente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fuente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fuente whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fuente whereUpdatedAt($value)
 */
	class Fuente extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $lead_id
 * @property int $usuario_id
 * @property string $mensaje
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Lead $lead
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead whereMensaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryLead whereUsuarioId($value)
 */
	class HistoryLead extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Horario whereUpdatedAt($value)
 */
	class Horario extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string $apellido_paterno
 * @property string $apellido_materno
 * @property string $celular
 * @property string|null $genero
 * @property string|null $codigo_pais
 * @property string|null $ciudad
 * @property int $sede_id
 * @property int|null $carrera_id
 * @property int|null $horario_id
 * @property int $estado_id
 * @property int|null $usuario_id
 * @property int $fuente_id
 * @property int|null $promocion_id
 * @property string $interes_nivel
 * @property \Carbon\CarbonImmutable $fecha_registro
 * @property \Carbon\CarbonImmutable|null $ultimo_contacto
 * @property string|null $observaciones
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $asesor
 * @property-read \App\Models\Carrera|null $carrera
 * @property-read \App\Models\Estado $estado
 * @property-read \App\Models\Fuente $fuente
 * @property-read mixed $full_name
 * @property-read mixed $tiene_contacto
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HistoryLead> $historyLeads
 * @property-read int|null $history_leads_count
 * @property-read \App\Models\Horario|null $horario
 * @property-read \App\Models\Promocion|null $promocion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recordatorio> $recordatorios
 * @property-read int|null $recordatorios_count
 * @property-read \App\Models\Sede $sede
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seguimiento> $seguimientos
 * @property-read int|null $seguimientos_count
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead asignados($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead sinContacto()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereApellidoMaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereApellidoPaterno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCarreraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCelular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCiudad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCodigoPais($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereFechaRegistro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereFuenteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereGenero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereHorarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereInteresNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead wherePromocionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereSedeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereUltimoContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereUsuarioId($value)
 */
	class Lead extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocion whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promocion whereUpdatedAt($value)
 */
	class Promocion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $lead_id
 * @property int $usuario_id
 * @property \Carbon\CarbonImmutable $fecha_recordatorio
 * @property string|null $descripcion
 * @property bool $completado
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Lead $lead
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio whereCompletado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio whereFechaRecordatorio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recordatorio whereUsuarioId($value)
 */
	class Recordatorio extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sede newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sede newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sede query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sede whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sede whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sede whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sede whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sede whereUpdatedAt($value)
 */
	class Sede extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $lead_id
 * @property int $usuario_id
 * @property string $tipo
 * @property string $resultado
 * @property string|null $observacion
 * @property \Carbon\CarbonImmutable $fecha_contacto
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Lead $lead
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereFechaContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereResultado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seguimiento whereUsuarioId($value)
 */
	class Seguimiento extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string $rol
 * @property bool $activo
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HistoryLead> $historyLeads
 * @property-read int|null $history_leads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recordatorio> $recordatorios
 * @property-read int|null $recordatorios_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Seguimiento> $seguimientos
 * @property-read int|null $seguimientos_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

