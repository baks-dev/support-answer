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
            'Благодарим вас за то, что остановили свой выбор на нашем магазине!',
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
            'Ждем вас в нашем магазине вновь!',
            'Будем рады видеть вас снова!',
            'Всего хорошего! Ждем вас в следующий раз!',
            'Нам будет приятно вас снова увидеть!',
            'Всегда рады вам! Увидимся в следующий раз!',
            'Спасибо за ваше время! Ждем вас снова!',
            'Мы ценим ваше мнение и ждем вас снова!',
            'Мы будем рады вашему возвращению!',
        ];

        $key = new Randomizer()->getInt(0, count($goodbye) - 1);

        return $goodbye[$key];
    }

    public function high(): string
    {
        $answerMessage[] = 'Мы ценим Ваше доверие и всегда стремимся предоставить лучший сервис и продукт высокого качества.';
        $answerMessage[] = 'Мы стремимся предоставить только лучшее, и ваша покупка подтверждает это.';
        $answerMessage[] = 'Мы ценим вашу поддержку и уверены, что вы будете довольны своим приобретением.';
        $answerMessage[] = 'Мы рады, что вы стали нашим клиентом, и надеемся, что ваш опыт с нашей продукцией будет исключительно положительным.';
        $answerMessage[] = 'Мы уверены, что наша продукция оправдает ваши ожидания.';
        $answerMessage[] = 'Мы уверены, что вы будете довольны качеством нашей продукции.';

        $key = new Randomizer()->getInt(0, count($answerMessage) - 1);
        $answerMessage = $answerMessage[$key];

        return $this->hello().PHP_EOL.$answerMessage.PHP_EOL.$this->goodbye();
    }

    public function avg(): string
    {
        $answerMessage[] = 'Мы рады узнать, что в целом вы остались довольны покупкой.'; //
        $answerMessage[] = 'Рады, что в целом вы остались довольны. Ваши замечания помогут нам стать лучше.';
        $answerMessage[] = 'Мы работаем над улучшениями и надеемся, что в следующий раз вы поставите 5 звезд.';
        $answerMessage[] = 'Рады, что в целом вам понравилось и вы остались довольны.';
        $answerMessage[] = 'Мы рады, что в целом вы остались довольны, и всегда готовы к улучшениям.';


        $key = new Randomizer()->getInt(0, count($answerMessage) - 1);
        $answerMessage = $answerMessage[$key];

        return $this->hello().PHP_EOL.$answerMessage.PHP_EOL.$this->goodbye();
    }

    public function low(): string
    {
        $answerMessage[] = 'Извините за возникшие неудобства. Нам жаль, что вы остались недовольны.';
        $answerMessage[] = 'Приносим искренние извинения за возможные неудобства, которые могло вызвать у вас.';
        $answerMessage[] = 'Нам важно ваше мнение! Мы надеемся, что вы дадите нам еще один шанс. Приносим искренние извинения за возможные неудобства, которые могло вызвать у вас.';
        $answerMessage[] = 'Мы надеемся, что вы позволите восстановить ваше доверие к нам. Приносим искренние извинения за возможные неудобства, которые могло вызвать у вас.';
        $answerMessage[] = 'Нам жаль, что вы остались недовольны. Извините за возникшие неудобства. Надеемся, вы вернетесь, чтобы увидеть наши изменения.';
        $answerMessage[] = 'Мы ценим ваш отзыв и будем работать над улучшениями. Извините за возникшие неудобства.';
        $answerMessage[] = 'Приносим извинения за возможные неудобства. Мы надеемся на возможность сделать ваш следующий визит лучше.';
        $answerMessage[] = 'Надеемся, вы вернетесь, чтобы увидеть наши улучшения. Нам жаль, что вы остались недовольны.';

        $key = new Randomizer()->getInt(0, count($answerMessage) - 1);
        $answerMessage = $answerMessage[$key];

        return $this->hello().PHP_EOL.$answerMessage.PHP_EOL.$this->goodbye();

    }
}