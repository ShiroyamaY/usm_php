# Отчет по лабораторной работе: Работа с массивами в PHP

## Цель работы

Освоить работу с массивами в PHP, применяя различные операции: создание, добавление, удаление, сортировка и поиск. Закрепить навыки работы с функциями, включая передачу аргументов, возвращаемые значения и анонимные функции.

---

## Условие

### Задание 1. Работа с массивами

Разработать систему управления банковскими транзакциями с возможностью:

- Добавления новых транзакций.
- Удаления транзакций.
- Сортировки транзакций по дате или сумме.
- Поиска транзакций по описанию.

#### Задание 1.1. Подготовка среды

1. Убедитесь, что у вас установлен PHP 8+.
2. Создайте новый PHP-файл `index.php`.
3. Включите строгую типизацию в начале файла:

```php
<?php
declare(strict_types=1);
```

#### Задание 1.2. Создание массива транзакций

Создан массив `$transactions`, содержащий информацию о банковских транзакциях. Каждая транзакция представлена в виде ассоциативного массива с полями:

- `id` – уникальный идентификатор транзакции.
- `date` – дата совершения транзакции (в формате `YYYY-MM-DD`).
- `amount` – сумма транзакции.
- `description` – описание назначения платежа.
- `merchant` – название организации, получившей платеж.

Пример массива:

```php
$transactions = [
    [
        "id" => 1,
        "date" => "2019-01-01",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2020-02-15",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
];
```

> Примечание: хоть тут и есть пример массива, генерация
> транзакций в моей реализации проходит в классе, и есть
> отдельный метод который генерирует транзакции, а
> результат сохраняет в сессию.

#### Задание 1.3. Вывод списка транзакций

Использован цикл `foreach` для вывода списка транзакций в HTML-таблице. Пример таблицы:

```html
<table border='1'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Merchant</th>
        </tr>
    </thead>
    <tbody>
        <!-- Вывод транзакций -->
    </tbody>
</table>
```

#### Задание 1.4. Реализация функций

Реализованы следующие функции:

1.**`calculateTotalAmount(array $transactions): float`**
   Вычисляет общую сумму всех транзакций.

```php
/**
 * Вычисляет общую сумму всех транзакций.
 *
 * @param array $transactions Массив транзакций.
 * @return float Общая сумма транзакций.
 */
public function calculateTotalAmount(array $transactions): float {
    $totalAmount = 0;
    foreach ($transactions as $transaction) {
        $totalAmount += $transaction['amount'];
    }
    return $totalAmount;
}
```

2.**`findTransactionByDescription(string $descriptionPart): array`**  
   Ищет транзакции по части описания.

```php
/**
 * Ищет транзакции по части описания.
 *
 * @param string $description Часть описания для поиска.
 * @return array Массив транзакций, соответствующих критерию поиска.
 */
public function findTransactionsByDescription(string $description): array {
    $filteredTransactions = [];
    foreach ($this->transactions as $transaction) {
        if (strpos($transaction['description'], $description) !== false) {
            $filteredTransactions[] = $transaction;
        }
    }
    return $filteredTransactions;
}
```

3.**`findTransactionById(int $id): array`**  
   Ищет транзакцию по идентификатору (реализовано с использованием `array_filter`).

```php
/**
 * Ищет транзакции по идентификатору.
 *
 * @param int $id Идентификатор транзакции.
 * @return array Массив транзакций, соответствующих критерию поиска.
 */
public function findTransactionsById(int $id): array {
    return array_filter($this->transactions, function($transaction) use ($id) {
        return $transaction['id'] === $id;
    });
}
```

4.**`daysSinceTransaction(string $date): int`**  
   Возвращает количество дней между датой транзакции и текущим днем.

```php
/**
 * Вычисляет количество дней с момента транзакции до текущей даты.
 *
 * @param string $date Дата транзакции в формате YYYY-MM-DD.
 * @return int Количество дней с момента транзакции.
 */
public function daysSinceTransaction(string $date): int {
    foreach ($this->transactions as $transaction) {
        if ($transaction['date'] === $date) {
            return (int)floor((time() - strtotime($date)) / 86400);
        }
    }
    return 0;
}
```

