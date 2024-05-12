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

    public function getBalance(string $accountId): ?int
    {
        return $this->accountRepository->getBalance($accountId);
    }
}
