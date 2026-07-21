<?php
declare(strict_types=1);

namespace App\Materia\Service;

use App\Materia\DTO\EvaluacionDTO;
use App\Materia\DTO\RecursoDTO;
use Exception;

class MateriaService
{
    private const TIPOS_VALIDOS = ['pdf', 'documento', 'enlace', 'video', 'otro'];
    private const TIPOS_EVALUACION = ['tarea', 'examen', 'proyecto', 'taller', 'otro'];
    private const TIPOS_ARCHIVO_PERMITIDOS = [
        'pdf' => 'application/pdf',
        'documento' => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'video' => ['video/mp4', 'video/webm', 'video/ogg'],
    ];

    public function validarRecurso(array $data): array
    {
        $errores = [];

        if (empty($data['titulo']) || trim($data['titulo']) === '') {
            $errores[] = 'El título es obligatorio.';
        }

        $tipo = $data['tipo'] ?? 'otro';
        if (!in_array($tipo, self::TIPOS_VALIDOS, true)) {
            $errores[] = 'El tipo de recurso no es válido.';
        }

        if ($tipo === 'enlace' && (empty($data['enlace_url']) || !filter_var($data['enlace_url'], FILTER_VALIDATE_URL))) {
            $errores[] = 'Debe proporcionar un enlace válido.';
        }

        if ($tipo !== 'enlace' && empty($data['archivo'])) {
            $errores[] = 'Debe subir un archivo.';
        }

        if ($tipo !== 'enlace' && !empty($data['archivo'])) {
            $extension = strtolower(pathinfo($data['archivo']['name'], PATHINFO_EXTENSION));
            $extensionesPermitidas = ['pdf', 'doc', 'docx', 'mp4', 'webm', 'ogg', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'png', 'jpg', 'jpeg', 'zip'];
            if (!in_array($extension, $extensionesPermitidas, true)) {
                $errores[] = "El formato .{$extension} no está permitido.";
            }
        }

        return $errores;
    }

    public function prepararDTO(
        int $idAsignacion,
        string $titulo,
        ?string $descripcion,
        string $tipo,
        ?string $archivoRuta,
        ?string $enlaceUrl,
        int $creadoPor,
        int $id = 0,
    ): RecursoDTO {
        return new RecursoDTO(
            $id,
            $idAsignacion,
            trim($titulo),
            $descripcion ? trim($descripcion) : null,
            $tipo,
            $archivoRuta,
            $enlaceUrl,
            $creadoPor,
        );
    }

    public function validarEvaluacion(array $data): array
    {
        $errores = [];

        if (empty($data['titulo']) || trim($data['titulo']) === '') {
            $errores[] = 'El título es obligatorio.';
        }

        $tipo = $data['tipo'] ?? 'tarea';
        if (!in_array($tipo, self::TIPOS_EVALUACION, true)) {
            $errores[] = 'El tipo de evaluación no es válido.';
        }

        if (empty($data['fecha_entrega'])) {
            $errores[] = 'La fecha de entrega es obligatoria.';
        } else {
            $ts = strtotime($data['fecha_entrega']);
            if ($ts === false) {
                $errores[] = 'La fecha de entrega no tiene un formato válido.';
            }
        }

        if (!empty($data['porcentaje'])) {
            $porcentaje = (float) $data['porcentaje'];
            if ($porcentaje <= 0 || $porcentaje > 100) {
                $errores[] = 'El porcentaje debe estar entre 1 y 100.';
            }
        }

        return $errores;
    }

    public function prepararEvaluacionDTO(
        int $idAsignacion,
        string $titulo,
        ?string $descripcion,
        string $tipo,
        ?float $porcentaje,
        string $fechaEntrega,
        int $creadoPor,
        int $id = 0,
    ): EvaluacionDTO {
        return new EvaluacionDTO(
            $id,
            $idAsignacion,
            trim($titulo),
            $descripcion ? trim($descripcion) : null,
            $tipo,
            $porcentaje,
            $fechaEntrega,
            $creadoPor,
        );
    }
}
