function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(function(panel) {
        panel.classList.remove('active');
    });

    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });

    document.getElementById('tab-' + name).classList.add('active');

    btn.classList.add('active');
}