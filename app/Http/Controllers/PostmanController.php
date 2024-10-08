<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class PostmanController extends Controller
{
    public function showForm()
    {
        return view('upload_form');
    }

    public function savePostmanCollection(Request $request)
    {
        $request->validate([
            'postman_collection' => 'required|mimes:json'
        ]);

        try {
            // Leer el archivo JSON subido
            $json = file_get_contents($request->file('postman_collection')->getPathName());
            $collection = json_decode($json, true);

            // Insertar información de la colección
            $collectionId = DB::table('postman_collections')->insertGetId([
                'postman_id' => $collection['info']['_postman_id'],
                'name' => $collection['info']['name'],
                'schema_url' => $collection['info']['schema']
            ]);

            // Insertar las solicitudes en la base de datos
            foreach ($collection['item'] as $item) {
                DB::table('postman_requests')->insert([
                    'collection_id' => $collectionId,
                    'request_name' => $item['name'],
                    'method' => $item['request']['method'],
                    'url' => $item['request']['url']['raw'],
                    'headers' => json_encode($item['request']['header'] ?? []),
                    'auth' => json_encode($item['request']['auth'] ?? []),
                    'body' => json_encode($item['request']['body'] ?? []),
                    'events' => json_encode($item['event'] ?? [])
                ]);
            }

            return back()->with('success', 'Colección guardada correctamente.');
        } catch (PDOException $e) {
            return back()->with('error', 'Error al guardar la colección: ' . $e->getMessage());
        }
    }
}
