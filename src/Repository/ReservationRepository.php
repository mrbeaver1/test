<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository implements ReservationRepositoryInterface
{
    /**
     * @param ManagerRegistry        $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @param int $reservationId
     * @param int $userId
     *
     * @return Reservation
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function getByReservationIdAndUserId(int $reservationId, int $userId): Reservation
    {
        $reservation = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from(Reservation::class, 'r')
            ->where('r.id = :reservationId')
            ->setParameter('reservationId', $reservationId)
            ->join(User::class, 'u', 'with', 'u.id = r.user')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($reservation)) {
            throw new EntityNotFoundException("Бронь с id $reservationId не найдена у юзера с id $userId");
        }

        return $reservation;
    }

    /**
     * @param int $id
     *
     * @return Reservation
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Reservation
    {
        $reservation = $this->findOneBy(['id' => $id]);

        if (empty($reservation)) {
            throw new EntityNotFoundException("Бронь с id = $id не найден");
        }

        return $reservation;
    }
}
