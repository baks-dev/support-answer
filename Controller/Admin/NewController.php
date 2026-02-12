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
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Support\Answer\Entity\SupportAnswer;
use BaksDev\Support\Answer\UseCase\Admin\NewEdit\SupportAnswerDTO;
use BaksDev\Support\Answer\UseCase\Admin\NewEdit\SupportAnswerForm;
use BaksDev\Support\Answer\UseCase\Admin\NewEdit\SupportAnswerHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[RoleSecurity('ROLE_SUPPORT_ADD')]
final class NewController extends AbstractController
{
    #[Route(
        '/admin/support/answer/new/{id}',
        name: 'admin.newedit.new',
        defaults: ['id' => null],
        methods: ['GET', 'POST']
    )]
    public function news(
        Request $request,
        SupportAnswerHandler $SupportAnswerHandler,
        Security $Security,
    ): Response
    {
        $supportAnswerDTO = new SupportAnswerDTO();

        /**
         * Если пользователь админ - ответ будет в дальнейшем отображаться у всех профилей, если нет - только у данного
         * профиля
         */
        if(false === $Security->isGranted('ROLE_ADMIN'))
        {
            $supportAnswerDTO->setProfile($this->getCurrentProfileUid());
        }


        /** Форма */
        $form = $this
            ->createForm(
                type: SupportAnswerForm::class,
                data: $supportAnswerDTO,
                options: ['action' => $this->generateUrl('support-answer:admin.newedit.new'),],
            )
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->refreshTokenForm($form);

            $handle = $SupportAnswerHandler->handle($supportAnswerDTO);

            $this->addFlash
            (
                type: 'page.new',
                message: $handle instanceof SupportAnswer ? 'success.new' : 'danger.new',
                domain: 'support-answer.admin',
                arguments: $handle,
            );

            return $handle instanceof SupportAnswer
                ? $this->redirectToRoute('support-answer:admin.index')
                : $this->redirectToReferer();
        }

        return $this->render(['form' => $form->createView()]);
    }
}