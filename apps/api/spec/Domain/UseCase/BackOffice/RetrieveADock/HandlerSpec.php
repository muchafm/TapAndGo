<?php

namespace spec\App\Domain\UseCase\BackOffice\RetrieveADock;

use App\Domain\Data\Collection\Docks;
use App\Domain\Data\Enum\State;
use App\Domain\Data\Model\Dock;
use App\Domain\Exception\DockNotFoundException;
use App\Domain\UseCase\BackOffice\RetrieveADock\Handler;
use App\Domain\UseCase\BackOffice\RetrieveADock\Input;
use App\Domain\UseCase\BackOffice\RetrieveADock\Output;
use PhpSpec\ObjectBehavior;

class HandlerSpec extends ObjectBehavior
{
    public function let(Docks $docks) 
    {
        $this->beConstructedWith($docks);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    function it_retrieve_a_dock(Docks $docks, Dock $dock)
    {
        $docks->find('dock-id')->willReturn($dock);
        $output = $this->__invoke(new Input('dock-id'));

        $output->shouldHaveType(Output::class);
        $output->dock->getId('dock-id');
    }

    function it_throws_an_exception_when_dock_does_not_exists(Docks $docks)
    {
        $docks->find('dock-id')->willReturn(null);

        $this->shouldThrow(DockNotFoundException::class)->during('__invoke', [new Input('dock-id')]);
    }
}