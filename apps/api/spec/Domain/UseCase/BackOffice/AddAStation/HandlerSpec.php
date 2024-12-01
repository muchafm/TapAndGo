<?php

namespace spec\App\Domain\UseCase\BackOffice\AddAStation;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Collection\Stations;
use App\Domain\Data\Enum\State;
use App\Domain\Data\Model\City;
use App\Domain\Data\Model\Station;
use App\Domain\Exception\CityNotFoundException;
use App\Domain\UseCase\BackOffice\AddAStation\Handler;
use App\Domain\UseCase\BackOffice\AddAStation\Input;
use App\Domain\UseCase\BackOffice\AddAStation\Output;
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
        $input = new Input('city-id', 'CATHEDRALE', 'Rue Flatters', 49.898124385191046, 2.299395003901743, 30);
        $cities->find('city-id')->willReturn($city);
        $city->isActive()->willReturn(true);

        $stations->add(Argument::type(Station::class))->shouldBeCalled();

        $output = $this->__invoke($input);

        $output->shouldHaveType(Output::class);
        $output->station->getName()->shouldBe('CATHEDRALE');
        $output->station->getAddress()->shouldBe('Rue Flatters');
        $output->station->getPosition()->getLatitude()->shouldBe(49.898124385191046);
        $output->station->getPosition()->getLongitude()->shouldBe(2.299395003901743);
        $output->station->getCapacity()->shouldBe(30);
        $output->station->getState()->shouldBe(State::ENABLED);
    }

    public function it_throws_an_exception_when_the_city_does_not_exists(
        Cities $cities
    ){
        $input = new Input('city-id', 'CATHEDRALE', 'Rue Flatters', 49.898124385191046, 2.299395003901743, 30);
        $cities->find('city-id')->willReturn(null);

        $this->shouldThrow(CityNotFoundException::class)->during('__invoke', [$input]);
    }
}