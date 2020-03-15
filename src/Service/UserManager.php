<?php
/**
 * @author Pavel Vasiluk <pavel.vasiluk@gmail.com>
 * Date: 3/14/2020
 * Time: 5:16 PM
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Service that manages user data
 */
class UserManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserManager constructor.
     *
     * @param UserRepository               $userRepository  User repository
     * @param UserPasswordEncoderInterface $passwordEncoder Password encoder service
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository  = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Add new user
     *
     * @param User|object $user
     *
     * @throws ORMException
     */
    public function addUser(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $this->userRepository->upgradePassword($user, $password);
    }
}