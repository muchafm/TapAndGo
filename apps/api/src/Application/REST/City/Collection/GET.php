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
                'id' => $city->id,
                'name' => $city->name,
                'latitude' => $city->position->latitude,
                'longitude' => $city->position->longitude,
                'isActive' => $city->isActive,
                'stationIds' => 0 === \count($city->stations->toArray()) ? null : array_map(fn (Station $station): string => $station->id, $city->stations->toArray())
            ];
        }
    }
}