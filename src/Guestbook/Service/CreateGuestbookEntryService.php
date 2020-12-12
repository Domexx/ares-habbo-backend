<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guestbook\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Guestbook\Entity\Guestbook;
use Ares\Guestbook\Exception\GuestbookException;
use Ares\Guestbook\Interfaces\Response\GuestbookResponseCodeInterface;
use Ares\Guestbook\Repository\GuestbookRepository;
use PHLAK\Config\Config;

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
     * @param GuestbookRepository $guestbookRepository
     * @param Config              $config
     */
    public function __construct(
        private GuestbookRepository $guestbookRepository,
        private Config $config
    ) {}

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException|GuestbookException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $commentCount = $this->guestbookRepository
            ->getUserCommentCount(
                $userId,
                $data['profile_id'],
                $data['guild_id']
            );

        if ($commentCount >= $this->config->get('hotel_settings.guestbook.comment_max')) {
            throw new GuestbookException(
                __('User exceeded allowed comments'),
                GuestbookResponseCodeInterface::RESPONSE_GUESTBOOK_USER_EXCEEDED_COMMENTS,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

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
