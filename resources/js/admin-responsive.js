/**
 * Admin Panel Responsive Functionality
 * Handles sidebar toggling on mobile devices and submenu collapse
 */

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const pageBodyWrapper = document.querySelector('.page-body-wrapper');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const body = document.body;

    if (!sidebar) {
        console.error('Sidebar element not found');
        return;
    }

    // Initialize sidebar from localStorage
    const wasCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
    if (wasCollapsed && window.innerWidth > 768) {
        sidebar.classList.add('sidebar-collapsed');
        if (pageBodyWrapper) {
            pageBodyWrapper.classList.add('sidebar-collapsed');
        }
    }

    // Desktop sidebar toggle
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Desktop sidebar toggle clicked');
            sidebar.classList.toggle('sidebar-collapsed');
            if (pageBodyWrapper) {
                pageBodyWrapper.classList.toggle('sidebar-collapsed');
            }

            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
            localStorage.setItem('adminSidebarCollapsed', isCollapsed);
        });
    }

    // Mobile sidebar toggle
    if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Mobile sidebar toggle clicked');
            sidebar.classList.toggle('show');

            // Add overlay class to body
            if (sidebar.classList.contains('show')) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = 'auto';
            }
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = mobileSidebarToggle && mobileSidebarToggle.contains(event.target);

        if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show') && window.innerWidth <= 768) {
            sidebar.classList.remove('show');
            body.style.overflow = 'auto';
        }
    });

    // Close sidebar when a link is clicked on mobile
    const navLinks = sidebar.querySelectorAll('.nav-link:not([data-toggle="collapse"])');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                body.style.overflow = 'auto';
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
            body.style.overflow = 'auto';

            // Restore collapsed state if it was saved
            if (wasCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                if (pageBodyWrapper) {
                    pageBodyWrapper.classList.add('sidebar-collapsed');
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                if (pageBodyWrapper) {
                    pageBodyWrapper.classList.remove('sidebar-collapsed');
                }
            }
        }
    });

    // Bootstrap collapse integration for submenu
    const collapseElements = sidebar.querySelectorAll('[data-toggle="collapse"]');
    collapseElements.forEach(element => {
        element.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                // Toggle the collapse
                const isShown = targetElement.classList.contains('show');
                targetElement.classList.toggle('show');

                // Update aria attributes
                this.setAttribute('aria-expanded', !isShown);

                // Update arrow rotation
                const arrow = this.querySelector('.menu-arrow');
                if (arrow) {
                    if (!isShown) {
                        arrow.style.transform = 'rotate(180deg)';
                    } else {
                        arrow.style.transform = 'rotate(0deg)';
                    }
                }
            }
        });
    });

    // Restore submenu states from localStorage
    collapseElements.forEach(element => {
        const targetId = element.getAttribute('href').replace('#', '');
        const savedState = localStorage.getItem(`submenu-${targetId}`);

        if (savedState === 'open') {
            const targetElement = document.querySelector(`#${targetId}`);
            if (targetElement) {
                targetElement.classList.add('show');
                element.setAttribute('aria-expanded', 'true');

                const arrow = element.querySelector('.menu-arrow');
                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                }
            }
        }
    });

    // Save submenu states
    const collapsibleElements = sidebar.querySelectorAll('.collapse');
    collapsibleElements.forEach(element => {
        element.addEventListener('show.bs.collapse', function() {
            const trigger = document.querySelector(`[href="#${this.id}"]`);
            if (trigger) {
                localStorage.setItem(`submenu-${this.id}`, 'open');
            }
        });

        element.addEventListener('hide.bs.collapse', function() {
            localStorage.removeItem(`submenu-${this.id}`);
        });
    });

    console.log('Admin responsive JS initialized');
});

