(() => {
    if (window.idleTimeoutInitialized) {
        // console.log('Idle timeout script already initialized');
        return;
    }
    window.idleTimeoutInitialized = true;

    document.addEventListener('alpine:init', () => {
        const idleChannel = new BroadcastChannel('idleTimeoutChannel');

        if (!Alpine.store('idleTimeoutStore')) {
            Alpine.store('idleTimeoutStore', {
                idleDurationSecs: null,
                warningBefore: null,
                timeLeftSecs: null,
                warningDisplayed: false,
                timer: null,
                resetCountdown(skipBroadcast = false) {
                    // console.log('Store resetCountdown called, skipBroadcast:', skipBroadcast);
                    this.timeLeftSecs = this.idleDurationSecs;
                    this.warningDisplayed = false;

                    if (this.timer) {
                        clearInterval(this.timer);
                    }

                    if (!skipBroadcast) {
                        idleChannel.postMessage({type: 'resetCountdown'});
                    }

                    this.startCountdown();
                },
                startCountdown() {
                    // console.log('Store startCountdown called');
                    if (this.timer) {
                        clearInterval(this.timer);
                    }
                    this.timer = setInterval(() => {
                        if (this.timeLeftSecs > 0) {
                            this.timeLeftSecs--;

                            if (
                                !this.warningDisplayed &&
                                this.timeLeftSecs <= this.warningBefore
                            ) {
                                this.showWarning();
                            }
                        } else {
                            clearInterval(this.timer);
                            this.logoutUser();
                        }
                    }, 1000);
                },
                showWarning() {
                    if (!this.warningDisplayed) {
                        const totalSeconds = this.warningBefore;
                        const minutes = Math.floor(totalSeconds / 60);
                        const seconds = totalSeconds % 60;

                        let timeMessage = '';
                        if (minutes > 0) {
                            timeMessage += `${minutes} minute${minutes !== 1 ? 's' : ''}`;
                        }

                        if (seconds > 0 || minutes === 0) {
                            if (minutes > 0) {
                                timeMessage += ' ';
                            }
                            timeMessage += `${seconds} second${seconds !== 1 ? 's' : ''}`;
                        }

                        new FilamentNotification()
                            .title('Your session is about to expire')
                            .body(`You will be logged out in ${timeMessage}.`)
                            .danger()
                            .color('danger')
                            .send();

                        this.warningDisplayed = true;
                    }
                },
                logoutUser() {
                    if (this.timer) {
                        clearInterval(this.timer);
                    }

                    document.getElementById('auto-logout-form').submit();
                },
            });

            const store = Alpine.store('idleTimeoutStore');

            const form = document.getElementById('auto-logout-form');
            if (form) {
                const autoLogoutEnabled = form.dataset.autoLogoutEnabled === '1';
                if (!autoLogoutEnabled) {
                    return;
                }

                store.idleDurationSecs = parseInt(form.dataset.duration, 10);
                store.warningBefore = parseInt(form.dataset.warnBefore, 10);
                store.timeLeftSecs = store.idleDurationSecs;

                // console.log('Auto logout enabled.');
                // console.log('Idle duration:', store.idleDurationSecs, 'seconds');
                // console.log('Warning before:', store.warningBefore, 'seconds');
            } else {
                return;
            }

            store.resetCountdown();

            const activityEvents = [
                'mousemove',
                'keypress',
                'click',
                'touchstart',
                'focus',
                'change',
                'mouseover',
                'mouseout',
                'mousedown',
                'mouseup',
                'keydown',
                'keyup',
                'submit',
                'reset',
                'select',
                'scroll',
            ];

            if (!window.idleTimeoutEventListenersAttached) {
                activityEvents.forEach((event) => {
                    window.addEventListener(event, () => {
                        idleChannel.postMessage({type: 'resetCountdown'});
                        store.resetCountdown(true);
                    });
                });
                window.idleTimeoutEventListenersAttached = true;
            }

            idleChannel.addEventListener('message', function (event) {
                if (event.data?.type === 'resetCountdown') {
                    store.resetCountdown(true);
                } else if (event.data?.type === 'showWarning') {
                    store.showWarning();
                } else if (event.data?.type === 'logout') {
                    store.logoutUser();
                }
            });

            if (form.dataset.showTimeleft === '1') {
                let el = document.getElementById('idle-timeout-element');

                if (!el) {
                    el = document.createElement('div');
                    el.id = 'idle-timeout-element';
                    document.body.appendChild(el);
                }

                const timeLeftText = form.dataset.timeLeftText || null;

                el.setAttribute(
                    'x-data',
                    JSON.stringify({
                        timeLeftText: timeLeftText,
                    })
                );

                let timerDisplay = el.querySelector('#timer-display');

                if (!timerDisplay) {
                    timerDisplay = document.createElement('div');
                    timerDisplay.id = 'timer-display';
                    el.appendChild(timerDisplay);
                }

                timerDisplay.setAttribute(
                    'x-text',
                    'timeLeftText + Math.floor($store.idleTimeoutStore.timeLeftSecs / 60) + "m " + ($store.idleTimeoutStore.timeLeftSecs % 60) + "s"'
                );

                Alpine.initTree(el);
            }
        }
    });
})();
