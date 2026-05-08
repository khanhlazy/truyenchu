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

                this.pointerHandler = null; // No longer used, using @click.outside instead

                window.addEventListener('resize', this.resizeHandler);
                // document.addEventListener('pointerdown', this.pointerHandler, true); // Removed

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

    Alpine.data('readerPreferences', (defaults = {}) => ({
        fontSize: defaults.fontSize || 18,
        lineHeight: defaults.lineHeight || 1.8,
        widthPreset: defaults.widthPreset || 'balanced',
        articleWidth: '800px',
        scrollProgress: 0,
        scrollHandler: null,

        init() {
            try {
                let savedFontSize = this.fontSize;
                let savedLineHeight = this.lineHeight;
                let savedWidth = this.widthPreset;

                try {
                    savedFontSize = Number(localStorage.getItem('reader_font_size') ?? this.fontSize);
                    savedLineHeight = Number(localStorage.getItem('reader_line_height') ?? this.lineHeight);
                    savedWidth = localStorage.getItem('reader_width_preset') ?? this.widthPreset;
                } catch (e) {}

                this.fontSize = savedFontSize;
                this.lineHeight = savedLineHeight;
                this.widthPreset = savedWidth;
                this.updateWidth();

                this.scrollHandler = () => {
                    const winScroll = document.documentElement.scrollTop;
                    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    this.scrollProgress = height > 0 ? (winScroll / height) * 100 : 0;
                };

                window.addEventListener('scroll', this.scrollHandler, { passive: true });
            } catch (e) {
                console.warn('readerPreferences init error:', e);
            }
        },

        destroy() {
            if (this.scrollHandler) {
                window.removeEventListener('scroll', this.scrollHandler);
            }
        },

        get articleStyle() {
            return {
                fontSize: `${this.fontSize}px`,
                lineHeight: this.lineHeight,
            };
        },

        adjustFontSize(delta) {
            this.fontSize = Math.max(12, Math.min(48, this.fontSize + delta));
            try {
                localStorage.setItem('reader_font_size', this.fontSize);
            } catch (e) {}
        },

        setLineHeight(val) {
            this.lineHeight = val;
            try {
                localStorage.setItem('reader_line_height', this.lineHeight);
            } catch (e) {}
        },

        setWidthPreset(val) {
            this.widthPreset = val;
            this.updateWidth();
            try {
                localStorage.setItem('reader_width_preset', this.widthPreset);
            } catch (e) {}
        },

        updateWidth() {
            const widths = {
                compact: '680px',
                balanced: '800px',
                expansive: '1000px'
            };
            this.articleWidth = widths[this.widthPreset] || '800px';
        }
    }));

    // railScroller simplified check
    Alpine.data('railScroller', () => ({
        startX: 0,
        scrollLeft: 0,
        isDown: false,
        moved: 0,

        handleDown(e) {
            this.isDown = true;
            this.startX = (e.pageX || e.touches[0].pageX) - this.$el.offsetLeft;
            this.scrollLeft = this.$el.scrollLeft;
            this.moved = 0;
        },

        handleUp() {
            this.isDown = false;
        },

        handleLeave() {
            this.isDown = false;
        },

        handleMove(e) {
            if (!this.isDown) return;
            e.preventDefault();
            const x = (e.pageX || e.touches[0].pageX) - this.$el.offsetLeft;
            const walk = (x - this.startX) * 1.5;
            this.$el.scrollLeft = this.scrollLeft - walk;
            this.moved = Math.abs(walk);
        },

        handleLinkClick(e) {
            if (this.moved > 10) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        },
    }));
});

Alpine.start();
