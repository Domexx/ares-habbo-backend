<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Rakit\Validation\Validator;

/**
 * Class ValidationServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class ValidationServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        Validator::class
    ];

    /**
     * @var array
     */
    public array $messages = [
        'required' => 'validation.required',
        'email' => 'validation.email',
        'password_confirmation:same' => 'register.password.same',
        'password:min' => 'register.password.min'
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(Validator::class, function () use ($container) {
            return new Validator($this->messages);
        });
    }
}
