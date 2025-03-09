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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Транзакции и Галерея</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>About Cats | News | Contacts</h1>
    </header>
    <nav>
        <a href="#cats">#cats</a>
        <a href="#transactions">Transactions</a>
        <a href="#gallery">Gallery</a>
    </nav>
    <div class="content">
        <section id="transactions">
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

            <?php
            // Тест 1: Вычисление общей суммы транзакций
            $totalAmount = $transactionService->calculateTotalAmount($transactionService->getTransactions());

            // Тест 2: Поиск транзакций по описанию
            $descriptionSearch = 'descriptions 1';
            $findedTransaction = $transactionService->findTransactionsByDescription($descriptionSearch);

            // Тест 3: Поиск транзакций по ID
            $idSearch = 2;
            $transaction = $transactionService->findTransactionsById($idSearch);

            // Тест 4: Вычисление дней с момента транзакции
            $transactionDate = $transactions[0]['date'];
            $days = $transactionService->daysSinceTransaction($transactionDate);
            ?>

            <h3>Общая сумма:</h3>
            <p><?php echo $totalAmount ?></p>

            <h3>Транзакции с описанием</h3>
            <p><?php echo $descriptionSearch ?>: <?php echo print_r($findedTransaction, true) ?></p>

            <h3>Транзакции с ID:</h3>
            <p><?php echo print_r($transaction, true) ?></p>

            <h3>Дни с момента транзакции от</h3>
            <p><?php echo $transactionDate ?>: <?php echo $days ?></p>

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

            <h3>Транзакции после добавления новой:</h3>
            <p><?php echo print_r($transactionService->getTransactions(), true); ?></p>

            <?php $transactionService->removeTransaction(101); ?>

            <h3>Транзакции после удаления новой:</h3>
            <p><?php echo print_r($transactionService->getTransactions(), true); ?></p>

            <?php $transactions = $transactionService->sortTransactionsByDate(); ?>

            <h3>Транзакции, отсортированные по дате:</h3>
            <p><?php echo print_r($transactions, true) ?></p>

            <?php $transactions = $transactionService->sortTransactionsByAmount(); ?>

            <h3>Транзакции, отсортированные по сумме:</h3>
            <p><?php echo print_r($transactions, true) ?></p>
        </section>

        <section id="gallery">
            <h1>Галерея изображений</h1>
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
        </section>
    </div>
    <footer>
        <p>&copy; 2023 About Cats. All rights reserved.</p>
    </footer>
</body>
</html>