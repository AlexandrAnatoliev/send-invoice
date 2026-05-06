<div align="center">
  <a id="russian"></a>
  <h1>send-invoice: Калькулятор заказа с генерацией счёта и отправкой на email</h1>

  ![Stars](https://img.shields.io/github/stars/AlexandrAnatoliev/send-invoice.svg?style=flat)
  ![Version 0.8.0](https://img.shields.io/badge/Version-0.8.0-orange.svg)
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
* [Разное](#other)

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
├── coverage/
├── img/
│   ├── Addon/
│   │   ├── print_on_clip.png
│   │   ├── print_on_colored_case.png
│   │   └── print_on_white_case.png
│   └── Item/
│       ├── lychee_pen.jpg
│       ├── ocean_pen.jpg
│       └── senator_pen.jpg
├── index.php
├── phpunit.xml.dist
├── README.md
├── src/
│   ├── Addon.php
│   ├── Card.php
│   ├── FormElement.php
│   └── Item.php
├── styles/
│   ├── Card.css
│   └── index.css
├── tests
│   ├── AddonTest.php
│   ├── HealthTest.php
│   └── ItemTest.php
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

<div align="center">
  <h3>Структура классов</h3>
</div>

```mermaid
classDiagram
  
  class FormElement {
    # name: string
    + static getCSS() string
    + FormElement(name: string)
    + getName() string
    + render() string*
  }

  class Card {
    # price: int
    # image: string
    + Card(name: string, price: int, image: string)
    + getPrice() int
    + getImage() string
  }

  class Item {
    + render() string
  }

  class Addon {
    + render() string
  }

  FormElement  <|-- Card
  Card <|-- Item
  Card <|-- Addon

```

<div align="center">
  <a id="other"></a>
  <h2>Разное</h2>
</div>

* Запуск всех тестов с автоматической генерацией покрытия (HTML-отчёт в папке `coverage/`):

```
vendor/phpunit/phpunit/phpunit
```

* Обновить карту классов в `vendor/composer/autoload_*.php`

```
composer dump-autoload
```
