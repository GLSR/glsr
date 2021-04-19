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

namespace Administration\Domain\Protocol\Repository;

use Administration\Domain\Settings\Model\Settings;

interface SettingsRepositoryProtocol
{
    public function save(Settings $settings): void;

    public function remove(Settings $settings): void;

    public function findOneByUuid(string $uuid): ?Settings;

    public function settingsExist(): bool;
}
