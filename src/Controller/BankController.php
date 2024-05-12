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

    //set the route, so [site URL]/hello will trigger this
    #[Route('/reset', methods:['POST'], name: 'reset')]
    public function reset(): Response
    {
        //create a new Response object
        $response = new Response();

        //reset the bankService
        $this->bankService->reset();

        //make sure we send a 200 OK status
        $response->setStatusCode(Response::HTTP_OK);

        // set the response content type to plain text
        $response->headers->set('Content-Type', 'application/json');

        // send the response with appropriate headers
        return $response;
    }

    //set the route, so [site URL]/hello will trigger this
    #[Route('/balance', methods:['GET'], name: 'balance')]
    public function balance(Request $request): Response
    {
        //create a new Response object
        $response = new Response();


        //reset the bankService
        $balance = $this->bankService->getBalance($request->get("account_id"));
        if($balance === null){
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        } else{
            //make sure we send a 200 OK status
            $response->setContent($balance);        // set the response content type to plain text
            $response->headers->set('Content-Type', 'text/html');
            $response->setStatusCode(Response::HTTP_OK);
        }
        


        // send the response with appropriate headers
        return $response;
    }


}