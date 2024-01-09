Сброс offsets 

`/opt/bitnami/kafka/bin/kafka-consumer-groups.sh --bootstrap-server localhost:9092 --group myConsumerGroup --reset-offsets --to-datetime 2020-12-20T00:00:00.000 --topic partTopic --execute`