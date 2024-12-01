<?php

declare(strict_types=1);

namespace App\Application\REST\City\Resource;

use App\Application\MessageBus;
use App\Domain\Data\Model\Station;
use App\Domain\UseCase\BackOffice\RetrieveACity;
use App\Domain\Exception\CityNotFoundException;
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
        description: 'Retrieve data of a city',
        content: new OA\MediaType(
            mediaType: "application/json", 
            schema: new OA\Schema(
                required: ['name', 'latitude', 'longitude', 'isActive' ],
                properties: [
                    new OA\Property(property: 'name', description: 'Name of the city', type: 'string'),
                    new OA\Property(property: 'latitude', description: 'Latitude point', type: 'float'),
                    new OA\Property(property: 'longitude', description: 'Longitude', type: 'float'),
                    new OA\Property(property: 'isActive', description: 'Status of activation of the city', type: 'boolean'),
                ]
            ),
        )
    )]
    public function __invoke(string $id): Response
    {
        try {
            /**
             * @var RetrieveACity\Output $output
             */
            $output = $this->messageBus->handle(new RetrieveACity\Input($id));
        } catch (CityNotFoundException $cityNotFoundException) {
            return new JsonResponse($cityNotFoundException->getMessage(), Response::HTTP_NOT_FOUND, json: true);
        }

        return new JsonResponse($this->serialize($output), Response::HTTP_OK);
    }

    /**
     * @return array<string, array<int, string>|bool|float|string|null>
     */
    private function serialize(RetrieveACity\Output $output): array
    {
        return [
            'id' => $output->city->getId(),
            'name' => $output->city->getName(),
            'latitude' => $output->city->getPosition()->getLatitude(),
            'longitude' => $output->city->getPosition()->getLongitude(),
            'isActive' => $output->city->isActive(),
            'stationIds' => 0 === \count($output->city->getStations()->toArray()) ? null : array_map(fn (Station $station): string => $station->getId(), $output->city->getStations()->toArray())
        ];
    }
}