(function () {
    const container = document.getElementById('wanc_container');
    const button = document.getElementById('wp-admin-bar-wanc_display_notification');
    const closeButton = document.getElementById('wanc_container_close');

    const showHiddenNotices = () => {
        const preNoticeStyle = document.getElementById('wanc_pre_notice_style-css');
        if (preNoticeStyle) preNoticeStyle.remove();
    };

    if (typeof wancSettings === 'undefined' || !container || !button) {
        showHiddenNotices();
        return;
    }

    const containsOneOf = (notice, words) => {
        const content = notice.innerHTML.toLowerCase();
        return words.some(word => content.includes(word.toLowerCase()));
    };

    const mustStayInPlace = notice => notice.hasAttribute('aria-hidden')
        || wancSettings.classesKeptInPlace.some(className => notice.classList.contains(className));

    const placeContainer = () => {
        const top = button.offsetTop + button.offsetHeight;
        const paddingTop = parseInt(getComputedStyle(container).paddingTop, 10) || 0;

        container.style.top = top + 'px';
        container.style.height = (window.innerHeight - top - paddingTop) + 'px';
    };

    const moveNotices = () => {
        showHiddenNotices();

        let movedCount = 0;
        document.querySelectorAll('.notice, #message, .fs-notice, .pms-cross-promo').forEach(notice => {
            if (containsOneOf(notice, wancSettings.whiteList) || mustStayInPlace(notice)) return;

            if (containsOneOf(notice, wancSettings.spamWords)) {
                notice.style.setProperty('display', 'none', 'important');
                return;
            }

            container.appendChild(notice);
            if (notice.offsetHeight > 0) movedCount++;
        });

        if (movedCount === 0) return;

        container.querySelector('h3').remove();
        button.firstElementChild.insertAdjacentHTML('beforeend', ' <span id="wanc_display_notification_number">' + movedCount + '</span>');
    };

    button.addEventListener('click', event => {
        event.preventDefault();
        placeContainer();
        container.style.visibility = container.style.visibility === 'visible' ? 'hidden' : 'visible';
    });

    closeButton.addEventListener('click', () => {
        container.style.visibility = 'hidden';
    });

    window.addEventListener('resize', placeContainer);

    placeContainer();
    setTimeout(moveNotices, 500);
})();
