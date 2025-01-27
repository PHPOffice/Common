<?php

/**
 * This file is part of PHPOffice Common
 *
 * PHPOffice Common is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/Common/contributors.
 *
 * @see        https://github.com/PHPOffice/Common
 *
 * @copyright   2009-2016 PHPOffice Common contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\Common\Tests\Microsoft;

use PhpOffice\Common\Microsoft\PasswordEncoder;

/**
 * Test class for PhpOffice\Common\PasswordEncoder
 *
 * @coversDefaultClass \PhpOffice\Common\PasswordEncoder
 */
class PasswordEncoderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test that a password can be hashed without specifying any additional parameters
     */
    public function testEncodePassword(): void
    {
        // Given
        $password = 'test';

        // When
        $hashPassword = PasswordEncoder::hashPassword($password);

        // Then
        $this->assertEquals('M795/MAlmGU8RIsY9Q9uDLHC7bk=', $hashPassword);
    }

    /**
     * Test that a password can be hashed with a custom salt
     */
    public function testEncodePasswordWithSalt(): void
    {
        // Given
        $password = 'test';
        $salt = base64_decode('uq81pJRRGFIY5U+E9gt8tA==');

        // When
        $hashPassword = PasswordEncoder::hashPassword($password, PasswordEncoder::ALGORITHM_SHA_1, $salt);

        // Then
        $this->assertEquals('QiDOcpia1YzSVJPiKPwWebl9p/0=', $hashPassword);
    }

    /**
     * Test that the encoder falls back on SHA-1 if a non supported algorithm is given
     */
    public function testDefaultsToSha1IfUnsupportedAlgorithm(): void
    {
        // Given
        $password = 'test';
        $salt = base64_decode('uq81pJRRGFIY5U+E9gt8tA==');

        // When
        $hashPassword = PasswordEncoder::hashPassword($password, PasswordEncoder::ALGORITHM_MAC, $salt);

        // Then
        $this->assertEquals('QiDOcpia1YzSVJPiKPwWebl9p/0=', $hashPassword);
    }

    /**
     * Test that the encoder falls back on SHA-1 if a non supported algorithm is given
     */
    public function testEncodePasswordWithNullAsciiCodeInPassword(): void
    {
        // Given
        $password = 'test' . chr(0);
        $salt = base64_decode('uq81pJRRGFIY5U+E9gt8tA==');

        // When
        $hashPassword = PasswordEncoder::hashPassword($password, PasswordEncoder::ALGORITHM_MAC, $salt, 1);

        // Then
        $this->assertEquals('rDV9sgdDsztoCQlvRCb1lF2wxNg=', $hashPassword);
    }
}
