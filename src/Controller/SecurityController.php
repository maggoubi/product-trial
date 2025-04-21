<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private UserService $userService;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserService $userService
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->userService = $userService;
    }

    #[Route('/account', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try {
            $userDto = $this->serializer->deserialize($request->getContent(), UserDto::class, 'json', ['groups' => ['user:write']]);
            
            $errors = $this->validator->validate($userDto);
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }
            
            $existingUser = $this->userService->getUserByEmail($userDto->getEmail());
            if ($existingUser) {
                return $this->json(['error' => 'User with this email already exists'], Response::HTTP_CONFLICT);
            }
            
            $user = $this->userService->createUser($userDto);
            
            return $this->json(
                $user,
                Response::HTTP_CREATED,
                [],
                ['groups' => ['user:read']]
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
