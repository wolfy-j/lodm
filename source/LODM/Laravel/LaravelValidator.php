<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
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
     * Data to be validated.
     *
     * @var array
     */
    private $data = [];

    /**
     * Rules to be applied to validation.
     *
     * @var array
     */
    private $rules = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($data = [], array $rules = [])
    {
        $this->data = $data;
        $this->rules = $rules;
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

        return $errors;
    }
}
