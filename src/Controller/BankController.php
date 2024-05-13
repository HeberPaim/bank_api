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
        
        //read bankService from session, if it exists
        if($session->get('bankService') != null)
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

        //read bankService from session, if it exists
        if($session->get('bankService') !== null)
            $this->setBankService($session->get('bankService'));

        $request = json_decode($request->getContent(), true);

        switch($request['type']){
            case 'deposit':
                if(is_numeric($request['destination']) && $request['destination'] > 0){
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setContent(json_encode($this->bankService->deposit($request['destination'], (float)$request['amount'])));
                    $response->setStatusCode(Response::HTTP_CREATED);
                } else{
                    $response->headers->set('Content-Type', 'text/html');
                    $response->setContent('Invalid destination');
                    $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                }
                break;
            case 'withdraw':
                if($this->bankService->checkAccount($request['origin'])){
                    $response->setContent(json_encode($this->bankService->withdraw($request['origin'], (float)$request['amount'])));
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setStatusCode(Response::HTTP_CREATED);
                } else{
                    $response->setContent(0);
                    $response->setStatusCode(Response::HTTP_NOT_FOUND);
                }
                break;
            case 'transfer':
                if($this->bankService->checkAccount($request['origin']) && $this->bankService->checkAccount($request['destination'])){
                    $response->setContent(json_encode($this->bankService->transfer($request['origin'], $request['destination'], (float)$request['amount'])));
                    $response->headers->set('Content-Type', 'application/json');
                    $response->setStatusCode(Response::HTTP_CREATED);
                } else {
                    $response->setContent(0);
                    $response->setStatusCode(Response::HTTP_NOT_FOUND);
                }
                break;
            default:
                $response->setContent(0);
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

        //read bankService from session, if it exists
        $session = $request->getSession();
        if($session->get('bankService') !== null)
            $this->setBankService($session->get('bankService'));

        $accountId = $request->get("account_id");

        if($this->bankService->checkAccount($accountId)){
            $response->setContent($this->bankService->getBalance($accountId));
            $response->setStatusCode(Response::HTTP_OK);
        } else{
            $response->setContent(0);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }
    //allaccounts route
    #[Route('/allaccounts', methods:['GET'], name: 'allaccounts')]
    public function allaccounts(Request $request): Response
    {
        //create a new Response object
        $response = new Response();

        //read bankService from session, if it exists
        $session = $request->getSession();

        if($session->get('bankService') !== null)
            $this->setBankService($session->get('bankService'));

        $response->setContent(json_encode($this->bankService->getAllAccounts(), true));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }
}