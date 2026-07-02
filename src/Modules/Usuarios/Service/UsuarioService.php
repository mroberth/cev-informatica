<?php
declare(strict_types=1);

namespace App\Usuarios\Service;

use App\Usuarios\DTO\UsuarioDTO;
use Exception;

class UsuarioService
{
	private const NOMBRE_REGEX = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/u';
	private const CORREO_REGEX = '/^[a-zA-Z0-9._%+-]+@(hotmail|yahoo|gmail|outlook)\.(com|es|net|org)$/i';
	private const ESTADOS_VALIDOS = ['activo', 'inactivo'];

	public function validarUsuario(UsuarioDTO $usuario): UsuarioDTO
	{
		$this->validarUsuarioInterno($usuario);

		return $usuario;
	}

	public function validarYPreparar(array $datos): array
	{
		$nombre = $this->normalizarTexto($datos['nombre'] ?? '');
		$apellido = $this->normalizarTexto($datos['apellido'] ?? '');
		$correo = $this->normalizarTexto($datos['correo'] ?? '');
		$password = (string) ($datos['password'] ?? '');
		$rolId = $datos['rol_id'] ?? null;
		$estado = $this->normalizarTexto($datos['estado'] ?? '');

		$this->validarNombre($nombre);
		$this->validarApellido($apellido);
		$this->validarCorreo($correo);
		$this->validarPassword($password);
		$rolId = $this->validarRolId($rolId);
		$this->validarEstado($estado);

		return [
			'nombre' => $nombre,
			'apellido' => $apellido,
			'correo' => $correo,
			'password_hash' => password_hash($password, PASSWORD_DEFAULT),
			'rol_id' => $rolId,
			'estado' => $estado,
		];
	}

	private function validarUsuarioInterno(UsuarioDTO $usuario): void
	{
		$this->validarNombre($usuario->getNombre());
		$this->validarApellido($usuario->getApellido());
		$this->validarCorreo($usuario->getCorreo());
		$this->validarPassword($usuario->getPassword());
		$this->validarRolId($usuario->getUsuarioId());
		$this->validarEstado($usuario->getEstado());
	}

	public function validarUsuarioSinPassword(UsuarioDTO $usuario): UsuarioDTO
	{
		$this->validarNombre($usuario->getNombre());
		$this->validarApellido($usuario->getApellido());
		$this->validarCorreo($usuario->getCorreo());
		$this->validarRolId($usuario->getUsuarioId());
		$this->validarEstado($usuario->getEstado());

		return $usuario;
	}

	private function validarNombre(string $nombre): void
	{
		if ($nombre === '') {
			throw new Exception('El nombre es obligatorio.', 400);
		}

		if ($this->longitud($nombre) > 20) {
			throw new Exception('El nombre ingresado excede los límites de longitud.', 400);
		}

		if ($this->longitud($nombre) < 3) {
			throw new Exception('El nombre ingresado es demasiado corto.', 400);
		}

		if (!preg_match(self::NOMBRE_REGEX, $nombre)) {
			throw new Exception('El nombre ingresado es inválido.', 400);
		}
	}

	private function validarApellido(string $apellido): void
	{
		if ($apellido === '') {
			throw new Exception('El apellido es obligatorio.', 400);
		}

		if ($this->longitud($apellido) > 20) {
			throw new Exception('El apellido ingresado excede los límites de longitud.', 400);
		}

		if ($this->longitud($apellido) < 3) {
			throw new Exception('El apellido ingresado es demasiado corto.', 400);
		}

		if (!preg_match(self::NOMBRE_REGEX, $apellido)) {
			throw new Exception('El apellido ingresado es inválido.', 400);
		}
	}

	private function validarCorreo(string $correo): void
	{
		if ($correo === '') {
			throw new Exception('El correo es obligatorio.', 400);
		}

		if ($this->longitud($correo) > 30) {
			throw new Exception('El correo ingresado excede los límites de longitud.', 400);
		}

		if (!preg_match(self::CORREO_REGEX, $correo)) {
			throw new Exception('El correo ingresado es inválido.', 400);
		}
	}

	private function validarPassword(string $password): void
	{
		if ($password === '') {
			throw new Exception('La contraseña es obligatoria.', 400);
		}

		if (!preg_match('/^(?=.*[#$%&.]).{8,}$/', $password)) {
			throw new Exception('La contraseña debe tener mínimo 8 caracteres y al menos un símbolo (#, $, %, &, .).', 400);
		}
	}

	private function validarRolId(mixed $rolId): int
	{
		$rolIdValidado = filter_var($rolId, FILTER_VALIDATE_INT, [
			'options' => ['min_range' => 1],
		]);

		if ($rolIdValidado === false) {
			throw new Exception('El rol del Usuario es obligatorio.', 400);
		}

		return (int) $rolIdValidado;
	}

	private function validarEstado(string $estado): void
	{
		if ($estado === '') {
			throw new Exception('El estado del Usuario es obligatorio.', 400);
		}

		if (!in_array($estado, self::ESTADOS_VALIDOS, true)) {
			throw new Exception('El estado del Usuario es inválido.', 400);
		}
	}

	private function normalizarTexto(mixed $valor): string
	{
		return trim((string) $valor);
	}

	private function longitud(string $valor): int
	{
		return function_exists('mb_strlen') ? mb_strlen($valor) : strlen($valor);
	}
}
