<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Gift;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\Gift\DailyGift;
use Ares\User\Entity\User;
use Ares\User\Exception\Gift\DailyGiftException;
use Ares\User\Repository\Gift\DailyGiftRepository;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class PickGiftService
 *
 * @package Ares\User\Service\Gift
 */
class PickGiftService
{
    /**
     * @var DailyGiftRepository
     */
    private DailyGiftRepository $dailyGiftRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * PickGiftService constructor.
     *
     * @param DailyGiftRepository $dailyGiftRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        DailyGiftRepository $dailyGiftRepository,
        UserRepository $userRepository
    ) {
        $this->dailyGiftRepository = $dailyGiftRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Checks and fetches user daily gift.
     *
     * @param User $user
     * @return CustomResponseInterface
     * @throws DailyGiftException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(User $user): CustomResponseInterface
    {
        $userId = $user->getId();

        /** @var DailyGift $dailyGift */
        $dailyGift = $this->dailyGiftRepository->getOneBy([
            'user_id' => $userId
        ]);

        if (!$dailyGift) {
            $dailyGift = $this->getNewDailyGift($userId);
        }

        $pickTime = $dailyGift->getPickTime();

        if (time() <= $pickTime) {
            throw new DailyGiftException(__('User already picked the daily gift.'), 409);
        }

        $this->applyGift($dailyGift, $user, $dailyGift->getAmount());

        return response()
            ->setData($dailyGift);
    }

    /**
     * Returns random gift amount.
     *
     * @return int
     * @throws \Exception
     */
    private function getRandomGiftAmount(): int
    {
        return random_int(3000, 5000);
    }

    /**
     * Applies gift to user.
     *
     * @param   DailyGift  $dailyGift
     * @param   User       $user
     * @param   int        $amount
     *
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     */
    private function applyGift(DailyGift $dailyGift, User $user, int $amount): void
    {
        $credits = $user->getCredits();
        $credits += $amount;

        $user->setCredits($credits);
        $dailyGift->setPickTime(strtotime('+1 day'));

        $this->userRepository->update($user);
        $this->dailyGiftRepository->update($dailyGift);
    }

    /**
     * Saves and returns new daily gift.
     *
     * @param   int  $userId
     *
     * @return DailyGift
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Exception
     */
    private function getNewDailyGift(int $userId): DailyGift
    {
        $dailyGift = new DailyGift();

        $dailyGift
            ->setUserId($userId)
            ->setPickTime(strtotime('+1 day'))
            ->setAmount($this->getRandomGiftAmount());

        $this->dailyGiftRepository->save($dailyGift);

        return $dailyGift;
    }
}