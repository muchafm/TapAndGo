<?php

declare(strict_types=1);

namespace App\Application\REST\Station\Resource;

use App\Application\MessageBus;
use App\Domain\UseCase\RetrieveAStation;
use App\Domain\Exception\StationNotFoundException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class GET
{
    public function __construct(private MessageBus $messageBus)
    {

    }

    #[OA\Response(
        response: 200,
        description: 'Retrieve data of a station',
        content: new OA\MediaType(
            mediaType: "application/json", 
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'name', description: 'Name of the station', type: 'string'),
                    new OA\Property(property: 'address', description: 'Address of the station', type: 'string'),
                    new OA\Property(property: 'latitude', description: 'Latitude point', type: 'float'),
                    new OA\Property(property: 'longitude', description: 'Longitude', type: 'float'),
                    new OA\Property(property: 'capacity', description: 'Total docks of the station', type: 'integer'),
                    new OA\Property(property: 'cityId', description: 'Id of th city where the station is located', type: 'string'),
                    new OA\Property(property: "state", description: "Current state of station", type: 'string')
                ]
            ),
        )
    )]
    public function __invoke(string $id): Response
    {
        try {
            /**
             * @var RetrieveAStation\Output $output
             */
            $output = $this->messageBus->handle(new RetrieveAStation\Input($id));
        } catch (StationNotFoundException $stationNotFoundException) {
            return new JsonResponse($stationNotFoundException->getMessage(), Response::HTTP_NOT_FOUND, json: true);
        }

        return new JsonResponse($this->serialize($output), Response::HTTP_OK);
    }

    /**
     * @return array<string, ?scalar>
     */
    private function serialize(RetrieveAStation\Output $output): array
    {
        return [
            'id' => $output->station->getId(),
            'name' => $output->station->getName(),
            'address' => $output->station->getAddress(),
            'latitude' => $output->station->getPosition()->getLatitude(),
            'longitude' => $output->station->getPosition()->getLongitude(),
            'capacity' => $output->station->getCapacity(),
            'cityId' => $output->station->getCity()->getId(),
            'state' => $output->station->getState()->name
        ];
    }
}