<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Guestbook\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Guestbook\Entity\Guestbook;
use Ares\Guestbook\Repository\GuestbookRepository;

/**
 * Class CreateGuestbookEntryService
 *
 * @package Ares\Guestbook\Service
 */
class CreateGuestbookEntryService
{
    /**
     * CreateGuestbookEntryService constructor.
     *
     * @param   GuestbookRepository  $guestbookRepository
     */
    public function __construct(
        private GuestbookRepository $guestbookRepository
    ) {}

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $entry = $this->getNewEntry($userId, $data);

        /** @var Guestbook $entry */
        $entry = $this->guestbookRepository->save($entry);
        $entry->getUser();

        return response()
            ->setData($entry);
    }

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return Guestbook
     */
    private function getNewEntry(int $userId, array $data): Guestbook
    {
        $entry = new Guestbook();

        return $entry
            ->setContent($data['content'])
            ->setUserId($userId)
            ->setProfileId($data['profile_id'])
            ->setGuildId($data['guild_id'])
            ->setLikes(0)
            ->setDislikes(0)
            ->setCreatedAt(new \DateTime());
    }
}
