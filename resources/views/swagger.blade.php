<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Documentación Swagger de la API del bufete de abogados">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bufete de Abogados - Documentación de la API</title>
    <link rel="icon" type="image/png" href="/vendor/swagger-ui/favicon-32x32.png">
    <link rel="stylesheet" href="/vendor/swagger-ui/swagger-ui.css">
</head>
<body>
<div id="swagger-ui"></div>

<script src="/vendor/swagger-ui/swagger-ui-bundle.js" charset="UTF-8"></script>
<script src="/vendor/swagger-ui/swagger-ui-standalone-preset.js" charset="UTF-8"></script>
<script>
    window.onload = function () {
        window.ui = SwaggerUIBundle({
            url: '/swagger.json',
            dom_id: '#swagger-ui',
            deepLinking: true,
            displayRequestDuration: true,
            filter: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset,
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl,
            ],
            layout: 'StandaloneLayout',
        });
    };
</script>
</body>
</html>