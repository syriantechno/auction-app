// HR CRUD System - Advanced Modal-based CRUD inspired by SuiteOne ERP
// This system provides unified CRUD operations with modals, validation, and real-time updates

class HRCrudSystem {
    constructor() {
        this.modals = {};
        this.forms = {};
        this.datatables = {};
        this.init();
    }

    init() {
        this.initializeModals();
        this.initializeForms();
        this.initializeDatatables();
        this.bindEvents();
    }

    // Modal Management
    initializeModals() {
        // Create modal template
        this.createModalTemplate();
        
        // Initialize modal triggers
        document.querySelectorAll('[data-hr-modal]').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalType = trigger.dataset.hrModal;
                const target = trigger.dataset.target;
                const data = trigger.dataset;
                
                this.openModal(modalType, target, data);
            });
        });
    }

    createModalTemplate() {
        const modalTemplate = `
            <div id="hr-modal-container"></div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalTemplate);
    }

    openModal(type, target, data = {}) {
        const modalId = `hr-${type}-modal`;
        
        // Load modal content via AJAX
        this.loadModalContent(type, data)
            .then(html => {
                document.getElementById('hr-modal-container').innerHTML = html;
                const modal = document.getElementById(modalId);
                
                if (modal) {
                    this.showModal(modal);
                    this.initializeModalForm(modal, type, data);
                }
            })
            .catch(error => {
                console.error('Failed to load modal:', error);
                this.showToast('Failed to load modal', 'error');
            });
    }

    async loadModalContent(type, data) {
        const url = this.getModalUrl(type, data);
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.text();
    }

    getModalUrl(type, data) {
        const baseUrl = '/admin/hr';
        
        switch(type) {
            case 'create':
                return `${baseUrl}/${data.entity}/create-modal`;
            case 'edit':
                return `${baseUrl}/${data.entity}/${data.id}/edit-modal`;
            case 'show':
                return `${baseUrl}/${data.entity}/${data.id}/show-modal`;
            default:
                throw new Error(`Unknown modal type: ${type}`);
        }
    }

    showModal(modal) {
        // Remove any existing modals
        const existingModals = document.querySelectorAll('.modal');
        existingModals.forEach(m => m.remove());
        
        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'hr-modal-backdrop';
        backdrop.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998 !important;
        `;
        document.body.appendChild(backdrop);
        
        // Show modal
        modal.style.display = 'block';
        modal.classList.add('show');
        modal.style.zIndex = '9999';
        
        // Focus management
        const firstInput = modal.querySelector('input, textarea, select');
        if (firstInput) {
            firstInput.focus();
        }
        
        // Close handlers
        this.bindModalCloseEvents(modal);
    }

    hideModal(modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.remove();
        
        const backdrop = document.getElementById('hr-modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    }

    bindModalCloseEvents(modal) {
        // Close on backdrop click
        const backdrop = document.getElementById('hr-modal-backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', () => this.hideModal(modal));
        }
        
        // Close on ESC key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                this.hideModal(modal);
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);
        
        // Close buttons
        modal.querySelectorAll('[data-dismiss="modal"], .btn-close').forEach(btn => {
            btn.addEventListener('click', () => this.hideModal(modal));
        });
    }

    // Form Management
    initializeModalForm(modal, type, data) {
        const form = modal.querySelector('form');
        if (!form) return;
        
        this.forms[type] = form;
        
        // Form submission
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleFormSubmit(form, type, data);
        });
        
        // Initialize form components
        this.initializeFormComponents(form);
        
        // Initialize validation
        this.initializeFormValidation(form);
    }

    initializeFormComponents(form) {
        // Date pickers
        form.querySelectorAll('input[type="date"]').forEach(input => {
            // Add date picker functionality
        });
        
        // File uploads
        form.querySelectorAll('input[type="file"]').forEach(input => {
            this.initializeFileUpload(input);
        });
        
        // Select2 for selects
        form.querySelectorAll('select').forEach(select => {
            if (select.classList.contains('select2')) {
                // Initialize Select2
            }
        });
        
        // Rich text editors
        form.querySelectorAll('textarea[data-editor]').forEach(textarea => {
            // Initialize rich text editor
        });
    }

    initializeFileUpload(input) {
        const preview = input.dataset.preview;
        const previewContainer = document.getElementById(preview);
        
        if (previewContainer) {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewContainer.src = e.target.result;
                        previewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    initializeFormValidation(form) {
        // Add real-time validation
        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const rules = field.dataset.rules ? field.dataset.rules.split('|') : [];
        
        for (const rule of rules) {
            const [ruleName, ruleValue] = rule.split(':');
            
            switch(ruleName) {
                case 'required':
                    if (!value) {
                        this.showFieldError(field, 'This field is required');
                        return false;
                    }
                    break;
                case 'email':
                    if (!this.isValidEmail(value)) {
                        this.showFieldError(field, 'Please enter a valid email');
                        return false;
                    }
                    break;
                case 'min':
                    if (value.length < parseInt(ruleValue)) {
                        this.showFieldError(field, `Minimum ${ruleValue} characters required`);
                        return false;
                    }
                    break;
            }
        }
        
        this.clearFieldError(field);
        return true;
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    async handleFormSubmit(form, type, data) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        try {
            const formData = new FormData(form);
            const url = form.action;
            const method = form.method || 'POST';
            
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                this.showToast(result.message || 'Operation completed successfully', 'success');
                this.hideModal(form.closest('.modal'));
                
                // Refresh datatable if exists
                this.refreshDatatable(data.entity);
                
                // Trigger custom event
                document.dispatchEvent(new CustomEvent('hr:crud:success', {
                    detail: { type, data: result.data }
                }));
                
            } else {
                // Show validation errors
                if (result.errors) {
                    this.showFormErrors(form, result.errors);
                } else {
                    this.showToast(result.message || 'Operation failed', 'error');
                }
            }
            
        } catch (error) {
            console.error('Form submission error:', error);
            this.showToast('An error occurred while processing your request', 'error');
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }

    showFormErrors(form, errors) {
        // Clear existing errors
        form.querySelectorAll('.is-invalid').forEach(field => {
            this.clearFieldError(field);
        });
        
        // Show new errors
        for (const [field, messages] of Object.entries(errors)) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                this.showFieldError(input, messages[0]);
            }
        }
    }

    // Datatable Management
    initializeDatatables() {
        document.querySelectorAll('[data-hr-datatable]').forEach(table => {
            const entity = table.dataset.hrDatatable;
            this.initializeDatatable(table, entity);
        });
    }

    initializeDatatable(table, entity) {
        const url = table.dataset.url || `/admin/hr/${entity}/datatable`;
        
        this.datatables[entity] = $(table).DataTable({
            ajax: {
                url: url,
                type: 'GET',
                data: (params) => {
                    // Add filters
                    const filters = this.getDatatableFilters(entity);
                    return { ...params, ...filters };
                }
            },
            columns: this.getDatatableColumns(entity),
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']],
            language: {
                search: 'Search...',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                }
            },
            initComplete: () => {
                this.bindDatatableEvents(table, entity);
            }
        });
    }

    getDatatableColumns(entity) {
        const columnMap = {
            employees: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'code', name: 'code' },
                { data: 'full_name', name: 'full_name' },
                { data: 'department_name', name: 'department_name' },
                { data: 'email', name: 'email' },
                { data: 'status', name: 'status', orderable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            departments: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'code', name: 'code' },
                { data: 'name', name: 'name' },
                { data: 'manager_name', name: 'manager_name' },
                { data: 'employees_count', name: 'employees_count' },
                { data: 'status', name: 'status', orderable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        };
        
        return columnMap[entity] || [];
    }

    getDatatableFilters(entity) {
        const filters = {};
        const filterPrefix = `${entity}-filter-`;
        
        document.querySelectorAll(`[id^="${filterPrefix}"]`).forEach(input => {
            const key = input.id.replace(filterPrefix, '');
            filters[key] = input.value;
        });
        
        return filters;
    }

    bindDatatableEvents(table, entity) {
        // Bind filter events
        const filterInputs = document.querySelectorAll(`[id^="${entity}-filter-"]`);
        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.datatables[entity].ajax.reload();
            });
            
            // Debounced search
            if (input.type === 'text' || input.type === 'search') {
                let timeout;
                input.addEventListener('input', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        this.datatables[entity].ajax.reload();
                    }, 400);
                });
            }
        });
        
        // Bind action buttons
        this.datatables[entity].on('click', '[data-hr-action]', (e) => {
            e.preventDefault();
            const action = e.target.dataset.hrAction;
            const id = e.target.dataset.id;
            const entity = e.target.dataset.entity;
            
            this.handleDatatableAction(action, id, entity);
        });
    }

    handleDatatableAction(action, id, entity) {
        switch(action) {
            case 'edit':
                this.openModal('edit', '', { entity, id });
                break;
            case 'show':
                this.openModal('show', '', { entity, id });
                break;
            case 'delete':
                this.confirmDelete(id, entity);
                break;
            default:
                console.warn(`Unknown action: ${action}`);
        }
    }

    confirmDelete(id, entity) {
        if (confirm('Are you sure you want to delete this record?')) {
            this.deleteRecord(id, entity);
        }
    }

    async deleteRecord(id, entity) {
        try {
            const url = `/admin/hr/${entity}/${id}`;
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                this.showToast(result.message || 'Record deleted successfully', 'success');
                this.refreshDatatable(entity);
            } else {
                this.showToast(result.message || 'Failed to delete record', 'error');
            }
            
        } catch (error) {
            console.error('Delete error:', error);
            this.showToast('An error occurred while deleting', 'error');
        }
    }

    refreshDatatable(entity) {
        if (this.datatables[entity]) {
            this.datatables[entity].ajax.reload();
        }
    }

    // Utility Methods
    showToast(message, type = 'info') {
        // Use existing toast system or create new one
        if (typeof window.showToast === 'function') {
            window.showToast(message, type);
        } else {
            // Fallback toast
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    }

    bindEvents() {
        // Global events
        document.addEventListener('hr:refresh', (e) => {
            const entity = e.detail.entity;
            this.refreshDatatable(entity);
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl+N for new record
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                const activeEntity = this.getActiveEntity();
                if (activeEntity) {
                    this.openModal('create', '', { entity: activeEntity });
                }
            }
        });
    }

    getActiveEntity() {
        // Determine current entity based on active page
        const path = window.location.pathname;
        const match = path.match(/\/admin\/hr\/([^\/]+)/);
        return match ? match[1] : null;
    }
}

// Initialize HR CRUD System
document.addEventListener('DOMContentLoaded', () => {
    window.hrCrud = new HRCrudSystem();
});

// Export for use in other scripts
export { HRCrudSystem };
