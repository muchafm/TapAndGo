<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Messenger;

use App\Application;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageBus implements Application\MessageBus
{
    use HandleTrait {
        HandleTrait::handle as _handle;
    }

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function handle(object $object): object
    {
        return $this->_handle($object);
    }
}