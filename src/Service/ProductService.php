<?php

namespace Product\Service;

use Content\Service\ItemService;
use stdClass;
use User\Service\AccountService;
use User\Service\UtilityService;
use function explode;
use function in_array;
use function is_object;
use function json_decode;

class ProductService implements ServiceInterface
{

    /** @var AccountService */
    protected AccountService $accountService;


    /** @var UtilityService */
    protected UtilityService $utilityService;
    /* @var array */
    protected array $config;

    /** @var ItemService */
    protected ItemService $itemService;

    public function __construct(
        AccountService      $accountService,
        UtilityService      $utilityService,
                            ItemService $itemService,
                            $config
    )
    {
        $this->accountService = $accountService;
        $this->utilityService = $utilityService;
        $this->itemService = $itemService;
        $this->config = $config;
    }

    public function start($account, $params): array
    {
        $time = time();
        $conversationId = $this->conversationIdBuilder(['user_id' => $account['id'], 'time' => $time]);
        $conversationSlug = $this->conversationSlugBuilder(['conversation_id' => $conversationId, 'page' => 1]);
        $listParams = [
            'conversation_id' => $conversationId,
            'page' => 1,
            'size' => 1,
            'slug' => $conversationSlug,
            'status' => 1,
            'time_create' => $time,
            'time_update' => 0
        ];
        $information = $listParams;
        $information['user_id'] = $account['id'];
        $information['users'] = [$account['id'], $params['user_id']];
        $information['profiles'] = [$this->accountService->getAccountProfile(['id' => $account['id']]), $this->accountService->getAccountProfile(['id' => $params['user_id']])];
        $information['type'] = 'private-Product';
        $message = $params['message'];
        $message['user_id'] = $account['id'];
        $message['time_send'] = $time;
        $message['id'] = $this->ProductIdBuilder(['slug' => $conversationSlug, 'user_id' => $account['id'], 'time' => $time]);

        $information['message'] = [$message];
        $listParams['information'] = json_encode($information);
        return $this->canonizeConversation($this->ProductRepository->addItem($listParams));

    }

    public function reply($account, $params): array
    {
        $time = time();
        $conversation = $this->canonizeConversation($this->ProductRepository->getItem(['slug' => $params['slug']]));
        if (empty($conversation)) {
            return [];
        }
        $message = $params['message'];
        $message['user_id'] = $account['id'];
        $message['time_send'] = $time;
        $message['id'] = $this->ProductIdBuilder(['slug' => $params['slug'], 'user_id' => $account['id'], 'time' => $time]);

        array_unshift($conversation['message'], $message);
        $conversation['size'] = $conversation['size'] + 1;
        $conversation['time_update'] = $time;
        $listParams = [
            'slug' => $params['slug'],
            'size' => $conversation['size'],
            'time_update' => $time,
            'information' => json_encode($conversation)
        ];
        return $this->canonizeConversation($this->ProductRepository->editItem($listParams));
    }

    public function update($account, $params): array
    {
        $message_id = $params['id'];
        $parts = explode('-', $message_id);
        $record_id = $parts[0] . '-' . $parts[1];
        $record = $this->get($account, ['slug' => $record_id]);

        foreach ($record['message'] as &$item) {
            if ($item["id"] === $message_id) {
                $item = $params;
                break;
            }
        }
        $listParams = [
            'slug' => $record_id,
            'time_update' => time(),
            'information' => json_encode($record)
        ];
        return $this->canonizeConversation($this->ProductRepository->editItem($listParams));
    }


    public function createGroup($params, $account): array
    {
        $time = time();
        $groupProfileSlug = uniqid();
        $groupProfile = [
            'slug' => $groupProfileSlug,
            'name' => $params['profile']['name'] ?? '',
            'bio' => $params['profile']['bio'] ?? '',
            'identity' => $params['profile']['identity'] ?? '',
            'status' => $params['profile']['status'] ?? 1,
            'time_created' => $time,
            'time_created_view' => $this->utilityService->date($time),
            'avatar' => $params['profile']['avatar'] ?? '',
        ];
        $conversationId = $this->conversationIdBuilder(['user_id' => $account['id'], 'time' => $time]);
        $conversationSlug = $this->conversationSlugBuilder(['conversation_id' => $conversationId, 'page' => 1]);
        $listParams = [
            'conversation_id' => $conversationId,
            'page' => 1,
            'type' => 'group-Product',
            'size' => 1,
            'slug' => $conversationSlug,
            'status' => 1,
            'time_create' => $time,
            'time_update' => 0
        ];
        $information = $listParams;
        $information['user_id'] = $account['id'];
        $information['users'] = array_merge([$account['id']], array_map('intval', explode(',', $params['user_id'])));
        $information['profiles'] = [];
        foreach ($information['users'] as $userId) {
            $information['profiles'][] = $this->accountService->getAccountProfile(['id' => $userId]);
        }
        $information['type'] = 'group-Product';
        $information['profile'] = $groupProfile;
        $information['admins'] = [$account['id']];

        $message = $params['message'];
        $message['user_id'] = $account['id'];
        $message['time_send'] = $time;
        $message['id'] = $this->ProductIdBuilder(['slug' => $conversationSlug, 'user_id' => $account['id'], 'time' => $time]);

        $information['message'] = [$message];
        $listParams['information'] = json_encode($information);
        return $this->canonizeConversation($this->ProductRepository->addItem($listParams));

    }


    public function get($account, $params): array
    {
        return $this->canonizeConversation($this->ProductRepository->getItem(['slug' => $params['slug']]));
    }

    private function conversationIdBuilder($params): string
    {
        return md5($params['user_id'] . '-' . $params['time'] . '-' . rand(100000, 999999));
    }

    private function conversationSlugBuilder(array $params): string
    {
        return $params['conversation_id'] . '-' . $params['page'];
    }

    private function ProductIdBuilder(array $params): string
    {
        return $params['slug'] . '-' . $params['user_id'] . '-' . $params['time'];
    }

    private function canonizeConversation(object|array $addItem): array
    {
        if (empty($addItem)) {
            return [];
        }

        if (is_object($addItem)) {
            $addItem = [
                'information' => $addItem->getInformation(),
            ];
        } else {
            $addItem = [
                'information' => $addItem['information'],
            ];
        }
        return json_decode($addItem['information'], true);
    }

}
