<?php
declare(strict_types=1);
namespace App\Inscripciones\Service;

class InscripcionService {

    public function validarGuardar(array $data): array {
        $errores = [];

        $idSeccion = (int) ($data['id_seccion'] ?? 0);
        if ($idSeccion <= 0) {
            $errores[] = 'La sección es obligatoria.';
        }

        $idEstudiantes = $data['estudiantes'] ?? [];
        if (!is_array($idEstudiantes)) {
            $errores[] = 'El formato de estudiantes es inválido.';
        }

        if (!empty($errores)) {
            throw new \InvalidArgumentException(implode(' ', $errores), 400);
        }

        $idEstudiantes = array_filter($idEstudiantes, fn($id) => (int) $id > 0);
        $idEstudiantes = array_map('intval', $idEstudiantes);
        $idEstudiantes = array_values(array_unique($idEstudiantes));

        return [
            'id_seccion' => $idSeccion,
            'id_estudiantes' => $idEstudiantes,
        ];
    }
}
