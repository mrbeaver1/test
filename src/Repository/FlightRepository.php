<?php

namespace App\Repository;

use App\Entity\Flight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Flight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flight[]    findAll()
 * @method Flight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlightRepository extends ServiceEntityRepository implements FlightRepositoryInterface
{
    /**
     * @param ManagerRegistry        $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Flight::class);
    }

    /**
     * @param int $number
     *
     * @return Flight | null
     *
     * @throws NonUniqueResultException
     */
    public function findByFlightNumber(int $number): ?Flight
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('f')
            ->from(Flight::class, 'f')
            ->where('f.number = :number')
            ->setParameter('number', $number)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $id
     *
     * @return Flight
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Flight
    {
        $flight = $this->findOneBy(['id' => $id]);

        if (empty($flight)) {
            throw new EntityNotFoundException("Рейс с id = $id не найден");
        }

        return $flight;
    }

}
