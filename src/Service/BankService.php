<?php 
// src/Service/BankService.php

namespace App\Service;

use App\Repository\AccountRepository;

class BankService
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function reset(): void
    {
        $this->accountRepository->reset();
    }

    public function getBalance(string $accountId): ?float
    {
        return $this->accountRepository->getBalance($accountId);
    }

    public function deposit(int $destination, float $amount): array{
        return $this->accountRepository->deposit($destination, $amount);
    }
}
?>