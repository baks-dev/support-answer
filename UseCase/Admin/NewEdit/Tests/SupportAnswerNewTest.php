<?php
/*
 *  Copyright 2024.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Support\Answer\UseCase\Admin\NewEdit\Tests;

use BaksDev\Support\Answer\Entity\SupportAnswer;
use BaksDev\Support\Answer\UseCase\Admin\NewEdit\SupportAnswerDTO;
use BaksDev\Support\Answer\UseCase\Admin\NewEdit\SupportAnswerHandler;
use BaksDev\Support\UseCase\Admin\New\SupportHandler;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @group support-answer
 */
#[When(env: 'test')]
class SupportAnswerNewTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $supportAnswer = $em->getRepository(SupportAnswer::class)
            ->findOneBy(['type' => TypeProfileUid::TEST]);

        if($supportAnswer)
        {
            $em->remove($supportAnswer);
        }

        $em->flush();
        $em->clear();
    }


    public function testUseCase(): void
    {

        /** SupportDTO */
        $SupportAnswerDTO = new SupportAnswerDTO();
        $SupportAnswerDTO->setTitle('New Test Title');
        self::assertSame('New Test Title', $SupportAnswerDTO->getTitle());

        $SupportAnswerDTO->setType(new TypeProfileUid(TypeProfileUid::TEST));
        self::assertSame((new TypeProfileUid(TypeProfileUid::TEST))->getTypeProfileValue(), $SupportAnswerDTO->getType()->getTypeProfileValue());

        $SupportAnswerDTO->setContent('New Test Content');
        self::assertSame('New Test Content', $SupportAnswerDTO->getContent());

        /** @var SupportHandler $SupportHandler */
        $SupportAnswerHandler = self::getContainer()->get(SupportAnswerHandler::class);
        $handle = $SupportAnswerHandler->handle($SupportAnswerDTO);

        self::assertTrue(($handle instanceof SupportAnswer), $handle.': Ошибка SupportAnswer');

    }
}