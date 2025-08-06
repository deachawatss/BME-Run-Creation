<?= $this->include('layouts/template/header') ?>

<div class="minimal-page-container">
    <div class="minimal-page-content">
        <div class="minimal-container">
            
            <!-- Page Header with Title and Actions -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--space-4); margin-bottom: var(--space-6); padding: var(--space-4) 0; border-bottom: 1px solid var(--color-gray-200);">
                <!-- Left Side: Company Logo and Title -->
                <div style="display: flex; align-items: center; gap: var(--space-3);">
                    <img src="<?= base_url('assets/img/nwfth-logo.png') ?>" 
                         alt="NWFTH Logo" 
                         style="height: 60px; width: auto; object-fit: contain;"
                         onerror="this.src='<?= base_url('assets/img/Logo.jpg') ?>'; this.style.height='55px';">
                    <h1 style="font-size: 1.875rem; font-weight: 700; color: var(--color-gray-900); margin: 0;">Create Run Bulk</h1>
                </div>
                
                <!-- Right Side: Action Buttons -->
                <div style="display: flex; align-items: center; gap: var(--space-3);">
                    <a href="<?= base_url() ?>" class="btn-minimal btn-primary" style="padding: var(--space-3) var(--space-6); font-size: var(--font-size-base); font-weight: 600; border-radius: var(--radius-lg); display: flex; align-items: center; gap: var(--space-2); text-decoration: none;" title="Back to Home">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Home</span>
                    </a>
                    <button type="button" class="btn-minimal btn-primary" id="btn-create-run" style="padding: var(--space-3) var(--space-6); font-size: var(--font-size-base); font-weight: 600;">
                        <i class="fas fa-plus"></i> Create Run
                    </button>
                </div>
            </div>

            <!-- Enterprise Data Table -->
            <div class="minimal-table-container" style="border-radius: var(--radius-xl); overflow: hidden;">
                <table id="auto-gen-Cust_BulkRun" class="minimal-table" style="width:100%">
                    <thead>
                        <tr>
                            <th data-priority="2">Run No</th>
                            <th data-priority="3">Row Num</th>
                            <th data-priority="5">Batch No</th>
                            <th data-priority="4">Formula ID</th>
                            <th data-priority="6">Formula Desc</th>
                            <th data-priority="4">Batches</th>
                            <th data-priority="7">Pallets</th>
                            <th data-priority="3">Status</th>
                            <th data-priority="8">User ID</th>
                            <th data-priority="9">Record Date</th>
                            <th data-priority="1">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Batch Selection Modal -->
<div class="modal fade" id="modal-batch-selection" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Create Run
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Run Information Form -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Run No:</label>
                        <input type="text" class="form-control form-control-sm" id="modal-run-no" readonly style="background-color: #f8f9fa;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Formula ID:</label>
                        <input type="text" class="form-control form-control-sm" id="modal-formula-id" readonly style="background-color: #f8f9fa;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Batch Size:</label>
                        <input type="text" class="form-control form-control-sm" id="modal-batch-size" readonly style="background-color: #f8f9fa;">
                    </div>
                    <div class="col-md-3">
                        <div class="mt-4" style="padding: 8px; background: var(--color-brown-50); border-radius: var(--radius-md); text-align: center;">
                            <small class="text-muted"><i class="fas fa-info-circle"></i> Batches will load automatically</small>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Total Batches:</label>
                        <input type="text" class="form-control form-control-sm" id="modal-total-batches" readonly style="background-color: #f8f9fa;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Batches Size:</label>
                        <input type="text" class="form-control form-control-sm" id="modal-total-batches-size" readonly style="background-color: #f8f9fa;">
                    </div>
                </div>

                <!-- Selected Batches Summary Panel -->
                <div id="selected-batches-summary" class="alert alert-brown" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><span id="selected-count">0</span> batches selected</strong>
                            <span id="selected-details" class="ml-2"></span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-brown" id="clear-selection">
                            <i class="fas fa-times"></i> Clear Selection
                        </button>
                    </div>
                </div>

                <!-- Filter Status Panel (Automatic Filtering like CI3) -->
                <div id="filter-status-panel" class="alert alert-info" style="display: none; margin-bottom: 16px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-filter mr-2"></i>
                        <span id="filter-status-text">No filters applied - showing all available batches</span>
                    </div>
                </div>

                <!-- Batch Search Table -->
                <div class="minimal-table-container" style="border-radius: var(--radius-lg);">
                    <table id="batch-selection-table" class="minimal-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th data-priority="1" style="width: 40px;">Select</th>
                                <th data-priority="2">Batch No</th>
                                <th data-priority="3">Formula ID</th>
                                <th data-priority="4">Batch Weight</th>
                                <th data-priority="5">Entry Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-minimal btn-primary" id="create-run-btn" disabled>
                    <i class="fas fa-plus"></i> Create Run
                </button>
                <button type="button" class="btn-minimal btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print Details Modal -->
