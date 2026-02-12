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

namespace BaksDev\Support\Answer\Controller\Admin;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Form\Search\SearchDTO;
use BaksDev\Core\Form\Search\SearchForm;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Support\Answer\Form\Admin\Index\SupportAnswerTypeProfileFilterDTO;
use BaksDev\Support\Answer\Form\Admin\Index\SupportAnswerTypeProfileFilterForm;
use BaksDev\Support\Answer\Repository\AllSupportAnswer\AllSupportAnswerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[RoleSecurity('ROLE_SUPPORT')]
final class IndexController extends AbstractController
{
    #[Route('/admin/support/answers/{page<\d+>}', name: 'admin.index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        AllSupportAnswerInterface $AllSupportRepository,
        Security $Security,
        int $page = 0,
    ): Response
    {
        /**
         * Форма фильтра
         */
        $filter = new SupportAnswerTypeProfileFilterDTO();
        $filterForm = $this
            ->createForm(
                type: SupportAnswerTypeProfileFilterForm::class,
                data: $filter,
                options: ['action' => $this->generateUrl('support-answer:admin.index'),]
            )
            ->handleRequest($request);

        /**
         * Форма поиска
         */
        $search = new SearchDTO();

        $searchForm = $this
            ->createForm(
                type: SearchForm::class,
                data: $search,
                options: ['action' => $this->generateUrl('support-answer:admin.index')]
            )
            ->handleRequest($request);


        /**
         * Если профиль не админ - выбрать ответы только текущего профиля либо общие
         * Если профиль админ - выбрать для любых профилей
         */
        if(false === $Security->isGranted('ROLE_ADMIN'))
        {
            $AllSupportRepository->forProfile($this->getProfileUid());
        }


        /**
         * Список ответов
         */
        $SupportAnswer = $AllSupportRepository
            ->search($search)
            ->filter($filter)
            ->findPaginator();

        return $this->render(
            [
                'query' => $SupportAnswer,
                'search' => $searchForm->createView(),
                'filter' => $filterForm->createView(),
            ],
            file: 'support-answer.html.twig'
        );
    }
}
