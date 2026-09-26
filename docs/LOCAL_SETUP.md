# Entorno local

## Requisitos verificados

- Git instalado.
- Docker Desktop con Docker Compose instalado.
- Puertos 8080 y 8081 disponibles.

## Preparación

En PowerShell, desde la raíz del proyecto:

```powershell
Copy-Item .env.example .env
docker compose config
docker compose up -d
docker compose ps
```

Abrir:

- WordPress: `http://localhost:8080`
- Adminer: `http://localhost:8081`

Para Adminer, usar servidor `database` y las credenciales definidas en `.env`.

## Primera instalación

Completar el asistente de WordPress, ingresar al panel, activar el tema **Chef en Casa** y después el plugin **Chef en Casa Core**.

## Detener el entorno

```powershell
docker compose stop
```

`docker compose down` elimina contenedores y red, pero conserva los volúmenes. No usar `docker compose down -v` salvo que se quiera borrar deliberadamente la base local.

