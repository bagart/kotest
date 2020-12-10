#run

```shell
docker-compose build
docker-compose up -d
docker-compose ps

# generete test data
docker exec -it kotest /usr/local/bin/php /app/gen.php 100000 500

#bench
time docker exec -it kotest /usr/local/bin/php /app/tree-bagart.php

#orig time
time docker exec -it kotest /usr/local/bin/php /app/tree-orig.php

```


#result:
```shell

➜  kotest git:(main) ✗ docker exec -it kotest /usr/local/bin/php /app/gen.php 100000 500
size of data: 10.51mb
➜  kotest git:(main) ✗ time docker exec -it kotest /usr/local/bin/php /app/tree-bagart.php
int(98564840)
result: 2131
time: 0.09s
+mem data: 93.59mb
+mem work: 0mb

docker exec -it kotest /usr/local/bin/php /app/tree-bagart.php  0.12s user 0.14s system 25% cpu 1.045 total
➜  kotest git:(main) ✗ time docker exec -it kotest /usr/local/bin/php /app/tree-orig.php
result: 2131
time: 7.85s
mem diff: 0kb

docker exec -it kotest /usr/local/bin/php /app/tree-orig.php  0.12s user 0.13s system 3% cpu 8.465 total

```
#бенч
бенч на 100к элементов с деревом со случайным кол-вом уровней до 500 быстрее перебора . разница во времени

- инвертированный индекс: 0.09s
- перебор: 7.85s

бенч на 1кк/500 эдлементов
- инвертированный индекс: 2.94s
- перебор: 104.29s
