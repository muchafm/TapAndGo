<?php

declare(strict_types=1);

namespace App\Application\REST\Station\Collection;

use App\Application\MessageBus;
use App\Domain\UseCase\BackOffice\RetrieveAllStations;
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
        description: 'Retrieve data of a collection of stations',
    )]
    public function __invoke(): JsonResponse
    {
        /**
         * @var RetrieveAllStations\Output $output
         */
        $output = $this->messageBus->handle(new RetrieveAllStations\Input());

        return new JsonResponse([
            ...$this->serialize($output)
        ], Response::HTTP_OK);
    }

    /**
     * @return iterable<array<string, mixed>>
     */
    private function serialize(RetrieveAllStations\Output $output): iterable
    {
        foreach ($output->stations as $station) {
            yield [
                'id' => $station->getId(),
                'name' => $station->getName(),
                'address' => $station->getAddress(),
                'latitude' => $station->getPosition()->getLatitude(),
                'longitude' => $station->getPosition()->getLongitude(),
                'capacity' => $station->getCapacity(),
                'cityId' => $station->getCity()->getId()
            ];
        }
    }
}