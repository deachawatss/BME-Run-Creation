/**
 * Enhanced DataTable Support for BME CreateRunBulk
 * Advanced filtering, search suggestions, and performance optimizations
 */

class EnhancedDataTable {
    constructor(tableId, options = {}) {
        this.tableId = tableId;
        this.table = null;
        this.options = {
            baseUrl: 'CreateRunBulk/',
            enhancedEndpoint: 'ajaxlist_enhanced',
            filterEndpoint: 'getFilterOptions',
            statsEndpoint: 'getRunStats',
            searchEndpoint: 'getSearchSuggestions',
            exportEndpoint: 'exportData',
            ...options
        };
        
        this.filters = {
            status: '',
            formula: '',
            user: '',
            date_from: '',
            date_to: '',
            batch_count_min: '',
            batch_count_max: ''
        };
        
        this.init();
    }
    
    init() {
        this.loadFilterOptions();
        this.initializeDataTable();
        this.setupEventListeners();
        this.loadDashboardStats();
    }
    
    /**
     * Initialize enhanced DataTable with advanced features
     */
    initializeDataTable() {
        const self = this;
        
        this.table = $(this.tableId).DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            ordering: false,
            responsive: true,
            autoWidth: false,
            
            // Enhanced AJAX configuration
            ajax: {
                url: this.options.baseUrl + this.options.enhancedEndpoint,
                type: 'POST',
                data: function(d) {
                    // Add enhanced filter parameters
                    return $.extend(d, self.filters);
                },
                dataSrc: function(json) {
                    // Update summary information
                    self.updateSummaryInfo(json.summary);
                    return json.data;
                },
                error: function(xhr, error, code) {
                    console.error('DataTable AJAX error:', error, code);
                    self.showErrorMessage('Failed to load data. Please try again.');
                }
            },
            
            // Enhanced column configuration
            columns: [
                { 
                    data: 'Cust_BulkRun.RunNo',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data) {
                            const formatted = row.Cust_BulkRun.FormattedRunNo || 
                                            'R' + new Date().getFullYear().toString().substr(-2) + 
                                            String(data).padStart(4, '0');
                            return '<span class="id-field">' + formatted + '</span>';
                        }
                        return '-';
                    }
                },
                { 
                    data: 'Cust_BulkRun.RowNum',
                    className: 'text-center',
                    render: function(data) {
                        return '<span class="number-field">' + (data || '-') + '</span>';
                    }
                },
                { 
                    data: 'Cust_BulkRun.BatchNo',
                    className: 'text-center',
                    render: function(data) {
                        return data ? '<span class="id-field">' + data + '</span>' : '-';
                    }
                },
                { 
                    data: 'Cust_BulkRun.FormulaId',
                    className: 'text-center',
                    render: function(data) {
                        return '<span style="font-weight: 500; font-family: monospace;">' + (data || '-') + '</span>';
                    }
                },
                { 
                    data: 'Cust_BulkRun.FormulaDesc',
                    className: 'text-center',
                    render: function(data) {
                        if (data && data.length > 30) {
                            return '<span style="font-size: 0.875rem;" title="' + data + '">' + 
                                   data.substring(0, 30) + '...</span>';
                        }
                        return '<span style="font-size: 0.875rem;">' + (data || '-') + '</span>';
                    }
                },
                { 
                    data: 'Cust_BulkRun.NoOfBatches',
                    className: 'text-center',
                    render: function(data, type, row) {
                        const count = data || '0';
                        const range = row.Cust_BulkRun.BatchCountRange || '';
                        return '<span class="number-field" title="' + range + '">' + count + '</span>';
                    }
                },
                { 
                    data: 'Cust_BulkRun.PalletsPerBatch',
                    className: 'text-center',
                    render: function(data) {
                        return '<span class="number-field">' + (data || '0') + '</span>';
                    }
                },
                { 
                    data: 'Cust_BulkRun.Status',
                    className: 'text-center',
                    render: function(data, type, row) {
                        const statusClass = row.Cust_BulkRun.StatusClass || 'badge-neutral';
                        const statusText = data || 'Unknown';
                        return '<span class="minimal-badge ' + statusClass + '">' + statusText + '</span>';
                    }
                },
                { 
                    data: 'Cust_BulkRun.RecUserId',
                    className: 'text-center',
                    render: function(data) {
                        return '<span style="font-weight: 500; font-size: 0.875rem;">' + (data || '-') + '</span>';
                    }
                },
                { 
                    data: 'Cust_BulkRun.RecDate',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if(data) {
                            const date = new Date(data);
                            const age = row.Cust_BulkRun.RecordAge || '';
                            return '<div style="font-size: 0.75rem; line-height: 1.3; text-align: center;" title="' + age + '">' + 
                                   '<div style="font-weight: 500;">' + String(date.getDate()).padStart(2, '0') + '/' +
                                   String(date.getMonth() + 1).padStart(2, '0') + '/' + 
                                   date.getFullYear().toString().substr(-2) + '</div>' +
                                   '<div style="color: var(--color-gray-500);">' + 
                                   String(date.getHours()).padStart(2, '0') + ':' +
                                   String(date.getMinutes()).padStart(2, '0') + '</div></div>';
                        }
                        return '<span style="color: var(--color-gray-400);">-</span>';
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const isEditable = row.Cust_BulkRun.IsEditable !== false;
                        const runNo = row.Cust_BulkRun.RunNo;
                        
                        if (!isEditable) {
                            return `
                                <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                    <button class="btn-minimal btn-secondary btn-sm" 
                                            disabled style="opacity: 0.5; cursor: not-allowed;" 
                                            title="Cannot edit - Status is PRINT">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-minimal btn-danger btn-sm" 
                                            disabled style="opacity: 0.5; cursor: not-allowed;" 
                                            title="Cannot delete - Status is PRINT">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        } else {
                            return `
                                <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                    <button class="btn-minimal btn-secondary btn-sm btn-edit-Cust_BulkRun" 
                                            data-id="${runNo}" title="Edit Run">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-minimal btn-danger btn-sm btn-delete-Cust_BulkRun" 
                                            data-id="${runNo}" title="Delete Run">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                }
            ],
            
