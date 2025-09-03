<x-app-layout>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="container mt-4">
             <product-form :product-id="{{ isset($product) ? $product->id : 'null' }}"></product-form>
        </div>
    </div>
</body>
</x-app-layout>
