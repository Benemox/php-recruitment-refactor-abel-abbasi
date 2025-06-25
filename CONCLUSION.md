# PHP Recruitment Refactor Assignment

## ✅ Design Patterns Used

- **Factory Method**: For creating Value Objects like `DoctorId`, `SlotId`.
- **Adapter Pattern**: Used to decouple Doctrine entities from Domain models (`DoctorAdapter`, `SlotAdapter`).
- **Service Layer**: Domain services like `SlotSynchronizer` and `DoctorUpdater` encapsulate business logic cleanly.
- **Repository Pattern**: Abstracts persistence operations for `Doctor` and `Slot`.
- **Dependency Injection**: All collaborators are injected via constructors for testability and decoupling.
- **Logger Factory**: Centralized Monolog instantiation to keep infrastructure clean and extendable.

---

## 🧪 Tests Implemented

- **Unit Tests**: Cover core services and adapters:
  - `DoctorUpdaterTest`
  - `SlotSynchronizerTest`
  - `DoctorAdapterTest`
  - `SlotAdapterTest`
  - `SynchronizeDoctorSlotsHandlerTest`
  - `DoctorExternalProviderTest` (with mocked HTTP)
- **Test structure** mirrors the source layout for maintainability.
- PHPUnit assertions verify both behavior and edge cases.

---

## 📁 Folder Structure
```plaintext
src/
├── Doctor/
│ ├── Domain/
│ ├── Infrastructure/
│ └── Application/
├── Provider/
│ └── DoctorExternal/
├── Shared/
│ └── Infrastructure/
├── Slot/
│ ├── Domain/
│ ├── Infrastructure/
│ └── Application/
tests/
├── Same hierarchy as src/
docker/
├── php/
├── nginx/
├── unitTestingApi/
```

---

## 🔜 TODOs for Future Improvements

- Introduce a **scheduled cron job or queue** to trigger synchronization automatically (instead of manual command).
- Add **integration tests** simulating full doctor-slot sync with real HTTP binary.
- Implement **retry logic or fallback** when external provider fails.
- Add observability features (e.g., Prometheus metrics or more detailed logs).
- Implement **pagination** for large datasets from vendor.

---

## 📈 Why This Structure Is Scalable

- Each context (`Doctor`, `Slot`, `Provider`) is **modular and decoupled**, following Domain-Driven Design (DDD).
- Domain logic is **framework-agnostic**, testable, and separated from infrastructure.
- New data sources, APIs, or workflows can be integrated with **minimal friction**.
- Well-defined boundaries allow for **team parallelization** and easy onboarding.
- Slots can scale horizontally if persisted asynchronously or behind a queue.

---

## 🐳 Why Dockerization Was Modified

- Added **service isolation** with `unit-testing-api` for the mock binary to avoid polluting main PHP app.
- Ensured `entrypoint.sh` is executable inside Alpine.
- Unified Docker setup with a shared `docker-compose.yml` that supports PHP + Nginx + vendor mock seamlessly.
- Mapped necessary ports (e.g., 8080, 2137) for local testing and debugging.

---

## 🧩 Why the Project Is Divided into Doctor, Provider, Shared, Slot

- **Doctor**: Core domain with its own logic and persistence lifecycle.
- **Slot**: Separate lifecycle and complexity (e.g., ranges, schedules), so isolation is critical.
- **Provider**: External APIs and infrastructure code, separated for clarity and testability.
- **Shared**: Contains cross-cutting concerns like `LoggerFactory` or exceptions.
- Each module can grow independently and be extracted into its own microservice if needed.

---

## 🛠️ Makefile Suggestions

Consider enhancing the `Makefile` with commands such as:

```Makefile
up:
	docker compose up --build

test:
	docker exec -it docplanner_php ./vendor/bin/phpunit

logs:
	docker compose logs -f

down:
	docker compose down
