<?php namespace Dredd;

use RuntimeException;

/**
 * Class Runner
 * @package Dredd
 * @method mixed runBeforeHooksForTransaction($transaction) Runs beforeHooks for a given transaction on Dredd\Hooks
 * @method mixed runBeforeValidationHooksForTransaction($transaction) Runs beforeValidationHooks for a given transaction on Dredd\Hooks
 * @method mixed runAfterHooksForTransaction($transaction) Runs afterHooks for a given transaction on Dredd\Hooks
 * @method mixed runBeforeEachHooksForTransaction($transaction) Runs beforeEachHooks for a given transaction on Dredd\Hooks
 * @method mixed runBeforeEachValidationHooksForTransaction($transaction) Runs beforeEachValidationHooks for a given transaction on Dredd\Hooks
 * @method mixed runAfterEachHooksForTransaction($transaction) Runs afterEachHooks for a given transaction on Dredd\Hooks
 * @method mixed runBeforeAllHooksForTransaction($transactions) Runs beforeAllHooks for a given transaction on Dredd\Hooks
 * @method mixed runAfterAllHooksForTransaction($transactions) Runs afterAllHooks for a given transaction on Dredd\Hooks
 */


class Runner {

    const METHOD_REGEX = '/(?<=run)(Before(?:All|Each|Validation|EachValidation)?|After(?:All|Each)?)(?=HooksForTransaction)/';

    public function __call($method, $args)
    {
        // access name index on transaction object
        $transaction = $args[0];

        // get all the hooks from Dredd\Hooks
        $hooks = $this->getHooksFromMethodCall($method, $transaction);

        if ( ! is_array($hooks)) throw new RuntimeException("Hooks must be an array");

        array_walk($hooks, function($hook) use (&$transaction) {

            $hook($transaction);
        });

        return $transaction;
    }

    public function getPropertyNameFromMethodCall($method)
    {
        if ( ! preg_match(self::METHOD_REGEX, $method, $matches)) throw new RuntimeException("Invalid method call {$method}");

        return lcfirst($matches[0]) . 'Hooks';
    }

    private function getHooksFromMethodCall($method, $transaction)
    {
        $propertyName = $this->getPropertyNameFromMethodCall($method);

        if ( ! property_exists(Hooks::class, $propertyName)) throw new RuntimeException("Invalid property {$propertyName} trying to be accessed");

        if (strpos($propertyName, 'All') || strpos($propertyName, 'Each')) {

            return Hooks::${$propertyName};
        }


        else if (array_key_exists($transaction->name, Hooks::${$propertyName})) {

            return Hooks::${$propertyName}[$transaction->name];
        }

        return [];
    }
}