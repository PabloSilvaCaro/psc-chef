---
name: chef-en-casa-workflow
description: Desarrolla y mantiene el proyecto WordPress Chef en Casa respetando su arquitectura tema/plugin, roadmap incremental y proceso de validación. Usar para cambios de código, datos, diseño o despliegue dentro de este proyecto.
---

# Chef en Casa Workflow

Lee `AGENTS.md` y la capa activa de `docs/ROADMAP.md` antes de modificar el proyecto.

## Flujo

1. Confirma cuál es la única capa activa y no incorpores funcionalidades de capas posteriores.
2. Revisa `docs/ARCHITECTURE.md` para decidir si el cambio pertenece al tema, al plugin o a infraestructura.
3. Conserva la lógica de presentación en el tema y la lógica de dominio/persistencia en el plugin.
4. Implementa archivos completos, prueba el comportamiento observable y documenta cómo reproducir la prueba.
5. Resume archivos modificados, validaciones y cualquier decisión pendiente; espera confirmación antes de avanzar de capa.

## Restricciones

- No modificar WordPress core ni versionar secretos, cargas de usuarios o respaldos.
- No ejecutar despliegues ni operaciones sobre el hosting sin autorización explícita y datos concretos del destino.
- Antes de una operación destructiva sobre volúmenes o base de datos, identificar el destino y solicitar confirmación.
- Para cambios visuales, comprobar desktop y móvil; para cambios de datos, verificar instalación, actualización y desinstalación segura.

