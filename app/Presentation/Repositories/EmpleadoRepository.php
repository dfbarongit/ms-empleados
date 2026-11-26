<?php

namespace App\Presentation\Repositories;

use App\Controllers\EmpleadosController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmpleadoRepository
{

    public function create(Request $request, Response $response, $args): Response
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new EmpleadosController();
            $row = $controller->create($data);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (\DomainException $ex) {
            return $this->conflictResponse($response, $ex);
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(406);
        }
    }

    public function get(Request $request, Response $response, $args): Response
    {
        $filters = $request->getQueryParams();
        $allowedFilters = ["documento", "area", "estado"];
        foreach ($filters as $key => $value) {
            if (!in_array($key, $allowedFilters, true) || !is_string($value) || trim($value) === "") {
                $response->getBody()->write(json_encode([
                    "error" => "Use los filtros documento, area o estado con valores de texto no vacíos."
                ]));
                return $response->withStatus(400)->withHeader("Content-Type", "application/json");
            }
        }

        $controller = new EmpleadosController();
        $rows = $controller->get_empleados($filters);
        $response->getBody()->write($rows->toJson());
        return $response->withHeader("Content-Type", "application/json");
    }

    public function update(Request $request, Response $response, $args): Response
    {
        try {
            $id = $args['id'];
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new EmpleadosController();
            $row = $controller->update($id, $data);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (\DomainException $ex) {
            return $this->conflictResponse($response, $ex);
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }

    private function conflictResponse(Response $response, \DomainException $ex): Response
    {
        $response->getBody()->write(json_encode(["error" => $ex->getMessage()]));
        return $response->withStatus(409)->withHeader("Content-Type", "application/json");
    }


    public function setEstado(Request $request, Response $response, $args): Response
    {
        try {
            $id = $args['id'];
            $body = $request->getBody()->getContents();
            $controller = new EmpleadosController();
            $row = $controller->setEstado($id);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }



    /*public function all(Request $request, Response $response, $args): Response
    {
        $controller = new UsersController();
        $rows = $controller->get_users();
        $response->getBody()->write($rows->toJson());
        return $response->withHeader("Content-Type", "application/json");
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        try {
            $id = $args['id'];
            $controller = new UsersController();
            $row = $controller->delete($id);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }*/
}
