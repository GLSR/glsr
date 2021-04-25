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

namespace Administration\Infrastructure\Persistence\DoctrineOrm\Entities;

use Administration\Domain\Company\Model\Company as CompanyModel;
use Core\Domain\Common\Model\VO\ContactUuid;
use Core\Domain\Common\Model\VO\EmailField;
use Core\Domain\Common\Model\VO\NameField;
use Core\Domain\Common\Model\VO\PhoneField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="Administration\Infrastructure\Persistence\DoctrineOrm\Repositories\DoctrineCompanyRepository")
 */
class Company extends Contact
{
    public function __construct(Contact $contact)
    {
        parent::__construct(
            $contact->uuid,
            $contact->companyName,
            $contact->address,
            $contact->zipCode,
            $contact->town,
            $contact->country,
            $contact->phone,
            $contact->facsimile,
            $contact->email,
            $contact->contactName,
            $contact->cellphone,
            $contact->slug
        );
    }

    public static function fromModel(CompanyModel $companyModel): self
    {
        $contact = Contact::create(
            $companyModel->uuid(),
            $companyModel->companyName(),
            $companyModel->address(),
            $companyModel->zipCode(),
            $companyModel->town(),
            $companyModel->country(),
            $companyModel->phone(),
            $companyModel->facsimile(),
            $companyModel->email(),
            $companyModel->contactName(),
            $companyModel->cellphone(),
            $companyModel->slug()
        );

        return new self($contact);
    }

    public static function toModel(self $company): CompanyModel
    {
        return CompanyModel::create(
            ContactUuid::fromString($company->getUuid()),
            NameField::fromString($company->getCompanyName()),
            $company->getAddress(),
            $company->getZipCode(),
            $company->getTown(),
            $company->getCountry(),
            PhoneField::fromString($company->getPhone()),
            PhoneField::fromString($company->getFacsimile()),
            EmailField::fromString($company->getEmail()),
            $company->getContactName(),
            PhoneField::fromString($company->getCellphone())
        );
    }
}