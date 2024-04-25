<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
    public function helloWorld(): Response
    {
        return new Response(
            '<html><body>Hello world</body></html>'
        );
    }
    public function helloName(string $name): Response
    {
        return new Response(
            '<html><body>Hello'.$name.' world</body></html>'
        );
    }
}