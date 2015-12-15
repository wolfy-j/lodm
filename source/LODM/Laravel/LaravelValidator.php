<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\LODM\Laravel;

use Spiral\Validation\ValidatorInterface;

/**
 * A simple validation manager which utilizes Laravel validation mechanism.
 *
 * @see https://github.com/spiral/guide/blob/master/components/validation.md
 */
class LaravelValidator implements ValidatorInterface
{
    /**
     * Rules to be applied to validation.
     *
     * @var array
     */
    private $rules = [];

    /**
     * Data to be validated.
     *
     * @var array
     */
    private $data = [];

    /**
     * Errors provided from outside.
     *
     * @var array
     */
    private $registeredErrors = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $rules = [], $data = [])
    {
        $this->rules = $rules;
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRules(array $validates)
    {
        $this->rules = $validates;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return empty($this->getErrors());
    }

    /**
     * {@inheritdoc}
     */
    public function registerError($field, $error)
    {
        $this->registeredErrors[$field] = $error;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flushRegistered()
    {
        $this->registeredErrors = [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasErrors()
    {
        return !empty($this->getErrors());
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = \Validator::make($this->data, $this->rules);

        //We have to normalize messages
        $errors = [];
        foreach ($validator->errors()->getMessages() as $field => $errors) {
            $errors[$field] = current($errors);
        }

        return $this->registeredErrors + $errors;
    }
}
