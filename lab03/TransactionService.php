<?php

declare(strict_types=1);

/**
 * Класс для управления банковскими транзакциями.
 * Предоставляет методы для добавления, удаления, поиска и сортировки транзакций.
 */
class TransactionService {
    private array $transactions = [];

    /**
     * Конструктор класса.
     * Инициализирует массив транзакций, либо загружая его из сессии, либо создавая случайные данные.
     */
    public function __construct() {
        if (isset($_SESSION['transactions']) && !empty($_SESSION['transactions'])) {
            $this->transactions = $_SESSION['transactions'];
        } else {
            $this->transactions = $this->generateRandomTransactions();
            $_SESSION['transactions'] = $this->transactions;
        }
    }

    /**
     * Генерирует массив случайных транзакций для тестирования.
     *
     * @return array Массив транзакций.
     */
    private function generateRandomTransactions(): array {
        $transactions = [];
        for ($i = 0; $i < 5; $i++) {
            $transactions[] = [
                'id' => $i + 1,
                'date' => date('Y-m-d', strtotime('-' . rand(1, 365) . ' days')),
                'amount' => mt_rand(100, 10000) / 100,
                'description' => 'Transaction descriptions ' . ($i + 1),
                'merchant' => 'Merchant ' . ($i + 1),
            ];
        }
        return $transactions;
    }

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

    /**
     * Возвращает массив всех транзакций.
     *
     * @return array Массив транзакций.
     */
    public function getTransactions(): array {
        return $this->transactions;
    }

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

    /**
     * Добавляет новую транзакцию в массив.
     *
     * @param array $transaction Данные новой транзакции.
     */
    public function addTransaction(array $transaction): void {
        $this->transactions[] = $transaction;
    }

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

    /**
     * Сортирует транзакции по дате.
     *
     * @return array Отсортированный массив транзакций.
     */
    public function sortTransactionsByDate(): array {
        $transactions = $this->transactions;

        usort($transactions, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        return $transactions;
    }

    /**
     * Сортирует транзакции по сумме (по убыванию).
     */
    public function sortTransactionsByAmount(): array {
        $transactions = $this->transactions;
        
        usort($transactions, function ($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });

        return $transactions;
    }
}