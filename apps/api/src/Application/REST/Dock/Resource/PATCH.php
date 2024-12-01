<?php

declare(strict_types=1);

namespace App\Application\REST\Dock\Resource;

use App\Application\MessageBus;
use App\Application\Validator;
use App\Domain\Data\Enum;
use App\Domain\Exception\DockNotFoundException;
use App\Domain\UseCase\BackOffice\ModifyADockState;
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
            description: 'Update a dock state',
            required: true, 
            content: new OA\MediaType(
                mediaType: "application/json", 
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "state", description: "Current state of dock", type: 'string')
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
             * @var ModifyADockState\Output $output
             */
            $output = $this->messageBus->handle(
                new ModifyADockState\Input($id, Enum\State::from($data['state'])
                )
            );

            return new JsonResponse($this->serialize($output), Response::HTTP_OK);
        } catch (DockNotFoundException $cityNotFoundException) {
            return new JsonResponse($cityNotFoundException->getMessage(), Response::HTTP_NOT_FOUND, json: true);
        }
    }

    /**
     * @return array<string, ?scalar>
     */
    private function serialize(ModifyADockState\Output $output): array
    {
        return [
            'id' => $output->dock->getId(),
            'station' => $output->dock->getStation()->getId(),
            'dockNumber' => $output->dock->getDockNumber(),
            'state' => $output->dock->getState()->value
        ];
    }
}