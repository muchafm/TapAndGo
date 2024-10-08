<?php

declare(strict_types=1);

namespace App\Application\REST\City\Resource;

use App\Application\MessageBus;
use App\Domain\UseCase\RemoveACity;
use App\Domain\Exception\CityNotFoundException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class DELETE
{
    public function __construct(private MessageBus $messageBus)
    {

    }

    #[OA\Response(
        response: 200,
        description: 'Delete a city'
    )]
    public function __invoke(string $id): Response
    {
        try {
            $this->messageBus->handle(new RemoveACity\Input($id));
        } catch (CityNotFoundException $cityNotFoundException) {
            return new JsonResponse($cityNotFoundException->getMessage(), json: true);
        }

        return new JsonResponse(Response::HTTP_OK);
    }
}