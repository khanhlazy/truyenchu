import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        darkMode: document.documentElement.classList.contains('dark'),
        set(value) {
            this.darkMode = value;
            document.documentElement.classList.toggle('dark', value);
            localStorage.setItem('darkMode', value ? 'true' : 'false');
        },
        toggle() {
            this.set(!this.darkMode);
        },
    });

    Alpine.data('uiShell', () => ({
        activeDropdown: null,
        mobileMenuOpen: false,
        resizeHandler: null,
        pointerHandler: null,

        init() {
            try {
                // Global Radio Cleanup: If we are not on a reading page, stop the radio
                if (!document.querySelector('.reading-copy')) {
                    if (window.speechSynthesis) {
                        window.speechSynthesis.cancel();
                    }
                    
                    try {
                        if (localStorage.getItem('radio_playing') === '1') {
                            localStorage.setItem('radio_playing', '0');
                        }
                    } catch (e) {}
                }

                this.resizeHandler = () => {
                    if (window.innerWidth >= 1024) {
                        this.mobileMenuOpen = false;
                    }
                };

                this.pointerHandler = (event) => {
                    if (!this.$refs.headerShell?.contains(event.target)) {
                        this.activeDropdown = null;
                    }
                };

                window.addEventListener('resize', this.resizeHandler);
                document.addEventListener('pointerdown', this.pointerHandler, true);

                this.$watch('mobileMenuOpen', (isOpen) => {
                    document.body.classList.toggle('overflow-hidden', isOpen);

                    if (isOpen) {
                        this.activeDropdown = null;
                    }
                });
            } catch (e) {
                console.warn('uiShell init error:', e);
            }
        },

        destroy() {
            if (this.resizeHandler) {
                window.removeEventListener('resize', this.resizeHandler);
            }

            if (this.pointerHandler) {
                document.removeEventListener('pointerdown', this.pointerHandler, true);
            }

            document.body.classList.remove('overflow-hidden');
        },

        closeAll() {
            this.activeDropdown = null;
            this.mobileMenuOpen = false;
        },

        toggleDropdown(name) {
            this.activeDropdown = this.activeDropdown === name ? null : name;
        },

        closeDropdown(name = null) {
            if (name === null || this.activeDropdown === name) {
                this.activeDropdown = null;
            }
        },

        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },

        toggleTheme() {
            Alpine.store('theme').toggle();
        },
    }));

    Alpine.data('readerPreferences', (defaults = {}) => {
        let savedFontSize = 18;
        let savedLineHeight = 1.8;
        let savedWidth = 'balanced';

        try {
            savedFontSize = Number(localStorage.getItem('reader_font_size') ?? defaults.fontSize ?? 18);
            savedLineHeight = Number(localStorage.getItem('reader_line_height') ?? defaults.lineHeight ?? 1.8);
            savedWidth = localStorage.getItem('reader_width') ?? defaults.widthPreset ?? 'balanced';
        } catch (e) {}

        return {
            fontSize: savedFontSize,
            lineHeight: savedLineHeight,
            widthPreset: savedWidth,
            scrollProgress: 0,
            scrollHandler: null,

            init() {
                this.scrollHandler = () => {
                    const scrollableHeight = document.documentElement.scrollHeight - window.innerHeight;
                    this.scrollProgress = scrollableHeight > 0
                        ? Math.min(100, Math.max(0, Math.round((window.scrollY / scrollableHeight) * 100)))
                        : 0;
                };

                window.addEventListener('scroll', this.scrollHandler, { passive: true });
                window.addEventListener('resize', this.scrollHandler);
                this.scrollHandler();
            },

            destroy() {
                if (this.scrollHandler) {
                    window.removeEventListener('scroll', this.scrollHandler);
                    window.removeEventListener('resize', this.scrollHandler);
                }
            },

            adjustFontSize(delta) {
                this.fontSize = Math.min(28, Math.max(14, this.fontSize + delta));
                try {
                    localStorage.setItem('reader_font_size', String(this.fontSize));
                } catch (e) {}
            },

            adjustLineHeight(delta) {
                const nextValue = Math.min(2.4, Math.max(1.4, this.lineHeight + delta));
                this.lineHeight = Number(nextValue.toFixed(2));
                try {
                    localStorage.setItem('reader_line_height', String(this.lineHeight));
                } catch (e) {}
            },

            setWidthPreset(preset) {
                this.widthPreset = preset;
                try {
                    localStorage.setItem('reader_width', preset);
                } catch (e) {}
            },

            get articleWidth() {
                return {
                    compact: '38rem',
                    balanced: '42rem',
                    expansive: '52rem',
                }[this.widthPreset] ?? '42rem';
            },

            get articleStyle() {
                return {
                    fontSize: `${this.fontSize}px`,
                    lineHeight: String(this.lineHeight),
                    maxWidth: this.articleWidth,
                };
            },
        };
    });

    // railScroller simplified check
    Alpine.data('railScroller', () => ({
        isDown: false,
        startX: 0,
        scrollLeft: 0,
        moved: 0,

        handleMouseDown(e) {
            this.isDown = true;
            this.moved = 0;
            if (this.$refs.track) {
                this.$refs.track.classList.add('is-dragging');
                this.startX = e.pageX - this.$refs.track.offsetLeft;
                this.scrollLeft = this.$refs.track.scrollLeft;
            }
        },

        handleMouseLeave() {
            if (!this.isDown) return;
            this.isDown = false;
            if (this.$refs.track) this.$refs.track.classList.remove('is-dragging');
        },

        handleMouseUp() {
            if (!this.isDown) return;
            this.isDown = false;
            if (this.$refs.track) this.$refs.track.classList.remove('is-dragging');
        },

        handleMouseMove(e) {
            if (!this.isDown || !this.$refs.track) return;
            e.preventDefault();
            const x = e.pageX - this.$refs.track.offsetLeft;
            const walk = (x - this.startX) * 1.5;
            this.moved = Math.abs(walk);
            this.$refs.track.scrollLeft = this.scrollLeft - walk;
        },

        handleLinkClick(e) {
            if (this.moved > 10) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        },

        scroll(direction = 1) {
            if (!this.$refs.track) return;
            const amount = Math.max(280, Math.round(this.$refs.track.clientWidth * 0.72));
            this.$refs.track.scrollBy({
                left: amount * direction,
                behavior: 'smooth',
            });
        },
    }));

    Alpine.data('radioPlayer', (options = {}) => ({
        isPlaying: false,
        isPaused: false,
        chunks: [],
        currentChunk: 0,
        synth: window.speechSynthesis || null,
        timerMinutes: 0,
        timerRemaining: 0,
        timerInterval: null,
        autoNext: true,
        wakeLock: null,
        nextUrl: options.nextUrl ?? null,

        init() {
            try {
                this.autoNext = localStorage.getItem('radio_auto_next') !== '0';

                // Recover Timer state first
                const timerEnd = localStorage.getItem('radio_timer_end');
                if (timerEnd) {
                    const remaining = Math.floor((parseInt(timerEnd) - Date.now()) / 1000);
                    if (remaining > 0) {
                        this.timerRemaining = remaining;
                        this.startCountdown();
                    } else {
                        localStorage.removeItem('radio_timer_end');
                    }
                }

                // Check if we should auto-continue from a previous chapter
                if (localStorage.getItem('radio_playing') === '1') {
                    setTimeout(() => {
                        if (!this.isPlaying) this.start();
                    }, 1500);
                }
                
                this.$watch('autoNext', (val) => {
                    localStorage.setItem('radio_auto_next', val ? '1' : '0');
                });
            } catch (e) {
                console.warn('radioPlayer init error:', e);
            }
        },

        async start() {
            if (!this.synth) {
                alert('Tính năng Radio (đọc truyện) không được hỗ trợ trên trình duyệt này.');
                return;
            }

            if (this.isPaused) {
                this.synth.resume();
                this.isPlaying = true;
                this.isPaused = false;
                this.requestWakeLock();
                return;
            }

            const content = document.querySelector('.reading-copy');
            if (!content) return;

            // Extract content
            const paragraphs = Array.from(content.querySelectorAll('p, div, h1, h2, h3'))
                .map(p => p.innerText.trim())
                .filter(text => text.length > 5);
            
            if (paragraphs.length === 0) return;

            this.chunks = paragraphs;
            this.currentChunk = 0;
            this.isPlaying = true;
            try {
                localStorage.setItem('radio_playing', '1');
            } catch (e) {}
            this.requestWakeLock();
            this.speakNext();
        },

        async requestWakeLock() {
            if ('wakeLock' in navigator) {
                try {
                    this.wakeLock = await navigator.wakeLock.request('screen');
                } catch (err) {}
            }
        },
        
        speakNext() {
            if (!this.isPlaying || !this.synth) return;
            
            if (this.currentChunk >= this.chunks.length) {
                if (this.autoNext && this.nextUrl) {
                    setTimeout(() => window.location.href = this.nextUrl, 2000);
                } else {
                    this.stop();
                }
                return;
            }

            this.synth.cancel();

            const text = this.chunks[this.currentChunk];
            const utterance = new SpeechSynthesisUtterance(text);
            
            const voices = this.synth.getVoices();
            const viVoice = voices.find(v => v.lang.includes('vi') || v.lang.includes('VI')) 
                            || voices.find(v => v.name.includes('Google') && v.lang.includes('en'));

            if (viVoice) utterance.voice = viVoice;
            utterance.rate = 1;

            utterance.onend = () => {
                if (this.isPlaying && !this.isPaused) {
                    this.currentChunk++;
                    setTimeout(() => this.speakNext(), 400);
                }
            };

            utterance.onerror = () => {
                if (this.isPlaying) this.stop();
            };

            this.synth.speak(utterance);
        },

        setTimer(minutes) {
            this.timerMinutes = minutes;
            if (minutes === 0) {
                clearInterval(this.timerInterval);
                this.timerRemaining = 0;
                try { localStorage.removeItem('radio_timer_end'); } catch (e) {}
                return;
            }
            
            this.timerRemaining = minutes * 60;
            const endTimestamp = Date.now() + (minutes * 60 * 1000);
            try { localStorage.setItem('radio_timer_end', endTimestamp.toString()); } catch (e) {}
            
            this.startCountdown();
        },

        startCountdown() {
            clearInterval(this.timerInterval);
            this.timerInterval = setInterval(() => {
                if (this.timerRemaining > 0) {
                    this.timerRemaining--;
                } else {
                    this.stop();
                    clearInterval(this.timerInterval);
                    try { localStorage.removeItem('radio_timer_end'); } catch (e) {}
                }
            }, 1000);
        },

        get timerDisplay() {
            if (this.timerRemaining <= 0) return '';
            const m = Math.floor(this.timerRemaining / 60);
            const s = this.timerRemaining % 60;
            return ` (${m}:${s.toString().padStart(2, '0')})`;
        },

        pause() {
            if (this.synth) this.synth.pause();
            this.isPlaying = false;
            this.isPaused = true;
            if (this.wakeLock) {
                this.wakeLock.release().then(() => this.wakeLock = null);
            }
        },

        stop() {
            if (this.synth) this.synth.cancel();
            this.isPlaying = false;
            this.isPaused = false;
            this.currentChunk = 0;
            try {
                localStorage.setItem('radio_playing', '0');
                localStorage.removeItem('radio_timer_end');
            } catch (e) {}
            if (this.wakeLock) {
                this.wakeLock.release().then(() => this.wakeLock = null);
            }
        },

        toggle() {
            if (this.isPlaying) {
                this.pause();
            } else {
                this.start();
            }
        }
    }));
});

window.Alpine = Alpine;

Alpine.start();
