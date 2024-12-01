<?php

namespace spec\App\Domain\UseCase\BackOffice\AddADock;

use App\Domain\Data\Collection\Docks;
use App\Domain\Data\Collection\Stations;
use App\Domain\Data\Enum\State;
use App\Domain\Data\Model\Dock;
use App\Domain\Data\Model\Station;
use App\Domain\Exception\StationDockCapacityFullException;
use App\Domain\Exception\StationNotFoundException;
use App\Domain\UseCase\BackOffice\AddADock\Handler;
use App\Domain\UseCase\BackOffice\AddADock\Input;
use App\Domain\UseCase\BackOffice\AddADock\Output;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerSpec extends ObjectBehavior
{
    public function let(
        Stations $stations,
        Docks $docks,
    ) {
        $this->beConstructedWith($stations, $docks);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    public function it_adds_a_new_dock(Stations $stations, Docks $docks, Station $station)
    {
        $stations->find("station-id")->willReturn($station);

        $station->getDocks()->willReturn(new ArrayCollection([]));
        $station->getCapacity()->willReturn(2);

        $docks->add(Argument::type(Dock::class))->shouldBeCalled();

        $station->addDock(Argument::type(Dock::class))->shouldBeCalled();

        $output = $this->__invoke(new Input("station-id", 32, State::ENABLED));

        $output->shouldHaveType(Output::class);
        $output->dock->getDockNumber()->shouldBe(32);
        $output->dock->getState()->shouldBe(State::ENABLED);
    }

    public function it_throws_an_exception_when_the_station_dock_capacity_is_full(Stations $stations, Docks $docks, Station $station)
    {
        $input = new Input("station-id", 32, State::ENABLED);

        $stations->find("station-id")->willReturn($station);

        $station->getDocks()->willReturn(new ArrayCollection([Argument::type(Station::class), Argument::type(Station::class)]));
        $station->getCapacity()->willReturn(2);
        $station->getId()->willReturn('station-id');

        $stations->add(Argument::type(Dock::class))->shouldNotBeCalled();

        $this->shouldThrow(StationDockCapacityFullException::class)->during('__invoke', [$input]);
    }

    public function it_throws_an_exception_when_the_station_does_not_exists(Stations $stations)
    {
        $input = new Input("station-id", 32, State::ENABLED);

        $stations->find("station-id")->willReturn(null);

        $stations->add(Argument::type(Dock::class))->shouldNotBeCalled();

        $this->shouldThrow(StationNotFoundException::class)->during('__invoke', [$input]);
    }
}