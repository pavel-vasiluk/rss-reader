<?php
/**
 * @author Pavel Vasiluk <pavel.vasiluk@gmail.com>
 * Date: 3/15/2020
 * Time: 12:25 AM
 */

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Entity Validation service
 */
class ValidationManager
{
    /**
     * Validator component
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * UserManager constructor
     *
     * @param ValidatorInterface $validator Validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param object $entity Entity to set with data
     * @param array  $data   Data assoc array
     *
     * @return object
     */
    public function fillEntityWithData(object $entity, array $data): object
    {
        foreach ($data as $field => $value) {
            $method = sprintf('set%s', ucfirst($field));
            $entity->$method($value);
        }

        return $entity;
    }

    /**
     * Validates given entity.
     * Returns an empty array if entity is valid.
     * When entity is invalid, returns assoc array with property path as key and error message as value.
     *
     * @param object $entity Provided entity object
     *
     * @return array
     */
    public function validateEntity(object $entity): array
    {
        $violationList = $this->validator->validate($entity);
        $errors = [];

        for ($i = 0; $i < $violationList->count(); $i++) {
            $violation = $violationList->get($i);
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}