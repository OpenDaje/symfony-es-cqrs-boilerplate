<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\OauthUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<OauthUser>
 *
 * @method OauthUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method OauthUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method OauthUser[]    findAll()
 * @method OauthUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method null loadUserByIdentifier(string $identifier)
 */
class OauthUserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OauthUser::class);
    }

    public function save(OauthUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OauthUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (! $user instanceof OauthUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

//    /**
//     * @return OauthUser[] Returns an array of OauthUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OauthUser
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function loadUserByUsername(string $identifier)
    {
        $entityManager = $this->getEntityManager();

        // Check if the identifier is an email address
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $this->findOneBy([
                'email' => $identifier,
            ]);
        }
        if (Uuid::isValid($identifier)) {
            return $this->findOneBy([
                'uuid' => Uuid::fromString($identifier)->toBinary(),
            ]);
        }

        return null;
    }
}