<div class="modal fade" id="modal-print-details" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-print"></i> Run Details - <span id="print-run-no"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print-modal-content">
                <!-- Professional Report Header -->
                <div class="print-report-header">
                    <h3 class="print-report-title">Run Bulk Report Details</h3>
                </div>

                <!-- Run Header Information Table -->
                <div class="print-header-section">
                    <table class="print-header-table">
                        <tr>
                            <td class="print-label-cell">Run No:</td>
                            <td class="print-value-cell" id="print-detail-run-no">-</td>
                            <td class="print-label-cell">No of Batches:</td>
                            <td class="print-value-cell" id="print-detail-no-batches">-</td>
                        </tr>
                        <tr>
                            <td class="print-label-cell">Formula Id:</td>
                            <td class="print-value-cell" id="print-detail-formula-id">-</td>
                            <td class="print-label-cell">Pallets Per Batch:</td>
                            <td class="print-value-cell" id="print-detail-pallets">-</td>
                        </tr>
                        <tr>
                            <td class="print-label-cell">Formula Desc:</td>
                            <td class="print-value-cell" id="print-detail-formula-desc">-</td>
                            <td class="print-label-cell"></td>
                            <td class="print-value-cell"></td>
                        </tr>
                        <tr>
                            <td class="print-label-cell">FG Item Key:</td>
                            <td class="print-value-cell" id="print-detail-fg-item-key">-</td>
                            <td class="print-label-cell">FG Qty:</td>
                            <td class="print-value-cell" id="print-detail-fg-qty">-</td>
                        </tr>
                        <tr>
                            <td class="print-label-cell">Description</td>
                            <td class="print-value-cell" id="print-detail-description">-</td>
                            <td class="print-label-cell">FG Unit:</td>
                            <td class="print-value-cell" id="print-detail-fg-unit">-</td>
                        </tr>
                    </table>
                </div>

                <!-- Batch Numbers Section -->
                <div class="print-batch-section">
                    <table class="print-batch-table">
                        <tr>
                            <td class="print-batch-label">Batch No</td>
                        </tr>
                        <tbody id="print-batch-numbers-body">
                            <!-- Batch numbers will be populated here -->
                        </tbody>
                    </table>
                </div>

                <!-- Ingredient Details Table -->
                <div class="print-ingredients-section">
                    <table class="print-ingredients-table">
                        <thead>
                            <tr>
                                <th>RM Item Key</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Qty/Batch</th>
                                <th>Total Qty</th>
                                <th>Pack Size</th>
                                <th>Bulk #</th>
                                <th>Bulk #/Batch</th>
                            </tr>
                        </thead>
                        <tbody id="print-ingredients-body">
                            <!-- Ingredient details will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-minimal btn-primary" id="btn-print-details">
                    <i class="fas fa-print"></i> Print
                </button>
                <button type="button" class="btn-minimal btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Verify jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded');
        return;
    }
    
    // Initialize main DataTable with responsive design
    var table = $('#auto-gen-Cust_BulkRun').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        ordering: false, // Disable all sorting to remove arrow indicators
        responsive: {
            details: {
                type: 'column',
                target: 'tr',
                renderer: function(api, rowIdx, columns) {
                    var data = $.map(columns, function(col, i) {
                        return col.hidden ?
                            '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                '<td class="minimal-label" style="font-weight: 600; color: var(--color-gray-700);">' + col.title + ':</td>' +
                                '<td class="minimal-value" style="color: var(--color-gray-900);">' + col.data + '</td>' +
                            '</tr>' : '';
                    }).join('');
                    
                    return data ? $('<table class="table"/>').append(data) : false;
                }
            },
            breakpoints: [
                { name: 'desktop', width: Infinity },
                { name: 'tablet-l', width: 1024 },
                { name: 'tablet-p', width: 768 },
                { name: 'mobile-l', width: 480 },
                { name: 'mobile-p', width: 320 }
            ]
        },
        dom: 'lf<"minimal-table-controls">rtip',
        autoWidth: false,
        language: {
            processing: '<div class="minimal-loading-text"><div class="minimal-spinner"></div>Loading runs...</div>',
            emptyTable: '<div class="minimal-empty-state"><div class="minimal-empty-state-icon"><i class="fas fa-inbox"></i></div><div class="minimal-empty-state-title">No runs found</div><div class="minimal-empty-state-text">Click \'ADD\' to create your first run.</div></div>',
            info: "_START_ - _END_ of _TOTAL_ runs",
            infoEmpty: "No runs available",
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
        ajax: {
            url: '<?= base_url("createrunbulk/ajaxlist") ?>',
            type: 'POST'
        },
        columns: [
            { 
                data: 'Cust_BulkRun.RunNo',
                className: 'text-center',
                render: function(data, type, row) {
                    if (data) {
                        // Show actual run number without R prefix
                        return '<span class="id-field">' + data + '</span>';
                    }
                    return '-';
                }
            },
            { 
                data: 'Cust_BulkRun.RowNum',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span class="number-field">' + (data || '-') + '</span>';
                }
            },
            { 
                data: 'Cust_BulkRun.BatchNo',
                className: 'text-center',
                render: function(data, type, row) {
                    if (data) {
                        return '<span class="id-field">' + data + '</span>';
                    }
                    return '-';
                }
            },
            { 
                data: 'Cust_BulkRun.FormulaId',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span style="font-weight: 500; font-family: monospace;">' + (data || '-') + '</span>';
                }
            },
            { 
                data: 'Cust_BulkRun.FormulaDesc',
                className: 'text-center',
                render: function(data, type, row) {
                    if (data && data.length > 30) {
                        return '<span style="font-size: 0.875rem;" title="' + data + '">' + data.substring(0, 30) + '...</span>';
                    }
                    return '<span style="font-size: 0.875rem;">' + (data || '-') + '</span>';
                }
            },
            { 
                data: 'Cust_BulkRun.NoOfBatches',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span class="number-field">' + (data || '0') + '</span>';
                }
            },
            { 
                data: 'Cust_BulkRun.PalletsPerBatch',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span class="number-field">' + (data || '0') + '</span>';
                }
            },
            { 
                data: 'Cust_BulkRun.Status',
                className: 'text-center',
                render: function(data, type, row) {
                    var badgeClass = 'badge-neutral';
                    var statusText = data || 'Unknown';
                    
                    switch(statusText.toLowerCase()) {
                        case 'active':
                        case 'new':
                        case 'running':
                            badgeClass = 'badge-success';
                            break;
                        case 'completed':
                        case 'finished':
                            badgeClass = 'badge-info';
                            break;
                        case 'paused':
                        case 'pending':
                            badgeClass = 'badge-warning';
                            break;
                        case 'error':
                        case 'failed':
                        case 'cancelled':
                            badgeClass = 'badge-error';
                            break;
                    }
                    
                    return '<span class="minimal-badge ' + badgeClass + '">' + statusText + '</span>';
                }
            },
            { 
                data: 'Cust_BulkRun.RecUserId',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span style="font-weight: 500; font-size: 0.875rem;">' + (data || '-') + '</span>';
                }
            },
            { 
                data: 'Cust_BulkRun.RecDate',
                className: 'text-center',
                render: function(data, type, row) {
                    if(data) {
                        var date = new Date(data);
                        return '<div style="font-size: 0.75rem; line-height: 1.3; text-align: center;">' + 
                               '<div style="font-weight: 500;">' + String(date.getDate()).padStart(2, '0') + '/' +
                               String(date.getMonth() + 1).padStart(2, '0') + '/' + date.getFullYear().toString().substr(-2) + '</div>' +
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
                    var status = row['Cust_BulkRun']['Status'];
                    var runNo = row['Cust_BulkRun']['RunNo'];
                    var isPrintStatus = status && status.toUpperCase() === 'PRINT';
                    
                    if (isPrintStatus) {
                        // For PRINT status, show enabled print button + disabled delete button
                        return `
                            <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                <button class="btn-minimal btn-secondary btn-sm btn-print-bulk-run" 
                                        data-id="${runNo}" title="Print Run Details">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button class="btn-minimal btn-danger btn-sm" 
                                        disabled style="opacity: 0.5; cursor: not-allowed;" 
                                        title="Cannot delete - Status is PRINT">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    } else {
                        // For other statuses, show print button + delete button
                        return `
                            <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                <button class="btn-minimal btn-secondary btn-sm btn-print-bulk-run" 
                                        data-id="${runNo}" title="Print Run Details">
                                    <i class="fas fa-print"></i>
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
        initComplete: function(settings, json) {
            // Set placeholder for search input
            $('.dataTables_filter input').attr('placeholder', 'Search runs...');
            
            // ENHANCED: Fix single character search issue
            // Override default DataTable search behavior to allow single character searches
            var searchInput = $('.dataTables_filter input');
            var searchTimeout;
            
            // Remove DataTable's default search event handlers
            searchInput.off('keyup.DT search.DT input.DT paste.DT cut.DT');
            
            // Add custom search handler with minimal delay for single characters
            searchInput.on('input.custom', function() {
                var searchValue = this.value;
                clearTimeout(searchTimeout);
                
                // Use shorter delay for single characters to improve responsiveness
                var delay = searchValue.length === 1 ? 100 : 300;
                
                searchTimeout = setTimeout(function() {
                    table.search(searchValue).draw();
                }, delay);
            });
            
            console.log('Enterprise DataTable initialized successfully');
        }
    });
    
    // Create Run button - opens batch selection modal
    $('#btn-create-run').click(function() {
        // Get next run number and open modal
        $.ajax({
            url: '<?= base_url("createrunbulk/getNextRunNumber") ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Set run number in modal
                    $('#modal-run-no').val(response.next_run_no);
                    
                    // Initialize batch selection table
                    if ($.fn.DataTable.isDataTable('#batch-selection-table')) {
                        $('#batch-selection-table').DataTable().destroy();
                    }
                    
                    var batchTable = $('#batch-selection-table').DataTable({
                        processing: true,
                        serverSide: true, // FIXED: Enable server-side processing for large datasets
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                        ordering: false, // Disable all sorting to remove arrow indicators
                        responsive: false, // Disable responsive details to remove (+) icons
                        dom: 'lf<"batch-controls">rtip',
                        language: {
                            search: "",
                            searchPlaceholder: "Search batches...",
                            processing: '<div class="minimal-loading-text"><div class="minimal-spinner"></div>Loading batches...</div>',
                            emptyTable: '<div class="minimal-empty-state"><div class="minimal-empty-state-icon"><i class="fas fa-box"></i></div><div class="minimal-empty-state-title">No batches found</div></div>',
                            info: "_START_ - _END_ of _TOTAL_ batches",
                            infoEmpty: "No batches available",
                            lengthMenu: "_MENU_ per page"
                        },
                        ajax: {
                            url: '<?= base_url("createrunbulk/getBatchListPaginated") ?>',
                            type: 'POST',
                            data: function(d) {
                                // Add custom filter parameters to DataTable request
                                d.formulaid = selectedFormulaId || '';
                                d.batchwt = selectedBatchWeight || '';
                                d.erunno = '';
                                return d;
                            }
                        },
                        columns: [
                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row) {
                                    return '<div class="custom-checkbox-wrapper">' +
                                           '<input type="checkbox" data-batch="' + row.BatchNo + '" data-formula="' + row.FormulaID + '" data-weight="' + row.BatchWeight + '" data-formula-desc="' + (row.FormulaDesc || '') + '">' +
                                           '<label class="custom-checkbox-label"></label>' +
                                           '</div>';
                                }
                            },
                            { data: 'BatchNo' },
                            { data: 'FormulaID' }, // Note: Backend returns FormulaID (not FormulaId)
                            { 
                                data: 'BatchWeight',
                                render: function(data) {
                                    return data ? parseFloat(data).toLocaleString() : '-';
                                }
                            },
                            { 
                                data: 'EntryDate',
                                render: function(data) {
                                    if (data) {
                                        var date = new Date(data);
                                        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                                    }
                                    return '-';
                                }
                            }
                        ]
                    });
                    
                    // Store reference for global access
                    currentBatchTable = batchTable;
                    
                    // FIXED: DataTable will load data automatically via AJAX - no manual loading needed
                    
                    // Show modal
                    $('#modal-batch-selection').modal('show');
                } else {
                    alert('Failed to get next run number: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                alert('Failed to get next run number');
            }
        });
    });

    // FIXED: Function to reload DataTable with filters (server-side)
    function loadBatchData(batchTable, formulaId = '', batchWeight = '', excludeRunNo = '', onComplete = null) {
        // Update filter status panel
        updateFilterStatusPanel(formulaId, batchWeight, formulaId || batchWeight ? true : false);
        
        // Update global filter variables for DataTable AJAX data function
        selectedFormulaId = formulaId;
        selectedBatchWeight = batchWeight;
        
        
        // Reload DataTable - it will automatically call the server with updated filters
        batchTable.ajax.reload(function(json) {
            
            // Call completion callback to restore selection state
            if (typeof onComplete === 'function') {
                setTimeout(onComplete, 100); // Small delay for DOM stability
            }
        });
    }

    // SIMPLIFIED: Function to update filter status panel (automatic filtering like CI3)
    function updateFilterStatusPanel(formulaId, batchWeight, filtersApplied = false) {
        if (formulaId || batchWeight) {
            $('#filter-status-panel').show();
            var filters = [];
            if (formulaId) filters.push('Formula ID: ' + formulaId);
            if (batchWeight) filters.push('Batch Weight: ' + batchWeight);
            
            if (filtersApplied) {
                $('#filter-status-text').text('Filtering by: ' + filters.join(', ') + ' (showing matching batches only)');
            } else {
                $('#filter-status-text').text('No filters applied - showing all available batches');
            }
        } else {
            $('#filter-status-panel').hide();
        }
    }

    // Global variables for batch selection filtering
    var currentBatchTable = null;
    var selectedFormulaId = '';
    var selectedBatchWeight = '';

    // RESTORED: Handle batch selection WITH automatic filtering (like CI3)
    $(document).on('change', '#batch-selection-table input[type="checkbox"]', function() {
        var isChecked = $(this).is(':checked');
        var formulaId = $(this).data('formula');
        var batchWeight = $(this).data('weight');
        var batchNo = $(this).data('batch');


        if (isChecked) {
            // First selection - automatically apply filters like CI3 (lines 217-219)
            if ($('#batch-selection-table input[type="checkbox"]:checked').length === 1) {
                selectedFormulaId = formulaId;
                selectedBatchWeight = batchWeight;
                
                
                // Store current selections before reload (like CI3 enhanced logic)
                var selectedBatchNumbers = [batchNo];
                
                // Show loading state
                $('#batch-selection-table').addClass('table-loading');
                
                // Apply filters with state preservation
                loadBatchData(currentBatchTable, selectedFormulaId, selectedBatchWeight, '', function() {
                    // Restore the first selection after filtering
                    $('#batch-selection-table input[data-batch="' + batchNo + '"]').prop('checked', true);
                    
                    // Update modal form fields
                    $('#modal-formula-id').val(selectedFormulaId);
                    $('#modal-batch-size').val(selectedBatchWeight);
                    
                    // Remove loading state
                    $('#batch-selection-table').removeClass('table-loading');
                    
                    // Update summary after restoring selection
                    updateSelectedBatchesSummary();
                    
                });
                
                // Update filter status to show filters are applied
                updateFilterStatusPanel(selectedFormulaId, selectedBatchWeight, true);
            }
        } else {
            // If this was the last checkbox, clear filters like CI3 (lines 230-233)
            if ($('#batch-selection-table input[type="checkbox"]:checked').length === 0) {
                selectedFormulaId = '';
                selectedBatchWeight = '';
                
                
                // Reload data without filters
                loadBatchData(currentBatchTable, '', '', '');
                updateFilterStatusPanel('', '', false);
            }
        }
        
        // Update summary after any change
        updateSelectedBatchesSummary();
    });

    // REMOVED: Manual filter button (now using automatic filtering like CI3)

    // FIXED: Clear selection button - resets filters and reloads data
    $('#clear-selection').click(function() {
        $('#batch-selection-table input[type="checkbox"]').prop('checked', false);
        
        // Reset filters (like CI3 lines 230-233)
        selectedFormulaId = '';
        selectedBatchWeight = '';
        
        // Reload data without filters
        loadBatchData(currentBatchTable);
        
        // Reset filter status panel
        updateFilterStatusPanel('', '', false);
        
        updateSelectedBatchesSummary();
    });

    // Function to update selected batches summary and modal form fields
    function updateSelectedBatchesSummary() {
        var selectedBatches = $('#batch-selection-table input[type="checkbox"]:checked');
        var count = selectedBatches.length;
        
        $('#selected-count').text(count);
        
        if (count > 0) {
            $('#selected-batches-summary').show();
            $('#create-run-btn').prop('disabled', false);
            
            // For same FormulaID/BatchWeight, calculate totals correctly
            var totalWeight = selectedBatchWeight ? (parseFloat(selectedBatchWeight) * count) : 0;
            var batchCount = count;
            
            // Update modal form fields (matching CI3 logic)
            $('#modal-formula-id').val(selectedFormulaId);
            $('#modal-batch-size').val(selectedBatchWeight);
            $('#modal-total-batches').val(batchCount);
            $('#modal-total-batches-size').val(totalWeight.toFixed(2));
            
            // Update details display
            $('#selected-details').text(`Formula: ${selectedFormulaId}, Weight: ${selectedBatchWeight}`);
            
        } else {
            $('#selected-batches-summary').hide();
            $('#create-run-btn').prop('disabled', true);
            
            // Clear modal form fields
            $('#modal-formula-id').val('');
            $('#modal-batch-size').val('');
            $('#modal-total-batches').val('');
            $('#modal-total-batches-size').val('');
            $('#selected-details').text('');
        }
        
    }

    // Create Run button in modal - Fixed to use correct FormulaID field name
    $('#create-run-btn').click(function() {
        // Validate that batches are selected
        var selectedCheckboxes = $('#batch-selection-table input[type="checkbox"]:checked');
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one batch');
            return;
        }
        
        // Get selected batches with corrected field names (FormulaID not FormulaId)
        var selectedBatches = [];
        selectedCheckboxes.each(function() {
            selectedBatches.push({
                batch_no: $(this).data('batch'),
                formula_id: $(this).data('formula'), // This comes from FormulaID in backend
                batch_weight: $(this).data('weight'),
                formula_desc: $(this).data('formula-desc') || '' // Include formula description
            });
        });
        
        // Additional validation - ensure all batches have same formula and weight (like CI3)
        var firstBatch = selectedBatches[0];
        var allSameFormula = selectedBatches.every(batch => batch.formula_id === firstBatch.formula_id);
        var allSameWeight = selectedBatches.every(batch => batch.batch_weight === firstBatch.batch_weight);
        
        if (!allSameFormula || !allSameWeight) {
            alert('All selected batches must have the same Formula ID and Batch Weight');
            return;
        }
        
        // Show loading state
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
        
        // Create the run
        $.ajax({
            url: '<?= base_url("createrunbulk/create") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                run_no: $('#modal-run-no').val(),
                selected_batches: selectedBatches,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#modal-batch-selection').modal('hide');
                    
                    // Show success message with run details like CI3
                    alert('Run created successfully!\n' +
                          'Run No: ' + response.run_no + '\n' +
                          'Formula: ' + selectedFormulaId + '\n' +
                          'Batches: ' + selectedBatches.length + '\n' +
                          'Total Weight: ' + (selectedBatchWeight * selectedBatches.length).toFixed(2));
                    
                    // Refresh main table and reset modal state
                    table.ajax.reload();
                    resetModalState();
                } else {
                    alert('Failed to create run: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Create run error:', {xhr, status, error});
                alert('Failed to create run: Network error or server unavailable');
            },
            complete: function() {
                // Reset button state
                $('#create-run-btn').prop('disabled', false).html('<i class="fas fa-plus"></i> Create Run');
            }
        });
    });
    
    // Function to reset modal state when closed
    function resetModalState() {
        selectedFormulaId = '';
        selectedBatchWeight = '';
        $('#modal-formula-id').val('');
        $('#modal-batch-size').val('');
        $('#modal-total-batches').val('');
        $('#modal-total-batches-size').val('');
        $('#selected-batches-summary').hide();
        $('#filter-status-panel').hide();
        $('#create-run-btn').prop('disabled', true);
        
        // Clear batch table if it exists
        if (currentBatchTable) {
            currentBatchTable.clear().draw();
        }
    }
    
    // Reset modal state when modal is closed
    $('#modal-batch-selection').on('hidden.bs.modal', function() {
        resetModalState();
    });
    
    // Delete button - Deletes ALL rows with the same RunNo
    $(document).on('click', '.btn-delete-Cust_BulkRun', function() {
        var runNo = $(this).data('id');
        var $button = $(this);
        
        // Enhanced confirmation message
        if(confirm('Are you sure you want to delete Run ' + runNo + '?\n\nThis will delete ALL batches in this run from both databases.\nThis action cannot be undone.')) {
            
            // Show loading state
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Make AJAX call to delete
            $.ajax({
                url: '<?= base_url("createrunbulk/delete/") ?>' + runNo,
                type: 'POST',
                dataType: 'json',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message with details
                        alert('Run deleted successfully!\n' +
                              'Run No: ' + response.run_no + '\n' +
                              'Batches deleted: ' + response.deleted_count + '\n' +
                              'Deleted from both TFCMOBILE and TFCPILOT3 databases');
                        
                        // Refresh the DataTable to show updated data
                        table.ajax.reload();
                        
                    } else {
                        alert('Failed to delete run: ' + (response.message || 'Unknown error'));
                        console.error('Delete failed:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', {xhr, status, error});
                    var errorMsg = 'Failed to delete run: Network error';
                    
                    // Try to get more specific error message
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = 'Failed to delete run: ' + xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMsg = 'Failed to delete run: Server error';
                    }
                    
                    alert(errorMsg);
                },
                complete: function() {
                    // Reset button state
                    $button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }
    });
    
    // Print button functionality
    $(document).on('click', '.btn-print-bulk-run', function() {
        var runNo = $(this).data('id');
        var $button = $(this);
        
        // Show loading state
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        // Fetch run details
        $.ajax({
            url: '<?= base_url("createrunbulk/getRunDetails/") ?>' + runNo,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    populatePrintModal(response.data);
                    $('#modal-print-details').modal('show');
                } else {
                    alert('Failed to load run details: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Print error:', {xhr, status, error});
                alert('Failed to load run details: Network error or server unavailable');
            },
            complete: function() {
                // Reset button state
                $button.prop('disabled', false).html('<i class="fas fa-print"></i>');
            }
        });
    });
    
    // Print button in modal
    $('#btn-print-details').click(function() {
        window.print();
    });
    
    // Function to populate print modal with run data
    function populatePrintModal(data) {
        var header = data.run_header;
        var batches = data.batch_numbers;
        var ingredients = data.ingredients;
        
        // Set modal title
        $('#print-run-no').text(header.RunNo);
        
        // Set header information
        $('#print-detail-run-no').text(header.RunNo);
        $('#print-detail-formula-id').text(header.FormulaId || '-');
        $('#print-detail-formula-desc').text(header.FormulaDesc || '-');
        $('#print-detail-no-batches').text(header.NoOfBatches || '-');
        $('#print-detail-pallets').text(header.PalletsPerBatch || '-');
        $('#print-detail-fg-item-key').text(header.FG_ItemKey || '-');
        $('#print-detail-fg-qty').text(header.FG_Qty || '-');
        $('#print-detail-description').text(header.FG_Description || '-');
        $('#print-detail-fg-unit').text(header.FG_Unit || '-');
        
        // Populate batch numbers
        var batchHtml = '';
        if (batches && batches.length > 0) {
            batches.forEach(function(batchNo) {
                batchHtml += '<tr><td class="print-batch-value">' + batchNo + '</td></tr>';
            });
        } else {
            batchHtml = '<tr><td class="print-batch-value">No batches available</td></tr>';
        }
        $('#print-batch-numbers-body').html(batchHtml);
        
        // Populate ingredients table
        var ingredientsHtml = '';
        if (ingredients && ingredients.length > 0) {
            ingredients.forEach(function(ingredient) {
                ingredientsHtml += 
                    '<tr>' +
                        '<td>' + (ingredient.ItemKey || '-') + '</td>' +
                        '<td>' + (ingredient.Description || '-') + '</td>' +
                        '<td>' + (ingredient.Unit || '-') + '</td>' +
                        '<td>' + (ingredient.QtyPerBatch ? parseFloat(ingredient.QtyPerBatch).toFixed(2) : '0.00') + '</td>' +
                        '<td>' + (ingredient.TotalQty ? parseFloat(ingredient.TotalQty).toFixed(2) : '0.00') + '</td>' +
                        '<td>' + (ingredient.PackSize ? parseFloat(ingredient.PackSize).toFixed(2) : '0.00') + '</td>' +
                        '<td>' + (ingredient.BulkQty || ingredient.BulkQty === 0 ? parseFloat(ingredient.BulkQty).toFixed(0) : '0') + '</td>' +
                        '<td>' + (ingredient.BulkQtyPerBatch || ingredient.BulkQtyPerBatch === 0 ? parseFloat(ingredient.BulkQtyPerBatch).toFixed(0) : '0') + '</td>' +
                    '</tr>';
            });
        } else {
            ingredientsHtml = '<tr><td colspan="8" style="text-align: center;">No ingredients available</td></tr>';
        }
        $('#print-ingredients-body').html(ingredientsHtml);
    }
});
</script>

