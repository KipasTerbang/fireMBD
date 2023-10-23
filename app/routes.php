<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    //get
    $app->get('/datapasien', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM datapasien');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/datapasien/{idPasien}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('SELECT * FROM datapasien WHERE idPasien = ?');
        $query->execute([$args['idPasien']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
        if (!empty($results)) {
            $response->getBody()->write(json_encode($results[0]));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Data pasien tidak ditemukan']));
        }
    
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/manage-pelayanan', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM jenispelayanan');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader('Content-Type', 'application/json');
    });
    
    $app->post('/manage-pelayanan/insert', function($request, $response) {
        $parsedBody = $request->getParsedBody();
    
        $namaPelayanan = $parsedBody['p_namaPelayanan'];
        $action = $parsedBody['p_action'];
        $idUpdate = $parsedBody['p_idToUpdate'];
    
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManagePelayanan(?, ?, ?)');
            $query->execute([$namaPelayanan, $action, $idUpdate]);
    
            $response->getBody()->write(json_encode([
                'message' => $namaPelayanan .' Pelayanan Berhasil Ditambahkan Pada'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menambahkan Pelayanan: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    $app->put('/manage-pelayanan/update', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $namaPelayanan = $parsedBody['p_namaPelayanan'];
        $action = $parsedBody['p_action'];
        $idUpdate = $parsedBody['p_idToUpdate'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManagePelayanan(?, ?, ?)');
            $query->execute([$namaPelayanan, $action, $idUpdate]);
        
            $affectedRows = $query->rowCount(); 
            if ($affectedRows > 0) {
                $response->getBody()->write(json_encode([
                    'message' => 'Pelayanan Berhasil Diupdate Pada Id ' . $idUpdate
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Pelayanan tidak ditemukan atau tidak ada perubahan dilakukan.'
                ]));
            }
        
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam mengupdate Pelayanan: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
    
    $app->delete('/manage-pelayanan/delete', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $namaPelayanan = $parsedBody['p_namaPelayanan'];
        $action = $parsedBody['p_action'];
        $idUpdate = $parsedBody['p_idToUpdate'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManagePelayanan(?, ?, ?)');
            $query->execute([$namaPelayanan, $action, $idUpdate]);

            $response->getBody()->write(json_encode([
                'message' => 'Pelayanan Berhasil Dihapus '
        
            ]));
        
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menghapus pelayanan: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
};
