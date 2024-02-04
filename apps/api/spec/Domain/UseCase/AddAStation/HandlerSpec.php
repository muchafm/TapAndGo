<?php

namespace spec\App\Domain\UseCase\AddAStation;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Collection\Stations;
use App\Domain\Data\Model\City;
use App\Domain\Data\Model\Station;
use App\Domain\Exception\CityNotFoundException;
use App\Domain\UseCase\AddAStation\Handler;
use App\Domain\UseCase\AddAStation\Input;
use App\Domain\UseCase\AddAStation\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerSpec extends ObjectBehavior
{
    public function let(
        Stations $stations,
        Cities $cities
    ) {
        $this->beConstructedWith($stations, $cities);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    public function it_adds_a_new_station(
        Cities $cities, 
        Stations $stations,
        City $city
    ){
        $input = new Input('09- CATHEDRALE', 'Rue Flatters', 49.898124385191046, 2.299395003901743, 30, 30, 'city-id');
        $cities->find('city-id')->willReturn($city);

        $stations->add(Argument::type(Station::class))->shouldBeCalled();

        $output = $this->__invoke($input);

        $output->shouldHaveType(Output::class);
        $output->station->name->shouldBe('09- CATHEDRALE');
        $output->station->address->shouldBe('Rue Flatters');
        $output->station->position->latitude->shouldBe(49.898124385191046);
        $output->station->position->longitude->shouldBe(2.299395003901743);
        $output->station->totalStands->shouldBe(30);
        $output->station->availableBikes->shouldBe(30);
    }

    public function it_throws_an_exception_when_the_city_does_not_exists(
        Cities $cities
    ){
        $input = new Input('09- CATHEDRALE', 'Rue Flatters', 49.898124385191046, 2.299395003901743, 30, 30, 'city-id');
        $cities->find('city-id')->willReturn(null);

        $this->shouldThrow(CityNotFoundException::class)->during('__invoke', [$input]);
    }
}