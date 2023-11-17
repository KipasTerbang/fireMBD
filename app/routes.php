<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {

    $app->map(['GET', 'POST', 'PUT', 'DELETE'], '/datapasien[/{idPasien}]', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        switch ($request->getMethod()) {
            case 'GET':
                if (isset($args['idPasien'])) {
                    $query = $db->prepare('SELECT * FROM datapasien WHERE idPasien = ?');
                    $query->execute([$args['idPasien']]);
                    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
                    $responseData = !empty($results) ? $results[0] : ['message' => 'Data pasien tidak ditemukan'];
                    $response->getBody()->write(json_encode($responseData));
                } else {
                    $query = $db->query('SELECT * FROM datapasien');
                    $results = $query->fetchAll(PDO::FETCH_ASSOC);
                    $response->getBody()->write(json_encode($results));
                }
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $parsedBody = $request->getParsedBody();
    
                $idPasien = isset($args['idPasien']) ? $args['idPasien'] : $parsedBody['p_idPasien'];
                $namaPasien = $parsedBody['p_namaPasien'];
                $jenisKelamin = $parsedBody['p_jenisKelamin'];
                $umur = $parsedBody['p_umur'];
                $tanggalLahir = $parsedBody['p_tanggalLahir'];
                $alamatLengkap = $parsedBody['p_alamatLengkap'];
                $pekerjaan = $parsedBody['p_pekerjaan'];
                $action = $parsedBody['p_action'];
    
                try {
                    $query = $db->prepare('CALL ManageDataPasien(?, ?, ?,?,?,?,?,?)');
                    $query->execute([$idPasien, $namaPasien, $jenisKelamin, $umur, $tanggalLahir, $alamatLengkap,
                        $pekerjaan, $action]);
    
                    $message = '';
    
                    switch ($request->getMethod()) {
                        case 'POST':
                            $message = 'Data Pasien Berhasil Ditambahkan';
                            break;
                        case 'PUT':
                            $message = 'Data Pasien Berhasil Diperbarui';
                            break;
                        case 'DELETE':
                            $message = 'Data Pasien Berhasil Dihapus';
                            break;
                    }
    
                    $response->getBody()->write(json_encode(['message' => $message]));
    
                    return $response->withHeader('Content-Type', 'application/json');
                } catch (PDOException $e) {
                    $response->getBody()->write(json_encode([
                        'error' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ]));
                    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                }
                break;
        }
    
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE'], '/manage-pelayanan', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        switch ($request->getMethod()) {
            case 'GET':
                $query = $db->query('SELECT * FROM jenispelayanan');
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                $response->getBody()->write(json_encode($results));
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $parsedBody = $request->getParsedBody();
    
                $namaPelayanan = $parsedBody['p_namaPelayanan'];
                $action = $parsedBody['p_action'];
                $idUpdate = $parsedBody['p_idToUpdate'];
    
                try {
                    $query = $db->prepare('CALL ManagePelayanan(?, ?, ?)');
                    $query->execute([$namaPelayanan, $action, $idUpdate]);
    
                    $message = '';
    
                    switch ($request->getMethod()) {
                        case 'POST':
                            $message = $namaPelayanan . ' Pelayanan Berhasil Ditambahkan';
                            break;
                        case 'PUT':
                            $message = 'Pelayanan Berhasil Diperbarui';
                            break;
                        case 'DELETE':
                            $message = 'Pelayanan Berhasil Dihapus';
                            break;
                    }
    
                    $response->getBody()->write(json_encode(['message' => $message]));
    
                    return $response->withHeader('Content-Type', 'application/json');
                } catch (PDOException $e) {
                    $response->getBody()->write(json_encode([
                        'error' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ]));
                    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                }
                break;
        }
    
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE'], '/rekam-medis', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        switch ($request->getMethod()) {
            case 'GET':
                $query = $db->query('CALL getAllDataRekamMedis()');
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                $response->getBody()->write(json_encode($results));
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $parsedBody = $request->getParsedBody();
    
                $action = $parsedBody['p_action'];
                $idRM = $parsedBody['p_idRM'];
                $idPasien = $parsedBody['p_idPasien'];
                $tempatPengobatan = $parsedBody['p_tempatPengobatan'];
                $idPelayanan = $parsedBody['p_idPelayanan'];
                $idStatus = $parsedBody['p_idStatus'];
                $diagnosis = $parsedBody['p_diagnosis'];
                $tanggalMasuk = $parsedBody['p_tanggalMasuk'];
                $tanggalKeluar = $parsedBody['p_tanggalKeluar'];
                $idMetodePembayaran = $parsedBody['p_idMetodePembayaran'];
                $nominal = $parsedBody['p_nominal'];
    
                try {
                    $query = $db->prepare('CALL ManageRekamMedis(?, ?, ?,?,?,?,?,?,?,?,?)');
                    $query->execute([$action, $idRM, $idPasien, $tempatPengobatan, $idPelayanan, $idStatus, $diagnosis,
                        $tanggalMasuk, $tanggalKeluar, $idMetodePembayaran, $nominal]);
    
                    $message = '';
    
                    switch ($request->getMethod()) {
                        case 'POST':
                            $message = 'Data Rekam Medis Berhasil Ditambahkan';
                            break;
                        case 'PUT':
                            $message = 'Data Rekam Medis Berhasil Diperbarui';
                            break;
                        case 'DELETE':
                            $message = 'Data Rekam Medis Berhasil Dihapus';
                            break;
                    }
    
                    $response->getBody()->write(json_encode(['message' => $message]));
    
                    return $response->withHeader('Content-Type', 'application/json');
                } catch (PDOException $e) {
                    $response->getBody()->write(json_encode([
                        'error' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ]));
                    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                }
                break;
        }
    
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->map(['GET'], '/rekam-medis/{action}', function ($request, $response, $args) {
        $db = $this->get(PDO::class);
    
        switch ($args['action']) {
            case 'cost':
                $query = $db->query('SELECT namapasien, CalculateTotalCost(idPasien) AS total_biaya FROM dataPasien;');
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                $response->getBody()->write(json_encode($results));
                break;
            case 'record':
                $query = $db->query('SELECT namaPasien, CountRecordsForPatient(idPasien) AS RECORDS FROM datapasien;');
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                $response->getBody()->write(json_encode($results));
                break;
            default:
                $response->getBody()->write(json_encode(['error' => 'Invalid action']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE'], '/status', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        switch ($request->getMethod()) {
            case 'GET':
                $query = $db->query('SELECT * FROM status');
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                $response->getBody()->write(json_encode($results));
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $parsedBody = $request->getParsedBody();
    
                $status = $parsedBody['p_status'];
                $action = $parsedBody['p_action'];
                $id = $parsedBody['p_id'];
    
                try {
                    $query = $db->prepare('CALL ManageStatus(?, ?, ?)');
                    $query->execute([$status, $action, $id]);
    
                    $message = '';
    
                    switch ($request->getMethod()) {
                        case 'POST':
                            $message = 'Status Berhasil Ditambahkan';
                            break;
                        case 'PUT':
                            $message = 'Status Berhasil Diperbarui';
                            break;
                        case 'DELETE':
                            $message = 'Status Berhasil Dihapus';
                            break;
                    }
    
                    $response->getBody()->write(json_encode(['message' => $message]));
    
                    return $response->withHeader('Content-Type', 'application/json');
                } catch (PDOException $e) {
                    $response->getBody()->write(json_encode([
                        'error' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ]));
                    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                }
                break;
        }
    
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE'], '/metode-pembayaran', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);
    
        switch ($request->getMethod()) {
            case 'GET':
                $query = $db->query('SELECT * FROM metodepembayaran');
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                $response->getBody()->write(json_encode($results));
                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $parsedBody = $request->getParsedBody();
    
                $mPembayaran = $parsedBody['p_metodePembayaran'];
                $action = $parsedBody['p_action'];
                $id = $parsedBody['p_id'];
    
                try {
                    $query = $db->prepare('CALL ManageMetodePembayaran(?, ?, ?)');
                    $query->execute([$mPembayaran, $action, $id]);
    
                    $message = '';
    
                    switch ($request->getMethod()) {
                        case 'POST':
                            $message = 'Metode Pembayaran Berhasil Ditambahkan';
                            break;
                        case 'PUT':
                            $message = 'Metode Pembayaran Berhasil Diperbarui';
                            break;
                        case 'DELETE':
                            $message = 'Metode Pembayaran Berhasil Dihapus';
                            break;
                    }
    
                    $response->getBody()->write(json_encode(['message' => $message]));
    
                    return $response->withHeader('Content-Type', 'application/json');
                } catch (PDOException $e) {
                    $response->getBody()->write(json_encode([
                        'error' => 'Terjadi kesalahan: ' . $e->getMessage()
                    ]));
                    return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                }
                break;
        }
    
        return $response->withHeader('Content-Type', 'application/json');
    });
};
