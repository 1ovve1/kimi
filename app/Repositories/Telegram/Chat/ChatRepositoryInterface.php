<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\Chat;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\Types\ChannelData;
use App\Data\Telegram\Chat\Types\GroupData;
use App\Data\Telegram\Chat\Types\PrivateData;
use App\Data\Telegram\Chat\Types\SupergroupData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Repositories\Abstract\RepositoryInterface;

interface ChatRepositoryInterface extends RepositoryInterface
{
    public function save(ChatData $chatData): ChatData;

    public function create(ChatData $chatData): ChatData;

    public function createPrivate(PrivateData $privateData): PrivateData;

    public function findOrCreateGroup(GroupData $groupData): GroupData;

    public function findOrCreateSupergroup(SupergroupData $supergroupData): SupergroupData;

    public function findOrCreateChannel(ChannelData $channelData): ChannelData;

    /**
     * @throws ChatNotFoundException
     * @throws UserNotFoundException
     */
    public function appendUser(ChatData $chatData, UserData $userData): UserData;

    /**
     * @throws ChatNotFoundException
     */
    public function setInteractiveMode(ChatData $chatData, bool $interactiveMode): ChatData;

    /**
     * Find chat in db
     *
     * @throws ChatNotFoundException
     */
    public function find(ChatData $chatData): ChatData;
}
