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

class UserProfileTypeAnswersRepository implements UserProfileTypeAnswersInterface
{
    public function __construct(
        private readonly DBALQueryBuilder $DBALQueryBuilder,
    ) {}

    /**
     *
     * Метод возвращает все ответы по указанному типу профиля, а также все ответы без типа профиля
     * (support_answer.type IS NULL)
     *
     * @param TypeProfileUid $type
     * @return array
     */
    public function findUserProfileTypeAnswers(TypeProfileUid|string $type): array
    {

        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        $dbal
            ->select('support_answer.id')
            ->addSelect('support_answer.title')
            ->addSelect('support_answer.content');

        $dbal
            ->addSelect('
            COALESCE(
                support_answer.type,
                NULL
            ) AS type
        ');

        $dbal->from(SupportAnswer::class, 'support_answer');

        $dbal->leftJoin(
            'support_answer',
            TypeProfile::class,
            'profile',
            'profile.id = support_answer.type'
        );

        /* TypeProfile Event */
        $dbal->leftJoin(
            'profile',
            TypeProfileEvent::class,
            'profile_event',
            'profile_event.id = profile.event'
        );

        /* TypeProfile Translate */
        $dbal
            ->addSelect('profile_trans.name')
            ->leftJoin(
                'profile',
                TypeProfileTrans::class,
                'profile_trans',
                'profile_trans.event = profile.event AND profile_trans.local = :local'
            );

        /**
         * В выборке должны быть ответы по выбранному типу, а также с НЕ выбранным типом
         */
        $dbal
            ->andWhere('support_answer.type = :type')
            ->setParameter(
                'type',
                $type,
                TypeProfileUid::TYPE
            );

        $dbal->orWhere('support_answer.type IS NULL');

        $dbal->orderBy('support_answer.title');

        $result = $dbal->fetchAllAssociative();

        $results = [];
        foreach($result as $item)
        {
            $supportAnswer = new SupportAnswer()
                ->setType(new TypeProfileUid($item['type']))
                ->setContent($item['content'])
                ->setTitle($item['title']);

            $results[] = $supportAnswer;
        }

        return $results;
    }
}