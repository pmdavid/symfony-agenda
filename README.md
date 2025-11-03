

# Sistema de reserva de horarios para áreas comunes.

## Despliegue en local

### 1. Clonar el repositorio

```bash
  git clone git@github.com:pmdavid/symfony-agenda.git
```

### 2. Levantar docker containers

```bash
  docker-compose up -d --build
```


### 3. Instalar dependencias
Desde el contenedor PHP:
```bash
  composer install
```

### 4. Ejecutar migration

Desde el contenedor PHP:
```bash
  php bin/console doctrine:migrations:migrate
```

## Ejecutar tests

Desde el contenedor PHP:
```bash
   vendor/bin/phpunit tests/Unit
```

---

## API y flujo de la aplicación

### - POST | `/api/reserve`
Reservar un slot para la hora, fecha y área común indicados. Estructura JSON de la request:

```json
{
  "commonArea": 1,
  "date": "2025-11-02",
  "hour": 10
}
```

La respuesta devuelve un JSON con el resultado de la petición:

```json
{
  "success": true
}
```

**Flujo de la solicitud:**
Request → ReservationController (Infra) → ReservationUseCase (Application) → ReservationService (Domain)

### - GET | `/api/availability`
Obtener los huecos de horas con su estado (`LIBRE` / `RESERVADO`) para una fecha determinada. Estructura JSON de la response:

```json
{
  "slots": [
    {"hour": 9, "status": "LIBRE"},
    {"hour": 10, "status": "RESERVADO"},
    ...
  ]
}
```



**Flujo de la solicitud:**
Request → AvailabilityController (Infra) → GetAvailabilityUseCase (Application) → ReservationService (Domain)



## Notas / Refactorings posibles

- Se ha implementado una arquitectura hexagonal para separar claramente las responsabilidades y facilitar el mantenimiento y escalabilidad del código:

1. **Capa de Dominio:** Contiene la lógica de negocio principal, incluyendo entidades y servicios. Podemos ver como en el Service estamos usando una Interface del repositorio, lo que permite desacoplar la lógica de negocio del acceso a datos. La implementacion cooncreta se encuentra en Infraestructura e implementa dicha interface.
2. **Capa de Aplicación:** Contiene los casos de uso que orquestan la lógica de negocio.
3. **Capa de Infraestructura:** Maneja la interacción con el mundo exterior, en este caso: controladores y acceso a database.




- **Tests para casos de uso:**  
  No se han añadido por tiempo y porque los UseCases actuales son muy simples y delegan la lógica al dominio. En contextos más complejos, sería útil testear que los UseCases llaman correctamente a los servicios de dominio. Se mockearia el servicio de dominio y se verificaría que se llama con los parámetros correctos.


- **Estado de reserva (`RESERVED` / `FREE`):**  
  Para la logica actual no era necesario. pero al final lo he "sobrecomplicado" añadiendo el status libre/reservado en como campo/propiedad de Reservation, pensando que a futuro podria ser útil tener guardadas las reservas que se han liberado (cancelado), a modo de historico. De esa foma, por ejemplo, el admin podría saber que vecinos cancelan muchas reservas. Lo mas simple para la escala del problema habria sido simplemente guardar reservas e interpretar guardadas como slot reservado y el resto como libres.


- **Common Areas:**
  Las Common areas (Pista de padel, Piscina...) se han hardcodeado en front para ahorrar tiempo, pero normalmente lo ideal sería obtenerlas desde API con una llamada especifica para ello (o donde encaje segun el contexto).


- **Uso de Entities y Repositories:**  
  En sistemas simples se podría usar directamente la Entity con el ORM. En este proyecto, para mantener arquitectura hexagonal, se ha creado un `ReservationEntity` en Infraestructura que maneja el acceso a datos real.


- **DTOs para Requests y Responses:**  
  En proyectos más complejos es buena práctica usar DTOs para transformar o validar la estructura de datos entre cliente y aplicación.  
  Ejemplo DTO: CreateReservationRequest, y de paso,dentro del propio DTO podemos validar los campos de la request mediante asserts. Este DTO iria en la capa de application aunque segun matices podría ir en infraestructura.


- **División de servicios:**  
  Se podría extraer `getAvailability()` a un `AvailabilityService`, separando claramente responsabilidades dentro del dominio.


- **Métodos del Repository:**  
  Actualmente se usa un único método para consultar reservas. Se podrían crear métodos separados (`findOne` y `findAll`) para mayor claridad y rendimiento. Esto es algo basico pero lo deje para el final y se me iba de tiempo.


- **ENUM para status:**  
  Se ha creado un ENUM `ReservationStatus` para el estado de las reservas. En lógicas más complejas se podría considerar un Value Object, pero un ENUM es más simple y suficiente.


- **Logging:**  
  Para auditar solicitudes o depurar, se podría inyectar por ejemplom en el UseCase un logger que registre los intentos de reserva con info de la request.


- **Manejo de errores:**

  Actualmente, si una reserva no puede ser realizada, se devuelve un JSON con success: false. En un sistema más robusto, se podrían lanzar excepciones específicas y manejarlas con try-catch en el controlador para devolver códigos HTTP adecuados.

- **Comentarios de funciones**
  Se podrían detallar mucho mejor algunas funciones dando contexto de lo que hacen si fuera necesario, y añadiendo sus types de params de entrada y del return. 

---

## Posibles funcionalidades a añadir

- Autenticación y roles de usuario.
- Gestión de `CommonAreas` desde un panel de administrador, incluyendo la posibilidad de anular reservas de cualquier usuario.
- Cancelación de reservas por el propio usuario.
- Establecer un limite por defecto de reservas por usuario. Penalizar usuarios que cancelan reservas excesivamente, usando el historial de reservas que estan guardadas en database status "reserved"
- Notificaciones por email al usuario al realizar o cancelar una reserva.



