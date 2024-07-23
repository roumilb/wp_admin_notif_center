const wanc_notification = {
    wancContainer: '',
    preAdminNotifications: '',
    adminNotifications: [],
    notificationsDisplayed: [],
    buttonNotification: '',
    closeButton: '',
    notificationSettings: '',
    classNeedToBeDisplay: {
        'welcome-panel': 0,
        'update-message': 0,
        'hidden': 0
    },
    spamWords: '',
    whiteList: '',
    init: function () {
        //We get the notification center
        this.wancContainer = document.querySelector('#wanc_container');

        //We get all the notifications to display in the modal
        this.preAdminNotifications = document.querySelectorAll('.notice, #message, .fs-notice, .pms-cross-promo');

        //We get the notification button
        this.buttonNotification = document.getElementById('wp-admin-bar-wanc_display_notification');

        //We get the close button
        this.closeButton = document.getElementById('wanc_container_close');

        //We get the notification settings
        this.notificationSettings = JSON.parse(this.wancContainer.getAttribute('wanc-data-display'));
        this.notificationSettings = Object.assign(this.notificationSettings, this.classNeedToBeDisplay);
        this.spamWords = this.notificationSettings.spam_words;
        this.whiteList = this.notificationSettings.white_list;

        this.initWordList('spamWords');
        this.initWordList('whiteList');
        this.initWhiteList();
        this.initNotificationCenterStyle();
        this.initClickDisplayNotificationCenter();
        this.initCloseNotificationCenter();
        this.initWindowRezise();

        setTimeout(() => {
            this.moveNotifications();
        }, 500);
    },
    initNotificationCenterStyle: function () {
        //We get the top and left to place it
        const top = this.buttonNotification.offsetTop + this.buttonNotification.offsetHeight;

        const paddingTopContainer = document.defaultView.getComputedStyle(this.wancContainer, '').getPropertyValue('padding-top').replace(/[^-\d\.]/g, '');

        //We place it
        this.wancContainer.style.top = top + 'px';
        this.wancContainer.style.height = (window.innerHeight - (top + parseInt(paddingTopContainer))) + 'px';
    },
    moveNotifications: function () {
        const preNoticeStyle = document.getElementById('wanc_pre_notice_style-css');
        if (preNoticeStyle) {
            preNoticeStyle.remove();
        }
        //If there is no notification to display => out
        if (this.adminNotifications.length < 1) return true;

        let numberOfNotification = 0;
        for (let i = 0; i < this.adminNotifications.length; i++) {
            let containsSpamWord = false;
            if (this.spamWords.length > 0) {
                for (let j = 0; j < this.spamWords.length; j++) {
                    if (this.adminNotifications[i].innerHTML.toLowerCase().indexOf(this.spamWords[j].toLowerCase()) === -1) continue;
                    containsSpamWord = true;
                    break;
                }
            }
            //if this is a critical or update notification we don't display it
            if (this.needToBeDisplayed(this.adminNotifications[i])
                || this.adminNotifications[i].hasAttribute('aria-hidden')
                || this.adminNotifications[i].classList.contains('hidden')) {
                continue;
            }

            if (containsSpamWord) {
                this.adminNotifications[i].setAttribute('style', 'display: none !important;');
                continue;
            }

            //We display it if this is a not a crucial notification
            this.wancContainer.appendChild(this.adminNotifications[i]);
            this.notificationsDisplayed.push(this.adminNotifications[i]);

            if (this.wancContainer.lastChild.offsetHeight > 0) numberOfNotification++;
        }

        if (numberOfNotification > 0) {
            //We erase the text saying there is no notification to display
            document.querySelector('#wanc_container h3').remove();
        }

        if (numberOfNotification) {
            //We display the number of notification
            this.buttonNotification.childNodes[0].innerHTML += ' <span id="wanc_display_notification_number">' + numberOfNotification + '</span>';
        }
    },
    initClickDisplayNotificationCenter: function () {
        this.buttonNotification.addEventListener('click', () => {
            this.initNotificationCenterStyle();
            this.wancContainer.style.visibility = this.wancContainer.style.visibility === 'visible' ? 'hidden' : 'visible';
        });
    },
    initCloseNotificationCenter: function () {
        this.closeButton.addEventListener('click', () => {
            this.wancContainer.style.visibility = 'hidden';
        });
    },
    initWindowRezise: function () {
        window.addEventListener('resize', () => {
            this.initNotificationCenterStyle();
        });
    },
    needToBeDisplayed: function (notification) {
        //let's run the settings and check if there is something to display
        for (let [noticeClass, displayNotice] of Object.entries(this.notificationSettings)) {
            if (displayNotice !== 1 && notification.classList.contains(noticeClass)) return true;
        }

        //If not we move it in the notification center
        return false;
    },
    initWordList: function (option) {
        // If no words in the option, we set it with an empty array
        if (this[option] === '') {
            this[option] = [];
            return true;
        }

        // we split the words list
        this[option] = this[option].split(',');

        // we set a nice array by removing all white space at the start and the end of each word
        const formattedWords = [];
        this[option].map(word => {
            formattedWords.push(word.trim());
        });

        this[option] = formattedWords;

        return true;
    },
    initWhiteList: function () {
        for (let i = 0; i < this.preAdminNotifications.length; i++) {
            let notWhiteListed = true;
            if (this.whiteList.length > 0) {
                for (let j = 0; j < this.whiteList.length; j++) {
                    if (this.preAdminNotifications[i].innerHTML.toLowerCase().indexOf(this.whiteList[j].toLowerCase()) === -1) continue;
                    notWhiteListed = false;
                    break;
                }
            }
            if (notWhiteListed) this.adminNotifications.push(this.preAdminNotifications[i]);
        }
    },
};

wanc_notification.init();