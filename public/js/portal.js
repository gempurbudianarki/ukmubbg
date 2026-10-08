/**
 * UKM ILMU KOMPUTER - PORTAL JAVASCRIPT
 * Lightweight, accessible interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const toggleBtn = document.querySelector('.nav-mobile-toggle');
    const mobileNav = document.querySelector('.mobile-nav');

    if (toggleBtn && mobileNav) {
        toggleBtn.addEventListener('click', () => {
            const isOpen = mobileNav.classList.toggle('open');
            toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // 2. Alert Dismissible
    const alertCloses = document.querySelectorAll('.alert-close');
    alertCloses.forEach(btn => {
        btn.addEventListener('click', () => {
            const alert = btn.closest('.alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 200);
            }
        });
    });

    // 3. Project / Gallery Client-side Filter Tabs
    const filterTabs = document.querySelectorAll('.filter-tab[data-filter]');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const filterValue = tab.getAttribute('data-filter');
            
            filterTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filterItems = document.querySelectorAll('[data-category]');
            filterItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // 4. Smooth Anchor Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href && href.length > 1) {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                    if (mobileNav && mobileNav.classList.contains('open')) {
                        mobileNav.classList.remove('open');
                        if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        });
    });

    // Close mobile nav when clicking outside
    document.addEventListener('click', (e) => {
        if (mobileNav && mobileNav.classList.contains('open')) {
            if (!mobileNav.contains(e.target) && !toggleBtn.contains(e.target)) {
                mobileNav.classList.remove('open');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // 5. Terminal Tabs Switcher
    window.switchTermTab = function(btn, tabId) {
        document.querySelectorAll('.terminal-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const bodyOverview = document.getElementById('term-body-overview');
        const bodyDivisions = document.getElementById('term-body-divisions');

        if (tabId === 'overview') {
            if (bodyOverview) bodyOverview.style.display = 'block';
            if (bodyDivisions) bodyDivisions.style.display = 'none';
        } else {
            if (bodyOverview) bodyOverview.style.display = 'none';
            if (bodyDivisions) bodyDivisions.style.display = 'block';
        }
    };
});
