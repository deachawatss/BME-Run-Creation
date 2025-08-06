/**
 * Enhanced UX JavaScript for BME Create Run Bulk Table
 * Advanced table interactions, accessibility features, and user experience enhancements
 * 
 * Features:
 * - Advanced keyboard navigation
 * - Accessibility improvements
 * - Progressive disclosure patterns
 * - Smart defaults and preferences
 * - Performance optimizations
 */

(function($) {
    'use strict';
    
    // Enhanced UX Configuration
    const EnhancedUX = {
        // Keyboard navigation configuration
        keyboard: {
            enabled: true,
            shortcuts: {
                'ctrl+n': 'createNew',
                'ctrl+f': 'focusSearch',
                'escape': 'clearFilters',
                'ctrl+e': 'exportData',
                'ctrl+shift+c': 'toggleColumns'
            }
        },
        
        // Accessibility configuration
        accessibility: {
            announceChanges: true,
            highContrast: false,
            reducedMotion: false
        },
        
        // Performance configuration
        performance: {
            debounceDelay: 300,
            virtualScrolling: false,
            lazyLoading: true
        },
        
        // User preferences
        preferences: {
            tableView: 'comfortable', // comfortable | compact
            defaultFilters: {},
            columnVisibility: {},
            sortPreferences: {}
        }
    };
    
    // Initialize Enhanced UX features when document is ready
    $(document).ready(function() {
        initializeEnhancedUX();
    });
    
    /**
     * Initialize all enhanced UX features
     */
    function initializeEnhancedUX() {
        // Load user preferences
        loadUserPreferences();
        
        // Initialize features
        initializeKeyboardNavigation();
        initializeAccessibilityFeatures();
        initializeSmartDefaults();
        initializeProgressiveDisclosure();
        initializePerformanceOptimizations();
        initializeErrorHandling();
        
        // Enhanced UX features initialized
    }
    
    /**
     * Advanced Keyboard Navigation
     */
    function initializeKeyboardNavigation() {
        if (!EnhancedUX.keyboard.enabled) return;
        
        // Global keyboard shortcuts
        $(document).on('keydown', function(e) {
            var shortcut = '';
            
            if (e.ctrlKey) shortcut += 'ctrl+';
            if (e.shiftKey) shortcut += 'shift+';
            if (e.altKey) shortcut += 'alt+';
            
            shortcut += e.key.toLowerCase();
            
            var action = EnhancedUX.keyboard.shortcuts[shortcut];
            if (action && typeof window[action] === 'function') {
                e.preventDefault();
                window[action]();
            }
        });
        
        // Table navigation with arrow keys
        $('.enhanced-table tbody').on('keydown', 'tr', function(e) {
            var $currentRow = $(this);
            var $table = $currentRow.closest('.enhanced-table');
            var $rows = $table.find('tbody tr:visible');
            var currentIndex = $rows.index($currentRow);
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < $rows.length - 1) {
                        $rows.eq(currentIndex + 1).focus();
                    }
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        $rows.eq(currentIndex - 1).focus();
                    }
                    break;
                    
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    // Trigger row expansion or primary action
                    $currentRow.find('.btn-view').trigger('click');
                    break;
                    
                case 'Delete':
                    e.preventDefault();
                    // Trigger delete action with confirmation
                    $currentRow.find('.btn-delete').trigger('click');
                    break;
            }
        });
        
        // Make table rows focusable
        $('.enhanced-table tbody tr').attr('tabindex', '0');
    }
    
    /**
     * Accessibility Features
     */
    function initializeAccessibilityFeatures() {
        // Add ARIA labels and descriptions
        addAriaLabels();
        
        // Implement live regions for dynamic content
        setupLiveRegions();
        
        // High contrast mode detection
        if (window.matchMedia && window.matchMedia('(prefers-contrast: high)').matches) {
            EnhancedUX.accessibility.highContrast = true;
            $('body').addClass('high-contrast-mode');
        }
        
        // Reduced motion detection
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            EnhancedUX.accessibility.reducedMotion = true;
            $('body').addClass('reduced-motion');
        }
        
        // Focus management
        initializeFocusManagement();
    }
    
    /**
     * Add comprehensive ARIA labels
     */
    function addAriaLabels() {
        // Table structure
        $('.enhanced-table').attr({
            'role': 'table',
            'aria-label': 'Bulk Production Runs Data Table'
        });
        
        $('.enhanced-table thead').attr('role', 'rowgroup');
        $('.enhanced-table tbody').attr('role', 'rowgroup');
        $('.enhanced-table tr').attr('role', 'row');
        $('.enhanced-table th').attr('role', 'columnheader');
        $('.enhanced-table td').attr('role', 'cell');
        
        // Sortable columns
        $('.enhanced-table th.sortable').attr({
            'aria-sort': 'none',
            'tabindex': '0'
        });
        
        // Action buttons
        $('.action-btn').each(function() {
            var $btn = $(this);
            var action = '';
            
            if ($btn.hasClass('btn-view')) action = 'View details';
            else if ($btn.hasClass('btn-edit')) action = 'Edit run';
            else if ($btn.hasClass('btn-delete')) action = 'Delete run';
            
            $btn.attr({
                'aria-label': action + ' for run ' + $btn.data('id'),
                'role': 'button'
            });
        });
        
        // Filter chips
        $('.table-filter-chip').attr({
            'role': 'button',
            'aria-pressed': 'false',
            'tabindex': '0'
        });
        
        // Update aria-pressed when chips are activated
        $(document).on('click', '.table-filter-chip', function() {
            var pressed = $(this).hasClass('active');
            $(this).attr('aria-pressed', pressed);
        });
    }
    
    /**
     * Setup live regions for dynamic announcements
     */
    function setupLiveRegions() {
        // Create live region for table updates
        if (!$('#table-status-live-region').length) {
            $('<div id="table-status-live-region" aria-live="polite" aria-atomic="true" class="sr-only"></div>').appendTo('body');
        }
        
        // Announce table changes
        function announceTableChange(message) {
            if (EnhancedUX.accessibility.announceChanges) {
                $('#table-status-live-region').text(message);
            }
        }
        
        // Monitor table updates
        if (typeof window.table !== 'undefined') {
            window.table.on('draw', function() {
                var info = window.table.page.info();
                announceTableChange(`Table updated. Showing ${info.start + 1} to ${info.end} of ${info.recordsDisplay} runs.`);
            });
            
            window.table.on('search', function() {
                setTimeout(function() {
                    var info = window.table.page.info();
                    announceTableChange(`Search completed. ${info.recordsDisplay} runs found.`);
                }, 100);
            });
        }
    }
    
    /**
     * Focus Management
     */
    function initializeFocusManagement() {
        // Track focus for better UX
        var lastFocusedElement = null;
        
        $(document).on('focusin', function(e) {
            lastFocusedElement = e.target;
        });
        
        // Return focus after modal closes
        $(document).on('hidden.bs.modal', '.modal', function() {
            if (lastFocusedElement && $(lastFocusedElement).is(':visible')) {
                $(lastFocusedElement).focus();
            }
        });
        
        // Focus trap for modals
        $(document).on('keydown', '.modal', function(e) {
            if (e.key === 'Tab') {
                var $modal = $(this);
                var $focusableElements = $modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                var $firstElement = $focusableElements.first();
                var $lastElement = $focusableElements.last();
                
                if (e.shiftKey) {
                    if ($(e.target).is($firstElement)) {
                        e.preventDefault();
                        $lastElement.focus();
                    }
                } else {
                    if ($(e.target).is($lastElement)) {
                        e.preventDefault();
                        $firstElement.focus();
                    }
                }
            }
        });
    }
    
    /**
     * Smart Defaults and User Preferences
     */
    function initializeSmartDefaults() {
        // Auto-save user preferences
        $(document).on('change', '.table-density-toggle button, .table-column-option input', function() {
            saveUserPreferences();
        });
        
        // Remember search terms
        $('#global-search').on('input', debounce(function() {
            EnhancedUX.preferences.lastSearch = $(this).val();
            saveUserPreferences();
        }, EnhancedUX.performance.debounceDelay));
        
        // Smart filter suggestions based on data
        implementSmartFilterSuggestions();
    }
    
    /**
     * Progressive Disclosure Patterns
     */
    function initializeProgressiveDisclosure() {
        // Collapsible sections
        $('.collapsible-header').on('click', function() {
            var $header = $(this);
            var $content = $header.next('.collapsible-content');
            var isExpanded = $header.attr('aria-expanded') === 'true';
            
            $header.attr('aria-expanded', !isExpanded);
            $content.slideToggle(200);
            
            // Update icon
            $header.find('.collapse-icon').toggleClass('fa-chevron-down fa-chevron-up');
        });
        
        // Progressive loading for large datasets
        if (EnhancedUX.performance.lazyLoading) {
            implementLazyLoading();
        }
    }
    
    /**
     * Performance Optimizations
     */
    function initializePerformanceOptimizations() {
        // Debounce search input
        $('#global-search').off('keyup search').on('keyup search', debounce(function() {
            if (typeof window.table !== 'undefined') {
                window.table.search(this.value).draw();
            }
        }, EnhancedUX.performance.debounceDelay));
        
        // Throttle scroll events
        $(window).on('scroll', throttle(function() {
            // Handle scroll-based optimizations
            updateStickyHeaders();
        }, 16)); // ~60fps
        
        // Optimize table redraws
        if (typeof window.table !== 'undefined') {
            // Batch table operations
            let tableUpdateQueue = [];
            
            function processTableUpdates() {
                if (tableUpdateQueue.length > 0) {
                    window.table.rows().invalidate().draw(false);
                    tableUpdateQueue = [];
                }
            }
            
            // Process queue periodically
            setInterval(processTableUpdates, 100);
        }
    }
    
    /**
     * Error Handling and User Feedback
     */
    function initializeErrorHandling() {
        // Global error handler for AJAX requests
        $(document).ajaxError(function(event, xhr, settings, error) {
            // AJAX Error logged for debugging
            
            // Show user-friendly error message
            showNotification('error', 'Something went wrong. Please try again later.');
        });
        
        // Form validation feedback
        $(document).on('submit', 'form', function(e) {
            var $form = $(this);
            var isValid = this.checkValidity();
            
            if (!isValid) {
                e.preventDefault();
                
                // Highlight invalid fields
                $form.find(':invalid').each(function() {
                    $(this).addClass('error').attr('aria-invalid', 'true');
                });
                
                // Focus first invalid field
                $form.find(':invalid').first().focus();
                
                showNotification('warning', 'Please check the highlighted fields.');
            }
        });
    }
    
    /**
     * Utility Functions
     */
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Throttle function
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    // User Preferences Management
    function loadUserPreferences() {
        try {
            const saved = localStorage.getItem('bme-table-preferences');
            if (saved) {
                EnhancedUX.preferences = { ...EnhancedUX.preferences, ...JSON.parse(saved) };
            }
        } catch (e) {
            // Failed to load user preferences
        }
    }
    
    function saveUserPreferences() {
        try {
            localStorage.setItem('bme-table-preferences', JSON.stringify(EnhancedUX.preferences));
        } catch (e) {
            // Failed to save user preferences
        }
    }
    
    // Smart Filter Suggestions
    function implementSmartFilterSuggestions() {
        // Analyze data to suggest relevant filters
        if (typeof window.table !== 'undefined') {
            window.table.on('draw', function() {
                // Get unique values from status column for filter suggestions
                var statusData = window.table.column(7, {search: 'applied'}).data().unique();
                
                // Update filter chips based on available data
                updateFilterChips('status', statusData.toArray());
            });
        }
    }
    
    function updateFilterChips(type, values) {
        var $container = $('.table-filter-chips');
        var existingChips = $container.find(`[data-filter="${type}"]`);
        
        // Show/hide chips based on available data
        existingChips.each(function() {
            var chipValue = $(this).data('value');
            var hasData = values.some(v => v && v.toLowerCase().includes(chipValue.toLowerCase()));
            $(this).toggle(hasData);
        });
    }
    
    // Lazy Loading Implementation
    function implementLazyLoading() {
        // Implement virtual scrolling for large datasets
        // This would require server-side support
        // Lazy loading ready for implementation
    }
    
    // Update Sticky Headers
    function updateStickyHeaders() {
        // Enhance sticky header behavior based on scroll position
        var scrollTop = $(window).scrollTop();
        var $stickyHeader = $('.enhanced-table thead');
        
        if (scrollTop > 100) {
            $stickyHeader.addClass('scrolled');
        } else {
            $stickyHeader.removeClass('scrolled');
        }
    }
    
    // Notification System
    function showNotification(type, message) {
        // Simple notification system
        var $notification = $(`
            <div class="notification notification-${type}" role="alert">
                <span>${message}</span>
                <button class="notification-close" aria-label="Close notification">&times;</button>
            </div>
        `);
        
        $('body').append($notification);
        
        // Auto-remove after 5 seconds
        setTimeout(function() {
            $notification.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
        
        // Manual close
        $notification.find('.notification-close').on('click', function() {
            $notification.fadeOut(function() {
                $(this).remove();
            });
        });
    }
    
    // Global functions for keyboard shortcuts
    window.createNew = function() {
        $('#btn-add-runbulk').trigger('click');
    };
    
    window.focusSearch = function() {
        $('#global-search').focus();
    };
    
    window.clearFilters = function() {
        $('.table-filter-chip.active').removeClass('active').attr('aria-pressed', 'false');
        $('#global-search').val('').trigger('keyup');
        if (typeof window.table !== 'undefined') {
            window.table.search('').columns().search('').draw();
        }
    };
    
    window.exportData = function() {
        $('#export-csv-btn').trigger('click');
    };
    
    window.toggleColumns = function() {
        $('#column-visibility-btn').trigger('click');
    };
    
    // Expose Enhanced UX object for external use
    window.EnhancedUX = EnhancedUX;
    
})(jQuery);