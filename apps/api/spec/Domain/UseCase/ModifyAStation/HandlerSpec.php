<?php

namespace spec\App\Domain\UseCase\ModifyAStation;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Collection\Stations;
use App\Domain\Data\Model\Station;
use App\Domain\Exception\StationNotFoundException;
use App\Domain\UseCase\ModifyAStation\Handler;
use App\Domain\UseCase\ModifyAStation\Input;
use App\Domain\UseCase\ModifyAStation\Output;
use PhpSpec\ObjectBehavior;

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

    function it_modifies_the_available_bikes_of_the_station(
        Stations $stations, 
        Station $station
    ){
        $input = new Input('station-id', null, null, null, null, null, 10, null);

        $stations->find('station-id')->willReturn($station);
        $stations->persist($station)->shouldBeCalled();

        $output = $this->__invoke($input);

        $output->shouldHaveType(Output::class);
        $output->station->availableBikes->shouldBe(10);
    }

    function it_throws_an_exception_when_station_does_not_exists(
        Stations $stations
    ){
        $input = new Input('station-id', null, null, null, null, null, 10, null);

        $stations->find('station-id')->willReturn(null);

        $this->shouldThrow(StationNotFoundException::class)->during('__invoke', [$input]);

    }
}