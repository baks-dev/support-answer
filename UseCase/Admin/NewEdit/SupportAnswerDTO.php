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

namespace BaksDev\Support\Answer\UseCase\Admin\NewEdit;

use BaksDev\Support\Answer\Type\Id\SupportAnswerUid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use Symfony\Component\Validator\Constraints as Assert;

final class SupportAnswerDTO
{
    
    /**
     * Идентификатор
     */
    #[Assert\Uuid]
    private ?SupportAnswerUid $id = null;

    #[Assert\NotBlank(message: 'Заголовок обязателен для заполнения')]
    private ?string $title = null;

    #[Assert\NotBlank(message: 'Содержимое обязательно для заполнения')]
    private ?string $content = null;

    /**
     * Тип профиля пользователей
     * @var TypeProfileUid
     */
    #[Assert\Uuid]
    private ?TypeProfileUid $type = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getType(): ?TypeProfileUid
    {
        return $this->type;
    }

    public function setType(?TypeProfileUid $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getId(): ?SupportAnswerUid
    {
        return $this->id;
    }

    public function setId(?SupportAnswerUid $id): self
    {
        $this->id = $id;

        return $this;
    }
    
}