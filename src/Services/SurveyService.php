<?php

namespace WANC\Services;

class SurveyService
{
    public function __construct()
    {
        add_filter( 'plugin_action_links_wp-admin-notification-center/index.php', [$this, 'displaySurveyListing'] );
    }

    public function displaySurveyListing($links) {
        $links[] = '<a target="_blank" href="https://forms.gle/ACCET5QCjWr2NvLQA">' . __( 'Take the survey!' ) . '</a>';

        return $links;
    }
}