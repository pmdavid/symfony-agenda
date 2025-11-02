

# Sistema de Reservas de Common Areas

## Despliegue en local

En progreso…

---

## Endpoints y flujo de lógica

### - POST | `/api/reserve`
Reservar un slot.

**Flujo de la solicitud:**
Request → ReservationController (Infra) → ReservationUseCase (Application) → ReservationService (Domain)

### - GET | `/api/availability`
Obtener los huecos de horas con su estado (`LIBRE` / `RESERVADO`) para una fecha determinada.

**Flujo de la solicitud:**
Request → AvailabilityController (Infra) → GetAvailabilityUseCase (Application) → ReservationService (Domain)



## Notas / Mejoras posibles

- **Tests para casos de uso:**  
  No se han añadido porque los UseCases actuales son muy simples y delegan la lógica al dominio. En contextos más complejos, sería útil testear que los UseCases llaman correctamente a los servicios de dominio. Se mockearia el servicio de dominio y se verificaría que se llama con los parámetros correctos.


- **Estado de reserva (`RESERVED` / `FREE`):**  
  Para la logica actual no sería necesario. pero he considerado el status libre/reservado en como campo/propiedad de Reservation, pensando que a futuro podria ser útil tener guardadas las reservas que se han liberado (cancelado), a modo de historico. De esa foma, por ejemplo, el admin podría saber que vecinos cancelan muchas reservas.


- **Uso de Entities y Repositories:**  
  En sistemas simples se podría usar directamente la Entity con el ORM. En este proyecto, para mantener arquitectura hexagonal, se ha creado un `ReservationEntity` en Infraestructura que maneja el acceso a datos real.


- **DTOs para Requests y Responses:**  
  En proyectos más complejos es buena práctica usar DTOs para transformar o validar la estructura de datos entre cliente y aplicación.  
  Ejemplo: `CreateReservationRequest`. Este DTO debería vivir en la capa de Application, aunque según el contexto podría ir en Infraestructura.


- **División de servicios:**  
  Se podría extraer `getAvailability()` a un `AvailabilityService`, separando claramente responsabilidades dentro del dominio.


- **Métodos del Repository:**  
  Actualmente se usa un único método para consultar reservas. Se podrían crear métodos separados (`findOne` y `findAll`) para mayor claridad y rendimiento.


- **ENUM para status:**  
  Se ha creado un ENUM `ReservationStatus` para el estado de las reservas. En lógicas más complejas se podría considerar un Value Object, pero un ENUM es más simple y suficiente.


- **Logging:**  
  Para auditar solicitudes o depurar, se puede inyectar por ejemplom en el UseCase, un logger y registrar los intentos de reserva.

---

## Posibles funcionalidades a añadir

- Autenticación y roles de usuario.
- Gestión de `CommonAreas` desde un panel de administrador, incluyendo la posibilidad de anular reservas de cualquier usuario.
- Cancelación de reservas por el propio usuario.
- Limitar reservas por usuario y penalizar usuarios que cancelan excesivamente, usando historial de reservas.

