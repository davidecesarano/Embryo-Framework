@include('partials/header', [
    'title' => $title,
    'css'   => 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css'
])

    <div class="d-flex flex-column justify-content-center h-100 text-center">
        <h1 class="display-1">{{ $title }}</h1>
        <div class="h5 text-muted mt-4">A PSR and MVC framework for PHP</div>
    </div>

@include('partials/footer', [
    'js' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'
])