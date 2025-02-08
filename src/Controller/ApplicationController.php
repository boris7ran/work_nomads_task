<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ApplicationDto;
use App\Service\FusionAuthApplicationService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApplicationController extends AbstractController
{
    #[Route('/applications', name: 'applications', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ApplicationDto::class))
        )
    )]
    public function applications(FusionAuthApplicationService $fusionAuthApplicationService)
    {
        return new JsonResponse($fusionAuthApplicationService->getApplications());
    }


    #[Route('/applications/{applicationId}', name: 'application_get', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: ApplicationDto::class)
    )]
    public function application(string $applicationId, FusionAuthApplicationService $fusionAuthApplicationService)
    {
        return new JsonResponse($fusionAuthApplicationService->getApplication($applicationId));
    }
}