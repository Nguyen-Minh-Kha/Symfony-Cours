<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="app_hello_afficher")
     */
    public function afficher(Request $request): Response // contient la fonctionalité d'afficher hello
    {
        $name = $request->query->get('name', 'dfzerf');
        return new Response(sprintf('Hello %s !', $name));
        
    }
}
