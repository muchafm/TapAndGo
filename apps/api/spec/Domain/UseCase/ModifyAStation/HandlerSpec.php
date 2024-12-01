<?php

namespace spec\App\Domain\UseCase\ModifyAStation;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Collection\Stations;
use App\Domain\Data\Enum\State;
use App\Domain\Data\Model\City;
use App\Domain\Data\Model\Station;
use App\Domain\Data\ValueObject\Position;
use App\Domain\Exception\StationNotFoundException;
use App\Domain\UseCase\ModifyAStation\Handler;
use App\Domain\UseCase\ModifyAStation\Input;
use App\Domain\UseCase\ModifyAStation\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerSpec extends ObjectBehavior
{
    function let(
        Stations $stations,
        Cities $cities
    ) {
        $this->beConstructedWith($stations, $cities);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    function it_modifies_a_station(Stations $stations)
    {
        $station = $this->buildStation();
        $newName = "new-name";
        $input = new Input('station-id', $newName, null, null, null, null);
        $stations->find('station-id')->willReturn($station);
        $stations->persist($station)->shouldBeCalledTimes(1);

        $output = $this->__invoke($input);
        
        $output->shouldHaveType(Output::class);

        $output->station->shouldReturnAnInstanceOf(Station::class);
        $station = $output->station;
        $station->getName()->shouldBe($newName);
    }

    function it_throws_an_exception_when_station_does_not_exists(
        Stations $stations
    ){
        $input = new Input('station-id', null, null, null, null, 10);

        $stations->find('station-id')->willReturn(null);

        $this->shouldThrow(StationNotFoundException::class)->during('__invoke', [$input]);

    }

    private function buildCity(): City
    {
        return new City("city-name", new Position(45.345555, -0.555555), true);
    }

    private function buildStation(): Station
    {
        return new Station("name", new Position(45.345555, -0.555555), "address", State::ENABLED, 10, $this->buildCity());
    }
}