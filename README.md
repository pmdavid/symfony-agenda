# Schedule reservation system for common areas.

## Local deployment

### 1. Clone the repository

```bash
  git clone git@github.com:pmdavid/symfony-agenda.git
```

### 2. Start docker containers

```bash
  docker-compose up -d --build
```

### 3. Install dependencies
From the PHP container:
```bash
  composer install
```

### 4. Run migration

From the PHP container:
```bash
  php bin/console doctrine:migrations:migrate
```

## Run tests

From the PHP container:
```bash
   vendor/bin/phpunit tests/Unit
```

---

## API and application flow

### - POST | `/api/reserve`
Reserve a slot for the indicated hour, date, and common area. JSON structure of the request:

```json
{
  "commonArea": 1,
  "date": "2025-11-02",
  "hour": 10
}
```

The response returns a JSON with the result of the request:

```json
{
  "success": true
}
```

**Request flow:**
Request → ReservationController (Infra) → ReservationUseCase (Application) → ReservationService (Domain)

### - GET | `/api/availability`
Get the hour slots with their status (`FREE` / `RESERVED`) for a given date. JSON structure of the response:

```json
{
  "slots": [
    {"hour": 9, "status": "FREE"},
    {"hour": 10, "status": "RESERVED"},
    ...
  ]
}
```

**Request flow:**
Request → AvailabilityController (Infra) → GetAvailabilityUseCase (Application) → ReservationService (Domain)

## Notes / Possible Refactorings

- A hexagonal architecture has been implemented to clearly separate responsibilities and facilitate code maintenance and scalability:

1. **Domain Layer:** Contains the main business logic, including entities and services. We can see how in the Service we are using a repository Interface, which allows decoupling business logic from data access. The concrete implementation is found in Infrastructure and implements that interface.
2. **Application Layer:** Contains the use cases that orchestrate the business logic.
3. **Infrastructure Layer:** Handles interaction with the outside world, in this case: controllers and database access.

- **Tests for use cases:**
They have not been added because the current UseCases are very simple and delegate the logic to the domain. In more complex contexts, it would be useful to test that the UseCases correctly call the domain services. The domain service would be mocked and it would be verified that it is called with the correct parameters.

- **Common Areas:**
The common areas (Paddle court, Pool...) have been hardcoded in the front end, but normally the ideal would be to obtain them from the API with a specific call for that (or wherever it fits according to the context).

- **Use of Entities and Repositories:**
In simple systems, the Entity could be used directly with the ORM. In this project, to maintain a hexagonal architecture, a `ReservationEntity` has been created in Infrastructure that handles the actual data access.

- **DTOs for Requests and Responses:**
In more complex projects, it is good practice to use DTOs to transform or validate the data structure between client and application.
DTO example: CreateReservationRequest, and at the same time, within the DTO itself, we can validate the request fields using asserts. This DTO would go in the application layer, although depending on nuances it could go in infrastructure.

- **Division of services:**
`getAvailability()` could be extracted to an `AvailabilityService`, clearly separating responsibilities within the domain.

- **Repository Methods:**
Currently, a single method is used to query reservations. Separate methods (`findOne` and `findAll`) could be created for greater clarity and performance.

- **ENUM for status:**  
  An ENUM `ReservationStatus` has been created for the status of reservations. In more complex logic, a Value Object could be considered, but an ENUM is simpler and sufficient.

- **Logging:**  
  To audit requests or debug, a logger could be injected, for example in the UseCase, to record reservation attempts with request info.

- **Error handling:**  
  Currently, if a reservation cannot be made, a JSON with success: false is returned. In a more robust system, specific exceptions could be thrown and handled with try-catch in the controller to return appropriate HTTP codes.

- **Function comments:**  
  Some functions could be detailed much better by providing context about what they do if necessary, and adding their input parameter types and return types.

