<?php

declare(strict_types=1);

namespace App\Application\REST\City\Collection;

use App\Application\MessageBus;
use App\Domain\Data\Model\Station;
use App\Domain\UseCase\RetrieveAllCities;
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
        description: 'Retrieve data of a collection of cities',
    )]
    public function __invoke(): JsonResponse
    {
        /**
         * @var RetrieveAllCities\Output $output
         */
        $output = $this->messageBus->handle(new RetrieveAllCities\Input());

        return new JsonResponse([
            ...$this->serialize($output)
        ], Response::HTTP_OK);
    }

    /**
     * @return iterable<array<string, mixed>>
     */
    private function serialize(RetrieveAllCities\Output $output): iterable
    {
        foreach ($output->cities as $city) {
            yield [
                'id' => $city->getId(),
                'name' => $city->getName(),
                'latitude' => $city->getPosition()->getLatitude(),
                'longitude' => $city->getPosition()->getLongitude(),
                'isActive' => $city->isActive(),
                'stationIds' => 0 === \count($city->getStations()->toArray()) ? null : array_map(fn (Station $station): string => $station->getId(), $city->getStations()->toArray())
            ];
        }
    }
}