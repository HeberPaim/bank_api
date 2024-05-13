<?php
// src/Repository/AccountRepository.php

namespace App\Repository;

class AccountRepository
{
    private array $accounts = [];

    public function reset(): void
    {
        $this->accounts = [];
    }

    public function getBalance(string $accountId): ?float
    {
        return $this->accounts[$accountId]['balance'] ?? null;
    }

    public function deposit(int $accountId, float $amount): array{

        if(isset($this->accounts[$accountId])){
            $this->accounts[$accountId]['balance'] += $amount;
        }else {
            $this->accounts[$accountId]['balance'] = 0;
            $this->accounts[$accountId]['balance'] += $amount;
        }
        return(array("destination"=> array("id"=> $accountId, "balance" => $this->accounts[$accountId]['balance'])));
    }
}