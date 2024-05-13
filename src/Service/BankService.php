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

    public function reset(): void {
        $this->accountRepository->reset();
    }

    public function getBalance(string $accountId): ?float {
        return $this->accountRepository->getBalance($accountId);
    }
    public function checkAccount(string $accountId): bool {
        return $this->accountRepository->checkAccount($accountId);
    }
    public function deposit(string $destination, float $amount): array {
        return $this->accountRepository->deposit($destination, $amount);
    }
    public function withdraw(string $origin, float $amount): array {
        return $this->accountRepository->withdraw($origin, $amount);
    }
}
?>