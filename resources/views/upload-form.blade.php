<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subir Colección Postman') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <h1>Subir Colección de Postman</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('save.postman') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="postman_collection" class="form-label">Selecciona un archivo JSON de Postman:</label>
                <input type="file" class="form-control" name="postman_collection" accept=".json" required>
            </div>
            <button type="submit" class="btn btn-primary">Cargar Colección</button>
        </form>
    </div>
</x-app-layout>
