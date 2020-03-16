<?php
/**
 * @author Pavel Vasiluk <pavel.vasiluk@gmail.com>
 * Date: 3/14/2020
 * Time: 4:29 PM
 */

namespace Feature;

use App\Entity\User;
use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserContext
 */
class UserContext extends RawMinkContext implements Context
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * SecurityContext constructor.
     *
     * @param UserRepository               $userRepository  User repository
     * @param EntityManagerInterface       $entityManager   Entity manager
     * @param UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository  = $userRepository;
        $this->entityManager   = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Create user
     *
     * @Given user with username :username and password :password exists
     *
     * @param string $username Username
     * @param string $password Password
     *
     * @throws ORMException
     */
    public function createUser(string $username, string $password): void
    {
        $this->removeUser($username);

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);

        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $this->userRepository->upgradePassword($user, $password);
    }

    /**
     * Remove user from test db
     *
     * @Then I should not have user with email :email
     *
     * @param string $email User email (username)
     */
    public function removeUser(string $email)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }
}