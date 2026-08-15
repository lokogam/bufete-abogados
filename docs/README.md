# Documentación del proyecto

Documentación oficial y recursos de consumo de la **API del bufete de abogados**.

## Estructura

```
docs/
├── README.md                            # este índice
└── postman/
    └── bufete_abogados.postman_collection.json   # Colección Postman v2.1
```

## Documentación Swagger

La API se documenta con **Swagger** a través de la interfaz interactiva que sirve la
propia aplicación en **`http://localhost:8080/docs`** (Swagger UI con *Try It Out*).

Define los 15 endpoints del sistema agrupados en:

- **Autenticación** — `login`, `register`, `logout`
- **Clientes** — CRUD (`clientes`)
- **Abogados** — CRUD (`abogados`)
- **Casos** — CRUD (`casos`), incluye asignación de abogados

Para regenerar la documentación tras cambiar la API:

```bash
docker compose exec app php artisan scribe:generate
```

## Colección de Postman

El archivo `docs/postman/bufete_abogados.postman_collection.json` es una colección
**Postman v2.1** con todas las peticiones de la API ya configuradas (autenticación,
CRUD de clientes, abogados y casos).

### Cómo importarla

1. Abre **Postman** → *Import* → *Upload Files* → selecciona
   `docs/postman/bufete_abogados.postman_collection.json`.
2. La variable de entorno `baseUrl` apunta a `http://localhost:8080`.
3. Peticiones protegidas: ejecuta primero `POST /api/login` (o `/api/register`)
   para obtener el token Bearer.

## Autenticación

La API usa **tokens Bearer** (Laravel Sanctum). Los endpoints protegidos requieren el
encabezado:

```
Authorization: Bearer {token}
```

Credenciales de demostración: `demo@bufete.com` / `password`.