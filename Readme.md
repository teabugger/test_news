Тестовая демонстрация API

##Задание:
Необходимо создать RESTful API для управления лентой новостей. API должно содержать следующие методы:
1. Добавление новости.
2. Редактирование новости.
3. Удаление новости.
4. Получение новости по идентификатору.
5. Поиск новостей. Параметры поиска:
   5.1 Список идентификаторов новостей.
   5.2 Период дат с точностью до дня. 
   5.3 Сортировка по полям: заголовок и дата.
   5.4 Передача параметров пагинации: смещение и лимит.
6. Получение количества новостей за каждый день в пределах указанного периода.
7. Внимание! Дополнительно нужно реализовать возможность добавления новостей путем передачи их через очередь
   RabbitMQ.


##Зависимости
* Docker
* Docker-compose

##Установка
1. docker-compose build

##Запуск
1. docker-composer up -d;
2. http://localhost (документация API)

##Работа с очередями
Для добавления новости через очередь используется rabbitmq, очередь create-news. Креды RabbitMQ указаны в .env-файле. Новость принимается json-строкой, совпадающей по структуре с созданием через API (схема описана на странице документации API)