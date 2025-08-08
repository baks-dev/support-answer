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

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Support\Answer\Entity\SupportAnswer;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\Trans\TypeProfileTrans;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\UserProfile\Entity\UserProfile;
use BaksDev\Users\Profile\UserProfile\Repository\UserProfileTokenStorage\UserProfileTokenStorageInterface;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use Generator;

class UserProfileTypeAnswersRepository implements UserProfileTypeAnswersInterface
{
    private UserProfileUid|false $profile = false;

    private TypeProfileUid|false $type = false;

    public function __construct(
        private readonly DBALQueryBuilder $DBALQueryBuilder,
        private readonly UserProfileTokenStorageInterface $UserProfileTokenStorage
    ) {}

    public function forProfile(UserProfileUid|UserProfile $profile): self
    {
        if($profile instanceof UserProfile)
        {
            $profile = $profile->getId();
        }

        $this->profile = $profile;

        return $this;
    }

    public function forType(TypeProfile|TypeProfileUid|string|null $type): self
    {
        if(empty($type))
        {
            $this->type = false;
            return $this;
        }

        if(is_string($type))
        {
            $type = new TypeProfileUid($type);
        }

        if($type instanceof TypeProfile)
        {
            $type = $type->getId();
        }

        $this->type = $type;

        return $this;
    }


    /**
     * Метод возвращает все ответы по указанному типу профиля, а также все ответы без типа профиля
     * (support_answer.type IS NULL)
     *
     * @return Generator<int, UserProfileTypeAnswersResult>|false
     */
    public function findAll(): Generator|false
    {
        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        $dbal
            ->select('support_answer.id')
            ->addSelect('support_answer.title')
            ->addSelect('support_answer.content')
            ->addSelect('support_answer.type')
            ->from(SupportAnswer::class, 'support_answer');

        /**
         * В выборке должны быть ответы по выбранному типу либо общие
         */

        $dbal->where('support_answer.type IS NULL');

        if(true === ($this->type instanceof TypeProfileUid))
        {
            $dbal
                ->orWhere('support_answer.type = :type')
                ->setParameter(
                    key: 'type',
                    value: $this->type,
                    type: TypeProfileUid::TYPE,
                );
        }

        /**
         * Выбрать ответы только текущего профиля либо общие
         */

        $dbal->andWhere('support_answer.profile IS NULL');

        if(true === ($this->profile instanceof UserProfileUid))
        {
            $dbal
                ->orWhere('support_answer.profile = :profile')
                ->setParameter(
                    key: 'profile',
                    value: $this->profile ?: $this->UserProfileTokenStorage->getProfile(),
                    type: UserProfileUid::TYPE,
                );
        }


        $dbal->leftJoin(
            'support_answer',
            TypeProfile::class,
            'profile',
            'profile.id = support_answer.type',
        );

        /* TypeProfile Event */
        $dbal->leftJoin(
            'profile',
            TypeProfileEvent::class,
            'profile_event',
            'profile_event.id = profile.event',
        );

        /* TypeProfile Translate */
        $dbal
            ->addSelect('profile_trans.name')
            ->leftJoin(
                'profile',
                TypeProfileTrans::class,
                'profile_trans',
                'profile_trans.event = profile.event AND profile_trans.local = :local',
            );

        $dbal->orderBy('support_answer.title');

        return $dbal->fetchAllHydrate(UserProfileTypeAnswersResult::class);
    }
}