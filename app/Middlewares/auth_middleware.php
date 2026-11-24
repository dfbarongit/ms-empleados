<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Models\User; // Ajusta a tu modelo de usuario/sesión

return function (Request $request, RequestHandler $handler) {
    // 1. Extraer el token de la cabecera Authorization
    $authHeader = $request->getHeaderLine('Authorization');
    $token = str_replace('Bearer ', '', $authHeader);

    // 2. Validar existencia del token
    if (empty($token)) {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => 'Token no proporcionado']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    // 3. Validar en BD que el token exista y la sesión esté activa
    $usuario = User::where('token', $token)
                      ->where('sesion_activa', 1)
                      ->first();

    if (!$usuario) {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => 'Sesión inválida o expirada']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    // 4. Pasar los datos del usuario autenticado al controlador y continuar
    $request = $request->withAttribute('usuario', $usuario);
    return $handler->handle($request);
};
