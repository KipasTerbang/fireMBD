<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    //Data-pasien
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

    $app->post('/datapasien', function($request, $response) {
        $parsedBody = $request->getParsedBody();
    
        $idPasien = $parsedBody['p_idPasien'];
        $namaPasien = $parsedBody['p_namaPasien'];
        $jenisKelamin = $parsedBody['p_jenisKelamin'];
        $umur = $parsedBody['p_umur'];
        $tanggalLahir = $parsedBody['p_tanggalLahir'];
        $alamatLengkap = $parsedBody['p_alamatLengkap'];
        $pekerjaan = $parsedBody['p_pekerjaan'];
        $action = $parsedBody['p_action'];
    
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageDataPasien(?, ?, ?,?,?,?,?,?)');
            $query->execute([$idPasien, $namaPasien, $jenisKelamin, $umur, $tanggalLahir, $alamatLengkap,
                            $pekerjaan, $action]);
    
            $response->getBody()->write(json_encode([
                'message' => 'Data Pasien Berhasil Ditambahkan'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menambahkan Data Pasien: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    $app->put('/datapasien', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $idPasien = $parsedBody['p_idPasien'];
        $namaPasien = $parsedBody['p_namaPasien'];
        $jenisKelamin = $parsedBody['p_jenisKelamin'];
        $umur = $parsedBody['p_umur'];
        $tanggalLahir = $parsedBody['p_tanggalLahir'];
        $alamatLengkap = $parsedBody['p_alamatLengkap'];
        $pekerjaan = $parsedBody['p_pekerjaan'];
        $action = $parsedBody['p_action'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageDataPasien(?, ?, ?,?,?,?,?,?)');
            $query->execute([$idPasien, $namaPasien, $jenisKelamin, $umur, $tanggalLahir, $alamatLengkap,
                            $pekerjaan, $action]);
    
            $response->getBody()->write(json_encode([
                'message' => 'Data Pasien Berhasil Diperbarui'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam memperbarui Data Pasien: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
    
    $app->delete('/datapasien', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $idPasien = $parsedBody['p_idPasien'];
        $namaPasien = $parsedBody['p_namaPasien'];
        $jenisKelamin = $parsedBody['p_jenisKelamin'];
        $umur = $parsedBody['p_umur'];
        $tanggalLahir = $parsedBody['p_tanggalLahir'];
        $alamatLengkap = $parsedBody['p_alamatLengkap'];
        $pekerjaan = $parsedBody['p_pekerjaan'];
        $action = $parsedBody['p_action'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageDataPasien(?, ?, ?,?,?,?,?,?)');
            $query->execute([$idPasien, $namaPasien, $jenisKelamin, $umur, $tanggalLahir, $alamatLengkap,
                            $pekerjaan, $action]);

            $response->getBody()->write(json_encode([
                'message' => 'Data Pasien Berhasil Dihapus '
        
            ]));
        
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menghapus Data Pasien: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    //Manage-Pelayanan
    $app->get('/manage-pelayanan', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM jenispelayanan');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader('Content-Type', 'application/json');
    });
    
    $app->post('/manage-pelayanan', function($request, $response) {
        $parsedBody = $request->getParsedBody();
    
        $namaPelayanan = $parsedBody['p_namaPelayanan'];
        $action = $parsedBody['p_action'];
        $idUpdate = $parsedBody['p_idToUpdate'];
    
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManagePelayanan(?, ?, ?)');
            $query->execute([$namaPelayanan, $action, $idUpdate]);
    
            $response->getBody()->write(json_encode([
                'message' => $namaPelayanan .' Pelayanan Berhasil Ditambahkan'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menambahkan Pelayanan: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    $app->put('/manage-pelayanan', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $namaPelayanan = $parsedBody['p_namaPelayanan'];
        $action = $parsedBody['p_action'];
        $idUpdate = $parsedBody['p_idToUpdate'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManagePelayanan(?, ?, ?)');
            $query->execute([$namaPelayanan, $action, $idUpdate]);
    
            $response->getBody()->write(json_encode([
                'message' => 'Pelayanan Berhasil Diperbarui'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam memperbarui Pelayanan: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
    
    $app->delete('/manage-pelayanan', function($request, $response) {
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

    //Rekam-Medis
    $app->get('/rekam-medis', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL getAllDataRekamMedis()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader('Content-Type', 'application/json');
    });
    
    $app->post('/rekam-medis', function($request, $response) {
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
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageRekamMedis(?, ?, ?,?,?,?,?,?,?,?,?)');
            $query->execute([$action,$idRM,$idPasien,$tempatPengobatan,$idPelayanan,$idStatus,$diagnosis,
                            $tanggalMasuk,$tanggalKeluar,$idMetodePembayaran,$nominal]);
    
            $response->getBody()->write(json_encode([
                'message' => ' Data Rekam Medis Berhasil Ditambahkan'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menambahkan Data Rekam Medis: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    $app->put('/rekam-medis', function($request, $response) {
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
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageRekamMedis(?, ?, ?,?,?,?,?,?,?,?,?)');
            $query->execute([$action,$idRM,$idPasien,$tempatPengobatan,$idPelayanan,$idStatus,$diagnosis,
                            $tanggalMasuk,$tanggalKeluar,$idMetodePembayaran,$nominal]);
    
            $response->getBody()->write(json_encode([
                'message' => 'Data Rekam Medis Berhasil Diperbarui'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam memperbarui Data Rekam Medis: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
    
    $app->delete('/rekam-medis', function($request, $response) {
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
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageRekamMedis(?, ?, ?,?,?,?,?,?,?,?,?)');
            $query->execute([$action,$idRM,$idPasien,$tempatPengobatan,$idPelayanan,$idStatus,$diagnosis,
                            $tanggalMasuk,$tanggalKeluar,$idMetodePembayaran,$nominal]);

            $response->getBody()->write(json_encode([
                'message' => 'Data Rekam Medis Berhasil Dihapus '
        
            ]));
        
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menghapus Data Rekam Medis: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    $app->get('/rekam-medis-cost', function($request, $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT namapasien, CalculateTotalCost(idPasien) AS total_biaya
                            FROM dataPasien;');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/rekam-medis-record', function($request, $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT namaPasien, CountRecordsForPatient(idPasien) AS RECORDS
                            FROM datapasien;');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader('Content-Type', 'application/json');
    });

    //status
    $app->get('/status', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM status');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader('Content-Type', 'application/json');
    });
    
    $app->post('/status', function($request, $response) {
        $parsedBody = $request->getParsedBody();
    
        $status = $parsedBody['p_status'];
        $action = $parsedBody['p_action'];
        $id = $parsedBody['p_id'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageStatus(?, ?, ?)');
            $query->execute([$status, $action, $id]);
    
            $response->getBody()->write(json_encode([
                'message' => ' Status Berhasil Ditambahkan'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menambahkan Status: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    $app->put('/status', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $status = $parsedBody['p_status'];
        $action = $parsedBody['p_action'];
        $id = $parsedBody['p_id'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageStatus(?, ?, ?)');
            $query->execute([$status, $action, $id]);
    
            $response->getBody()->write(json_encode([
                'message' => 'Status Berhasil Diperbarui'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam memperbarui Status: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
    
    $app->delete('/status', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $status = $parsedBody['p_status'];
        $action = $parsedBody['p_action'];
        $id = $parsedBody['p_id'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageStatus(?, ?, ?)');
            $query->execute([$status, $action, $id]);

            $response->getBody()->write(json_encode([
                'message' => 'Status Berhasil Dihapus '
        
            ]));
        
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menghapus Status: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    //metodePembayaran
    $app->get('/metode-pembayaran', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM metodepembayaran');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader('Content-Type', 'application/json');
    });
    
    $app->post('/metode-pembayaran', function($request, $response) {
        $parsedBody = $request->getParsedBody();
    
        $mPembayaran = $parsedBody['p_metodePembayaran'];
        $action = $parsedBody['p_action'];
        $id = $parsedBody['p_id'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageMetodePembayaran(?, ?, ?)');
            $query->execute([$mPembayaran, $action, $id]);
    
            $response->getBody()->write(json_encode([
                'message' => ' Metode Pembayaran Berhasil Ditambahkan'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menambahkan Metode Pembayaran: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    $app->put('/metode-pembayaran', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $mPembayaran = $parsedBody['p_metodePembayaran'];
        $action = $parsedBody['p_action'];
        $id = $parsedBody['p_id'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageMetodePembayaran(?, ?, ?)');
            $query->execute([$mPembayaran, $action, $id]);
    
            $response->getBody()->write(json_encode([
                'message' => 'Metode Pembayaran Berhasil Diperbarui'
            ]));
    
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam memperbarui Metode Pembayaran: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
    
    $app->delete('/metode-pembayaran', function($request, $response) {
        $parsedBody = $request->getParsedBody();
        
        $mPembayaran = $parsedBody['p_metodePembayaran'];
        $action = $parsedBody['p_action'];
        $id = $parsedBody['p_id'];
        
        $db = $this->get(PDO::class);
        
        try {
            $query = $db->prepare('CALL ManageMetodePembayaran(?, ?, ?)');
            $query->execute([$mPembayaran, $action, $id]);

            $response->getBody()->write(json_encode([
                'message' => 'Metode Pembayaran Berhasil Dihapus '
        
            ]));
        
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Terjadi kesalahan dalam menghapus Metode Pembayaran: ' . $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
};
