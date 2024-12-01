<?php

declare(strict_types=1);

namespace App\Application\REST\Station\Resource;

use App\Application\MessageBus;
use App\Domain\Exception\CityNotFoundException;
use App\Domain\UseCase\BackOffice\ModifyAStation;
use App\Domain\Exception\StationNotFoundException;
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
            description: 'Update a station',
            required: true, 
            content: new OA\MediaType(
                mediaType: "application/json", 
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'name', description: 'Name of the station', type: 'string'),
                        new OA\Property(property: 'address', description: 'Address of the station', type: 'string'),
                        new OA\Property(property: 'latitude', description: 'Latitude point', type: 'float'),
                        new OA\Property(property: 'longitude', description: 'Longitude', type: 'float'),
                        new OA\Property(property: 'capacity', description: 'Total docks of the station', type: 'integer'),
                        new OA\Property(property: "state", description: "Current state of station", type: 'string')
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
             * @var ModifyAStation\Output $output
             */
            $output = $this->messageBus->handle(
                new ModifyAStation\Input(
                    $id,
                    $data['name'] ?? null, 
                    $data['address'] ?? null, 
                    $data['latitude'] ?? null, 
                    $data['longitude'] ?? null, 
                    $data['capacity'] ?? null
                )
            );

            return new JsonResponse($this->serialize($output), Response::HTTP_OK);
        } catch (StationNotFoundException $stationNotFoundException) {
            return new JsonResponse($stationNotFoundException->getMessage(), Response::HTTP_NOT_FOUND, json: true);
        } catch (CityNotFoundException $cityNotFoundException) {
            return new JsonResponse($cityNotFoundException->getMessage(), Response::HTTP_NOT_FOUND, json: true);
        }
    }

    /**
     * @return array<string, ?scalar>
     */
    private function serialize(ModifyAStation\Output $output): array
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