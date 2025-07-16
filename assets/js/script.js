function logout() {
            window.location.href = "php/logout.php";
        }
function toggleFeatures() {
    const features = document.getElementById('moreFeatures');
    const btn = document.getElementById('moreBtn');
    if (features.style.display === 'none') {
      features.style.display = 'block';
      btn.textContent = 'Less ▲';
    } else {
      features.style.display = 'none';
      btn.textContent = 'More ▼';
    }
  }