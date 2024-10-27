<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostmanController extends Controller
{
    // Muestra el formulario para cargar el archivo JSON
    public function showForm()
    {
        return view('upload-form');
    }

    // Procesa la colección de Postman
    public function savePostmanCollection(Request $request)
    {
        // Validar que el archivo existe y es un JSON
        $validator = Validator::make($request->all(), [
            'postman_collection' => 'required|file|mimes:json|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Obtener el archivo
        $file = $request->file('postman_collection');

        // Leer el contenido del archivo JSON
        $jsonContent = file_get_contents($file->getRealPath());
        $collection = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['postman_collection' => 'El archivo JSON no es válido.']);
        }

        // Insertar información de la colección
        try {
            DB::beginTransaction();

            // Insertar en la tabla postman_collections
            $collectionId = DB::table('postman_collections')->insertGetId([
                'postman_id' => $collection['info']['_postman_id'],
                'name' => $collection['info']['name'],
                'schema_url' => $collection['info']['schema'],
            ]);

            // Insertar solicitudes y sus detalles en la base de datos
            foreach ($collection['item'] as $item) {
                $url = $item['request']['url']['raw'];
                $method = $item['request']['method'];
                $name = $item['name'];
                $headers = json_encode($item['request']['header'] ?? []);
                $auth = json_encode($item['request']['auth'] ?? []);
                $body = json_encode($item['request']['body'] ?? []);
                $events = json_encode($item['event'] ?? []);

                DB::table('postman_requests')->insert([
                    'collection_id' => $collectionId,
                    'request_name' => $name,
                    'method' => $method,
                    'url' => $url,
                    'headers' => $headers,
                    'auth' => $auth,
                    'body' => $body,
                    'events' => $events,
                ]);
            }

            DB::commit();

            return redirect()->route('upload.form')->with('success', 'Colección de Postman cargada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['postman_collection' => 'Error al guardar la colección: ' . $e->getMessage()]);
        }
    }

    // Elimina la solicitud de Postman junto con sus logs
    public function eliminar($id)
    {
        try {
            DB::beginTransaction();

            // Eliminar registros relacionados en request_logs
            DB::table('request_logs')->where('request_id', $id)->delete();

            // Eliminar la solicitud de postman_requests
            DB::table('postman_requests')->where('id', $id)->delete();

            DB::commit();

            return redirect()->route('upload.form')->with('success', 'Solicitud eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['delete' => 'Error al eliminar la solicitud: ' . $e->getMessage()]);
        }
    }
}
