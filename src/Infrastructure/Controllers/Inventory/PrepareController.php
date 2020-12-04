<?php

declare(strict_types=1);

/*
 * This file is part of the  G.L.S.R. Apps package.
 *
 * (c) Dev-Int Création <info@developpement-interessant.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infrastructure\Controllers\Inventory;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

class PrepareController extends AbstractController
{
    /**
     * @Route("inventory/prepare")
     */
    public function __invoke(): BinaryFileResponse
    {
        $projectDir = $this->getParameter('kernel.project_dir');
        $dataInventoryFolder = $projectDir . '/data/Inventory';
        $prepareFile = new \SplFileInfo($dataInventoryFolder . '/prepare.pdf');

        return new BinaryFileResponse($prepareFile);
    }
}
