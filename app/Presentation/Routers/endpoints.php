<?php

// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
use App\Presentation\Repositories\EmpleadoRepository;
use Slim\App;

return function (App $app) {
    // $app->get('/usuarios', function (Request $request, Response $response, $args) {
    //     $repository = new EmpleadoRepository();
    //     return $repository->all($request, $response, $args);
    // });
    
    $app->post('/empleados', [EmpleadoRepository::class, 'create']);
    $app->get('/empleados', [EmpleadoRepository::class, 'get']);
    $app->put('/empleados/{id}', [EmpleadoRepository::class, 'update']);
    $app->put('/empleados/{id}/estado', [EmpleadoRepository::class, 'setEstado']);
};
