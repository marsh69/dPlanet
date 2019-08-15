<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractFOSRestController
{
    /**
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {   
        $name = $request->get('name');
        return $this->render('user/index.html.twig',[
            'name' => $name
        ]);
    }
}
