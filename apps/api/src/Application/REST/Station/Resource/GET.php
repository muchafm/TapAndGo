<?php

declare(strict_types=1);

namespace App\Application\REST\Station\Resource;

use App\Application\MessageBus;
use App\Domain\UseCase\RetrieveAStation;
use App\Domain\Exception\StationNotFoundException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final readonly class GET
{
    public function __construct(private MessageBus $messageBus)
    {

    }

    #[Route('/api/stations/{id}', methods:'GET')]
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
                    new OA\Property(property: 'totalStands', description: 'Total stands of the station', type: 'integer'),
                    new OA\Property(property: 'availableBikes', description: 'Number of bikes availables at the station', type: 'integer'),
                    new OA\Property(property: 'city', description: 'Id of th city where the station is located', type: 'string')
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
            throw new NotFoundHttpException(
                previous: $stationNotFoundException
            );
        }

        return new JsonResponse($this->serialize($output), Response::HTTP_OK);
    }

    /**
     * @return array<string, ?scalar>
     */
    private function serialize(RetrieveAStation\Output $output): array
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