/**
 * AJAX Search dengan Debounce
 * Reusable untuk semua halaman admin
 * 
 * Usage:
 * AjaxSearch.init({
 *     searchInputId: 'searchInput',
 *     tableBodyId: 'tableBody',
 *     routeUrl: '/admin/route',
 *     filters: ['#filter1', '#filter2'],
 *     debounceDelay: 500
 * });
 */

const AjaxSearch = {
    searchTimeout: null,
    
    init: function(config) {
        this.config = {
            searchInputId: config.searchInputId || 'searchInput',
            tableBodyId: config.tableBodyId || 'tableBody',
            routeUrl: config.routeUrl,
            filters: config.filters || [],
            debounceDelay: config.debounceDelay || 500,
            loadingHtml: config.loadingHtml || this.defaultLoadingHtml(),
            errorHtml: config.errorHtml || this.defaultErrorHtml(),
            colspan: config.colspan || 4
        };
        
        this.attachEventListeners();
    },
    
    attachEventListeners: function() {
        const searchInput = document.getElementById(this.config.searchInputId);
        
        if (!searchInput) {
            console.error('Search input not found:', this.config.searchInputId);
            return;
        }
        
        // Search input dengan debounce
        searchInput.addEventListener('input', () => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => this.performSearch(), this.config.debounceDelay);
        });
        
        // Filter changes trigger immediate search
        this.config.filters.forEach(filterSelector => {
            const filterElement = document.querySelector(filterSelector);
            if (filterElement) {
                filterElement.addEventListener('change', () => this.performSearch());
            }
        });
    },
    
    performSearch: function() {
        const searchInput = document.getElementById(this.config.searchInputId);
        const tableBody = document.getElementById(this.config.tableBodyId);
        
        if (!tableBody) {
            console.error('Table body not found:', this.config.tableBodyId);
            return;
        }
        
        // Build query parameters
        const params = new URLSearchParams();
        params.append('search', searchInput.value);
        
        // Add filter parameters
        this.config.filters.forEach(filterSelector => {
            const filterElement = document.querySelector(filterSelector);
            if (filterElement && filterElement.value) {
                const paramName = filterElement.id.replace('Filter', '').toLowerCase();
                params.append(paramName, filterElement.value);
            }
        });
        
        // Show loading
        tableBody.innerHTML = this.config.loadingHtml;
        
        // Perform AJAX request
        fetch(`${this.config.routeUrl}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            tableBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            tableBody.innerHTML = this.config.errorHtml;
        });
    },
    
    defaultLoadingHtml: function() {
        return `
            <tr>
                <td colspan="${this.config.colspan}" class="text-center">
                    <div style="padding: 40px 20px;">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; margin-bottom: 12px;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p style="color: #64748b; margin: 0;">Mencari data...</p>
                    </div>
                </td>
            </tr>
        `;
    },
    
    defaultErrorHtml: function() {
        return `
            <tr>
                <td colspan="${this.config.colspan}" class="text-center">
                    <div style="padding: 40px 20px;">
                        <i class="bi bi-exclamation-triangle" style="font-size: 48px; color: #ef4444; display: block; margin-bottom: 12px;"></i>
                        <p style="color: #64748b; margin: 0;">Terjadi kesalahan saat memuat data</p>
                    </div>
                </td>
            </tr>
        `;
    }
};

// Export untuk digunakan di module lain jika diperlukan
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AjaxSearch;
}
