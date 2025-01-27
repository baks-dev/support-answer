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

namespace BaksDev\Support\Answer\Repository\UserProfileType\Tests;

use BaksDev\Support\Answer\Repository\UserProfileType\UserProfileTypeInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group support-answer
 */
class UserProfileTypeRepositoryTest extends KernelTestCase
{
    public function testUseCase(): void
    {

        /** @var UserProfileTypeInterface $UserProfileTypeInterface */
        $UserProfileTypeInterface = self::getContainer()->get(UserProfileTypeInterface::class);

        $response = $UserProfileTypeInterface->findUserTypeProfiles();

        if(count($response))
        {
            $current = current($response);

            self::assertTrue(property_exists($current, 'option'));
            self::assertTrue(property_exists($current, 'attr'));
            self::assertTrue(method_exists($current, 'getTypeProfileValue'));
        }

        self::assertTrue(true);
    }
}