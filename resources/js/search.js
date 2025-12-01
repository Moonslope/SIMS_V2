// Real-time AJAX search functionality
document.addEventListener('DOMContentLoaded', function() {
   const searchInput = document.getElementById('searchInput');
   const searchForm = document.getElementById('searchForm');
   let searchTimeout;
   
   if (searchInput && searchForm) {
      searchInput. addEventListener('input', function() {
         clearTimeout(searchTimeout);
         
         // Search after 300ms of no typing
         searchTimeout = setTimeout(() => {
            performSearch();
         }, 300);
      });
      
      // Also search on Enter key
      searchInput.addEventListener('keypress', function(e) {
         if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch();
         }
      });
   }
   
   function performSearch() {
      const formData = new FormData(searchForm);
      const searchParams = new URLSearchParams(formData);
      const url = searchForm.action + '?' + searchParams.toString();
      
      // Fetch data without page reload
      fetch(url, {
         headers: {
            'X-Requested-With': 'XMLHttpRequest'
         }
      })
      .then(response => response.text())
      .then(html => {
         // Parse the HTML response
         const parser = new DOMParser();
         const doc = parser.parseFromString(html, 'text/html');
         
         // Update the table body
         const newTableBody = doc.querySelector('tbody');
         const currentTableBody = document.querySelector('tbody');
         if (newTableBody && currentTableBody) {
            currentTableBody.innerHTML = newTableBody.innerHTML;
         }
         
         // Update pagination if exists
         const newPagination = doc.querySelector('. pagination');
         const currentPagination = document.querySelector('.pagination');
         if (newPagination && currentPagination) {
            currentPagination.innerHTML = newPagination. innerHTML;
         }
         
         // Update search info alert
         const newAlert = doc.querySelector('.alert-info');
         const currentAlert = document.querySelector('.alert-info');
         if (newAlert) {
            if (currentAlert) {
               currentAlert.replaceWith(newAlert);
            } else {
               // Insert after search form
               searchForm.parentElement.insertAdjacentElement('afterend', newAlert);
            }
         } else if (currentAlert) {
            currentAlert.remove();
         }
         
         // Update URL without reload
         window.history.pushState({}, '', url);
      })
      . catch(error => {
         console.error('Search error:', error);
      });
   }
});