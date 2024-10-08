<?php

declare(strict_types=1);

namespace App\Application\REST\Station\Resource;

use App\Application\MessageBus;
use App\Domain\Exception\CityNotFoundException;
use App\Domain\UseCase\AddAStation;
use JsonException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class POST
{
    public function __construct(private MessageBus $messageBus)
    {

    }

    #[OA\RequestBody(
            description: 'Add a new station',
            required: true, 
            content: new OA\MediaType(
                mediaType: "application/json", 
                schema: new OA\Schema(
                    required: ['name', 'address', 'latitude', 'longitude', 'totalStands', 'availableBikes', 'city' ],
                    properties: [
                        new OA\Property(property: 'name', description: 'Name of the station', type: 'string'),
                        new OA\Property(property: 'address', description: 'Address of the station', type: 'string'),
                        new OA\Property(property: 'latitude', description: 'Latitude point', type: 'float'),
                        new OA\Property(property: 'longitude', description: 'Longitude', type: 'float'),
                        new OA\Property(property: 'totalStands', description: 'Total stands of the station', type: 'integer'),
                        new OA\Property(property: 'availableBikes', description: 'Number of bikes availables at the station', type: 'integer'),
                        new OA\Property(property: 'city', description: 'Id of th city where the station is located', type: 'string')
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

        } catch (JsonException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST, json: true);
        }

        try {
            /**
             * @var AddAStation\Output $output
             */
            $output = $this->messageBus->handle(
                new AddAStation\Input(
                    $data['name'], 
                    $data['address'], 
                    $data['latitude'], 
                    $data['longitude'], 
                    $data['totalStands'],
                    $data['availableBikes'],
                    $data['city']
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
            'id' => $output->station->id,
            'name' => $output->station->name,
            'address' => $output->station->address,
            'latitude' => $output->station->position->latitude,
            'longitude' => $output->station->position->longitude,
            'totalStands' => $output->station->totalStands,
            'availableBikes' => $output->station->availableBikes,
            'cityId' => $output->station->city->id
        ];
    }
}