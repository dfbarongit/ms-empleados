<?php

namespace App\Controllers;

use App\Models\Empleado;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class EmpleadosController
{
    function create(array $data): Empleado
    {
        $empleado = new Empleado();
        $empleado->nombre = $data["nombre"];
        $empleado->username = $data["username"];
        $empleado->password = $data["password"];
        $empleado->save();
        return $empleado;
    }
    function get_empleados():Collection{
        return Empleado::all();
    }
    function update(int $id, array $data):Empleado{
        $empleado = Empleado::findOrFail($id);
        $empleado->nombre = $data["nombre"];
        $empleado->username = $data["username"];
        $empleado->password = $data["password"];
        $empleado->save();
        return $empleado;
    }
    function setEstado(int $id):Empleado{
        $empleado = Empleado::findOrFail($id);
        if ($empleado->estado == "activo") {
            $empleado->estado = "inactivo";
        } else if ( $empleado->estado == "inactivo") {
            $empleado->estado = "activo";
        } else {
            throw new Exception("El estado no es válido");
        }
        
        $empleado->save();
        return $empleado;
    }

    function find_empleado(string $documento, string $area, string $estado): Empleado
    {
        return Empleado::where("documento", $documento)->orWhere("area", $area)->orWhere("estado", $estado)->firstOrFail();
    }

    function delete(int $id):User{
        $user = User::findOrFail($id);
        $user->delete();
        return $user;
    }
}
