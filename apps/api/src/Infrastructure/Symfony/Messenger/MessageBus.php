<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Messenger;

use App\Application;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessageBus implements Application\MessageBus
{
    use HandleTrait {
        HandleTrait::handle as _handle;
    }

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function handle(object $message): object
    {
        try {
            $envelope = $this->messageBus->dispatch($message);
        } catch (HandlerFailedException $handlerFailedException) {
            foreach ($handlerFailedException->getWrappedExceptions() as $wrappedException) {
                throw $wrappedException;
            }

            throw new \Exception(
                'Unable to rethrow handler failure wrapped exception.',
                0,
                $handlerFailedException,
            );
        }

        /**
         * @var HandledStamp[]
         */
        $handledStamps = $envelope->all(HandledStamp::class);

        if ([] === $handledStamps) {
            throw new \LogicException(sprintf('Message of type "%s" was handled zero times. Exactly one handler is expected when using "%s::%s()".', get_debug_type($envelope->getMessage()), static::class, __FUNCTION__));
        }

        if (\count($handledStamps) > 1) {
            $handlers = implode(', ', array_map(fn (HandledStamp $stamp): string => sprintf('"%s"', $stamp->getHandlerName()), $handledStamps));

            throw new \LogicException(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected when using "%s::%s()", got %d: %s.', get_debug_type($envelope->getMessage()), static::class, __FUNCTION__, \count($handledStamps), $handlers));
        }

        $result = $handledStamps[0]->getResult();

        if (false === \is_object($result)) {
            throw new \LogicException(sprintf('Message of type "%s" was handled but result is not an object, %s returned.', get_debug_type($envelope->getMessage()), get_debug_type($result)));
        }

        return $result;
    }
}