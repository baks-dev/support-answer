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

use BaksDev\Ozon\Orders\Type\ProfileType\TypeProfileFbsOzon;
use BaksDev\Support\Answer\Repository\UserProfileTypeAnswers\UserProfileTypeAnswersInterface;
use BaksDev\Support\Answer\Repository\UserProfileTypeAnswers\UserProfileTypeAnswersResult;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use PHPUnit\Framework\Attributes\Group;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

#[Group('support-answer')]
#[When(env: 'test')]
class UserProfileTypeAnswersRepositoryTest extends KernelTestCase
{
    public function testUseCase(): void
    {
        /** @var UserProfileTypeAnswersInterface $UserProfileTypeAnswersInterface */
        $UserProfileTypeAnswersInterface = self::getContainer()->get(UserProfileTypeAnswersInterface::class);


        $result = $UserProfileTypeAnswersInterface
            ->forProfile(new UserProfileUid())
            ->forType(new TypeProfileUid(TypeProfileFbsOzon::TYPE))
            ->findAll();

        foreach($result as $UserProfileTypeAnswersResult)
        {
            // Вызываем все геттеры
            $reflectionClass = new ReflectionClass(UserProfileTypeAnswersResult::class);
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach($methods as $method)
            {
                // Методы без аргументов
                if($method->getNumberOfParameters() === 0)
                {
                    // Вызываем метод
                    $value = $method->invoke($UserProfileTypeAnswersResult);
                    // dump($value);
                }
            }
        }

        self::assertTrue(true);
    }
}