<style>
/* Enhanced checkbox styling for better UX - BROWN THEME */
.custom-checkbox-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.custom-checkbox-wrapper input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
    opacity: 0;
    position: absolute;
    z-index: 2;
    /* Remove all blue background overrides */
    background: none !important;
    box-shadow: none !important;
    outline: none !important;
}

.custom-checkbox-label {
    width: 20px;
    height: 20px;
    border: 2px solid var(--color-brown-300);
    border-radius: 4px;
    background: white !important;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    position: relative;
    /* Ensure no blue background shows through */
    box-shadow: none !important;
}

.custom-checkbox-wrapper input[type="checkbox"]:checked + .custom-checkbox-label {
    background: var(--color-brown-600) !important;
    border-color: var(--color-brown-600) !important;
    /* Override any browser default blue */
    box-shadow: none !important;
}

.custom-checkbox-wrapper input[type="checkbox"]:checked + .custom-checkbox-label::after {
    content: '✓' !important;
    color: white !important;
    font-size: 14px !important;
    font-weight: bold !important;
    line-height: 1 !important;
}

.custom-checkbox-wrapper input[type="checkbox"]:not(:checked):hover + .custom-checkbox-label {
    border-color: var(--color-brown-500) !important;
    box-shadow: 0 0 0 2px rgba(93, 64, 55, 0.1) !important;
    background: white !important;
}

