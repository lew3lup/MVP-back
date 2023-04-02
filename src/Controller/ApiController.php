<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends AbstractController
{
    /** @var UserService */
    protected $userService;

    /**
     * ApiController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return Response
     */
    abstract public function options(): Response;

    /**
     * @param Request $request
     * @return User
     */
    protected function getCurrentUser(Request $request): User
    {
        return $this->userService->getCurrentUser($request->headers->get('Authorization'));
    }
}