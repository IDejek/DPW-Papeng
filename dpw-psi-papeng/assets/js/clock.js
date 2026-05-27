/**
 * Real-Time Clock — WIT & WIB
 * @package DPW_PSIPapeng
 */
(function () {
    'use strict';

    function updateClocks() {
        var now = new Date();
        var clocks = document.querySelectorAll('.dpw-clock-time');
        clocks.forEach(function (el) {
            var offset = parseInt(el.getAttribute('data-utc-offset'), 10) || 0;
            var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            var target = new Date(utc + (offset * 3600000));
            var h = String(target.getHours()).padStart(2, '0');
            var m = String(target.getMinutes()).padStart(2, '0');
            var s = String(target.getSeconds()).padStart(2, '0');
            el.textContent = h + ':' + m + ':' + s;
        });
    }

    updateClocks();
    setInterval(updateClocks, 1000);
})();
