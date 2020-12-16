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

namespace Unit\Tests\Infrastructure\Administration\Company\Controller;

use Infrastructure\DataFixtures\CompanyFixtures;
use Symfony\Component\HttpFoundation\Response;
use Unit\Tests\Infrastructure\AbstractControllerTest;

class DeleteCompanyControllerTest extends AbstractControllerTest
{
    final public function testDeleteCompanySuccess(): void
    {
        // Arrange
        $this->loadFixture(new CompanyFixtures());
        $this->client->request('DELETE', '/administration/company/delete/a136c6fe-8f6e-45ed-91bc-586374791033');

        // Act
        $response = $this->client->getResponse();

        // Assert
        static::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        static::assertTrue($response->isRedirect('/administration/company/'));
    }
}