<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
 *  
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Support\Answer\Messenger\AutoReply;

use BaksDev\Ozon\Support\Type\OzonReviewProfileType;
use BaksDev\Support\Answer\Service\AutoMessagesHello;
use BaksDev\Support\Entity\Event\SupportEvent;
use BaksDev\Support\Entity\Support;
use BaksDev\Support\Messenger\SupportMessage;
use BaksDev\Support\Repository\SupportCurrentEvent\CurrentSupportEventRepository;
use BaksDev\Support\Type\Status\SupportStatus;
use BaksDev\Support\Type\Status\SupportStatus\Collection\SupportStatusClose;
use BaksDev\Support\Type\Status\SupportStatus\Collection\SupportStatusOpen;
use BaksDev\Support\UseCase\Admin\New\Invariable\SupportInvariableDTO;
use BaksDev\Support\UseCase\Admin\New\Message\SupportMessageDTO;
use BaksDev\Support\UseCase\Admin\New\SupportDTO;
use BaksDev\Support\UseCase\Admin\New\SupportHandler;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Random\Randomizer;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/** Пробуем автоматически ответить на однотипный вопрос о наличии */
#[AsMessageHandler]
final readonly class AutoReplyMessageDispatcher
{

    public function __construct(
        #[Target('ozonSupportLogger')] private LoggerInterface $logger,
        private SupportHandler $SupportHandler,
        private CurrentSupportEventRepository $CurrentSupportEventRepository,
    ) {}

    private function checkPhrases($text): bool
    {
        $text = mb_strtolower($text);

        $phrases = [
            ['когда', 'наличи'],

            ['ещё', 'будут', 'наличи'],
            ['еще', 'будут', 'наличи'],
            ['ещё', 'будет', 'наличи'],
            ['еще', 'будет', 'наличи'],

            ['появится', 'наличи'],
            ['появиться', 'наличи'],
            ['появятся', 'наличи'],
            ['появяться', 'наличи'],

            ['будут', 'в продаж'],
            ['будут', 'в продаж'],
            ['будет', 'в продаж'],
            ['будет', 'в продаж'],
            ['появятся', 'в продаж'],
            ['появяться', 'в продаж'],

            ['ожидает', 'поступление'],
            ['когда', 'поступление'],

        ];

        foreach($phrases as $phrase)
        {

            $allFound = true;

            foreach($phrase as $word)
            {

                if(str_contains($text, $word) === false)
                {
                    $allFound = false;
                    break;
                }
            }

            if($allFound)
            {
                return true;
            }
        }

        return false;

    }

    /**
     * Пробуем автоматически ответить на однотипный вопрос о наличии
     */
    public function __invoke(SupportMessage $message): void
    {
        $CurrentSupportEvent = $this->CurrentSupportEventRepository
            ->forSupport($message->getId())
            ->find();

        if(false === ($CurrentSupportEvent instanceof SupportEvent))
        {
            $this->logger->critical(
                'Ошибка получения события по идентификатору :'.$message->getId(),
                [self::class.':'.__LINE__],
            );

            return;
        }


        // гидрируем DTO активным событием
        /** @var SupportDTO $SupportDTO */
        $SupportDTO = $CurrentSupportEvent->getDto(SupportDTO::class);

        // обрабатываем только ОТКРЫТЫЕ тикеты
        if(false === ($SupportDTO->getStatus()->getSupportStatus() instanceof SupportStatusOpen))
        {
            return;
        }

        $SupportInvariableDTO = $SupportDTO->getInvariable();

        if(false === ($SupportInvariableDTO instanceof SupportInvariableDTO))
        {
            return;
        }

        /**
         * Если в переписке уже имеется ответ - не отвечаем
         */

        $isOut = $SupportDTO->getMessages()->filter(function(SupportMessageDTO $element) {
            return $element->getOut();
        });

        if(false === $isOut->isEmpty())
        {
            return;
        }

        /**
         * Последнее сообщение в открытом чате = сообщение от клиента
         *
         * @var SupportMessageDTO $lastMessage
         */
        $lastMessage = $SupportDTO->getMessages()->last();

        // проверяем наличие внешнего ID - для наших ответов его быть не должно
        if(null !== $lastMessage->getExternal())
        {
            return;
        }

        $isFound = $this->checkPhrases($lastMessage);

        if(false === $isFound)
        {
            return;
        }

        /** Текст приветствия */
        $answerMessage = new AutoMessagesHello()->hello();

        $answer = [
            'Наш магазин постоянно пополняется разными моделями шин от прямых поставщиков. Вы можете добавить товар в Избранное и отследить его поступление в необходимом для вас количестве. Стоимость продукции уточняйте на момент поступления в продажу.',
            'Мы постоянно расширяем ассортимент за счет прямых поставок. Чтобы не пропустить нужную модель, просто добавьте её в «Избранное» — мы сообщим вам, когда шины появятся в нужном количестве. Актуальные цены будут известны непосредственно в момент поступления товара на склад.',
            'Ассортимент нашего магазина регулярно пополняется напрямую от производителей. Отслеживайте поступление интересующих моделей через функцию «Избранное». Стоимость товара фиксируется на дату его поступления в продажу.',
            'Мы постоянно пополняем ассортимент через прямые поставки. Просто сохраните нужную модель в «Избранное» — это ваш надежный способ отследить её появление. Стоимость товара уточняйте на момент поступления в продажу. ',
            'Наши поставки от производителей приходят регулярно, и чтобы Вы были в курсе именно вашего варианта, просто добавьте его в «Избранное». Актуальную цену Вы узнаете сразу при поступлении товара к нам.',
            'Мы постоянно получаем новые шины от поставщиков! Чтобы получить персональное уведомление, просто нажмите «Добавить в Избранное» под понравившейся моделью. Как только она поступит к нам в нужном количестве, мы сразу с вами свяжемся и сообщим актуальную на тот момент стоимость.',
        ];

        $key = new Randomizer()->getInt(0, count($answer) - 1);

        $answerMessage .= PHP_EOL.$answer[$key];


        /** Отправляем сообщение клиенту */

        $supportMessageDTO = new SupportMessageDTO()
            ->setName('auto (Bot Seller)')
            ->setMessage($answerMessage)
            ->setDate(new DateTimeImmutable('now'))
            ->setOutMessage();

        $SupportDTO
            ->setStatus(new SupportStatus(SupportStatusClose::PARAM)) // закрываем чат
            ->addMessage($supportMessageDTO) // добавляем сформированное сообщение
        ;

        // сохраняем ответ
        $Support = $this->SupportHandler->handle($SupportDTO);

        if(false === ($Support instanceof Support))
        {
            $this->logger->critical(
                'ozon-support: Ошибка при отправке автоматического ответа',
                [$Support, self::class.':'.__LINE__],
            );
        }
    }
}
