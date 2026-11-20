    <script>
        /* ══════════ SVG ICONS (inline) ══════════ */
        const svgIcons = {
            ship: '<svg class="icon-svg icon-svg-lg" viewBox="0 0 24 24"><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M19.38 20A11.6 11.6 0 0021 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/><path d="M19 13V7a2 2 0 00-2-2H7a2 2 0 00-2 2v6"/><path d="M12 1v4"/></svg>',
            truck: '<svg class="icon-svg icon-svg-lg" viewBox="0 0 24 24"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/><path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"/><circle cx="7" cy="18" r="2"/><path d="M15 18h-4"/><circle cx="17" cy="18" r="2"/></svg>',
            plane: '<svg class="icon-svg icon-svg-lg" viewBox="0 0 24 24"><path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.4-.1.9.3 1.1L11 12l-2 3H6l-1 1 3 2 2 3 1-1v-3l3-2 3.8 7.3c.2.4.7.5 1.1.3l.5-.3c.4-.2.6-.6.5-1.1z"/></svg>',
            package: '<svg class="icon-svg icon-svg-lg" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" fill="none" stroke="var(--gold)" stroke-width="1.5"/><path d="M9 12l2 2 4-4" fill="none" stroke="var(--gold)" stroke-width="1.5"/></svg>',
            mapPin: '<svg class="icon-svg icon-svg-lg" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>',
            clock: '<svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            globe: '<svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>',
            lock: '<svg class="icon-svg" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>',
            userCheck: '<svg class="icon-svg" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>',
            check: '<svg class="icon-svg" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        };

        /* ══════════ SERVICES ══════════ */
        const services = [{
                icon: 'ship',
                title: 'Ocean Freight',
                tagline: 'Reliable global sea logistics.',
                desc: 'Full container and less-than-container shipping across major international trade routes with real-time tracking.'
            },
            {
                icon: 'truck',
                title: 'Road Freight',
                tagline: 'Efficient overland transport.',
                desc: 'Flexible road haulage solutions for local and cross-border deliveries with optimized routing and scheduling.'
            },
            {
                icon: 'plane',
                title: 'Air Freight',
                tagline: 'Speed meets precision.',
                desc: 'Time-critical cargo handled with priority access to global air networks for express and charter shipments.'
            },
            {
                icon: 'package',
                title: 'Door Step Delivery',
                tagline: 'Last-mile excellence.',
                desc: 'End-to-end delivery from warehouse to doorstep with proof of delivery and live status notifications.'
            },
            {
                icon: 'mapPin',
                title: 'Pick Solution',
                tagline: 'Smart pickup logistics.',
                desc: 'Scheduled and on-demand pickup services with route optimization and seamless warehouse integration.'
            },
        ];

        const grid = document.getElementById('servicesGrid');
        services.forEach((s, i) => {
            const div = document.createElement('div');
            div.className = 'service-card fade-up';
            div.style.transitionDelay = i * 0.1 + 's';
            div.innerHTML = `
        <div class="service-icon">${svgIcons[s.icon]}</div>
        <h3>${s.title}</h3>
        <p class="tagline">${s.tagline}</p>
        <p class="desc">${s.desc}</p>`;
            grid.appendChild(div);
        });

        /* ══════════ REASONS ══════════ */
        const reasons = [{
                icon: 'clock',
                text: '24/7 Global Operations Command'
            },
            {
                icon: 'globe',
                text: 'Elite Aviation Partners Worldwide'
            },
            {
                icon: 'lock',
                text: 'High-Security Logistics Protocols'
            },
            {
                icon: 'userCheck',
                text: 'VIP Client Confidentiality'
            },
            {
                icon: 'check',
                text: 'On-Time Performance Guarantee'
            },
        ];

        const list = document.getElementById('reasonsList');
        reasons.forEach((r, i) => {
            const div = document.createElement('div');
            div.className = 'reason-item fade-left';
            div.style.transitionDelay = i * 0.1 + 's';
            div.innerHTML = `${svgIcons[r.icon]}<span>${r.text}</span>`;
            list.appendChild(div);
        });

        /* ══════════ YEAR ══════════ */
        document.getElementById('footerYear').textContent = new Date().getFullYear();

        /* ══════════ THEME TOGGLE ══════════ */
        const sunSVG = '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
        const moonSVG = '<svg viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>';

        const toggle = document.getElementById('themeToggle');
        const root = document.documentElement;
        const saved = localStorage.getItem('theme') || 'dark';
        root.setAttribute('data-theme', saved);
        toggle.innerHTML = saved === 'dark' ? sunSVG : moonSVG;

        toggle.addEventListener('click', () => {
            const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            toggle.innerHTML = next === 'dark' ? sunSVG : moonSVG;
        });

        /* ══════════ MOBILE MENU ══════════ */
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });

        document.querySelectorAll('.mobile-link').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.remove('open'));
        });

        /* ══════════ SCROLL ANIMATIONS ══════════ */
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '-50px'
        });

        document.querySelectorAll('.fade-up, .fade-left').forEach(el => observer.observe(el));
    </script>

    <!-- Vite entry (keeps Vite dev server happy) -->

    <aside id="lovable-badge" role="complementary" dir="ltr" lang="en" aria-label="Edit with Lovable">
        <a id="lovable-badge-cta" target="_blank" href="https://lovable.dev/projects/f81a5f4d-b5cc-4d6d-a167-f674c3e3250d?utm_source=lovable-badge" rel="noopener" aria-label="Edit with Lovable">
		<span id="lovable-badge-text">Edit with</span>
		<svg xmlns="http://www.w3.org/2000/svg" width="52" height="16" fill="none" viewBox="0 0 52 16">
  <path fill="#FCFBF8" fill-rule="evenodd" d="M20.318 5.25c.643 0 1.206.14 1.69.418a2.81 2.81 0 0 1 1.118 1.191c.266.513.4 1.115.4 1.807s-.134 1.296-.4 1.812a2.81 2.81 0 0 1-1.118 1.193c-.484.278-1.047.418-1.69.418s-1.208-.14-1.695-.418a2.85 2.85 0 0 1-1.125-1.193c-.262-.516-.393-1.12-.393-1.812s.131-1.294.393-1.807a2.848 2.848 0 0 1 1.125-1.191c.487-.279 1.052-.418 1.695-.418Zm0 1.425c-.27 0-.504.076-.7.228-.193.147-.34.37-.443.67-.102.295-.153.66-.153 1.093 0 .435.05.801.153 1.1.102.3.25.524.443.676.196.147.43.22.7.22.27 0 .502-.073.694-.22.193-.152.341-.375.443-.67.103-.299.153-.667.153-1.106 0-.65-.112-1.145-.337-1.481a1.08 1.08 0 0 0-.953-.51ZM32.7 5.25c.61 0 1.127.1 1.549.3.422.197.74.48.953.849.217.368.325.809.325 1.32v2.704c0 .29.02.562.062.812.044.245.108.4.19.466V12h-1.935a5.895 5.895 0 0 1-.105-.684 7.745 7.745 0 0 1-.02-.228 2.293 2.293 0 0 1-.151.203c-.205.242-.47.437-.793.584-.32.143-.685.215-1.094.215-.406 0-.77-.08-1.094-.24a1.845 1.845 0 0 1-.756-.682 1.984 1.984 0 0 1-.27-1.045c0-.606.178-1.069.535-1.388.356-.324.87-.534 1.542-.633l1.125-.16c.225-.032.403-.074.534-.123a.622.622 0 0 0 .288-.196.549.549 0 0 0 .093-.327.65.65 0 0 0-.11-.367.702.702 0 0 0-.32-.27c-.14-.07-.31-.105-.51-.105-.32 0-.576.083-.768.251-.193.164-.298.39-.314.676h-1.923c.016-.434.147-.82.393-1.155.25-.34.596-.604 1.039-.792.442-.189.954-.283 1.535-.283Zm.99 3.498a.98.98 0 0 1-.215.14 2.49 2.49 0 0 1-.584.178l-.473.092c-.315.061-.553.156-.713.283-.155.127-.233.305-.233.534 0 .23.084.412.252.547.168.135.383.203.645.203s.494-.058.694-.173c.201-.118.355-.282.461-.49.11-.21.166-.448.166-.714v-.6Zm4.526-2.375c.065-.125.138-.243.221-.349.197-.25.437-.44.719-.571.282-.135.6-.203.952-.203.528 0 .988.138 1.377.412.389.275.688.67.896 1.186.21.512.314 1.12.314 1.824 0 .7-.107 1.309-.32 1.825-.213.512-.518.906-.915 1.18-.393.275-.854.412-1.383.412-.352 0-.667-.062-.946-.184a1.832 1.832 0 0 1-.7-.554 2.2 2.2 0 0 1-.234-.383V12h-1.843V3h1.862v3.373Zm1.284.296c-.274 0-.51.085-.707.253-.192.163-.338.397-.436.7a3.376 3.376 0 0 0-.148 1.05c0 .406.05.759.148 1.058.098.299.243.53.436.694.197.164.433.246.707.246.279 0 .512-.082.7-.246.193-.164.336-.395.43-.694.099-.3.148-.652.148-1.058 0-.405-.05-.757-.147-1.056-.095-.299-.238-.53-.43-.694a1.015 1.015 0 0 0-.7-.253Zm9.416-1.419c.602 0 1.136.131 1.604.393.466.262.829.643 1.086 1.143.263.5.394 1.097.394 1.794 0 .25-.002.449-.006.596H47.51c.018.288.071.538.164.75a1.3 1.3 0 0 0 .491.596c.214.13.465.196.757.196.319 0 .583-.082.792-.246.209-.167.34-.403.393-.706h1.862a2.48 2.48 0 0 1-.485 1.235 2.54 2.54 0 0 1-1.051.805c-.439.188-.949.283-1.53.283-.655 0-1.225-.125-1.708-.375a2.672 2.672 0 0 1-1.13-1.143c-.267-.508-.4-1.137-.4-1.887 0-.712.14-1.327.418-1.843a2.86 2.86 0 0 1 1.155-1.186c.491-.27 1.051-.405 1.678-.405Zm-.044 1.345c-.274 0-.516.068-.725.203a1.29 1.29 0 0 0-.479.59 2.045 2.045 0 0 0-.132.498h2.562a1.873 1.873 0 0 0-.138-.602 1.061 1.061 0 0 0-.418-.516 1.243 1.243 0 0 0-.67-.173Z" clip-rule="evenodd"/>
  <path fill="#FCFBF8" d="m26.605 9.995 1.342-4.566h1.924L27.628 12h-2.07l-2.33-6.57h1.98l1.397 4.565Zm-13.013.143h2.256c1.632 0 1.421 1.837 1.418 1.861h-5.603V3h1.93v7.138Zm31.516 1.861h-1.862V3h1.862v8.999Z"/>
  <path fill="url(#a)" fill-rule="evenodd" d="M2.7 3c1.492 0 2.7 1.192 2.7 2.663v1.012h.9c1.49 0 2.7 1.192 2.7 2.662S7.791 12 6.3 12H0V5.663C0 4.193 1.209 3 2.7 3Z" clip-rule="evenodd"/>
  <defs>
    <radialGradient id="a" cx="0" cy="0" r="1" gradientTransform="matrix(-1.54236 7.07838 -10.231 -2.15602 4.627 5.022)" gradientUnits="userSpaceOnUse">
      <stop offset=".106" stop-color="#FE7B02"/>
      <stop offset=".394" stop-color="#FE3F21"/>
      <stop offset=".608" stop-color="#F858BC"/>
      <stop offset=".929" stop-color="#575ECF"/>
    </radialGradient>
  </defs>
