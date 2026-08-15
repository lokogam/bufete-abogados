# Bufete de Abogados — Prueba técnica

Aplicación Laravel para la gestión de los casos de un bufete de abogados: clientes, expedientes, abogados asignados, API autenticada con Bearer Token y exportación a Excel con una hoja por abogado. El proyecto corre íntegramente sobre Docker.

## Tecnologías

- **Laravel 13** (última versión estable) + **PHP 8.4**
- **MySQL 8.4** (LTS)
- **Blade + Tailwind CSS** (frontend)
- **Laravel Sanctum** (autenticación API con tokens)
- **Maatwebsite/Laravel-Excel** (generación de Excel)
- **Docker Compose** (app + nginx + mysql)

## Requisitos

- Docker Engine 24+ y Docker Compose v2
- (Opcional) PHP 8.3+ y Composer 2 para desarrollo fuera de Docker

## Estructura de la base de datos

```
CLIENTE 1 ──── N CASOS N ──── N ABOGADOS
                     │
                     └── caso_abogado (tabla pivote con fecha_asignacion)
```

| Tabla          | Descripción                                  |
|----------------|----------------------------------------------|
| `clientes`     | Datos personales del cliente (cédula única)  |
| `abogados`     | Datos personales del abogado (cédula única)  |
| `casos`        | Expediente, período, estado, cliente         |
| `caso_abogado` | Relación N:M caso ↔ abogado                  |

### No se puede eliminar ningún registro

La protección se implementa en dos capas:

1. **Nivel base de datos** — `database/sql/bufete_abogados.sql` define triggers `BEFORE DELETE` que lanzan `SIGNAL SQLSTATE '45000'` y bloquean la eliminación física de cualquier fila.
2. **Nivel aplicación** — todos los modelos usan `SoftDeletes` (`deleted_at`); los registros solo se marcan como eliminados.

## Instalación con Docker

```bash
# 1. Copiar configuración y generar la clave de la aplicación
cp .env.example .env
# (la clave se genera automáticamente con el primer `php artisan key:generate`)

# 2. Construir y levantar los contenedores
docker compose up -d --build

# 3. Instalar dependencias y generar APP_KEY (si no se generó antes)
docker compose exec app composer install --no-interaction
docker compose exec app php artisan key:generate

# 4. Migraciones y datos de demostración
docker compose exec app php artisan migrate --seed

# 5. Compilar assets del frontend (Vite)
npm install
npm run build
```

La aplicación queda disponible en **http://localhost:8080**.

> Nota: `docker-compose.yml` expone MySQL en el puerto `33061` del host para evitar conflictos con instalaciones locales.

## Credenciales de demostración

| Campo    | Valor            |
|----------|------------------|
| Email    | `demo@bufete.com`|
| Password | `password`       |

## API

### Autenticación (Bearer Token)

```
POST /api/login
Content-Type: application/json

{ "email": "demo@bufete.com", "password": "password" }
```

Respuesta:

```json
{
  "message": "Autenticación exitosa.",
  "token": "1|i3t1HXF2ntGL6H2aXxcHG3tsPOiDuCEVybmbHzYf7316ee3c",
  "token_type": "Bearer",
  "user": { "id": 1, "name": "Usuario Demo", "email": "demo@bufete.com" }
}
```

### Obtener toda la información de un caso por su id

```
GET /api/casos/{id}
Authorization: Bearer <token>
Accept: application/json
```

Respuestas:

| Código | Caso                                   |
|--------|----------------------------------------|
| `200`  | Información completa del caso (cliente + abogados) |
| `401`  | Token ausente o inválido               |
| `404`  | El caso no existe                      |

```json
{
  "data": {
    "id": 1,
    "numero_expediente": "EXP-2024-0001",
    "estado": { "value": "en_tramite", "label": "En trámite" },
    "fecha_inicio": "2024-01-15",
    "fecha_finalizacion": null,
    "descripcion": "Demanda civil por incumplimiento de contrato",
    "cliente": { "id": 1, "cedula": "1012345678", "nombre": "Carlos", "apellido": "Gómez" },
    "abogados": [
      { "id": 1, "cedula": "2011112222", "nombre": "Juan", "apellido": "Pérez", "fecha_asignacion": "2024-01-15" }
    ]
  }
}
```

Ejemplo con `curl`:

```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"demo@bufete.com","password":"password"}'

curl http://localhost:8080/api/casos/1 \
  -H "Authorization: Bearer <token>" \
  -H "Accept: application/json"
```

## Comando de exportación a Excel

Genera un libro con **una hoja independiente por abogado**; cada hoja contiene los clientes y sus casos (expediente, cliente, cédula, estado, fechas y abogados del caso).

```bash
docker compose exec app php artisan casos:export
```

Resultado: `storage/app/private/casos_por_abogado.xlsx`

También se puede descargar desde la interfaz web (botón *Exportar Excel* en la barra de navegación).

## Script SQL y consultas

El archivo `database/sql/bufete_abogados.sql` contiene:

1. Esquema completo (tablas, índices, FKs, triggers anti-eliminación).
2. Datos de demostración (5 clientes, 4 abogados, 10 casos, relaciones N:M).
3. Las tres consultas solicitadas:

```sql
-- Casos asociados a un cliente según su cédula
SELECT c.numero_expediente, c.estado, c.fecha_inicio, c.fecha_finalizacion,
       cl.cedula, CONCAT(cl.nombre, ' ', cl.apellido) AS cliente
FROM casos c
INNER JOIN clientes cl ON cl.id = c.cliente_id
WHERE cl.cedula = '1012345678'
ORDER BY c.numero_expediente;

-- Todos los casos en orden ascendente
SELECT * FROM casos ORDER BY numero_expediente ASC;

-- Los 5 (cinco) primeros registros
SELECT * FROM casos ORDER BY id ASC LIMIT 5;
```

## Pruebas

```bash
docker compose exec app php artisan test
```

Cubre: autenticación (login, 401, token), recurso de caso (200/401/404), soft delete y generación del Excel con una hoja por abogado.

## Estilo de código

```bash
docker compose exec app ./vendor/bin/pint
```

## Estructura del proyecto

```
app/
├── Console/Commands/ExportarCasosCommand.php   # php artisan casos:export
├── Enums/CasoEstado.php                        # estados del caso
├── Exports/                                    # exportaciones Excel
│   ├── CasosPorAbogadoExport.php               # libro multi-hoja
│   └── Sheets/CasosDeAbogadoSheet.php          # hoja por abogado
├── Http/
│   ├── Controllers/Api/                        # AuthController, CasoController
│   ├── Controllers/                            # Dashboard, CasoWeb, Export
│   ├── Requests/LoginRequest.php               # validación de login
│   └── Resources/                              # CasoResource, ClienteResource, AbogadoResource
├── Models/                                     # Cliente, Abogado, Caso, CasoAbogado
└── Services/AuthService.php                    # lógica de emisión de tokens
database/
├── migrations/                                 # esquema (clientes, abogados, casos, caso_abogado)
├── seeders/                                    # datos de demostración
└── sql/bufete_abogados.sql                     # script SQL solicitado en la prueba
docker/nginx/default.conf                       # configuración de nginx
Dockerfile                                      # imagen php:8.4-fpm-alpine
docker-compose.yml                              # app + nginx + mysql
```

## Recursos web

| Ruta            | Descripción                          |
|-----------------|--------------------------------------|
| `/`             | Dashboard con indicadores            |
| `/casos`        | Listado de casos (orden ascendente)  |
| `/casos/{id}`   | Detalle del caso                     |
| `/exportar-excel` | Descarga el libro Excel            |