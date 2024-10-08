<?php

declare(strict_types=1);

namespace App\Application\REST\Station\Resource;

use App\Application\MessageBus;
use App\Domain\UseCase\RemoveAStation;
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
        description: 'Delete a station'
    )]
    public function __invoke(string $id): Response
    {
        try {
            $this->messageBus->handle(new RemoveAStation\Input($id));
        } catch (CityNotFoundException $cityNotFoundException) {
            return new JsonResponse($cityNotFoundException->getMessage(), json: true);
        }

        return new JsonResponse(Response::HTTP_OK);
    }
}