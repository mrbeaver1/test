<?php

namespace App\Repository;

use App\Entity\Flight;
use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository implements PlaceRepositoryInterface
{
    /**
     * @param ManagerRegistry        $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Place::class);
    }

    /**
     * @param int $id
     *
     * @return Place
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Place
    {
        $place = $this->findOneBy(['id' => $id]);

        if (empty($place)) {
            throw new EntityNotFoundException("Место с id = $id не найден");
        }

        return $place;
    }

    /**
     * @param int $flightId
     * @param int $number
     *
     * @return Place
     *
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function getByNumberAndFlightId(int $flightId, int $number): Place
    {
        $place = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from(Place::class, 'p')
            ->where('p.placeNumber = :number')
            ->setParameter('number', $number)
            ->join(Flight::class, 'f', 'with', 'f.id = p.flight')
            ->andWhere('f.id = :flightId')
            ->setParameter('flightId', $flightId)
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($place)) {
            throw new EntityNotFoundException("Место с номером $number не найдено у рейса с id $flightId");
        }

        return $place;
    }
}
