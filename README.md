# Chef en Casa — WordPress

Nueva versión del MVP Chef en Casa, preparada para ejecutarse localmente con WordPress, PHP 8.2 y MySQL 8 antes de publicarse en un hosting tradicional.

## Estado

La Capa 1 incluye únicamente:

- estructura versionable del proyecto;
- entorno local con Docker Compose;
- esqueletos del tema y plugin personalizados;
- documentación de arquitectura, GitHub, entorno local y despliegue;
- instrucciones persistentes para Codex y skill del proyecto.

La réplica visual, el logo, el modelo funcional y el despliegue real se incorporarán en capas posteriores, después de validar cada etapa.

## Inicio rápido

1. Copiar `.env.example` como `.env`.
2. Cambiar las contraseñas locales del archivo `.env`.
3. Ejecutar `docker compose up -d`.
4. Abrir `http://localhost:8080` y completar la instalación de WordPress.
5. Activar el tema **Chef en Casa** y el plugin **Chef en Casa Core**.

Consulta [docs/LOCAL_SETUP.md](docs/LOCAL_SETUP.md) para el procedimiento completo.

## Documentación

- [Arquitectura](docs/ARCHITECTURE.md)
- [Instalación local](docs/LOCAL_SETUP.md)
- [Git y GitHub](docs/GITHUB.md)
- [Despliegue al hosting](docs/HOSTING.md)
- [Plan incremental](docs/ROADMAP.md)
