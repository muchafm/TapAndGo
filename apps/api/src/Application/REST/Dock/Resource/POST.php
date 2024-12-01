<?php

declare(strict_types=1);

namespace App\Application\REST\Dock\Resource;

use App\Application\MessageBus;
use App\Domain\Data\Enum;
use App\Domain\Exception\StationNotFoundException;
use App\Domain\UseCase\BackOffice\AddADock;
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
            description: 'Add a new dock',
            required: true, 
            content: new OA\MediaType(
                mediaType: "application/json", 
                schema: new OA\Schema(
                    required: ['stationId', 'dockNumber', 'state'],
                    properties: [
                        new OA\Property(property: 'stationId', description: 'Id of the station to which the dock is attach to', type: 'string'),
                        new OA\Property(property: "dockNumber", description: "Number of the dock"),
                        new OA\Property(property: "state", description: "Current state of dock", type: 'string')
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
             * @var AddADock\Output $output
             */
            $output = $this->messageBus->handle(
                new AddADock\Input(
                    $data['stationId'],
                    $data['dockNumber'],
                    Enum\State::from($data['state'])
                )
            );

        
            return new JsonResponse($this->serialize($output), Response::HTTP_CREATED);
        } catch (StationNotFoundException $stationNotFoundException) {
            return new JsonResponse($stationNotFoundException->getMessage(), json: true);
        }
    }

    /**
     * @return array<string, ?scalar>
     */
    private function serialize(AddADock\Output $output): array
    {
        return [
            'id' => $output->dock->getId(),
            'station' => $output->dock->getStation()->getId(),
            'dockNumber' => $output->dock->getDockNumber(),
            'state' => $output->dock->getState()->value
        ];
    }
}