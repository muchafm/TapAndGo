<?php

namespace spec\App\Domain\UseCase\BackOffice\ModifyADockState;

use App\Domain\Data\Collection\Docks;
use App\Domain\Data\Enum\State;
use App\Domain\Data\Model\Dock;
use App\Domain\Exception\DockNotFoundException;
use App\Domain\UseCase\BackOffice\ModifyADockState\Handler;
use App\Domain\UseCase\BackOffice\ModifyADockState\Input;
use App\Domain\UseCase\BackOffice\ModifyADockState\Output;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerSpec extends ObjectBehavior
{
    function let(Docks $docks) 
    {
        $this->beConstructedWith($docks);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    function it_modifies_the_state_of_a_dock(Docks $docks, Dock $dock)
    {
        $docks->find('dock-id')->willReturn($dock);

        $dock->update(State::UNDER_REPAIR)->shouldBeCalled();

        $docks->persist(Argument::that(Dock::class))->shouldBeCalled();

        $output = $this->__invoke(new Input('dock-id', State::UNDER_REPAIR));

        $output->shouldHaveType(Output::class);
        $output->dock->getState()->shouldBe(State::UNDER_REPAIR);
    }

    function it_throws_an_exception_when_dock_does_not_exists(Docks $docks)
    {
        $docks->find('dock-id')->willReturn(null);

        $this->shouldThrow(DockNotFoundException::class)->during('__invoke', [new Input('dock-id')]);
    }
}