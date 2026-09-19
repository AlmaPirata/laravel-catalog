# Каталог товаров

## Запуск

```bash
docker compose up --build
```

Либо через Makefile:

```bash
make up
```

После запуска откройте [http://localhost:8000](http://localhost:8000).

При первом старте будут созданы таблицы и загружены тестовые данные.

Внутри установлен xdebug на порту 9003 c IDEKEY=VSCODE
Для запуска устанавливается расширение PHP Debug, настройка для launch.json
```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Debug project",
            "type": "go",
            "request": "launch",
            "mode": "debug",
            "program": "${workspaceFolder}/cmd/project-name",
            "cwd": "${workspaceFolder}"
        }
    ]
}
```
