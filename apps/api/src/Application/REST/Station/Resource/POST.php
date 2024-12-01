<?php

declare(strict_types=1);

namespace App\Application\REST\Station\Resource;

use App\Application\MessageBus;
use App\Application\Validator;
use App\Domain\Exception\CityNotFoundException;
use App\Domain\UseCase\BackOffice\AddAStation;
use JsonException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class POST
{
    public function __construct(private MessageBus $messageBus, private Validator\Station $stationValidator)
    {

    }

    #[OA\RequestBody(
            description: 'Add a new station',
            required: true, 
            content: new OA\MediaType(
                mediaType: "application/json", 
                schema: new OA\Schema(
                    required: ['name', 'address', 'latitude', 'longitude', 'capacity', 'cityId', 'state'],
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
        )
    ]
    public function __invoke(Request $request): Response
    {
        try {
            $data = json_decode(
                $request->getContent(),
                true,
                flags: JSON_THROW_ON_ERROR,
            );

            $this->stationValidator->validatePostData($data);

        } catch (JsonException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST, json: true);
        }

        try {
            /**
             * @var AddAStation\Output $output
             */
            $output = $this->messageBus->handle(
                new AddAStation\Input(
                    $data['cityId'],
                    $data['name'], 
                    $data['address'], 
                    $data['latitude'], 
                    $data['longitude'], 
                    $data['capacity']
                )
            );

            return new JsonResponse($this->serialize($output), Response::HTTP_CREATED);
        } catch (CityNotFoundException $cityNotFoundException) {
            return new JsonResponse($cityNotFoundException->getMessage(), json: true);
        }
    }

    /**
     * @return array<string, ?scalar>
     */
    private function serialize(AddAStation\Output $output): array
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