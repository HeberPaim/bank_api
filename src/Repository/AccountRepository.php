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

    public function getBalance(string $accountId): ?float {
        return $this->accounts[$accountId]['balance'] ?? null;
    }

    public function getAllAccounts() {
        return $this->accounts ?? null;
    }

    public function checkAccount($accountId): bool{
        return isset($this->accounts[$accountId]);
    }
    
    public function deposit($accountId, float $amount): array{
        if($this->checkAccount($accountId)){
            $this->accounts[$accountId]['balance'] += $amount;
        }else {
            $this->accounts[$accountId]['balance'] = $amount;
        }
        return(array("destination"=> array("id"=> (string)$accountId, "balance" => (int)$this->accounts[$accountId]['balance'])));
    }

    public function withdraw(string $accountId, string $amount){
        $this->accounts[$accountId]['balance'] -= $amount;
        return(array("origin"=> array("id"=> (string)$accountId, "balance" => (float)$this->accounts[$accountId]['balance'])));
    }
    
    public function transfer (string $origin, string $destination, float $amount){

        $this->accounts[$origin]['balance'] -= $amount;
        if($this->checkAccount($destination)){
            $this->accounts[$destination]['balance'] += $amount;
        }else {
            $this->accounts[$destination]['balance'] = $amount;
        }

        return(array("origin"       => array("id"      => (string)$origin, 
                                             "balance" => (float)$this->accounts[$origin]['balance']),
                     "destination"  => array("id"      => (string)$destination, 
                                             "balance" => (float)$this->accounts[$destination]['balance'])));
    }


}