<?php

declare(strict_types=1);

namespace App\Application\REST\City\Resource;

use App\Application\MessageBus;
use App\Domain\Data\Model\Station;
use App\Domain\UseCase\BackOffice\ModifyACity;
use App\Domain\Exception\CityNotFoundException;
use JsonException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class PATCH
{
    public function __construct(private MessageBus $messageBus)
    {

    }

    #[OA\RequestBody(
            description: 'Update a city',
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json", 
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'name', description: 'Name of the city', type: 'string'),
                        new OA\Property(property: 'latitude', description: 'Latitude point', type: 'float'),
                        new OA\Property(property: 'longitude', description: 'Longitude', type: 'float'),
                        new OA\Property(property: 'isActive', description: 'Status of activation of the city', type: 'boolean'),
                        new OA\Property(property: 'stationIds', type: 'array', description: 'Ids of station to be attached to the city', items: new OA\Items(type: 'string'))
                    ]
                ),
            )
        )
    ]
    public function __invoke(string $id, Request $request): Response
    {
        try {
            $data = json_decode(
                $request->getContent(),
                true,
                flags: JSON_THROW_ON_ERROR,
            );

        } catch (JsonException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST, json: true);
        }

        try {
            /**
             * @var ModifyACity\Output $output
             */
            $output = $this->messageBus->handle(
                new ModifyACity\Input(
                    $id, 
                    $data['name'] ?? null, 
                    $data['latitude'] ?? null, 
                    $data['longitude'] ?? null, 
                    $data['isActive'] ?? null, 
                    $data['stationIds'] ?? null
                )
            );

            return new JsonResponse($this->serialize($output), Response::HTTP_OK);
        } catch (CityNotFoundException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_NOT_FOUND, json: true);
        }
    }

    /**
     * @return array<string, array<int, string>|scalar>
     */
    private function serialize(ModifyACity\Output $output): array
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