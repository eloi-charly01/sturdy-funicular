<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TestController extends AbstractController
{
    public function index()
    {
        // This is a test controller method
        return 'Hello, this is a test!';
    }

    public function anotherMethod()
    {
        // Another method for testing purposes
        return 'This is another test method!';
    }
}
