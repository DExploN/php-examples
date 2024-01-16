Запуск cli клиента 

`docker run -it --rm --network=php_example_net yandex/clickhouse-client --host clickhouse-server`

Зайти под bash в cli клиент и прокинуть workdir = current host dir 

`docker run --rm -ti -v ${PWD}:/workdir --workdir=/workdir --entrypoint='bash' --network=php_example_net yandex/clickhouse-client`

Пример запуска csv импорта из bash. Файл должен быть прокинут внутрь контейнера

`clickhouse-client --host clickhouse-server --format_csv_allow_single_quotes 0 --input_format_null_as_default 0 --query "INSERT INTO dish FORMAT CSVWithNames" < Dish.csv`

Источник тестовой базы https://clickhouse.com/docs/ru/getting-started/example-datasets/menus