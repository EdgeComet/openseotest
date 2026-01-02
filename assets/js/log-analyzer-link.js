document.addEventListener('DOMContentLoaded', function() {
    var grid = document.querySelector('.test-info-grid');
    if (!grid) return;

    var item = document.createElement('div');
    item.className = 'test-info-item';
    item.style.marginTop = '1rem';

    var label = document.createElement('strong');
    label.textContent = 'Tools:';

    var link = document.createElement('span');
    link.className = 'log-analyzer-link';
    link.innerHTML = '&#x1F50D; <span style="text-decoration: underline">Open in Log Analyzer</span>';
    link.style.cursor = 'pointer';
    link.style.color = '#ff6b4a';
    link.onclick = function() {
        window.open('/logs/?url=' + encodeURIComponent(window.location.pathname), '_blank');
    };

    item.appendChild(label);
    item.appendChild(document.createTextNode(' '));
    item.appendChild(link);
    grid.appendChild(item);
});