5.**`addTransaction(array $transaction): void`**  
   Добавляет новую транзакцию в массив.

```php
/**
 * Добавляет новую транзакцию в массив.
 *
 * @param array $transaction Данные новой транзакции.
 */
public function addTransaction(array $transaction): void {
    $this->transactions[] = $transaction;
}
```

6.**`removeTransaction(int $id): void`**  
   Удаляет транзакцию по идентификатору.

```php
/**
 * Удаляет транзакцию по идентификатору.
 *
 * @param int $id Идентификатор транзакции.
 */
public function removeTransaction(int $id): void {
    foreach ($this->transactions as $key => $transaction) {
        if ($transaction['id'] === $id) {
            unset($this->transactions[$key]);
        }
    }
    $this->transactions = array_values($this->transactions); // Переиндексация массива
}
```

#### Задание 1.5. Сортировка транзакций

1. **Сортировка по дате**
   Использована функция `usort()` для сортировки транзакций по дате.

```php
    /**
 * Сортирует транзакции по дате.
 *
 * @return array Отсортированный массив транзакций.
 */
public function sortTransactionsByDate(): array {
    usort($this->transactions;, function ($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    });
}
```

2.**Сортировка по сумме (по убыванию)**  
   Использована функция `usort()` для сортировки транзакций по сумме.

```php
/**
 * Сортирует транзакции по сумме (по убыванию).
 */
public function sortTransactionsByAmount(): void {
    usort($this->transactions, function ($a, $b) {
        return $b['amount'] <=> $a['amount'];
    });
}
```

---

### Задание 2. Работа с файловой системой

1. Создана директория `image`, в которой сохранено не менее 20-30 изображений с расширением `.jpg`.

2. В файле `index.php` реализована веб-страница с выводом изображений в виде галереи.

Пример скрипта:

```php
<?php
$dir = 'image/';
$files = scandir($dir);

if ($files === false) {
    return;
}

for ($i = 0; $i < count($files); $i++) {
    if (($files[$i] != ".") && ($files[$i] != "..")) {
        $path = $dir . $files[$i];
        echo "<img src='$path' alt='Изображение' width='150'>";
    }
}
```

---

## Документация кода

Код задокументирован в соответствии со стандартом PHPDoc. Каждая функция и метод содержат описание:

- Назначение функции.
- Входные параметры.
- Возвращаемые значения.

Пример документации:

```php
/**
 * Вычисляет общую сумму всех транзакций.
 *
 * @param array $transactions Массив транзакций.
 * @return float Общая сумма транзакций.
 */
public function calculateTotalAmount(array $transactions): float {
    $totalAmount = 0;
    foreach ($transactions as $transaction) {
        $totalAmount += $transaction['amount'];
    }
    return $totalAmount;
}
```

---

## Контрольные вопросы

1. **Что такое массивы в PHP?**

   Массивы в PHP — это структуры данных, которые хранят набор значений (элементов) под одним именем. Они могут быть индексированными, ассоциативными или многомерными.

2. **Каким образом можно создать массив в PHP?**  
   Массив можно создать с помощью конструкции `array()` или короткого синтаксиса `[]`. Пример:

   ```php
   $array = [1, 2, 3];
   $assocArray = ["key" => "value"];
   ```

3. **Для чего используется цикл `foreach`?**  
   Цикл `foreach` используется для перебора элементов массива. Он автоматически проходит по всем элементам массива, не требуя указания индексов. Пример:

   ```php
   foreach ($array as $value) {
       echo $value;
   }
   ```

---

## Результаты работы

1. Реализована система управления банковскими транзакциями с возможностью добавления, удаления, сортировки и поиска.
2. Создана галерея изображений, которая выводит изображения из указанной директории.
3. Код полностью задокументирован в соответствии с PHPDoc.

---

## Заключение

В ходе выполнения лабораторной работы были освоены основные операции с массивами в PHP, включая создание, добавление, удаление, сортировку и поиск. Также были закреплены навыки работы с функциями, циклами и файловой системой.