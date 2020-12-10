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

```shell
➜  kotest git:(main) ✗ docker exec -it kotest /usr/local/bin/php /app/gen.php 1000000 500 
size of data: 107.98mb
➜  kotest git:(main) ✗ time docker exec -it kotest /usr/local/bin/php /app/tree-bagart.php
int(899499080)
result: 2003
time: 2.94s
+mem data: 857.42mb
+mem work: 0mb

docker exec -it kotest /usr/local/bin/php /app/tree-bagart.php  0.12s user 0.14s system 2% cpu 12.284 total
➜  kotest git:(main) ✗ time docker exec -it kotest /usr/local/bin/php /app/tree-orig.php
result: 2003
time: 104.29s
mem diff: 0kb

docker exec -it kotest /usr/local/bin/php /app/tree-orig.php  0.15s user 0.34s system 0% cpu 1:52.55 total

```
#бенч
бенч на 100к элементов с деревом со случайным кол-вом уровней до 500 быстрее перебора . разница во времени

- инвертированный индекс: 0.09s
- перебор: 7.85s

бенч на 1кк/500 эдлементов (data:107.98mb)
- инвертированный индекс: **2.94s**
- перебор: **104.29s**
