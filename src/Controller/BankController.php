<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Service\BankService;

class BankController extends AbstractController
{
    private BankService $bankService;
    public function __construct(BankService $bankService){
        $this->bankService = $bankService;
    }

    private function setBankService(BankService $bankService){
        $this->bankService = $bankService;
    }
    /*
        The RESET route clear all current stored accounts on the session.
    */
    //RESET route
    #[Route('/reset', methods:['POST'], name: 'reset')]
    public function reset(Request $request): Response
    {
        $response = new Response();

        $session = $request->getSession();
        $this->setBankService($session->get('bankService'));

        //reset all accounts on bankService
        $this->bankService->reset();

        $response->setContent('OK');
        $response->setStatusCode(Response::HTTP_OK);

        $response->headers->set('Content-Type', 'application/json');
        
        $session->set('bankService', $this->bankService); 
        return $response;
    }

    // EVENT route
    #[Route('/event', methods:['POST'], name: 'event')]
    public function event(Request $request): Response
    {
        $response = new Response();
        $session = $request->getSession();
        $this->setBankService($session->get('bankService'));
        $request = json_decode($request->getContent(), true);

        switch($request['type']){
            case 'deposit':
                if(is_numeric($request['destination']) && $request['destination'] > 0){
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setContent(json_encode($this->bankService->deposit((int)$request['destination'], (float)$request['amount'])));
                    $response->setStatusCode(Response::HTTP_CREATED);
                } else{
                    $response->headers->set('Content-Type', 'text/html');
                    $response->setContent('Invalid destination');
                    $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                }
                break;
            case 'withdraw':
                break;
            case 'transfer':
                break;
            default:
                var_dump('ou aqui?');
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                break;
        };

        $session->set('bankService', $this->bankService); 
        return $response;
    }

    //BALANCE route
    #[Route('/balance', methods:['GET'], name: 'balance')]
    public function balance(Request $request): Response
    {
        //create a new Response object
        $response = new Response();

        //reset the bankService
        $balance = $this->bankService->getBalance($request->get("account_id"));

        if($balance === null){
            $response->setContent(0);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        } else{
            $response->setContent($balance);
            $response->setStatusCode(Response::HTTP_OK);
        }

        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }
}