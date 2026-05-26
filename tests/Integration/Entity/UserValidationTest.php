<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <hello@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Nominal case : A valid user passes validation.
     */
    public function testValidUserHasNoViolation(): void
    {
        $user = (new User())
            ->setEmail('studio@carolinenoyer.fr')
            ->setPassword('$2y$13$dummyhashedpasswordstrings');

        $errors = $this->validator->validate($user);
        $this->assertCount(0, $errors);

        $this->assertSame('studio@carolinenoyer.fr', $user->getEmail());
        $this->assertSame('studio@carolinenoyer.fr', $user->getUserIdentifier());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    /**
     * Test invalid email format triggers violation.
     */
    public function testInvalidEmailFormatTriggerViolation(): void
    {
        $user = (new User())
            ->setEmail('ceci-n-est-pas-un-email')
            ->setPassword('password123');

        $errors = $this->validator->validate($user);
        $this->assertGreaterThan(0, count($errors));
        $this->assertSame('email', $errors[0]->getPropertyPath());
    }

    /**
     * Test that role hierarchy adds complementary custom roles.
     */
    public function testRolesManagement(): void
    {
        $user = (new User())->setRoles(['ROLE_ADMIN']);

        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles()); // Always guaranteed
    }

    /**
     * Test that the magic __serialize method secures the password hash for session security.
     */
    public function testSerializationSecuresPasswordHash(): void
    {
        $user = (new User())
            ->setEmail('admin@test.fr')
            ->setPassword('super_secret_hash');

        $serialized = $user->__serialize();

        // Verify that the private property password key does not contain plain text
        // but the crc32c hash expected by your implementation
        $passwordKey = "\0".User::class."\0password";
        $this->assertArrayHasKey($passwordKey, $serialized);
        $this->assertSame(hash('crc32c', 'super_secret_hash'), $serialized[$passwordKey]);
    }

    /**
     * Test the ID via Reflection.
     */
    public function testGetId(): void
    {
        $user = new User();

        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, 1);

        $this->assertSame(1, $user->getId());
    }
}
