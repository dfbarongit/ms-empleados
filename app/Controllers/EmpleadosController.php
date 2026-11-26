<?php

namespace App\Controllers;

use App\Models\Empleado;
use DomainException;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use DateTime;

class EmpleadosController
{
    function create(array $data): Empleado
    {
        $this->ensureUniqueFields($data);
        $empleado = new Empleado();
        $empleado->nombres = $data["nombres"];
        $empleado->apellidos = $data["apellidos"];
        $empleado->documento = $data["documento"];
        $empleado->correo = $data["correo"];
        $empleado->telefono = $data["telefono"];
        $empleado->cargo = $data["cargo"];
        $empleado->area = $data["area"];
        $empleado->fecha_ingreso = new DateTime($data["fecha_ingreso"]);
        $empleado->save();
        return $empleado;
    }
    function get_empleados(array $filters = []): Collection
    {
        $query = Empleado::query();
        foreach (["documento", "area", "estado"] as $field) {
            if (array_key_exists($field, $filters)) {
                $query->where($field, $filters[$field]);
            }
        }
        return $query->get();
    }
    function update(int $id, array $data): Empleado
    {
        $empleado = Empleado::findOrFail($id);
        $this->ensureUniqueFields($data, $id);
        $empleado->nombres = $data["nombres"];
        $empleado->apellidos = $data["apellidos"];
        $empleado->documento = $data["documento"];
        $empleado->correo = $data["correo"];
        $empleado->telefono = $data["telefono"];
        $empleado->cargo = $data["cargo"];
        $empleado->area = $data["area"];
        $empleado->area = $data["estado"];
        $empleado->save();
        return $empleado;
    }

    private function ensureUniqueFields(array $data, ?int $ignoreId = null): void
    {
        foreach (["documento" => "documento", "correo" => "correo electrónico"] as $field => $label) {
            $query = Empleado::where($field, $data[$field]);
            if ($ignoreId !== null) {
                $query->where("id", "!=", $ignoreId);
            }
            if ($query->exists()) {
                throw new DomainException("Ya existe un empleado con ese $label.");
            }
        }
    }

    function setEstado(int $id): Empleado
    {
        $empleado = Empleado::findOrFail($id);
        if ($empleado->estado == "activo") {
            $empleado->estado = "inactivo";
        } else if ($empleado->estado == "inactivo") {
            $empleado->estado = "activo";
        } else {
            throw new Exception("El estado no es válido");
        }

        $empleado->save();
        return $empleado;
    }

    function delete(int $id): User
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $user;
    }
}
