<?php
declare(strict_types=1);
namespace App\AsignacionDocente\Service;

class AsignacionDocenteService {

    public function validarGuardar(array $data): array {
        $errores = [];

        $idSeccion = (int) ($data['id_seccion'] ?? 0);
        if ($idSeccion <= 0) {
            $errores[] = 'La sección es obligatoria.';
        }

        $asignaciones = $data['asignaciones'] ?? [];
        if (!is_array($asignaciones)) {
            $errores[] = 'El formato de asignaciones es inválido.';
        }

        if (!empty($errores)) {
            throw new \InvalidArgumentException(implode(' ', $errores), 400);
        }

        $asignacionesValidas = [];
        foreach ($asignaciones as $asig) {
            $idUc = (int) ($asig['id_unidad_curricular'] ?? 0);
            $idDocente = (int) ($asig['id_docente'] ?? 0);

            if ($idUc <= 0) continue;

            $asignacionesValidas[] = [
                'id_unidad_curricular' => $idUc,
                'id_docente' => $idDocente > 0 ? $idDocente : null,
            ];
        }

        return [
            'id_seccion' => $idSeccion,
            'asignaciones' => $asignacionesValidas,
        ];
    }
}
