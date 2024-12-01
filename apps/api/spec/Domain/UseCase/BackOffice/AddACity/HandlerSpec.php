<?php

namespace spec\App\Domain\UseCase\BackOffice\AddACity;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Model\City;
use App\Domain\Exception\CityAlreadyExistsException;
use App\Domain\UseCase\BackOffice\AddACity\Handler;
use App\Domain\UseCase\BackOffice\AddACity\Input;
use App\Domain\UseCase\BackOffice\AddACity\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerSpec extends ObjectBehavior
{
    public function let(
        Cities $cities,
    ) {
        $this->beConstructedWith($cities);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    public function it_adds_a_new_city(Cities $cities)
    {
        $input = new Input('Lyon', 49.898124385191046, 2.299395003901743, true);

        $cities->findByName('Lyon')->willReturn(null);

        $cities->add(Argument::type(City::class))->shouldBeCalled();

        $output = $this->__invoke($input);

        $output->shouldHaveType(Output::class);
        $output->city->getName()->shouldBe('Lyon');
        $output->city->getPosition()->getLatitude()->shouldBe(49.898124385191046);
        $output->city->getPosition()->getLongitude()->shouldBe(2.299395003901743);
        $output->city->isActive()->shouldBe(true);
    }

    public function it_throws_an_exception_when_the_name_of_the_city_already_exists(Cities $cities, City $city)
    {
        $input = new Input('Lyon', 49.898124385191046, 2.299395003901743, true);

        $cities->findByName('Lyon')->willReturn($city);

        $cities->add(Argument::type(City::class))->shouldNotBeCalled();

        $this->shouldThrow(CityAlreadyExistsException::class)->during('__invoke', [$input]);
    }
}