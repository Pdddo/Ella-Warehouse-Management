@if (session('success'))
    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        {{ session('error') }}
    </div>
@endif