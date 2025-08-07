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

namespace BaksDev\Support\Answer\Repository\UserProfileTypeAnswers;

use BaksDev\Support\Answer\Type\Id\SupportAnswerUid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use Symfony\Component\Validator\Constraints as Assert;

/** @see UserProfileTypeAnswersResult */
final readonly class UserProfileTypeAnswersResult
{
    public function __construct(
        private string $id, //" => "01988136-5c1d-7d3b-bbbf-d32d75260f80"
        private string $title, //" => "Быстрый ответ 2"
        private string $content, //" => "Содержимое ответа"
        private ?string $type, //" => "c024b3d2-1866-72c3-83e9-922f8678bf23"
        private ?string $name, //" => "Ozon"
    ) {}

    public function getId(): SupportAnswerUid
    {
        return new SupportAnswerUid($this->id);
    }

    public function getType(): TypeProfileUid|false
    {
        return $this->type ? new TypeProfileUid($this->type) : false;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}