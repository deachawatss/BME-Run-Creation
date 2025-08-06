/**
 * Micro-interactions JavaScript for BME Create Run Bulk Table
 * Subtle animations and feedback for enhanced user experience
 * 
 * Features:
 * - Hover animations and button feedback
 * - Loading states and skeleton animations
 * - Success/error feedback animations
 * - Scroll-based interactions
 * - Performance-optimized animations
 */

(function($) {
    'use strict';
    
    // Micro-interactions Configuration
    const MicroInteractions = {
        // Animation preferences
        animations: {
            enabled: true,
            duration: {
                fast: 150,
                normal: 300,
                slow: 500
            },
            easing: {
                smooth: 'cubic-bezier(0.4, 0, 0.2, 1)',
                bounce: 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                ease: 'ease-in-out'
            }
        },
        
        // Performance settings
        performance: {
            useTransforms: true,
            useWillChange: true,
            throttleScrollEvents: true
        }
    };
    
    // Initialize micro-interactions when document is ready
    $(document).ready(function() {
        // Check for reduced motion preference
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            MicroInteractions.animations.enabled = false;
            MicroInteractions.animations.duration.fast = 1;
            MicroInteractions.animations.duration.normal = 1;
            MicroInteractions.animations.duration.slow = 1;
        }
        
        if (MicroInteractions.animations.enabled) {
            initializeMicroInteractions();
        }
        
        // Micro-interactions initialized
    });
    
    /**
     * Initialize all micro-interaction features
     */
    function initializeMicroInteractions() {
        initializeButtonAnimations();
        initializeHoverEffects();
        initializeLoadingAnimations();
        initializeFeedbackAnimations();
        initializeScrollEffects();
        initializeTableInteractions();
        initializeFormInteractions();
        initializeModalAnimations();
    }
    
    /**
     * Enhanced Button Animations
     */
    function initializeButtonAnimations() {
        // Primary CTA button animations
        $('.table-primary-cta, .btn-minimal.btn-primary').on('mouseenter', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'translateY(-2px) scale(1.02)',
                'box-shadow': '0 8px 25px rgba(162, 105, 74, 0.25)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        }).on('mouseleave', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'translateY(0) scale(1)',
                'box-shadow': '0 4px 12px rgba(162, 105, 74, 0.15)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        });
        
        // Action button hover effects
        $('.action-btn').on('mouseenter', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'translateY(-1px) scale(1.05)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.bounce}`
            });
        }).on('mouseleave', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'translateY(0) scale(1)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        });
        
        // Click ripple effect
        $('.table-primary-cta, .action-btn, .table-control-btn').on('click', function(e) {
            if (!MicroInteractions.animations.enabled) return;
            
            createRippleEffect($(this), e);
        });
        
        // Button press animation
        $('.table-primary-cta, .btn-minimal, .action-btn').on('mousedown', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'scale(0.98)',
                'transition': `all ${MicroInteractions.animations.duration.fast / 2}ms ${MicroInteractions.animations.easing.smooth}`
            });
        }).on('mouseup mouseleave', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            setTimeout(() => {
                $(this).css({
                    'transform': '',
                    'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
                });
            }, 50);
        });
    }
    
    /**
     * Create ripple effect on button click
     */
    function createRippleEffect($element, event) {
        const rect = $element[0].getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        const $ripple = $('<div class="ripple-effect"></div>').css({
            position: 'absolute',
            left: x + 'px',
            top: y + 'px',
            width: size + 'px',
            height: size + 'px',
            borderRadius: '50%',
            background: 'rgba(255, 255, 255, 0.3)',
            transform: 'scale(0)',
            pointerEvents: 'none',
            zIndex: 1
        });
        
        // Ensure button has relative positioning
        if ($element.css('position') === 'static') {
            $element.css('position', 'relative');
        }
        
        $element.append($ripple);
        
        // Animate ripple
        $ripple.animate({
            transform: 'scale(2)',
            opacity: 0
        }, MicroInteractions.animations.duration.normal, function() {
            $ripple.remove();
        });
    }
    
    /**
     * Enhanced Hover Effects
     */
    function initializeHoverEffects() {
        // Table row hover enhancement
        $('.enhanced-table tbody tr').on('mouseenter', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'translateY(-1px)',
                'box-shadow': '0 4px 12px rgba(93, 64, 55, 0.08)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
            
            // Highlight related elements
            $(this).find('.action-btn').css({
                'opacity': '1',
                'transform': 'scale(1)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        }).on('mouseleave', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'translateY(0)',
                'box-shadow': '',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
            
            $(this).find('.action-btn').css({
                'opacity': '',
                'transform': '',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        });
        
        // Filter chip hover effects
        $('.table-filter-chip').on('mouseenter', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'translateY(-2px) scale(1.05)',
                'box-shadow': '0 4px 12px rgba(162, 105, 74, 0.15)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.bounce}`
            });
        }).on('mouseleave', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'translateY(0) scale(1)',
                'box-shadow': '',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        });
        
        // Status chip subtle pulse on hover
        $('.status-chip').on('mouseenter', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'scale(1.05)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        }).on('mouseleave', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'scale(1)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        });
    }
    
    /**
     * Loading States and Skeleton Animations
     */
    function initializeLoadingAnimations() {
        // Enhanced DataTable loading
        if (typeof window.table !== 'undefined') {
            window.table.on('processing.dt', function(e, settings, processing) {
                if (processing) {
                    showLoadingAnimation();
                } else {
                    hideLoadingAnimation();
                }
            });
        }
        
        // Form submission loading states
        $('form').on('submit', function() {
            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"], input[type="submit"]');
            
            if ($submitBtn.length) {
                showButtonLoading($submitBtn);
            }
        });
    }
    
    /**
     * Show loading animation for tables
     */
    function showLoadingAnimation() {
        if (!MicroInteractions.animations.enabled) return;
        
        const $container = $('.enhanced-table-container');
        if (!$container.find('.loading-overlay').length) {
            const $overlay = $(`
                <div class="loading-overlay" style="
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(255, 255, 255, 0.8);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                    backdrop-filter: blur(2px);
                ">
                    <div class="loading-spinner" style="
                        width: 40px;
                        height: 40px;
                        border: 3px solid var(--color-brown-200);
                        border-top: 3px solid var(--color-brown-600);
                        border-radius: 50%;
                        animation: spin 1s linear infinite;
                    "></div>
                </div>
            `);
            
            $container.css('position', 'relative').append($overlay);
            
            // Fade in overlay
            $overlay.css('opacity', '0').animate({ opacity: 1 }, MicroInteractions.animations.duration.fast);
        }
    }
    
    /**
     * Hide loading animation
     */
    function hideLoadingAnimation() {
        const $overlay = $('.loading-overlay');
        if ($overlay.length) {
            $overlay.animate({ opacity: 0 }, MicroInteractions.animations.duration.fast, function() {
                $overlay.remove();
            });
        }
    }
    
    /**
     * Show button loading state
     */
    function showButtonLoading($button) {
        if (!MicroInteractions.animations.enabled) return;
        
        const originalText = $button.text();
        const originalHtml = $button.html();
        
        $button.data('original-content', originalHtml);
        $button.prop('disabled', true);
        $button.html(`
            <span style="display: inline-flex; align-items: center; gap: 8px;">
                <div style="width: 16px; height: 16px; border: 2px solid currentColor; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                Processing...
            </span>
        `);
    }
    
    /**
     * Success/Error Feedback Animations
     */
    function initializeFeedbackAnimations() {
        // Success feedback
        $(document).on('success.bme', function(e, message) {
            showFeedbackAnimation('success', message || 'Operation completed successfully');
        });
        
        // Error feedback
        $(document).on('error.bme', function(e, message) {
            showFeedbackAnimation('error', message || 'An error occurred');
        });
        
        // Form validation feedback
        $(document).on('invalid', 'input, select, textarea', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            const $field = $(this);
            $field.addClass('shake-animation');
            
            setTimeout(() => {
                $field.removeClass('shake-animation');
            }, MicroInteractions.animations.duration.slow);
        });
    }
    
    /**
     * Show feedback animation (toast-style)
     */
    function showFeedbackAnimation(type, message) {
        if (!MicroInteractions.animations.enabled) return;
        
        const colors = {
            success: {
                bg: 'var(--color-success-50)',
                border: 'var(--color-success-200)',
                text: 'var(--color-success-800)',
                icon: '✓'
            },
            error: {
                bg: 'var(--color-danger-50)',
                border: 'var(--color-danger-200)',
                text: 'var(--color-danger-800)',
                icon: '✕'
            },
            warning: {
                bg: 'var(--color-warning-50)',
                border: 'var(--color-warning-200)',
                text: 'var(--color-warning-800)',
                icon: '⚠'
            }
        };
        
        const color = colors[type] || colors.success;
        
        const $toast = $(`
            <div class="feedback-toast" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${color.bg};
                border: 1px solid ${color.border};
                color: ${color.text};
                padding: 16px 20px;
                border-radius: 12px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 14px;
                font-weight: 500;
                max-width: 400px;
                transform: translateX(100%);
                transition: all ${MicroInteractions.animations.duration.normal}ms ${MicroInteractions.animations.easing.smooth};
            ">
                <span style="font-size: 18px;">${color.icon}</span>
                <span>${message}</span>
                <button type="button" style="
                    background: none;
                    border: none;
                    color: ${color.text};
                    font-size: 18px;
                    cursor: pointer;
                    opacity: 0.7;
                    margin-left: auto;
                " onclick="$(this).closest('.feedback-toast').remove();">×</button>
            </div>
        `);
        
        $('body').append($toast);
        
        // Slide in
        setTimeout(() => {
            $toast.css('transform', 'translateX(0)');
        }, 50);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            $toast.css('transform', 'translateX(100%)');
            setTimeout(() => {
                $toast.remove();
            }, MicroInteractions.animations.duration.normal);
        }, 5000);
    }
    
    /**
     * Scroll-based Effects
     */
    function initializeScrollEffects() {
        if (!MicroInteractions.animations.enabled) return;
        
        let ticking = false;
        
        function updateScrollEffects() {
            const scrollTop = $(window).scrollTop();
            
            // Sticky header enhancement
            const $stickyHeader = $('.enhanced-table thead');
            if ($stickyHeader.length) {
                if (scrollTop > 100) {
                    $stickyHeader.addClass('scrolled');
                } else {
                    $stickyHeader.removeClass('scrolled');
                }
            }
            
            // Parallax effect for table container
            const $tableContainer = $('.enhanced-table-container');
            if ($tableContainer.length && scrollTop > 0) {
                const parallaxOffset = scrollTop * 0.1;
                $tableContainer.css('transform', `translateY(${parallaxOffset}px)`);
            }
            
            ticking = false;
        }
        
        $(window).on('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateScrollEffects);
                ticking = true;
            }
        });
        
        // Smooth scroll to top functionality
        const $scrollToTop = $(`
            <button class="scroll-to-top" style="
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: var(--color-brown-600);
                color: white;
                border: none;
                box-shadow: 0 4px 12px rgba(162, 105, 74, 0.3);
                cursor: pointer;
                z-index: 1000;
                opacity: 0;
                transform: scale(0);
                transition: all ${MicroInteractions.animations.duration.normal}ms ${MicroInteractions.animations.easing.bounce};
                font-size: 18px;
            ">↑</button>
        `);
        
        $('body').append($scrollToTop);
        
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 300) {
                $scrollToTop.css({
                    'opacity': '1',
                    'transform': 'scale(1)'
                });
            } else {
                $scrollToTop.css({
                    'opacity': '0',
                    'transform': 'scale(0)'
                });
            }
        });
        
        $scrollToTop.on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, MicroInteractions.animations.duration.slow);
        });
    }
    
    /**
     * Table-specific Interactions
     */
    function initializeTableInteractions() {
        // Row expansion animation
        $(document).on('click', '.btn-view', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            const $row = $(this).closest('tr');
            const $expansionRow = $row.next('.expansion-row');
            
            if ($expansionRow.length) {
                // Collapse animation
                $expansionRow.find('.expansion-content').slideUp(MicroInteractions.animations.duration.normal, function() {
                    $expansionRow.remove();
                    $row.removeClass('expanded');
                });
            } else {
                // Expand animation will be handled by the main table code
                // We just add a slight delay for the animation
                setTimeout(() => {
                    const $newExpansionRow = $row.next('.expansion-row');
                    if ($newExpansionRow.length) {
                        $newExpansionRow.find('.expansion-content').hide().slideDown(MicroInteractions.animations.duration.normal);
                    }
                }, 50);
            }
        });
        
        // Column visibility animation
        $('.table-column-option input[type="checkbox"]').on('change', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            const $checkbox = $(this);
            const isChecked = $checkbox.is(':checked');
            
            // Add a subtle animation to the checkbox
            $checkbox.closest('.table-column-option').css({
                'transform': 'scale(1.05)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.bounce}`
            });
            
            setTimeout(() => {
                $checkbox.closest('.table-column-option').css({
                    'transform': 'scale(1)',
                    'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
                });
            }, 100);
        });
    }
    
    /**
     * Form Interactions
     */
    function initializeFormInteractions() {
        // Input focus animations
        $('input, select, textarea').on('focus', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'scale(1.02)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        }).on('blur', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            $(this).css({
                'transform': 'scale(1)',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        });
        
        // Search input animation
        $('#global-search').on('input', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            const $input = $(this);
            const hasValue = $input.val().length > 0;
            
            if (hasValue) {
                $input.css({
                    'box-shadow': '0 0 0 3px rgba(162, 105, 74, 0.1), 0 4px 12px rgba(162, 105, 74, 0.15)',
                    'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
                });
            } else {
                $input.css({
                    'box-shadow': '',
                    'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
                });
            }
        });
    }
    
    /**
     * Modal Animations
     */
    function initializeModalAnimations() {
        // Modal show animation
        $(document).on('show.bs.modal', '.modal', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            const $modal = $(this);
            $modal.find('.modal-dialog').css({
                'transform': 'scale(0.8) translateY(-50px)',
                'opacity': '0',
                'transition': `all ${MicroInteractions.animations.duration.normal}ms ${MicroInteractions.animations.easing.bounce}`
            });
        });
        
        $(document).on('shown.bs.modal', '.modal', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            const $modal = $(this);
            $modal.find('.modal-dialog').css({
                'transform': 'scale(1) translateY(0)',
                'opacity': '1'
            });
        });
        
        // Modal hide animation
        $(document).on('hide.bs.modal', '.modal', function() {
            if (!MicroInteractions.animations.enabled) return;
            
            const $modal = $(this);
            $modal.find('.modal-dialog').css({
                'transform': 'scale(0.9) translateY(20px)',
                'opacity': '0',
                'transition': `all ${MicroInteractions.animations.duration.fast}ms ${MicroInteractions.animations.easing.smooth}`
            });
        });
    }
    
    // Add CSS animations to document head
    const animationStyles = `
        <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes shake {
            0%, 20%, 50%, 80%, 100% { transform: translateX(0); }
            10%, 30%, 70%, 90% { transform: translateX(-2px); }
            40%, 60% { transform: translateX(2px); }
        }
        
        .shake-animation {
            animation: shake ${MicroInteractions.animations.duration.slow}ms ease-in-out;
        }
        
        .enhanced-table thead.scrolled {
            box-shadow: 0 2px 8px rgba(93, 64, 55, 0.1);
        }
        
        .scroll-to-top:hover {
            background: var(--color-brown-700) !important;
            transform: scale(1.1) !important;
        }
        
        /* Smooth transitions for all interactive elements */
        .action-btn,
        .table-filter-chip,
        .table-primary-cta,
        .table-control-btn,
        .enhanced-table tbody tr {
            will-change: transform, box-shadow, opacity;
        }
        
        /* Reduce motion for accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        </style>
    `;
    
    if (MicroInteractions.animations.enabled) {
        $('head').append(animationStyles);
    }
    
    // Expose MicroInteractions object for external use
    window.MicroInteractions = MicroInteractions;
    
    // Utility functions
    window.showFeedback = function(type, message) {
        $(document).trigger(type + '.bme', [message]);
    };
    
    window.showSuccess = function(message) {
        showFeedbackAnimation('success', message);
    };
    
    window.showError = function(message) {
        showFeedbackAnimation('error', message);
    };
    
    window.showWarning = function(message) {
        showFeedbackAnimation('warning', message);
    };
    
})(jQuery);