.custom-checkbox-wrapper input[type="checkbox"]:checked:hover + .custom-checkbox-label {
    /* Keep checked appearance on hover - no visual change */
    background: var(--color-brown-600) !important;
    border-color: var(--color-brown-600) !important;
    box-shadow: 0 0 0 2px rgba(93, 64, 55, 0.2) !important;
}

.custom-checkbox-wrapper input[type="checkbox"]:focus + .custom-checkbox-label {
    outline: 2px solid var(--color-brown-600) !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 2px rgba(93, 64, 55, 0.2) !important;
}

/* ENHANCED: Brown alert styling for selected batches summary */
.alert-brown {
    color: var(--color-brown-800) !important;
    background-color: var(--color-brown-50) !important;
    border: 1px solid var(--color-brown-200) !important;
    border-radius: var(--radius-md) !important;
    padding: 12px 16px !important;
    margin-bottom: 16px !important;
}

/* Loading state for table during filtering (like CI3) */
.table-loading {
    position: relative;
    opacity: 0.6;
    pointer-events: none;
}

.table-loading::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-loading::after {
    content: 'Filtering batches...';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 11;
    color: var(--color-brown-600);
    font-weight: 500;
    background: white;
    padding: 8px 16px;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Print Styling for Professional Reports */
.print-report-header {
    text-align: center;
    margin-bottom: 20px;
}

.print-report-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0;
    color: #333;
}

