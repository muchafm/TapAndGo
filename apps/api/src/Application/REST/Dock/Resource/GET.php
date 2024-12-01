<?php

declare(strict_types=1);

namespace App\Application\REST\Dock\Resource;

use App\Application\MessageBus;
use App\Domain\UseCase\BackOffice\RetrieveADock;
use App\Domain\Exception\DockNotFoundException;
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
        description: 'Retrieve data of a dock',
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
    )]
    public function __invoke(string $id): Response
    {
        try {
            /**
             * @var RetrieveADock\Output $output
             */
            $output = $this->messageBus->handle(new RetrieveADock\Input($id));
        } catch (DockNotFoundException $dockNotFoundException) {
            return new JsonResponse($dockNotFoundException->getMessage(), Response::HTTP_NOT_FOUND, json: true);
        }

        return new JsonResponse($this->serialize($output), Response::HTTP_OK);
    }

    /**
     * @return array<string, array<int, string>|bool|float|string|null>
     */
    private function serialize(RetrieveADock\Output $output): array
    {
        return [
            'id' => $output->dock->getId(),
            'station' => $output->dock->getStation()->getId(),
            'dockNumber' => $output->dock->getDockNumber(),
            'state' => $output->dock->getState()->value
        ];
    }
}