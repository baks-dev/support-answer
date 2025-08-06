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

namespace BaksDev\Support\Answer\Entity;

use BaksDev\Support\Answer\Type\Id\SupportAnswerUid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/* SupportAnswer */

#[ORM\Entity]
#[ORM\Table(name: 'support_answer')]
class SupportAnswer
{
    /**
     * Идентификатор сущности
     */
    #[ORM\Id]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: SupportAnswerUid::TYPE)]
    private SupportAnswerUid $id;

    #[Assert\NotBlank(message: 'Заголовок обязателен для заполнения')]
    #[ORM\Column(type: Types::STRING)]
    private ?string $title = null;

    #[Assert\NotBlank(message: 'Содержимое обязательно для заполнения')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * Тип профиля пользователей
     * @var TypeProfileUid
     */
    #[Assert\Uuid]
    #[ORM\Column(type: TypeProfileUid::TYPE, nullable: true)]
    private ?TypeProfileUid $type;

    /** Профиль пользователя */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: UserProfileUid::TYPE, nullable: true)]
    private UserProfileUid $profile;

    public function __construct()
    {
        $this->id = new SupportAnswerUid();
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

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


    public function getId(): SupportAnswerUid
    {
        return $this->id;
    }

    public function getProfile(): UserProfileUid
    {
        return $this->profile;
    }

    public function setProfile(UserProfileUid $profile): self
    {
        $this->profile = $profile;
        return $this;
    }


}