.print-header-section,
.print-batch-section,
.print-ingredients-section {
    margin-bottom: 20px;
}

.print-header-table,
.print-batch-table,
.print-ingredients-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #333;
}

.print-header-table td,
.print-batch-table td,
.print-ingredients-table th,
.print-ingredients-table td {
    border: 1px solid #333;
    padding: 8px;
    text-align: left;
    vertical-align: top;
}

.print-label-cell {
    font-weight: bold;
    background-color: #f5f5f5;
    width: 15%;
}

.print-value-cell {
    width: 35%;
}

.print-batch-label {
    font-weight: bold;
    background-color: #f5f5f5;
    text-align: center;
}

.print-batch-value {
    text-align: center;
}

.print-ingredients-table th {
    background-color: #f5f5f5;
    font-weight: bold;
    text-align: center;
}

.print-ingredients-table td {
    text-align: center;
}

/* Print Media Query */
@media print {
    /* Hide main page navigation elements only */
    .main-header, 
    .main-sidebar, 
    .navbar,
    .minimal-page-container {
        display: none !important;
    }
    
    /* Hide modal header and footer for clean report */
    .modal-header, 
    .modal-footer {
        display: none !important;
    }
    
    /* Ensure modal is visible and properly positioned for print */
    .modal.fade.show {
        display: block !important;
        opacity: 1 !important;
    }
    
    .modal-backdrop {
        display: none !important;
    }
    
    /* Style modal for print */
    .modal-dialog {
        max-width: none !important;
        margin: 0 !important;
        width: 100% !important;
        transform: none !important;
        position: static !important;
    }
    
    .modal-content {
        box-shadow: none !important;
        border: none !important;
        position: static !important;
    }
    
    .modal-body {
        padding: 20px !important;
    }
    
    /* Ensure modal content is visible */
    #modal-print-details {
        display: block !important;
        position: static !important;
        padding: 0 !important;
    }
    
    /* Print table styling */
    .print-header-table,
    .print-batch-table,
    .print-ingredients-table {
        border: 2px solid #000 !important;
        page-break-inside: avoid;
    }
    
    .print-header-table td,
    .print-batch-table td,
    .print-ingredients-table th,
    .print-ingredients-table td {
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
    
    .print-label-cell,
    .print-batch-label,
    .print-ingredients-table th {
        background-color: #f5f5f5 !important;
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
}
</style>

<?= $this->include('layouts/template/footer') ?>