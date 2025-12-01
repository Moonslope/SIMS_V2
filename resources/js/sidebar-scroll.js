// Save sidebar and main content scroll positions
window.addEventListener('beforeunload', function() {
   const sidebar = document.getElementById('sidebar');
   const mainContent = document.getElementById('mainContent');
   
   if (sidebar) {
      localStorage. setItem('sidebarScrollPos', sidebar.scrollTop);
   }
   
   if (mainContent) {
      localStorage.setItem('mainContentScrollPos', mainContent.scrollTop);
   }
});

// Restore sidebar and main content scroll positions
window.addEventListener('load', function() {
   const sidebar = document.getElementById('sidebar');
   const mainContent = document.getElementById('mainContent');
   
   const sidebarScrollPos = localStorage.getItem('sidebarScrollPos');
   const mainContentScrollPos = localStorage.getItem('mainContentScrollPos');
   
   if (sidebar && sidebarScrollPos) {
      sidebar.scrollTop = parseInt(sidebarScrollPos);
   }
   
   if (mainContent && mainContentScrollPos) {
      mainContent.scrollTop = parseInt(mainContentScrollPos);
   }
});

// Save and restore accordion states
document.addEventListener('DOMContentLoaded', function() {
   const accordions = document.querySelectorAll('details');
   
   accordions.forEach(function(detail, index) {
      // Restore saved state
      const savedState = localStorage.getItem('accordion-' + index);
      if (savedState !== null) {
         detail. open = savedState === 'true';
      }
      
      // Save state when toggled
      detail.addEventListener('toggle', function() {
         localStorage.setItem('accordion-' + index, detail. open);
      });
   });
});