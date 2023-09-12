<?php

namespace WANC\Controllers;

use WANC\Entities\Notice;
use WANC\Repositories\NoticeRepository;

class Notices
{
    private $allowedKses;

    public function __construct()
    {
        $this->allowedKses = wp_kses_allowed_html() + ['div' => []];
        add_action('wp_ajax_save_notices', [$this, 'saveNotice']);
    }

    public function sanitizeHtml($content) {
        return wp_kses(stripslashes($content), $this->allowedKses);
    }

    public function saveNotice(){
        echo '<pre>';
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

        foreach ($rawNotices as $rawNotice) {
            $rawNotice = strip_tags($rawNotice, '<p><a><div>');
            $rawNotice = str_replace('"', '', $rawNotice);
            $notice = $noticeRepository->getOneByContent($rawNotice);

            if (empty($notice)) {
                $notice = new Notice();
                $notice->setContent($rawNotice);
                $notice->setUserId(wp_get_current_user()->ID);
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
}