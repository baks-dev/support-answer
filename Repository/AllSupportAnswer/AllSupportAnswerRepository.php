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

namespace BaksDev\Support\Answer\Repository\AllSupportAnswer;

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Core\Form\Search\SearchDTO;
use BaksDev\Core\Services\Paginator\PaginatorInterface;
use BaksDev\Support\Answer\Entity\SupportAnswer;
use BaksDev\Support\Answer\Form\Admin\Index\SupportAnswerTypeProfileFilterDTO;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\Trans\TypeProfileTrans;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\UserProfile\Repository\UserProfileTokenStorage\UserProfileTokenStorageInterface;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;

final class AllSupportAnswerRepository implements AllSupportAnswerInterface
{
    private SearchDTO|false $search = false;

    private SupportAnswerTypeProfileFilterDTO|false $filter = false;

    public function __construct(
        private readonly DBALQueryBuilder $DBALQueryBuilder,
        private readonly PaginatorInterface $paginator,
        private readonly UserProfileTokenStorageInterface $UserProfileTokenStorage
    ) {}

    public function search(SearchDTO $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function filter(SupportAnswerTypeProfileFilterDTO $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    /** Метод возвращает пагинатор SupportAnswer */
    public function findPaginator(): PaginatorInterface
    {
        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        $dbal->select('support_answer.id');
        $dbal->addSelect('support_answer.title');

        $dbal
            ->addSelect('
            COALESCE(
                support_answer.type,
                NULL
            ) AS type
        ');

        $dbal->addSelect('support_answer.content');

        $dbal->from(SupportAnswer::class, 'support_answer');

        /* Задать профиль текущего пользователя */
        $dbal
            ->where('support_answer.profile = :profile')
            ->setParameter(
                'profile',
                $this->UserProfileTokenStorage->getProfileCurrent(),
                UserProfileUid::TYPE
            );


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

        if($this->filter && !is_null($this->filter->getType()))
        {
            if($this->filter->getType() !== TypeProfileUid::TEST)
            {
                $dbal
                    ->andWhere('support_answer.type = :type')
                    ->setParameter(
                        'type',
                        $this->filter->getType(),
                        TypeProfileUid::TYPE
                    );
            }
            else
            {
                /**
                 * Выбрать ответ с НЕ выбранным типом профиля пользователя
                 */
                $dbal
                    ->andWhere('support_answer.type IS NULL')
                ;
            }
        }

        /* Поиск */
        if($this->search->getQuery())
        {
            $dbal
                ->createSearchQueryBuilder($this->search)
                ->addSearchLike('support_answer.content')
                ->addSearchLike('support_answer.title');
        }

        $dbal
            ->orderBy('support_answer.title');
        return $this->paginator->fetchAllAssociative($dbal);
    }

}