<?php

namespace Phonyland;

use Phonyland\Group\Group;
use Phonyland\Group\Standard;
use RuntimeException;

/**
 * Class Phony.
 *
 *
 * @property-read \Phonyland\Fake\Standard\Address     address
 * @property-read \Phonyland\Fake\Standard\Address     📫
 * @property-read \Phonyland\Fake\Standard\Alphabet    alphabet
 * @property-read \Phonyland\Fake\Standard\Alphabet    🔤
 * @property-read \Phonyland\Fake\Standard\Ancient     ancient
 * @property-read \Phonyland\Fake\Standard\Ancient     📜
 * @property-read \Phonyland\Fake\Standard\Artist      artist
 * @property-read \Phonyland\Fake\Standard\Boolean     boolean
 * @property-read \Phonyland\Fake\Standard\Coin        coin
 * @property-read \Phonyland\Fake\Standard\Cosmere     cosmere
 * @property-read \Phonyland\Fake\Standard\Currency    currency
 * @property-read \Phonyland\Fake\Standard\Number      number
 * @property-read \Phonyland\Fake\Standard\Person      person
 * @property-read \Phonyland\Fake\Standard\SlackEmoji  slack_emoji
 *
 * @property-read \Phonyland\Group\Standard            standard
 * @property-read \Phonyland\Group\ProgrammingLanguage programming_language
 */
class Phony
{
    public Loader $loader;
    public string $defaultLocale;
    private array $groups;
    private array $groupInstances = [];

    /**
     * Phony constructor.
     *
     * @param  string  $defaultLocale
     */
    public function __construct(string $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
        $this->loader = new Loader($defaultLocale);
        $this->groups = Group::All;
        $this->groupInstances['standard'] = new Standard($this);
    }

    // region Magic Setup

    /**
     * Get attributes by magic.
     *
     * @param $attribute
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($attribute)
    {
        // If it's a fake in the standard group
        if (isset($this->groupInstances['standard']->fakes[$attribute])) {
            return $this->groupInstances['standard']->$attribute;
        }

        // If it's a group
        if (isset($this->groups[$attribute])) {
            if (isset($this->groupInstances[$attribute])) {
                return $this->groupInstances[$attribute];
            }

            $groupClassName = $this->groups[$attribute];

            return new $groupClassName($this);
        }

        throw new RuntimeException("The {$attribute} group or standard fake is not found!");
    }

    /**
     * Don't allow setting magic attributes.
     *
     * @param $attribute
     * @param $value
     */
    public function __set($attribute, $value)
    {
        throw new RuntimeException('Setting group or fake is not allowed!');
    }

    /**
     * Check if a magic attribute exists.
     *
     * @param $attribute
     *
     * @return bool
     */
    public function __isset($attribute): bool
    {
        // If it's a fake in the standard group or a group
        return
            isset($this->groupInstances['standard']->fakes[$attribute]) ||
            isset($this->groups[$attribute]);
    }

    // endregion

    // region Caching

    public function setCacheSize(int $cacheSize): Phony
    {
        $this->loader->setCacheSize($cacheSize);

        return $this;
    }

    public function getCacheSize(): int
    {
        return $this->loader->getCacheSize();
    }

    public function getCacheUsage(): int
    {
        return $this->loader->getCacheUsage();
    }

    // endregion
}
