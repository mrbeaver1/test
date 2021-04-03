<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository implements TicketRepositoryInterface
{
    /**
     * @param ManagerRegistry        $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * @param int $id
     *
     * @return Ticket
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Ticket
    {
        $ticket = $this->findOneBy(['id' => $id]);

        if (empty($ticket)) {
            throw new EntityNotFoundException("Билет с id = $id не найден");
        }

        return $ticket;
    }
}