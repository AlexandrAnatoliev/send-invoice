<div align="center">
  <a id="russian"></a>
  <h1>send-invoice: Калькулятор заказа с генерацией счёта и отправкой на email</h1>

  ![Stars](https://img.shields.io/github/stars/AlexandrAnatoliev/send-invoice.svg?style=flat)
  ![Version 0.2.0](https://img.shields.io/badge/Version-0.2.0-orange.svg)
  ![Forks](https://img.shields.io/github/forks/AlexandrAnatoliev/send-invoice.svg?style=flat)
  ![GitHub repo size](https://img.shields.io/github/repo-size/AlexandrAnatoliev/send-invoice)
  
</div>

  > **Author:** Alexandr Anatoliev

  > **GitHub:** [AlexandrAnatoliev](https://github.com/AlexandrAnatoliev)

---

<div align="center">
  <h2>Навигация</h2>
</div>

* [Общая архитектура](#architecture)

---

<div align="center">
  <a id="architecture"></a>
  <h2>Общая архитектура</h2>
</div>

<div align="center">
  <h3>Файловая структура</h3>
</div>
```
.
├── composer.json
├── composer.lock
├── coverage
├── img
│   └── Card
│       └── ocean_pen.jpg
├── index.php
├── README.md
├── src
│   └── Card.php
├── styles
│   ├── Card.css
│   └── index.css
├── tests
│   └── CardTest.php
└── vendor
    ├── autoload.php
    ├── bin
    ├── composer
    ├── myclabs
    ├── nikic
    ├── phar-io
    ├── phpunit
    ├── sebastian
    └── theseer
```

* Запуск тестов:

```
./vendor/bin/phpunit tests
```

* Покрытие тестами

```
 > ./vendor/bin/phpunit --coverage-html coverage --coverage-filter src tests
```
