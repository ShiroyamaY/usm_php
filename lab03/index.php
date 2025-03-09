<?php

declare(strict_types=1);

require_once 'TransactionService.php';
require_once 'ImageGallary.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Инициализация сервиса транзакций.
 * @var TransactionService $transactionService
 */
$transactionService = new TransactionService();
$transactions = $transactionService->getTransactions();
?>

<!-- Задание 1.3. Вывод списка транзакций -->
<html>
    <head>
        <title>Транзакции</title>
    </head>
    <body>
        <h1>Транзакции</h1>
        <table border="1">
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
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo $transaction['id'] ?></td>
                        <td><?php echo $transaction['date'] ?></td>
                        <td><?php echo $transaction['amount'] ?></td>
                        <td><?php echo $transaction['description'] ?></td>
                        <td><?php echo $transaction['merchant'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Задание 1.4. Реализация функций -->

        <?php
        // Тест 1: Вычисление общей суммы транзакций
        $totalAmount = $transactionService->calculateTotalAmount($transactionService->getTransactions());

        // Тест 2: Поиск транзакций по описанию
        $descriptionSearch = 'descriptions 1';
        $findedTransaction = $transactionService->findTransactionsByDescription($descriptionSearch);
        echo "";

        // Tecт 3: Поиск транзакций по ID
        $idSearch = 2;
        echo "Транзакции с ID '$idSearch':</br>";
        print_r($transactionService->findTransactionsById($idSearch));

        // Тест 4: Вычисление дней с момента транзакции
        $transactionDate = $transactions[0]['date'];
        $days = $transactionService->daysSinceTransaction($transactionDate);
        ?>

        <!-- Тест 1 -->    
        <h3>Общая сумма:</h3>
        <p>
            <?php echo $totalAmount ?>
        </p>
        <!-- Тест 2 -->
        <h3>Транзакции с описанием</h3>
        <p>
            <?php echo $descriptionSearch ?>: <?php echo print_r($findedTransaction, true) ?>
        </p>
        <!-- Тест 3 -->
        <h3>Дни с момента транзакции от</h3>
        <p>
            <?php echo $transactionDate ?>: <?php echo $days ?>
        </p>

        <?php 
        // Тест 5: Добавление новой транзакции
        $newTransaction = [
            'id' => 101,
            'date' => date('Y-m-d'),
            'amount' => 50.00,
            'description' => 'Новая транзакция',
            'merchant' => 'Новый магазин'
        ];

        $transactionService->addTransaction($newTransaction);
        ?>

        <!-- Тест 5 -->
        <h3>Транзакции после добавления новой:</h3>
        <p>
            <?php echo print_r($transactionService->getTransactions(),true); ?>
        </p>


        <!-- Тест 7: Удаление транзакции по ID -->
        <?php $transactionService->removeTransaction(101); ?>

        <h3>Транзакции после удаления новой:</h3>
        <p>
            <?php echo print_r($transactionService->getTransactions(), true); ?>
        </p>

        <!-- Тест 8: Сортировка транзакций по дате -->
        <?php $transactions = $transactionService->sortTransactionsByDate(); ?>

        <h3>Транзакции, отсортированные по дате:</h3>
        <p>
            <?php echo print_r($transactions, true) ?>
        </p>

        <!-- Тест 9: Сортировка транзакций по сумме -->
        <?php $transactions = $transactionService->sortTransactionsByAmount(); ?>

        <h3>Транзакции, отсортированные по сумме:</h3>
        <p>
            <?php echo print_r($transactions, true) ?>
        </p>
    
        <?php
        $imageGallery = new ImageGallery('image');

        $images = $imageGallery->getImages();
        ?>

        <?php if (empty($images)): ?>
            <p>Нет изображений для отображения.</p>
        <?php else: ?>
            <table border="1">
                <tbody>
                    <?php
                    $chunks = array_chunk($images, 3);
                    foreach ($chunks as $chunk):
                    ?>
                        <tr>
                            <?php foreach ($chunk as $image): ?>
                                <td><img src="image/<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" alt="Изображение" width="150"></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </body>
</html>
