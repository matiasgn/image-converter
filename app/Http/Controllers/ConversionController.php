<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Imagick;
use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;

class ConversionController extends Controller
{
    public function convert(Request $request)
    {


        $client = new GoogleClient();
        $client->setApplicationName('	pruebaxinelink');
        $client->setDeveloperKey('AIzaSyCqJuJyUPQe40wfcrQqTixuKSDVTln7hNY');
        // ID del archivhttps://drive.google.com/file/d/1EnZUi9rbiGK0_0A0dJ2aG4HKvImim_NX/view?usp=drive_linko que deseas probar

        $fileId = '1EnZUi9rbiGK0_0A0dJ2aG4HKvImim_NX';

        // Intenta obtener la información del archivo
        try {
            $drive = new \Google\Service\Drive($client);
            $file = $drive->files->get($fileId, ['alt' => 'media']);
            return response($file->getBody()->getContents(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="archivo.pdf"'
            ]);
        } catch (\Exception $e) {
            return $e->getMessage(); // Maneja los errores de conexión
        }
        dd();
//        $client = new GoogleClient();
//        $client->setDeveloperKey('AIzaSyCqJuJyUPQe40wfcrQqTixuKSDVTln7hNY');
//        $drive = new \Google\Service\Drive($client);
//        $files = $drive->files->listFiles();
//        dd($files);

        $client = new GoogleClient();
        $client->setAuthConfig([
            'web' => [
                'client_id' => '191480218455-0u1imedt79n3t5mr4jck5ns047qln69c.apps.googleusercontent.com',
                'project_id' => 'pruebaxinelink',
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri' => 'https://oauth2.googleapis.com/token',
                'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                'client_secret' => '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'
            ]
        ]);
        $client->setApiKey('AIzaSyCqJuJyUPQe40wfcrQqTixuKSDVTln7hNY');


//        $client->setAuthConfig(storage_path('app/client_secret_701038100400-739fnk7t0q8eudnt9odr3b5hhpl6jtnr.apps.googleusercontent.com.json'));
        $client->setScopes([\Google\Service\Drive::DRIVE_READONLY]);



        // Intenta realizar una solicitud a la API de Google Drive
        try {
            $drive = new \Google\Service\Drive($client);
            $files = $drive->files->listFiles();
            dd($files);
        } catch (\Exception $e) {
            return $e->getMessage(); // Maneja los errores de conexión
        }

        die();


        phpinfo();
        die();
        $filePath = Storage::path('test_files/99126026.TIF');
        // Obtener archivos TIFF del formulario
        $tiffFiles = $request->file('tiffFiles');

        // Convertir archivos TIFF a PDF
        $pdf = new Imagick();

        $pdf->readImage($filePath);

        $pdf->setImageFormat('pdf');

        // Devolver el PDF como respuesta
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->getImageBlob();
        }, 'output.pdf', ['Content-Type' => 'application/pdf']);
    }
}
