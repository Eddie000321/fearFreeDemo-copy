// Course list search filter
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('wplp-search');
  if (!searchInput) {
    console.warn('[WPLP] Search input not found on page');
    return;
  }

  searchInput.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.wplp-course-list li');

    items.forEach(function(item) {
      const title = item.getAttribute('data-title') || '';
      if (title.includes(query)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });
});
