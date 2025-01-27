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

namespace BaksDev\Support\Answer\Repository\UserProfileTypeAnswers\Tests;

use BaksDev\Support\Answer\Repository\UserProfileTypeAnswers\UserProfileTypeAnswersInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group support-answer
 */
class UserProfileTypeAnswersRepositoryTest extends KernelTestCase
{
    public function testUseCase(): void
    {
        /** @var UserProfileTypeAnswersInterface $UserProfileTypeAnswersInterface */
        $UserProfileTypeAnswersInterface = self::getContainer()->get(UserProfileTypeAnswersInterface::class);

        $response = $UserProfileTypeAnswersInterface->findUserProfileTypeAnswers(TypeProfileUid::TEST);

        if(count($response))
        {
            $current = current($response);

            self::assertTrue(property_exists($current,"id"));
            self::assertTrue(property_exists($current,"title"));
            self::assertTrue(property_exists($current,"type"));
            self::assertTrue(property_exists($current,"content"));
        }

        self::assertTrue(true);
    }
}