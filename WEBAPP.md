# Web application

Se convoca a toda la conunidad a participar en la construcción de una aplicación web para exponer el estado de los proyectos y sus contrucciones con estado.

## Primera etapa

En la primera fase se trata de explotar la información que actualmente se está generando. La estructura de la información interna está en archivos, de la siguiente forma:

```text
storage/<resource-name>/state.json               Archivo de estado de la última ejecución.
storage/<resource-name>/logs/<build-date>.log    Archivo de registro de la ejecución.
storage/<resource-name>/logs/<build-date>.state  Archivo de estado de la ejecución.
```

El archivo de log es texto (tipo TTY)

El archivo de estado es un json como este:

```json
{
    "project":"sat-catalogs",
    "date":1594525169,
    "state": "success",
    "change": "same"
}
```

Donde:

- `project` es el nombre del proyecto,
- `date` es un `timestamp`,
- `state` solo puede ser `build`, `success` o `fail`,
- `change` siempre está vacío a menos que `state` sea `success`, en ese caso solo puede ser `updated` en caso de que el repositorio se haya actualizado o `same` en caso de que no haya necesitado ninguna actualización.

Lo que se pretende es tener una aplicación que muestre:

- `GET /`: Listado de recursos, por cuántos son no es necesario paginar, pero sí mostrar en el listado el recurso y el estado de su última ejecución.
- `GET /<resource-name>/`: Recurso con el estado de su última ejecución, además de un listado paginado de sus builds, mostrando para cada build el estado de su ejecución.
- `GET /<resource-name>/<timestamp>`: Estado de la ejecución y archivo de log.
- `GET /<resource-name>/<timestamp>/state`: Archivo JSON de la ejecución.
- `GET /<resource-name>/<timestamp>/log`: Archivo LOG de la ejecución.
- `GET /<resource-name>/<timestamp>/shield-img`: Redirección a la ruta de <https://shields.io/>.
- `GET /<resource-name>/<timestamp>/shield-url`: Ruta de <https://shields.io/> que indique el estado del build, por ejemplo: `[sat-xml|building]`, `[sat-xml|failed]`, `[sat-xml|updated]` o `[sat-xml|passing]`.

Para rutas se puede sustituir `<timestamp>` por `"current"`, en ese caso deberá entregar la información de la última ejecución.

Las rutas que no son definitivas responden con HTML, JSON, o con error, dependiendo de la cabecera `Accept` de la petición.

Las rutas son relativas, es probable que esta aplicación esté montada dentro de una ruta más amplia, por ejemplo <https://www.phpcfdi.com/recursos/builds/>.

Entre más simple y modificable mejor.

- Lenguaje de servidor: PHP 7.3 o JS con Node.
- Salida para el cliente: HTML5 + CSS3, no veo nada que obligue a usar JS del lado del cliente, a menos que lo consideren necesario.

## Siguientes etapa

Documentar la API.

Para las siguientes etapa es probable que existan cambios internos, por ejemplo:

Actualmente se intenta la construcción de un recurso durante una serie determinada de intentos, esto podría cambiar para que cada intento genere su propio archivo de log y un estado general.

Otro cambio podría ser que, en lugar de esperar que la información simplemente exista, la información sea *posteada* desde el exterior, lo que llevaría a nuevas rutas en la aplicación, de esta forma la aplicación ya no solo estaría encargada de mostrar la información y ahora estaría en pleno control de la información y cómo almacenarla. Se debe garantizar por medio de un acceso seguro (token o autenticación) que existen los privilegios para postear la construcción.

### Usuarios, roles y suscripciones

Si la aplicación fuera capaz de recibir la información de las construcciones entonces también puede ser capaz de reportar los cambios. Son dos cambios fundamentales: cambio de estado (de `fail` a `success` y viceversa) y cambio de actualización (`updated`). Esto podría generar diferentes tipos de alertas:

- Alerta administrativa de cambio de estado: A los usuarios *administradores* se les notifica por correo del cambio.

- Alerta de cambio: A los *suscriptores* se les notifica por correo que hay una nueva versión de la información.

- Ejecución de *webhooks*: A los *suscriptores premium* se les ejecuta el *webhooks* del proyecto, de esta forma podrían automatizar el cambio en sus propios almacenes.

Las ideas anteriores implican muchas nuevas modificaciones:

- Sistema de *usuarios* con *roles* (administrador y usuario).
- Partes de la aplicación para usuarios y partes públicas.
- Relación de *Suscripción* entre *Proyectos* y *Usuarios*.
- Los usuarios pueden tener *Membresías* (gratuita, pagada o colaborador).
  - Gratuita: Notificaciones de actualizaciones vía correo.
  - Pagada: Gratuita y además ejecuciones de *webhooks*.
  - Colaborador: Lo mismo que pagada pero otorgada sin pago.
- Integración con un sistema de pago para pagar la membresía.
