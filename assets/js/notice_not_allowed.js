const wanc_notification_not_allowed = {
    init: function () {
        const adminNotifications = document.querySelectorAll('.notice, #message');
        for (let i = 0 ; i < adminNotifications.length ; i++) {
            adminNotifications[i].remove();
        }
    }
};

wanc_notification_not_allowed.init();