            // Enhanced language configuration
            language: {
                processing: '<div class="minimal-loading-text"><div class="minimal-spinner"></div>Loading enhanced data...</div>',
                emptyTable: '<div class="minimal-empty-state"><div class="minimal-empty-state-icon"><i class="fas fa-search"></i></div><div class="minimal-empty-state-title">No matching runs found</div><div class="minimal-empty-state-text">Try adjusting your filters or search criteria.</div></div>',
                info: "_START_ - _END_ of _TOTAL_ runs",
                infoEmpty: "No runs available",
                infoFiltered: "(filtered from _MAX_ total runs)",
                lengthMenu: "_MENU_ per page",
                search: "",
                searchPlaceholder: "Search runs...",
                paginate: {
                    first: "First",
                    last: "Last", 
                    next: "Next",
                    previous: "Prev"
                }
            },
            
            // Enhanced DOM structure
            dom: '<"enhanced-datatable-header"<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>>' +
                 '<"enhanced-datatable-filters">' +
                 '<"enhanced-datatable-stats">' +
                 'rt' +
                 '<"enhanced-datatable-footer"<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>>',
            
            initComplete: function(settings, json) {
                self.setupEnhancedSearch();
                self.setupAdvancedFilters();
                self.setupExportButtons();
                console.log('Enhanced DataTable initialized successfully');
            },
            
