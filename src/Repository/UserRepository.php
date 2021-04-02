<?php

namespace App\Repository;

use App\Entity\User;
use App\VO\Email;
use App\VO\PhoneNumber;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface, UserLoaderInterface
{
    /**
     * @param ManagerRegistry        $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByUsername(string $username)
    {
    }

    /**
     * @param string            $passportSeries
     * @param string            $passportNumber
     * @param string            $passportDivisionName
     * @param string            $passportDivisionCode
     * @param DateTimeImmutable $passportIssueDate
     * @param string | null     $firstName
     * @param string | null     $lastName
     * @param string | null     $middleName
     *
     * @return User | null
     *
     * @throws NonUniqueResultException
     */
    public function findByPassport(
        string $passportSeries,
        string $passportNumber,
        string $passportDivisionName,
        string $passportDivisionCode,
        DateTimeImmutable $passportIssueDate,
        ?string $firstName,
        ?string $lastName,
        ?string $middleName
    ): ?User {
        $queryBuilder =  $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.passport.passportSeries = :passportSeries')
            ->andWhere('u.passport.passportNumber = :passportNumber')
            ->andWhere('u.passport.passportDivisionName = :passportDivisionName')
            ->andWhere('u.passport.passportDivisionCode = :passportDivisionCode')
            ->andWhere('u.passport.passportIssueDate = :passportIssueDate')
            ->setParameters([
                'passportSeries' => $passportSeries,
                'passportNumber' => $passportNumber,
                'passportDivisionName' => $passportDivisionName,
                'passportDivisionCode' => $passportDivisionCode,
                'passportIssueDate' => $passportIssueDate,
            ]);

        if (!is_null($firstName)) {
            $queryBuilder->andWhere('LOWER(u.passport.firstName) = LOWER(:firstName)')
                ->setParameter('firstName', $firstName);
        }

        if (!is_null($lastName)) {
            $queryBuilder->andWhere('LOWER(u.passport.lastName) = LOWER(:lastName)')
                ->setParameter('lastName', $lastName);
        }

        if (!is_null($middleName)) {
            $queryBuilder->andWhere('LOWER(u.passport.middleName) = LOWER(:middleName)')
                ->setParameter('middleName', $middleName);
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
