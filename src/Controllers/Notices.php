<?php

namespace WANC\Controllers;

use WANC\Core\Views;
use WANC\Entities\Notice;
use WANC\Repositories\NoticeRepository;
use WANC\Services\NoticeListingService;

class Notices
{
    public function __construct()
    {
        add_action('wp_ajax_save_notices', [$this, 'saveNotice']);
    }

    public function sanitizeHtml($content) {
        return strip_tags(stripslashes($content));
    }

    public function saveNotice(){
        $rawNotices = array_map([$this, 'sanitizeHtml'], $_POST['notices']);

        if (empty(wp_get_current_user())) {
            wp_send_json_error(['message' => 'Cannot get the current user'], 422);
        }

        if (empty($rawNotices)) {
            wp_send_json_error(['message' => 'No notices to save'], 422);
        }

        $noticeRepository = new NoticeRepository();
        $dateNow = new \DateTime('now');
        $dateToSave = $dateNow->format('Y-m-d H:i:s');
        $userId = wp_get_current_user()->ID;

        foreach ($rawNotices as $rawNotice) {
            $rawNotice = strip_tags($rawNotice, '<p><a><div>');
            $rawNotice = str_replace('"', '', $rawNotice);
            $notice = $noticeRepository->getOneByContentUserId($rawNotice, $userId);

            if (empty($notice)) {
                $notice = new Notice();
                $notice->setContent($rawNotice);
                $notice->setUserId($userId);
                $notice->setFirstSeenDate($dateToSave);
                $currentNumberSeen = 0;
            } else {
                $currentNumberSeen = $notice->getNumberSeen();
            }

            $notice->setLastSeenDate($dateToSave);
            $notice->setNumberSeen($currentNumberSeen + 1);

            $noticeRepository->save($notice);
        }

        $return = [
            'notice' => $notice,
            'user' => wp_get_current_user()
        ];

        wp_send_json($return);
    }

    public function listing() {
        $noticeListingService = new NoticeListingService();
        Views::includeViews('notice/listing', ['noticeListingService' => $noticeListingService]);
    }
}