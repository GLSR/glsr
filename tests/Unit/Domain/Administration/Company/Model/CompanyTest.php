<?php

declare(strict_types=1);

/*
 * This file is part of the G.L.S.R. Apps package.
 *
 * (c) Dev-Int Création <info@developpement-interessant.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit\Tests\Domain\Administration\Company\Model;

use Domain\Administration\Company\Model\Company;
use Domain\Common\Model\ContactUuid;
use Domain\Common\Model\VO\ContactAddress;
use Domain\Common\Model\VO\EmailField;
use Domain\Common\Model\VO\NameField;
use Domain\Common\Model\VO\PhoneField;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    final public function testInstantiateSupplier(): void
    {
        // Arrange & Act
        $company = Company::create(
            ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
            NameField::fromString('Davigel'),
            '15, rue des givrés',
            '75000',
            'Paris',
            'France',
            PhoneField::fromString('+33100000001'),
            PhoneField::fromString('+33100000002'),
            EmailField::fromString('contact@davigel.fr'),
            'David',
            PhoneField::fromString('+33600000001')
        );

        // Assert
        static::assertEquals(
            new Company(
                ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
                NameField::fromString('Davigel'),
                ContactAddress::fromString(
                    '15, rue des givrés',
                    '75000',
                    'Paris',
                    'France'
                ),
                PhoneField::fromString('+33100000001'),
                PhoneField::fromString('+33100000002'),
                EmailField::fromString('contact@davigel.fr'),
                'David',
                PhoneField::fromString('+33600000001')
            ),
            $company
        );
    }

    final public function testRenameCompany(): void
    {
        // Arrange
        $company = Company::create(
            ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
            NameField::fromString('Davigel'),
            '15, rue des givrés',
            '75000',
            'Paris',
            'France',
            PhoneField::fromString('+33100000001'),
            PhoneField::fromString('+33100000002'),
            EmailField::fromString('contact@davigel.fr'),
            'David',
            PhoneField::fromString('+33600000001')
        );

        // Act
        $company->renameCompany(NameField::fromString('Trans Gourmet'));

        // Assert
        static::assertEquals(
            new Company(
                ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
                NameField::fromString('Trans Gourmet'),
                ContactAddress::fromString(
                    '15, rue des givrés',
                    '75000',
                    'Paris',
                    'France'
                ),
                PhoneField::fromString('+33100000001'),
                PhoneField::fromString('+33100000002'),
                EmailField::fromString('contact@davigel.fr'),
                'David',
                PhoneField::fromString('+33600000001')
            ),
            $company
        );
    }

    final public function testRewriteAddress(): void
    {
        // Arrange
        $company = Company::create(
            ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
            NameField::fromString('Davigel'),
            '15, rue des givrés',
            '75000',
            'Paris',
            'France',
            PhoneField::fromString('+33100000001'),
            PhoneField::fromString('+33100000002'),
            EmailField::fromString('contact@davigel.fr'),
            'David',
            PhoneField::fromString('+33600000001')
        );

        // Act
        $company->rewriteAddress([
            '25, rue des givrons',
            '56000',
            'Lorient',
            'France',
        ]);

        // Assert
        static::assertEquals(
            new Company(
                ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
                NameField::fromString('Davigel'),
                ContactAddress::fromString(
                    '25, rue des givrons',
                    '56000',
                    'Lorient',
                    'France',
                ),
                PhoneField::fromString('+33100000001'),
                PhoneField::fromString('+33100000002'),
                EmailField::fromString('contact@davigel.fr'),
                'David',
                PhoneField::fromString('+33600000001')
            ),
            $company
        );
    }

    final public function testChangePhoneNumbers(): void
    {
        // Arrange
        $company = Company::create(
            ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
            NameField::fromString('Davigel'),
            '15, rue des givrés',
            '75000',
            'Paris',
            'France',
            PhoneField::fromString('+33100000001'),
            PhoneField::fromString('+33100000002'),
            EmailField::fromString('contact@davigel.fr'),
            'David',
            PhoneField::fromString('+33600000001')
        );

        // Act
        $company->changePhoneNumber('+33100050001');
        $company->changeFacsimileNumber('+33100050002');
        $company->changeCellphoneNumber('+33600050001');

        // Assert
        static::assertEquals(
            new Company(
                ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
                NameField::fromString('Davigel'),
                ContactAddress::fromString(
                    '15, rue des givrés',
                    '75000',
                    'Paris',
                    'France',
                ),
                PhoneField::fromString('+33100050001'),
                PhoneField::fromString('+33100050002'),
                EmailField::fromString('contact@davigel.fr'),
                'David',
                PhoneField::fromString('+33600050001')
            ),
            $company
        );
    }

    final public function testChangeEmail(): void
    {
        // Arrange
        $company = Company::create(
            ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
            NameField::fromString('Davigel'),
            '15, rue des givrés',
            '75000',
            'Paris',
            'France',
            PhoneField::fromString('+33100000001'),
            PhoneField::fromString('+33100000002'),
            EmailField::fromString('contact@davigel.fr'),
            'David',
            PhoneField::fromString('+33600000001')
        );

        // Act
        $company->rewriteEmail(EmailField::fromString('david@davigel.fr'));

        // Assert
        static::assertEquals(
            new Company(
                ContactUuid::fromString('a136c6fe-8f6e-45ed-91bc-586374791033'),
                NameField::fromString('Davigel'),
                ContactAddress::fromString(
                    '15, rue des givrés',
                    '75000',
                    'Paris',
                    'France',
                ),
                PhoneField::fromString('+33100000001'),
                PhoneField::fromString('+33100000002'),
                EmailField::fromString('david@davigel.fr'),
                'David',
                PhoneField::fromString('+33600000001')
            ),
            $company
        );
    }
}
