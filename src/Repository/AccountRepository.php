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

    public function checkAccount(string $accountId): bool{
        return isset($this->accounts[$accountId]);
    }
    
    public function deposit(string $accountId, float $amount): array{

        if($this->checkAccount($accountId)){
            $this->accounts[$accountId]['balance'] += $amount;
        }else {
            $this->accounts[$accountId]['balance'] = 0;
            $this->accounts[$accountId]['balance'] += $amount;
        }
        return(array("destination"=> array("id"=> (string)$accountId, "balance" => (float)$this->accounts[$accountId]['balance'])));
    }

    public function withdraw(string $accountId, string $amount){

        $this->accounts[$accountId]['balance'] -= $amount;
        return(array("origin"=> array("id"=> (string)$accountId, "balance" => (float)$this->accounts[$accountId]['balance'])));
    }
}