</svg>


	</a>

        <span id="lovable-badge-divider" aria-hidden="true"></span>

        <button id="lovable-badge-close" aria-label="Dismiss" title="Dismiss" type="button">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 16 16" aria-hidden="true">
			<path d="M10.646 4.646a.5.5 0 1 1 .707.708L8.707 8l2.646 2.646a.5.5 0 1 1-.707.707L8 8.707l-2.646 2.646a.5.5 0 1 1-.708-.707L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708L8 7.293l2.646-2.647Z"/>
		</svg>
	</button>
    </aside>
    <script>
        // Don't show the lovable-badge if the page is in an iframe or if it's being rendered by puppeteer (screenshot service)
        if (window.self !== window.top || navigator.userAgent.includes('puppeteer')) {
            // the page is in an iframe
            var badge = document.getElementById('lovable-badge');
            if (badge) {
                badge.style.display = 'none';
            }
        }

        // Add click event listener to close button with animation
        var closeButton = document.getElementById('lovable-badge-close');
        if (closeButton) {
            closeButton.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                var badge = document.getElementById('lovable-badge');
                if (badge) {
                    badge.classList.add('closing');
                    setTimeout(function() {
                        if (badge) {
                            badge.style.display = 'none';
                        }
                    }, 240);
                }
            });
        }
    </script>