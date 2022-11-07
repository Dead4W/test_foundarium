### Архитектура:

Все стандартные фреймворк классы находятся в **Framework**
* [Framework](./Framework)
    * [Console](./Framework/Console)
    * [Http](./Framework/Http)
    * [Providers](./Framework/Providers)
    * [Exceptions](./Framework/Exceptions)

Все модели в **Models**

Весь функционал сущности находится в её папке, пример **User** и **Cars**:

```bash
├── Cars
│   ├── Actions
│   ├── Controllers
│   ├── Enums
│   ├── Exceptions
│   ├── Queries
│   └── ResourceModels
│   
└── User
    ├── Controllers
    ├── Requests
    └── ResourceModels
```

