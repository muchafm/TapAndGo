<?php

declare(strict_types=1);

namespace App\Application\REST\City\Resource;

use App\Application\MessageBus;
use App\Domain\UseCase\AddACity;
use JsonException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final readonly class POST
{
    public function __construct(private MessageBus $messageBus)
    {

    }

    #[Route('/api/cities', methods:'POST')]
    #[OA\RequestBody(
            description: 'Add a new city',
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json", 
                schema: new OA\Schema(
                    required: ['name', 'latitude', 'longitude', 'isActive' ],
                    properties: [
                        new OA\Property(property: 'name', description: 'Name of the city', type: 'string'),
                        new OA\Property(property: 'latitude', description: 'Latitude point', type: 'float'),
                        new OA\Property(property: 'longitude', description: 'Longitude', type: 'float'),
                        new OA\Property(property: 'isActive', description: 'Status of activation of the city', type: 'boolean')
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

        /**
         * @var AddACity\Output $output
         */
        $output = $this->messageBus->handle(new AddACity\Input($data['name'], $data['latitude'], $data['longitude'], $data['isActive']));

        return new JsonResponse($this->serialize($output), Response::HTTP_CREATED);
    }

    /**
     * @return array<string, array<int, string>|scalar>
     */
    private function serialize(AddACity\Output $output): array
    {
        return [
            'id' => $output->city->id,
            'name' => $output->city->name,
            'latitude' => $output->city->position->latitude,
            'longitude' => $output->city->position->longitude,
            'isActive' => $output->city->isActive
        ];
    }
}