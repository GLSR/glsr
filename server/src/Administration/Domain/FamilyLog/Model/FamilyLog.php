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

namespace Administration\Domain\FamilyLog\Model;

use Administration\Domain\FamilyLog\Model\VO\FamilyLogUuid;
use Core\Domain\Common\Model\VO\NameField;

final class FamilyLog
{
    private string $uuid;
    private string $label;
    private int $level;
    private ?FamilyLog $parent = null;
    /**
     * @var FamilyLog[]|null
     */
    private ?array $children = null;
    private string $slug;
    private string $path;

    public function __construct(
        FamilyLogUuid $uuid,
        NameField $label,
        int $level,
        ?self $parent = null,
        ?string $path = null
    ) {
        $this->uuid = $uuid->toString();
        $this->label = $label->getValue();
        $this->path = $label->slugify();
        if (null !== $path) {
            $this->path = $path;
        }
        $this->slug = $label->slugify();
        $this->level = $level;
        if (null !== $parent) {
            $this->attributeParent($parent);
        }
    }

    public static function create(
        FamilyLogUuid $uuid,
        NameField $label,
        int $level,
        ?self $parent = null,
        ?string $path = null
    ): self {
        return new self($uuid, $label, $level, $parent, $path);
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function level(): int
    {
        return $this->level;
    }

    public function parent(): ?self
    {
        return $this->parent;
    }

    public function children(): ?array
    {
        return $this->children;
    }

    public function childrenArrayLabels(self $familyLog): ?array
    {
        if (null !== $familyLog->children) {
            return \array_map(static function (self $child) {
                return $child->label;
            }, $familyLog->children);
        }

        return null;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function parseTree(): array
    {
        $arrayChildren = [];
        if (null === $this->children) {
            return [$this->label => $arrayChildren];
        }

        foreach ($this->children as $child) {
            if (null !== $this->childrenArrayLabels($child)) {
                $arrayChildren[$child->label] = $this->childrenArrayLabels($child);
            } else {
                $arrayChildren[] = $child->label;
            }
        }

        return [$this->label => $arrayChildren];
    }

    public function rename(NameField $label): void
    {
        $this->label = $label->getValue();
        $this->slug = $label->slugify();
        $this->path = $this->slug();
    }

    public function attributeParent(self $parent): void
    {
        $this->parent = $parent;
        $this->level = 1;
        if (null !== $parent) {
            $this->parent->addChild($this);
            $this->path = $parent->path() . '/' . $this->slug();
            $this->level = $parent->level + 1;
        }
        if ($this->hasChildren()) {
            $this->changeLevel($this->children(), 1);
        }
    }

    public function hasChildren(): bool
    {
        return null !== $this->children;
    }

    private function addChild(self $child): void
    {
        $this->children[] = $child;
    }

    /**
     * @param FamilyLog[] $children
     */
    private function changeLevel(array $children, int $scale): void
    {
        foreach ($children as $child) {
            $child->level += $scale;
            if ($child->hasChildren()) {
                $this->changeLevel($child->children(), 1);
            }
        }
    }
}
