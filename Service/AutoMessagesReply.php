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

namespace BaksDev\Support\Answer\Service;

use Random\Randomizer;

final class AutoMessagesReply
{

    /**
     * Текст приветствия
     */

    private function hello(): string
    {
        $hello = [
            'Благодарим Вас за то, что выбрали наш магазин для покупки!',
            'Спасибо, что выбрали наш магазин для покупки!',
            'Спасибо, что выбрали именно нас!',
            'Благодарим Вас за то, что остановили свой выбор на нашем магазине!',
            'Спасибо, что сделали выбор в пользу нашего магазина!',
        ];

        $key = new Randomizer()->getInt(0, count($hello) - 1);

        return $hello[$key];
    }

    /**
     * Прощальный текст
     */

    private function goodbye(): string
    {

        $goodbye = [
            'Ждем Вас в нашем магазине вновь!',
            'Будем рады видеть Вас снова!',
            'Всего хорошего! Ждем Вас в следующий раз!',
            'Нам будет приятно Вас снова увидеть!',
            'Всегда рады Вам! Увидимся в следующий раз!',
            'Спасибо за Ваше время! Ждем Вас снова!',
            'Мы ценим Ваше мнение и ждем Вас снова!',
            'Мы будем рады Вашему возвращению!',
        ];

        $key = new Randomizer()->getInt(0, count($goodbye) - 1);

        return $goodbye[$key];
    }

    public function high(): string
    {
        $answerMessage[] = 'Мы ценим Ваше доверие и всегда стремимся предоставить лучший сервис и продукт высокого качества.';
        $answerMessage[] = 'Мы стремимся предоставить только лучшее, и Ваша покупка подтверждает это.';
        $answerMessage[] = 'Мы ценим Вашу поддержку и уверены, что Вы будете довольны своим приобретением.';
        $answerMessage[] = 'Мы рады, что Вы стали нашим клиентом, и надеемся, что Ваш опыт с нашей продукцией будет исключительно положительным.';
        $answerMessage[] = 'Мы уверены, что наша продукция оправдает Ваши ожидания.';
        $answerMessage[] = 'Мы уверены, что Вы будете довольны качеством нашей продукции.';
        $answerMessage[] = 'Для нас важно, чтобы каждый клиент оставался доволен, поэтому мы постоянно работаем над улучшением качества сервиса.';
        $answerMessage[] = 'Мы ценим каждого клиента и стремимся, чтобы наш сервис становился только лучше благодаря таким отзывам.';

        $key = new Randomizer()->getInt(0, count($answerMessage) - 1);
        $answerMessage = $answerMessage[$key];

        return $this->hello().PHP_EOL.$answerMessage.PHP_EOL.$this->goodbye();
    }

    public function avg(): string
    {
        $answerMessage[] = 'Мы рады узнать, что в целом Вы остались довольны покупкой.'; //
        $answerMessage[] = 'Рады, что в целом Вы остались довольны. Ваши замечания помогут нам стать лучше.';
        $answerMessage[] = 'Мы работаем над улучшениями и надеемся, что в следующий раз Вы поставите 5 звезд.';
        $answerMessage[] = 'Рады, что в целом Вам понравилось и Вы остались довольны.';
        $answerMessage[] = 'Мы рады, что в целом Вы остались довольны, и всегда готовы к улучшениям.';


        $key = new Randomizer()->getInt(0, count($answerMessage) - 1);
        $answerMessage = $answerMessage[$key];

        return $this->hello().PHP_EOL.$answerMessage.PHP_EOL.$this->goodbye();
    }

    public function low(): string
    {
        $answerMessage[] = 'Извините за возникшие неудобства. Нам жаль, что Вы остались недовольны.';
        $answerMessage[] = 'Нам важно Ваше мнение! Мы надеемся, что Вы дадите нам еще один шанс. Приносим искренние извинения за возможные неудобства.';
        $answerMessage[] = 'Мы надеемся, что Вы позволите восстановить Ваше доверие к нам. Приносим искренние извинения за возможные неудобства.';
        $answerMessage[] = 'Нам жаль, что Вы остались недовольны. Извините за возникшие неудобства. Надеемся, Вы вернетесь, чтобы увидеть наши изменения.';
        $answerMessage[] = 'Мы ценим Ваш отзыв и будем работать над улучшениями. Извините за возникшие неудобства.';
        $answerMessage[] = 'Приносим извинения за возможные неудобства. Мы надеемся на возможность сделать Ваш следующий визит лучше.';
        $answerMessage[] = 'Надеемся, Вы вернетесь, чтобы увидеть наши улучшения. Нам жаль, что Вы остались недовольны.';
        $answerMessage[] = 'Благодарим за обратную связь. Приносим свои искренние извинения за то, что не оправдали Ваших ожиданий.';

        $key = new Randomizer()->getInt(0, count($answerMessage) - 1);
        $answerMessage = $answerMessage[$key];

        return $this->hello().PHP_EOL.$answerMessage.PHP_EOL.$this->goodbye();

    }
}