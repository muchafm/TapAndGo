<?php

namespace spec\App\Domain\UseCase\AddACity;

use App\Domain\Data\Collection\Cities;
use App\Domain\Data\Model\City;
use App\Domain\UseCase\AddACity\Handler;
use App\Domain\UseCase\AddACity\Input;
use App\Domain\UseCase\AddACity\Output;
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

        $cities->add(Argument::type(City::class))->shouldBeCalled();

        $output = $this->__invoke($input);

        $output->shouldHaveType(Output::class);
        $output->city->name->shouldBe('Lyon');
        $output->city->position->latitude->shouldBe(49.898124385191046);
        $output->city->position->longitude->shouldBe(2.299395003901743);
        $output->city->isActive->shouldBe(true);
    }
}