            drawCallback: function(settings) {
                self.updatePerformanceMetrics(settings);
            }
        });
    }
    
    /**
     * Load filter options from server
     */
    loadFilterOptions() {
        const self = this;
        
        $.get(this.options.baseUrl + this.options.filterEndpoint)
            .done(function(data) {
                self.populateFilterDropdowns(data);
            })
            .fail(function() {
                console.warn('Failed to load filter options');
            });
    }
    
    /**
     * Populate filter dropdowns with data
     */
    populateFilterDropdowns(data) {
        // Populate status filter
        if (data.statuses && data.statuses.length > 0) {
            const statusSelect = $('#status-filter');
            if (statusSelect.length) {
                statusSelect.empty().append('<option value="">All Statuses</option>');
                data.statuses.forEach(status => {
                    statusSelect.append(`<option value="${status}">${status}</option>`);
                });
            }
        }
        
        // Populate formula filter
        if (data.formulas && data.formulas.length > 0) {
            const formulaSelect = $('#formula-filter');
            if (formulaSelect.length) {
                formulaSelect.empty().append('<option value="">All Formulas</option>');
                data.formulas.slice(0, 20).forEach(formula => {
                    formulaSelect.append(`<option value="${formula.id}">${formula.id} (${formula.count})</option>`);
                });
            }
        }
        
        // Populate user filter
        if (data.users && data.users.length > 0) {
            const userSelect = $('#user-filter');
            if (userSelect.length) {
                userSelect.empty().append('<option value="">All Users</option>');
                data.users.forEach(user => {
                    userSelect.append(`<option value="${user.id}">${user.id} (${user.count})</option>`);
                });
            }
        }
        
        // Set batch count ranges
        if (data.batch_ranges) {
            $('#batch-min').attr('max', data.batch_ranges.max_batches);
            $('#batch-max').attr('max', data.batch_ranges.max_batches);
            $('#batch-avg').text(Math.round(data.batch_ranges.avg_batches || 0));
        }
    }
    
    /**
     * Setup enhanced search with suggestions
     */
    setupEnhancedSearch() {
        const self = this;
        const searchInput = $('.dataTables_filter input');
        
        // Add search suggestions
        searchInput.autocomplete({
            source: function(request, response) {
                $.get(self.options.baseUrl + self.options.searchEndpoint, { q: request.term })
                    .done(function(data) {
                        response(data.map(item => ({
                            label: `${item.value} (${item.type})`,
                            value: item.value,
                            type: item.type,
                            icon: item.icon
                        })));
                    });
            },
            minLength: 2,
            delay: 300,
            select: function(event, ui) {
                searchInput.val(ui.item.value);
                self.table.search(ui.item.value).draw();
            }
        });
        
        // Enhanced search with debouncing
        let searchTimeout;
        searchInput.on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                self.table.search(this.value).draw();
            }, 500);
        });
    }
    
    /**
     * Setup advanced filtering controls
     */
    setupAdvancedFilters() {
        const self = this;
        
        // Create filter panel if it doesn't exist
        if ($('.enhanced-datatable-filters').length && !$('.filter-panel').length) {
            $('.enhanced-datatable-filters').html(`
                <div class="filter-panel" style="background: var(--color-brown-25); padding: var(--spacing-lg); border-radius: var(--radius-lg); margin-bottom: var(--spacing-lg);">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label">Status:</label>
                            <select id="status-filter" class="form-control form-control-sm">
                                <option value="">All Statuses</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Formula:</label>
                            <select id="formula-filter" class="form-control form-control-sm">
                                <option value="">All Formulas</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">User:</label>
                            <select id="user-filter" class="form-control form-control-sm">
                                <option value="">All Users</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date From:</label>
                            <input type="date" id="date-from-filter" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date To:</label>
                            <input type="date" id="date-to-filter" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Actions:</label>
                            <div>
                                <button id="apply-filters" class="btn btn-primary btn-sm" style="width: 100%;">
                                    <i class="fas fa-filter"></i> Apply
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label class="form-label">Batch Count Range:</label>
                            <div class="input-group">
                                <input type="number" id="batch-min" class="form-control form-control-sm" placeholder="Min">
                                <span class="input-group-text">-</span>
                                <input type="number" id="batch-max" class="form-control form-control-sm" placeholder="Max">
                            </div>
                            <small class="text-muted">Avg: <span id="batch-avg">0</span></small>
                        </div>
                        <div class="col-md-3">
                            <button id="clear-filters" class="btn btn-secondary btn-sm mt-4">
                                <i class="fas fa-times"></i> Clear Filters
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="btn-group mt-4" role="group">
                                <button id="export-csv" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-file-csv"></i> Export CSV
                                </button>
                                <button id="export-json" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-file-code"></i> Export JSON
                                </button>
                                <button id="refresh-stats" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-chart-bar"></i> Refresh Stats
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
        
        // Setup filter event handlers
        $('#apply-filters').on('click', function() {
            self.applyFilters();
        });
        
        $('#clear-filters').on('click', function() {
            self.clearFilters();
        });
        
        // Auto-apply filters when changed
        $('.filter-panel select, .filter-panel input[type="date"], .filter-panel input[type="number"]').on('change', function() {
            self.applyFilters();
        });
    }
    
    /**
     * Apply current filter settings
     */
    applyFilters() {
        this.filters = {
            status_filter: $('#status-filter').val(),
            formula_filter: $('#formula-filter').val(),
            user_filter: $('#user-filter').val(),
            date_from: $('#date-from-filter').val(),
            date_to: $('#date-to-filter').val(),
            batch_count_min: $('#batch-min').val(),
            batch_count_max: $('#batch-max').val()
        };
        
        this.table.ajax.reload();
    }
    
    /**
     * Clear all filters
     */
    clearFilters() {
        this.filters = {
            status_filter: '',
            formula_filter: '',
            user_filter: '',
            date_from: '',
            date_to: '',
            batch_count_min: '',
            batch_count_max: ''
        };
        
        $('.filter-panel select').val('');
        $('.filter-panel input').val('');
        
        this.table.ajax.reload();
    }
    
    /**
     * Setup export functionality
     */
    setupExportButtons() {
        const self = this;
        
        $('#export-csv').on('click', function() {
            self.exportData('csv');
        });
        
        $('#export-json').on('click', function() {
            self.exportData('json');
        });
        
        $('#refresh-stats').on('click', function() {
            self.loadDashboardStats();
        });
    }
    
    /**
     * Export data with current filters
     */
    exportData(format) {
        const params = new URLSearchParams(this.filters);
        params.append('format', format);
        
        const url = this.options.baseUrl + this.options.exportEndpoint + '?' + params.toString();
        window.open(url, '_blank');
    }
    
    /**
     * Load dashboard statistics
     */
    loadDashboardStats() {
        const self = this;
        
        $.get(this.options.baseUrl + this.options.statsEndpoint)
            .done(function(data) {
                self.updateDashboardStats(data);
            })
            .fail(function() {
                console.warn('Failed to load dashboard stats');
            });
    }
    
    /**
     * Update dashboard statistics display
     */
    updateDashboardStats(data) {
        // Create stats panel if it doesn't exist
        if ($('.enhanced-datatable-stats').length && !$('.stats-panel').length) {
            $('.enhanced-datatable-stats').html(`
                <div class="stats-panel" style="background: var(--color-brown-25); padding: var(--spacing-md); border-radius: var(--radius-lg); margin-bottom: var(--spacing-lg);">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h4 class="mb-1"><span id="stat-total-runs">0</span></h4>
                                <small class="text-muted">Total Runs</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h4 class="mb-1"><span id="stat-unique-formulas">0</span></h4>
                                <small class="text-muted">Unique Formulas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h4 class="mb-1"><span id="stat-total-batches">0</span></h4>
                                <small class="text-muted">Total Batches</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h4 class="mb-1"><span id="stat-avg-batches">0</span></h4>
                                <small class="text-muted">Avg Batches/Run</small>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
        
        // Update stat values
        if (data.summary) {
            $('#stat-total-runs').text(data.summary.total_runs || 0);
            $('#stat-unique-formulas').text(data.summary.unique_formulas || 0);
            $('#stat-total-batches').text(data.summary.total_batches || 0);
            $('#stat-avg-batches').text(data.summary.avg_batches_per_run || 0);
        }
    }
    
    /**
     * Update summary information after data load
     */
    updateSummaryInfo(summary) {
        if (summary) {
            // Update any summary displays
            $('.dataTables_info').append(
                ` (Query: ${summary.query_time || 'N/A'})`
            );
        }
    }
    
    /**
     * Update performance metrics
     */
    updatePerformanceMetrics(settings) {
        const info = this.table.page.info();
        console.log(`Displayed ${info.recordsDisplay} of ${info.recordsTotal} records`);
    }
    
    /**
     * Show error message to user
     */
    showErrorMessage(message) {
        // Simple error display - can be enhanced with toast notifications
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', message, 'error');
        } else {
            alert('Error: ' + message);
        }
    }
    
    /**
     * Public method to refresh the table
     */
    refresh() {
        this.table.ajax.reload();
    }
    
    /**
     * Public method to get selected rows
     */
    getSelectedRows() {
        return this.table.rows('.selected').data().toArray();
    }
}

// Export for global use
window.EnhancedDataTable = EnhancedDataTable;