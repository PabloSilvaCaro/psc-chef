# Instrucciones del proyecto

## Alcance

Este repositorio contiene la versión WordPress/MySQL de Chef en Casa. El tema controla presentación y accesibilidad; el plugin contiene entidades, reglas de negocio, persistencia y futuras APIs.

## Reglas de trabajo

- Implementar una sola capa del `docs/ROADMAP.md` por vez y esperar validación antes de avanzar.
- No colocar lógica de negocio en el tema.
- No modificar WordPress core ni plugins de terceros.
- Mantener datos estructurados; no guardar relaciones de ingredientes, platos o menús como texto serializado cuando requieran consultas.
- Escapar salidas, sanitizar entradas y verificar nonces/capacidades en operaciones administrativas.
- Mantener secretos fuera de Git y usar `.env` solo para desarrollo local.
- Verificar cada cambio en desktop y móvil, y documentar cómo probarlo.

